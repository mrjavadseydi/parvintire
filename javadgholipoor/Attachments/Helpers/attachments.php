<?php

use LaraBase\Attachments\Attachments;
use LaraBase\Attachments\Models\Attachment;

function attachment() {
    $attachment = new Attachments();
    return $attachment->manager();
}

function renderResize($path, $width, $height) {
    $parts = explode('/', $path);
    $lastIndex = count($parts) - 1;
    $originalName = $parts[$lastIndex];
    $resizeName   = "{$width}x{$height}-{$originalName}";
    unset($parts[$lastIndex]);
    $resizePath = implode('/', $parts) . '/' . $resizeName;

    return [
        'original' => [
            'name' => $originalName,
            'path' => $path
        ],
        'resize' => [
            'name' => $resizeName,
            'path' => $resizePath
        ]
    ];
}

function resizeImage($path, $width, $height)
{
    return renderImage($path, $width, $height);
}

function renderImage($path, $width, $height) {

    if (file_exists($path)) {
        $renderResize = renderResize($path, $width, $height);
        $filePath = $renderResize['resize']['path'];

        if (!file_exists($filePath)) {
            $img = \Intervention\Image\Facades\Image::make(public_path($path));
            $img->fit($width, $height)->save($filePath);

            $attachment = Attachment::where(['path' => $path])->first();
            if ($attachment != null) {
                Attachment::create([
                    'title'     => $attachment->title,
                    'user_id'   => $attachment->user_id,
                    'mime'      => $attachment->mime,
                    'type'      => $attachment->type,
                    'extension' => $attachment->extension,
                    'size'      => filesize($filePath),
                    'path'      => $filePath,
                    'in'        => $attachment->in,
                    'parent'    => $attachment->id
                ]);
            }

        }

        return url($filePath);
    }

    return image('thumbnail.jpg', 'admin');

}
