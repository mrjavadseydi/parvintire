<?php


namespace LaraBase\Attachments\Models;


use LaraBase\CoreModel;

class Attachment extends CoreModel {

    use \LaraBase\Attachments\Actions\Attachment;

    protected $fillable = [
        'user_id',
        'relation_type',
        'relation_id',
        'title',
        'description',
        'poster',
        'mime',
        'type',
        'extension',
        'size',
        'duration',
        'path',
        'parent',
        'in',
        'created_at',
        'updated_at',
    ];

    public function url()
    {
        $path = path;
        $uploadIn = $this->in;
        if (in_array($uploadIn, [1, 2])) {
            return url($path);
        } else if ($uploadIn == 5) {
            $url = getDownloadServerUrl();
            $token = getUserDownloadServerToken();
            return url("users/{$token}/" . $path);
        }

    }

}
