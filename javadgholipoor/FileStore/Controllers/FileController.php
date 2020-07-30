<?php


namespace LaraBase\FileStore\Controllers;


use Illuminate\Http\Request;
use LaraBase\Attachments\Models\Attachment;
use LaraBase\Options\Models\Option;
use LaraBase\Posts\Models\Post;
use Mockery\Exception;

class FileController
{

    public function files(Request $request)
    {
        $output = validate($request, [
            'postId' => 'required'
        ]);
        $output['view'] = false;
        $output['html'] = null;
        if ($output['status'] = 'success') {
            if ($request->has('view')) {
                $output['view'] = true;
                $post = Post::find($request->postId);
                $filesGroups = $post->filesGroups(false);
                $output['html'] = view($request->view, compact('post', 'filesGroups'))->render();
            }
        }
        return $output;
    }

    public function get(Request $request)
    {
        $output = validate($request, [
            'fileId' => 'required',
            'groupId' => 'required',
            'postId' => 'required'
        ]);
        $groupId = $request->groupId;
        $postId = $request->postId;
        $output['view'] = false;
        $output['html'] = null;
        $file = null;
        $attachment = null;
        if ($output['status'] == 'success') {
            $file = \LaraBase\FileStore\Models\File::find($request->fileId);
            $attachment = Attachment::where('id', $file->attachment_id)->first();
            $file['attachment'] = $attachment;
            $output['file'] = $file;
        }
        if ($request->has('view')) {
            $output['view'] = true;
            $output['html'] = view($request->view, compact('file', 'groupId', 'postId', 'attachment'))->render();
        }
        return $output;
    }

    public function add(Request $request)
    {

        $output = validate($request, [
            'title' => 'required',
            'postId' => 'required',
            'groupId' => 'required',
            'server' => 'required',
            'path' => 'required',
            'type' => 'required',
            'status' => 'required'
        ]);

        if ($output['status'] == 'success') {

            $path = $request->path[0] == '/' ? substr($request->path, 1) : $request->path;

            if (strlen($path) > 150) {
                return [
                    'status' => 'error',
                    'message' => 'مسیر فایل نمی تواند بیشتر از 150 باشد'
                ];
            }

            $attachment = Attachment::where(['path' => $path])->first();
            $server = $request->input('server');

            if ($attachment == null) {

                if ($server == 'website') {
                    return [
                        'status' => 'error',
                        'message' => 'این فایل در سرور اصلی یافت نشد لطفا سرور مورد نظر را انتخاب کنید'
                    ];
                }

                $getServer = Option::where(['key' => 'servers', 'value' => $server])->first();
                if ($getServer == null) {
                    return [
                        'status' => 'error',
                        'message' => 'سرور غیر مجاز می باشد'
                    ];
                }

                $token = getOption('downloadToken');

                if (empty($token)) {
                    return [
                        'status' => 'error',
                        'message' => 'توکن دانلود ثبت نشده است. downloadToken'
                    ];
                }

                $fileSize = 0;

                try {

                    $url = $getServer->more . '?token=' . $token . '&path=' . $path . '&size=true';
                    $response = json_decode(file_get_contents($url));

                    if ($response->status == 'success') {
                        $fileSize = $response->size;
                    } else {
                        return [
                            'status' => 'error',
                            'message' => 'فایل در سرور یافت نشد'
                        ];
                    }

                } catch (Exception $error) {
                    return [
                        'status' => 'error',
                        'message' => 'اتصال به سرور انجام نشد'
                    ];
                }

                $pathParts = explode('.', $path);
                $extension = end($pathParts);
                $mimeType = \GuzzleHttp\Psr7\mimetype_from_extension($extension);
                $type = explode('/', $mimeType)[0];

                $attachment = attachment()->create([
                    'user_id'       => auth()->id(),
                    'relation_type' => 'postFile',
                    'relation_id'   => $request->postId,
                    'title'         => $request->title,
                    'mime'          => $mimeType,
                    'type'          => $type,
                    'extension'     => $extension,
                    'size'          => $fileSize,
                    'path'          => $path,
                    'in'            => 3
                ]);

            }

            // TODO box download attachment bug
//            return [
//                'status' => 'error',
//                'message' => 'bug attachment is ' . $attachment->id
//            ];

            $where = [
                'post_id' => $request->postId,
                'attachment_id' => $attachment->id,
                'group_id' => $request->groupId
            ];

            $getFile = \LaraBase\FileStore\Models\File::where($where);

            $oldFile = $getFile->first();

            $episode = $getFile->orderBy('episode', 'asc')->first();
            if ($episode == null) {
                $episode = 1;
            } else {
                $episode = $episode->episode + 1;
            }

            if ($oldFile == null) {
                \LaraBase\FileStore\Models\File::create([
                    'post_id' => $request->postId,
                    'attachment_id' => $attachment->id,
                    'group_id' => $request->groupId,
                    'title' => $request->title,
                    'episode' => $episode,
                    'server' => $server,
                    'type' => $request->type,
                    'status' => $request->status,
                    'note' => $request->note,
                    'sort' => $episode
                ]);
            } else {
                $getFile->update([
                    'title' => $request->title,
                    'server' => $server,
                    'type' => $request->type,
                    'status' => $request->status,
                    'note' => $request->note,
                ]);
            }

        }

        return $output;
    }

    public function delete(Request $request)
    {

        $output = validate($request, [
            'fileId' => 'required'
        ]);

        if ($output['status'] == 'success') {
            $file = \LaraBase\FileStore\Models\File::find($request->fileId);
            if ($file != null) {
                $file->delete();
            }
        }

        return $output;

    }

    public function sort(Request $request)
    {
        $output = validate($request, [
            'files' => 'required'
        ]);

        if ($output['status'] == 'success') {
            foreach ($request->input('files') as $i => $fileId) {
                \LaraBase\FileStore\Models\File::where('id', $fileId)->update(['sort' => $i]);
            }
            $output['message'] = 'مرتب سازی با موفقیت انجام شد';
        }

        return $output;
    }

}
