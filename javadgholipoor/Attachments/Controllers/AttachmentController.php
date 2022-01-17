<?php

namespace LaraBase\Attachments\Controllers;

use Illuminate\Http\Request;
use LaraBase\Attachments\Models\Attachment;

class AttachmentController {
    
    public function edit($id) {
        $attachment = Attachment::find($id);
        return adminView('attachments.edit', compact('attachment'));
    }
    
    public function update($id, Request $request) {
        
        $request->validate([
            'title' => 'required',
            'duration' => 'int',
        ]);
        
        $attachment = Attachment::find($id);
        $attachment->update($request->all());
        return redirect()->back()->with('success', 'با موفقیت بروزرسانی شد.');
        
    }
    
}
