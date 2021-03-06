<?php


namespace LaraBase\FileStore\Controllers;


use Illuminate\Http\Request;
use LaraBase\Attachments\Models\Attachment;
use LaraBase\CoreController;
use LaraBase\FileStore\Models\File;
use LaraBase\Options\Models\Option;
use LaraBase\Posts\Models\Post;
use Mockery\Exception;

class FileController extends CoreController
{

    public function files(Request $request)
    {
        $this->apiSecurity($request, 'files');
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
        $this->apiSecurity($request, 'getFile');
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
        $this->apiSecurity($request, 'addFile');
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
                    'message' => '???????? ???????? ?????? ?????????? ?????????? ???? 150 ????????'
                ];
            }

            $attachment = Attachment::where(['path' => $path])->first();
            $server = $request->input('server');

            if ($attachment == null) {

                if ($server == 'website') {
                    return [
                        'status' => 'error',
                        'message' => '?????? ???????? ???? ???????? ???????? ???????? ?????? ???????? ???????? ???????? ?????? ???? ???????????? ????????'
                    ];
                }

                $getServer = Option::where(['key' => 'servers', 'value' => $server])->first();
                if ($getServer == null) {
                    return [
                        'status' => 'error',
                        'message' => '???????? ?????? ???????? ???? ????????'
                    ];
                }

                $token = getOption('downloadToken');

                if (empty($token)) {
                    return [
                        'status' => 'error',
                        'message' => '???????? ???????????? ?????? ???????? ??????. downloadToken'
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
                            'message' => '???????? ???? ???????? ???????? ??????'
                        ];
                    }

                } catch (Exception $error) {
                    return [
                        'status' => 'error',
                        'message' => '?????????? ???? ???????? ?????????? ??????'
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

            $edit = false;
            if ($request->has('fileId')) {
                if (!empty($request->fileId)) {
                    $edit = true;
                }
            }

            if ($edit) {

                $file = File::where('id', $request->fileId)->first();
                if ($file->post_id != $request->postId && $file->group_id != $request->groupId) {
                    return [
                        'status' => 'error',
                        'message' => '???????? ???????????????????? ?????????????????? ???? ?????????? ??????????'
                    ];
                }

                $file->update([
                    'attachment_id' => $attachment->id,
                    'title' => $request->title,
                    'server' => $server,
                    'type' => $request->type,
                    'status' => $request->status,
                    'note' => $request->note,
                ]);

            } else {

                $episode = File::where('post_id', $request->postId)->orderBy('episode', 'desc')->first();
                if ($episode == null) {
                    $episode = 1;
                } else {
                    $episode = $episode->episode + 1;
                }

                File::create([
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

            }

        }

        return $output;
    }

    public function delete(Request $request)
    {

        $this->apiSecurity($request, 'deleteFile');
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
        $this->apiSecurity($request, 'sortFiles');
        $output = validate($request, [
            'files' => 'required'
        ]);

        if ($output['status'] == 'success') {
            foreach ($request->input('files') as $i => $fileId) {
                \LaraBase\FileStore\Models\File::where('id', $fileId)->update(['sort' => $i]);
            }
            $output['message'] = '???????? ???????? ???? ???????????? ?????????? ????';
        }

        return $output;
    }

}
