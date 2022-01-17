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

    // TODO attachment query not cache

    $renderResize = renderResize($path, $width, $height);
    $imagePath = true;
    $filePath = $renderResize['resize']['path'];
    $output = url($filePath);

    if (!file_exists($filePath)) {
        $imagePath = null;
        $attachment = Attachment::where(['path' => $path])->first();
        if ($attachment != null) {
            $uploadIn = $attachment->in;
            $savePath = $filePath;

            if ($uploadIn == 1) {
                if (file_exists(public_path($path))) {
                    $imagePath = public_path($path);
                }
            } else if ($uploadIn == 2) {
                $output = route('renderImage', ['id' => $attachment->id, 'width' => $width, 'height' => $height]);
                if (file_exists(base_path($filePath))) {
                    return $output;
                }
                if (file_exists(base_path($path))) {
                    $imagePath = base_path($path);
                }
            } else if ($uploadIn == 3) {
                $dlUrl = getDownloadServerUrl();
                $output = $dlUrl . '/' . $filePath;
                $imagePath = $dlUrl . '/' . $path;
                if(@getimagesize($output)){
                    return $output;
                }
            } else if ($uploadIn == 4) {
                $token = getDlToken();
                $output = ftpCreateToken($token, [
                    'method' => 'image',
                    'path' => $path
                ]);
                $imagePath = $output;
            } else if($uploadIn == 5) {
                $dlUrl = getDownloadServerUrl();
                $userToken = getUserDownloadServerToken();
                $userTokenPath = "uploads/users/{$userToken}/";
                $filePath = $userTokenPath . $filePath;
                $output = $dlUrl . "/" . $filePath;
                $imagePath = $dlUrl . "/{$userTokenPath}" . $path;
                if(@getimagesize($output)){
                    return $output;
                }
            }

            if ($imagePath != null) {
                try {
                    $img = \Intervention\Image\Facades\Image::make($imagePath);
                    $saveTo = public_path($filePath);
                    if(in_array($uploadIn, [2, 4])) {
                        $saveTo = base_path($filePath);
                        makeDir(base_path(''), $filePath);
                    } else {
                        makeDir(public_path('/'), $filePath);
                    }
                    $img->fit($width, $height)->save($saveTo);
                    if ($attachment != null) {
                        if (in_array($uploadIn, [1, 3, 5])) {
                            $fileSize = filesize(public_path($filePath));
                        } else {
                            $fileSize = filesize(base_path($filePath));
                        }
                        if (!Attachment::where('path', $filePath)->exists()) {
                            Attachment::create([
                                'title'     => $attachment->title,
                                'user_id'   => $attachment->user_id,
                                'mime'      => $attachment->mime,
                                'type'      => $attachment->type,
                                'extension' => $attachment->extension,
                                'size'      => $fileSize,
                                'path'      => $savePath,
                                'in'        => $attachment->in,
                                'parent'    => $attachment->id
                            ]);
                        }
                        if (in_array($uploadIn, [3, 5])) {
                            ftpUpload(public_path($filePath), 'public_html/'.$filePath);
                            if (file_exists(public_path($filePath))) {
                                unlink(public_path($filePath));
                            }
                        }
                        if ($uploadIn == 4) {
                            ftpUpload(base_path($filePath), $filePath);
                            if (file_exists(base_path($filePath))) {
                                unlink(base_path($filePath));
                            }
                        }
                    }
                } catch (Exception $err) {
                }
            }
        }

    }

    if ($imagePath == null)
        return image('thumbnail.jpg', 'admin');

    return $output;

}
