<?php


namespace LaraBase\Download\Controllers;


use LaraBase\Attachments\Models\Attachment;
use LaraBase\CoreController;
use LaraBase\Posts\Models\PostVideo;

class DownloadController extends CoreController {
    
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
