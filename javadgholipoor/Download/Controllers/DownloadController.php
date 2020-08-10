<?php

namespace LaraBase\Download\Controllers;

use LaraBase\Attachments\Models\Attachment;
use LaraBase\CoreController;
use LaraBase\FileStore\Models\File;
use LaraBase\Payment\Models\Transaction;
use LaraBase\Posts\Models\PostVideo;

class DownloadController extends CoreController {

    public function file($fileId)
    {

        $canDownload = false;
        $file = File::find($fileId);

        $message = 'فایل مورد نظر یافت نشد';
        if ($file != null) {
            if ($file->status == 1) {
                $type = $file->type;
                if ($type == 3) { // نقدی
                    if (Transaction::where(['relation' => 'course', 'relation_id' => $file->post_id, 'status' => '1'])->exists()) {
                        $canDownload = true;
                    } else {
                        $payment = '<span class="payment-course-button btn btn-success pointer">پرداخت</span>';
                        $message = "دسترسی به فایل های نقدی بعد از پرداخت امکان پذیر می باشد. اگر مایل به خرید هستید بر روی دکمه {$payment} کلیک کنید";
                    }
                } elseif ($type == 0) { // رایگان
                    $canDownload = true;
                } elseif ($type == 1) { // اعضا
                    if(auth()->check()) {
                        $canDownload = true;
                    } else {
                        $login = '<a href="'.route('login').'" clss="text-danger">وارد سایت شوید</a>';
                        $register = '<a href="'.route('register').'" clss="text-success">ثبت نام</a>';
                        $message = "دسترسی به این فایل فقط برای اعضای سایت امکان پذیر می باشد. لطفا ابتدا {$login} و یا در سایت {$register} کنید";
                    }
                } elseif ($type == 2) { // اعضای ویژه

                }
            }
        }

        if ($canDownload) {
            $attachment = Attachment::find($file->attachment_id);
            $extension = getStringExtension($attachment->path);
            $name = "{$file->episode}-{$file->title}.{$extension}";
            return response()->download($attachment->in == '1' ? public_path($attachment->path) : base_path($attachment->path), $name);
        }

        return redirect()->back()->with('error', $message);

    }

    public function download($id, $title) {
        $attachment = Attachment::find($id);

        $path = $attachment->path;
        if (!$attachment->in_public) {
            $path = base_path($path);
        }

        return response()->download($path);
    }

    public function attachment($id) {

        $attachment = Attachment::find($id);

        $path = $attachment->path;

        $canDownload = true;
        if (!$attachment->in_public) {
            $user = auth()->user();
            if (!$user->can('downloadBeforePublic')) {
                $canDownload = false;
            } else {
                if ($user->id != $attachment->user_id) {
                    $canDownload = false;
                }
            }
            $path = base_path($attachment->path);
        }

        if (auth()->check()) {
            if (can('administrator')) {
                $canDownload = true;
            }
        }

        if ($canDownload)
            return response()->download($path);

        return templateView('download.failed');

    }

    public function video($id) {

        $video = PostVideo::find($id);
        $path = base_path($video->video);
        $canDownload = true;

//        if (!$attachment->in_public) {
//            $user = auth()->user();
//            if (!$user->can('downloadBeforePublic')) {
//                $canDownload = false;
//            } else {
//                if ($user->id != $attachment->user_id) {
//                    $canDownload = false;
//                }
//            }
//        }

        if (auth()->check()) {
            if (can('administrator')) {
                $canDownload = true;
            }
        }

        if ($canDownload)
            return response()->download($path);

        return templateView('download.failed');

    }

}
