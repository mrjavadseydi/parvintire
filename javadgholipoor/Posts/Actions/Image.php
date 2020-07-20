<?php

namespace LaraBase\Posts\Actions;

use LaraBase\Attachments\Models\Attachment;
use LaraBase\Posts\Models\PostMeta;
use LaraBase\Uploader\Controllers\UploadController;

trait Image {

    public function default() {
        return image('thumbnail.jpg', 'admin');
    }

    public function originalThumbnail() {
        if (empty($this->thumbnail))
            return $this->default();

        return url($this->thumbnail);
    }

    public function thumbnail($width = false, $height = false) {

        if (!empty($this->thumbnail)) {

            if (!$width && !$height) {
                return url($this->thumbnail);
            }

            $parts = $this->parts($width, $height);

            if (file_exists($this->thumbnail)) {
                if (!file_exists("{$parts['path']}/{$parts['resizeName']}")) {
                    $this->generateImage($width, $height);
                }
            }

            return url("{$parts['path']}/{$parts['resizeName']}");

        }

        return $this->default();
    }

    public function thumbnailId() {
        if ($this->hasMeta('thumbnail')) {
            $meta = PostMeta::where(['post_id' => $this->id, 'key' => 'thumbnail', 'value' => $this->thumbnail])->first();
            return $meta->more;
        }
    }

    public function parts($width, $height) {
        $parts = explode('/', $this->thumbnail);
        $lastIndex = count($parts) - 1;
        $originalName = $parts[$lastIndex];
        $resizeName   = "{$width}x{$height}-{$originalName}";
        unset($parts[$lastIndex]);
        $path = implode('/', $parts);

        return [
            'originalName' => $originalName,
            'resizeName'   => $resizeName,
            'path'         => $path
        ];
    }

    public function generateImage($width, $height) {
        $parts = $this->parts($width, $height);
        $img = \Intervention\Image\Facades\Image::make(public_path($parts['path'] . '/' . $parts['originalName']));
        $filePath = "{$parts['path']}/{$parts['resizeName']}";
        $img->fit($width, $height)->save($filePath);
        $attachmentId = $this->thumbnailId();
        $attachment   = Attachment::find($attachmentId);
        if ($attachment != null) {
            Attachment::create([
                'title'     => $attachment->title,
                'user_id'   => $attachment->user_id,
                'mime'      => $attachment->mime,
                'type'      => $attachment->type,
                'extension' => $attachment->extension,
                'size'      => filesize($filePath),
                'path'      => $filePath,
                'in_public' => '1',
                'parent'    => $attachment->id
            ]);
        }
    }

    public function gallery()
    {
        $gallery = PostMeta::where([
            'post_id' => $this->id,
            'key'     => 'gallery',
        ])->get();
        return $gallery;
    }

}
