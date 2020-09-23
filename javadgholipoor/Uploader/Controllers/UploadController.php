<?php

namespace LaraBase\Uploader\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use LaraBase\Attachments\Models\Attachment;
use LaraBase\Auth\Models\User;
use LaraBase\CoreController;

class UploadController extends CoreController {

    private
        $file,
        $fileName
    ;

    private
        $yearDir = true,
        $monthDir = true,
        $dayDir = false,
        $hourDir = false;

    private
        $path = 'uploads',
        $uploadIn = 1;

    private $sizes = [];
    private $validations = null;
    private $type = null;
    private $data = [];
    private $relationType = null;
    private $relationId = null;

    public function setFile($file) {
        $this->file = $file;
        return $file;
    }

    public function getFile() {
        return $this->file;
    }

    public function getFileExtension() {
        return $this->getFile()->getClientOriginalExtension();
    }

    public function getFileMimeType() {
        return $this->getFile()->getClientMimeType();
    }

    public function getFileType() {
        return explode('/', $this->getFileMimeType())[0];
    }

    public function setFileName() {
        $this->fileName = uniqid() . '.' . $this->getFileExtension();
    }

    public function getFileName() {
        return $this->fileName;
    }

    function uploadIn() {
        return $this->uploadIn;
    }

    public function getPath() {
        $path = "{$this->path}";

        if ($this->yearDir) {
            $year  = date('Y');
            $path .= "/{$year}";
        }

        if ($this->monthDir) {
            $month = date('m');
            $path .= "/{$month}";
        }

        if ($this->dayDir) {
            $day = date('d');
            $path .= "/{$day}";
        }

        if ($this->hourDir) {
            $hour = date('H');
            $path .= "/{$hour}";
        }

        return $path;
    }

    public function loadOptions($uploaderKey) {

        $key = $uploaderKey .'_'. uploaderHash($_SERVER['HTTP_REFERER']);
        $option = getCache($key);
        if ($option != null) {
            foreach (json_decode($option, true) as $key => $value) {
                $this->$key = $value;
            }
        }

    }

    public function upload(Request $request) {

        $output = $this->uploadValidation($request);
        if ($output['status'] == 'success') {

            if ($request->has('file')) {

                $userId = null;
                if (auth()->check()) {
                    $user = auth()->user();
                    $userId = $user->id;
                }
                $file = $this->setFile($request->file);
                $this->setFileName();
                $name = $this->getFileName();
                $path = $this->getPath();
                $type = $this->getFileType();
                $extension = $this->getFileExtension();
                $size = filesize($file);
                $pathWithName = "{$path}/{$name}";
                $savePath = $pathWithName;

                if ($this->uploadIn() == '1') { // public

                } else if ($this->uploadIn() == '2') { // root
                    $path = base_path($path);
                } else if ($this->uploadIn() == '3') { // ftp public

                } else if ($this->uploadIn() == '4') { // ftp root
                    $path = base_path($path);
                } else if ($this->uploadIn() == '5') {
                    $userToken = getUserDownloadServerToken();
                    $path = "uploads/users/{$userToken}/" . $path;
                    $pathWithName = "uploads/users/{$userToken}/" . $pathWithName;
                }

                $file->move($path, $name);

                $attachment = attachment()->create([
                    'user_id'       => $userId,
                    'relation_type' => $this->relationType,
                    'relation_id'   => $this->relationId,
                    'title'         => $file->getClientOriginalName(),
                    'mime'          => $this->getFileMimeType(),
                    'type'          => $type,
                    'extension'     => $extension,
                    'size'          => $size,
                    'path'          => $savePath,
                    'in'            => $this->uploadIn()
                ]);

                // TODO تصویر تامبنیل منتقل شود به ftp
                // بررسی شود که انتقال به ftp با موفقیت انجام شده است
                // فیلد انتقال به ftp در جدول اتچمنت اضافه شود
                // متد تامبنیل اتچمنت تکمیل شود
                // متد ریسایز از ftp تکمیل شود
                // متد کراپ از ftp تکمیل شود
                // فایل دریافت از روت و پابلیک ftp تکمیل شود

                if (in_array($this->uploadIn(), [3, 5])) { // move to ftp public
                    ftpUpload(public_path($pathWithName), 'public_html/' . $pathWithName);
                    if (file_exists(public_path($pathWithName))) {
                        unlink(public_path($pathWithName));
                    }
                } else if ($this->uploadIn() == 4) { // move to ftp root
                    ftpUpload(base_path($pathWithName), $pathWithName);
                    if (file_exists(base_path($pathWithName))) {
                        unlink(base_path($pathWithName));
                    }
                }

                // create uploader thumbnail file
                $imageIcon = '';
                if ($type == 'image') {
                    $output['thumbnail'] = $attachment->thumbnail();
                } else {
                    $output['thumbnail'] = $this->getIcon();
                    $imageIcon = 'icon';
                }

                $output['id'] = $attachment->id;
                $output['url'] = $attachment->url();
                $output['path'] = $attachment->path;
                $output['name'] = $file->getClientOriginalName();
                $output['type'] = $type;
                $output['status'] = 'success';
                $output['subType'] = $extension;
                $output['imageIcon'] = $imageIcon;
                $output['selectIcon'] = $this->getSelectIcon();
                $output['deleteIcon'] = uploaderDeleteIcon();
                $output['checkedIcon'] = image('checked.png', 'uploader');
                $output['data'] = $this->validations[$request->key]['data'] ?? [];

                if (isset($this->validations[$request->key]['method'])) {
                    $method = $this->validations[$request->key]['method'];
                    if (method_exists($this, $method)) {
                        $this->$method($output);
                    }
                }

            }

        }

        return $output;

    }

    public function uploadValidation($request) {
        $messages = [];
        if ($request->has('key')) {
            $key = $request->key;
            $this->loadOptions($key);
            $validations = $this->validations;
            if (isset($validations[$key])) {
                $getRecord = $validations[$key];
                $rules = ['file' => $getRecord['validations']];
                $this->uploadIn = $getRecord['in'];
                if (isset($getRecord['path'])) {
                    $this->path = $getRecord['path'];
                }
                if (isset($getRecord['year'])) {
                    $this->yearDir = $getRecord['year'];
                }
                if (isset($getRecord['month'])) {
                    $this->monthDir = $getRecord['month'];
                }
                if (isset($getRecord['day'])) {
                    $this->dayDir = $getRecord['day'];
                }
                if (isset($getRecord['hour'])) {
                    $this->hourDir = $getRecord['hour'];
                }
                if (isset($getRecord['clearPath'])) {
                    $this->clearPath = $getRecord['clearPath'];
                }
            } else {
                $rules = ['key' => 'false'];
                $messages = ['key.false' => 'کلید معتبر نمی باشد'];
            }
        } else {
            $rules = ['key' => 'required'];
        }
        return validate($request, $rules, $messages);
    }

    function files(Request $request) {

        $all = false;
        if ($request->ajax()) {

            $where = [];
            $extensions = [];

            if ($request->has('key')) {

                $this->loadOptions($request->key);
                if (isset($this->validations[$request->key])) {

                    $options = $this->validations[$request->key];

                    $where = [
                        'parent' => null,
                        'in'     => $options['in'] ?? $this->uploadIn()
                    ];

                    preg_match('/mimes:(.*?)\|/', $options['validations'] . '|', $matches);
                    $extensions = explode(',', str_replace(' ', '', $matches[1]));

                    if (in_array('all', $extensions)) {
                        $all = true;
                    }

                    if (isset($options['where'])) {
                        $where = $options['where'];
                    } else {
                        if (auth()->check()) {
                            $user = auth()->user();
                            if (!$user->can('uploaderViewAllFiles')) {
                                $where['user_id'] = $user->id;
                            }
                        } else {
                            $where['user_id'] = 0;
                        }
                    }

                } else {
                    $where['user_id'] = 0;
                }

            } else {
                $where['user_id'] = 0;
            }

            $attachments = Attachment::whereNull('parent')->where($where)->extensions($extensions)->orderBy('updated_at', 'desc')->paginate($request->count ?? 20);
            return uploaderView($request->view, compact('attachments', 'all'))->render();

        }

    }

    public function resize($width, $height, $path, $name) {

        if ($this->uploadIn() == '1') {
            $img = Image::make(public_path($path . '/' . $name));
        } else if ($this->uploadIn() == '2'){
            $img = Image::make($path . '/' . $name);
        } else if ($this->uploadIn() == '3') {

        }

        $fileName = "{$width}x{$height}-{$name}";
        $filePath = "{$path}/{$fileName}";
        $img->fit($width, $height)->save($filePath);

        return [
            'name' => $fileName,
            'path' => $filePath,
            'url'  => url($filePath)
        ];

    }

    public function getTheme() {
        return uploaderTheme();
    }

    public function getIcon() {
        $name = uploaderIcon($this->getFileExtension());
        return image($name, 'uploader');
    }

    public function getSelectIcon() {
        return image('selected.gif', 'uploader');
    }

    public function save() {

        $type = $this->type;

        if ($type == 'post') {
            $post = PostMeta::create([
                'post_id' => $this->data['postId'],
                'key'     => 'gallery',
                'value'   => $this->data['value']
            ]);
            return $post['id'];
        }

    }

    public function delete($attachmentId) {

        $output = [
            'result'  => false,
            'message' => 'شما اجازه انجام این کار را ندارید'
        ];

        if (auth()->check()) {
            $user = auth()->user();
            if ($user->can('deleteImage')) {
                $attachment = Attachment::where('id', $attachmentId)->first();
                if ($attachment != null) {
                    $name = $attachment->path;
                    $deletes[] = $name;
                    if ($attachment->type == 'image') {
                        $parts = explode('/', $name);
                        $last = count($parts) - 1;
                        $parts[$last] = '150x150-' . end($parts);
                        $deletes[] = implode('/', $parts);
                    }
                    foreach ($deletes as $delete) {
                        if ($attachment->in == '1') {
                            if (file_exists(public_path($delete))) {
                                unlink(public_path($delete));
                            }
                        } else {
                            if (file_exists(base_path($delete))) {
                                unlink(base_path($delete));
                            }
                        }
                    }
                    $attachment->delete();
                    $output['status'] = 'success';
                    $output['message'] = 'با موفقیت حذف شد';
                }
            }
        }

        return $output;

    }

    function renderImage($id, $width, $height, $attachment = false) {
        if ($attachment == false)
            $attachment = Attachment::find($id);

        return response()->download(base_path(uploaderGenerateNameBySize($attachment->path, $width, $height)));
    }

    public function imageCropper(Request $request) {

        $output = [
            'status'  => 'error',
            'message' => null
        ];

        if ($request->has('attachmentId')) {

            try {

                $attachmentId = $request->attachmentId;
                $x = round($request->x);
                $y = round($request->y);
                $width = round($request->width);
                $height = round($request->height);
                $cropperWidth = round($request->cropperWidth);
                $cropperHeight = round($request->cropperHeight);
                $imageWidth = round($request->imageWidth);
                $imageHeight = round($request->imageHeight);
                $rotate = ($request->rotate > 0 ? -round($request->rotate) : round($request->rotate));
                $attachment = Attachment::find($attachmentId);

                $originalAttachment = $attachment;
                if (!empty($attachment->parent)) {
                    $originalAttachment = Attachment::find($attachment->parent);
                }

                $uploadIn = $originalAttachment->in;
                $originalAttachmentPath = $originalAttachment->path;

                $makePath = public_path($originalAttachmentPath);
                if($uploadIn == 1) {

                } elseif ($uploadIn == 2) {
                    $makePath = base_path($originalAttachmentPath);
                } elseif ($uploadIn == 3) {
                    $dl = getDownloadServerUrl();
                    $makePath = $dl . '/' . $originalAttachmentPath;
                } elseif ($uploadIn == 4) {

                } elseif ($uploadIn == 5) {
                    $dl = getDownloadServerUrl();
                    $token = getUserDownloadServerToken();
                    $makePath = $dl . "/uploads/users/{$token}/" . $originalAttachmentPath;
                }

                $cropWidth = round($imageWidth - abs($x));
                $cropHeight = round($imageHeight - abs($y));

                if ($x > $imageWidth || $y > $imageHeight) {
                    $cropWidth = $cropHeight = 0;
                } else {
                    if ($x < 0) {
                        $cropWidth = $cropperWidth - abs($x);
                    }
                    if ($y < 0) {
                        $cropHeight = $cropperHeight - abs($y);
                    }
                    if ($cropperWidth > $imageWidth) {
                        $cropWidth = $imageWidth;
                    }
                    if($cropHeight > $imageHeight) {
                        $cropHeight = $imageHeight;
                    }
                }

                if ($cropWidth < 0 || $cropHeight < 0) {
                    $cropWidth = $cropHeight = 0;
                }

                $cropX = $x;
                $cropY = $y;

                if ($x < 0) {
                    $cropX = 0;
                }

                if ($y < 0) {
                    $cropY = 0;
                }

                //                if ($cropWidth == 0 && $cropHeight == 0) {
                //                    $cropX = $cropY = 0;
                //                }

                $crop = Image::make($makePath);
                $crop->rotate($rotate);
                $crop->crop($cropWidth, $cropHeight, $cropX, $cropY);
                $resizeWidth = ($width * $cropWidth) / $cropperWidth;
                $resizeHeight = ($height * $cropHeight) / $cropperHeight;
                $crop->resize($resizeWidth, $resizeHeight);
                $crop->trim();

                $insertX = $insertY = 0;

                if ($x < 0) {
                    $insertX = round($width - (($width * ($cropperWidth - abs($x))) / $cropperWidth));
                }

                if ($y < 0) {
                    $insertY = round($height - (($height * ($cropperHeight - abs($y))) / $cropperHeight));
                }

                $img = Image::canvas($width, $height)->insert($crop, 'top-left', $insertX, $insertY);

                $originalPath = $originalAttachment->path;
                $originalPathParts = explode('/', $originalPath);
                $originalPathPartsLastIndex = count($originalPathParts) - 1;
                $originalName = $originalPathParts[$originalPathPartsLastIndex];
                unset($originalPathParts[$originalPathPartsLastIndex]);
                $originalPathPartsImplode = implode('/', $originalPathParts);

                $cropPath = $originalPathPartsImplode;
                $cropName = "{$width}x{$height}-c-{$attachment->id}_{$x}_{$y}_{$rotate}-{$originalName}";
                $cropPathName = "{$cropPath}/{$cropName}";

                if ($uploadIn == 5) {
                    $token = getUserDownloadServerToken();
                    $cropPathName = "uploads/users/{$token}/{$cropPathName}";
                }

                if (in_array($uploadIn, [1, 3, 5])) { // in public
                    makeDir(public_path('/'), $cropPathName);
                    $cropPathName = public_path($cropPathName);
                } else { // in before public
                    makeDir(base_path('/'), $cropPathName);
                    $cropPathName = base_path($cropPathName);
                }

                $img->save($cropPathName);

                $path = "{$cropPath}/{$cropName}";
                $cropAttachment = Attachment::where(['parent' => $originalAttachment->id, 'path' => $path])->first();
                if ($cropAttachment == null) {
                    $cropAttachment = Attachment::create([
                        'user_id'       => auth()->check() ? auth()->id() : null,
                        'title'         => $attachment->title,
                        'mime'          => $attachment->mime,
                        'type'          => $attachment->type,
                        'extension'     => $attachment->extension,
                        'size'          => filesize($cropPathName),
                        'path'          => $path,
                        'in'            => $attachment->in,
                        'parent'        => $originalAttachment->id
                    ]);
                }

                $ftpPath = $path;
                if (in_array($uploadIn, [3, 5])) {
                    if ($uploadIn == 5) {
                        $token = getUserDownloadServerToken();
                        $ftpPath = "uploads/users/{$token}/{$ftpPath}";
                    }
                    ftpUpload($cropPathName, 'public_html/'.$ftpPath);
                    if (file_exists($cropPathName)) {
                        unlink($cropPathName);
                    }
                }
                if ($uploadIn == 4) {
                    ftpUpload($cropPathName, $ftpPath);
                    if (file_exists($cropPathName)) {
                        unlink($cropPathName);
                    }
                }

                $output['id'] = $cropAttachment->id;
                $output['url'] = $cropAttachment->url();
                $output['path'] = $cropAttachment->path;
                $output['name'] = $cropAttachment->title;
                $output['type'] = $cropAttachment->type;
                $output['status'] = 'success';
                $output['subType'] = $cropAttachment->extension;
//                $output['imageIcon'] = $resize['url'];
                $output['selectIcon'] = $this->getSelectIcon();
                $output['thumbnail'] = $cropAttachment->thumbnail();

            } catch (\Exception $er) {

            }

        }

        return $output;

    }

    public function profile($data)
    {
        if (isset($data['data']['user'])) {
            $user = User::find($data['data']['user']['id']);
        } else {
            $user = auth()->user();
        }
        $user->update(['avatar' => $data['path']]);
    }

}
