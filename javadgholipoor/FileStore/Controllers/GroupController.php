<?php

namespace LaraBase\FileStore\Controllers;

use Illuminate\Http\Request;
use LaraBase\Attachments\Models\Attachment;
use LaraBase\CoreController;
use LaraBase\FileStore\Models\FileGroup;
use LaraBase\Posts\Models\Post;

class GroupController extends CoreController
{

    public function get(Request $request)
    {
        $output = validate($request, [
            'groupId' => 'required',
            'postId' => 'required'
        ]);
        $group = null;
        $output['view'] = false;
        $output['html'] = null;
        if ($output['status'] == 'success') {
            $post = Post::find($request->postId);
            $group = FileGroup::find($request->groupId);
            $output['group'] = $group;
        }
        if ($request->has('view')) {
            $output['view'] = true;
            $output['html'] = view($request->view, compact('group', 'post'))->render();
        }
        return $output;
    }

    public function add(Request $request)
    {
        $output = validate($request, [
            'title' => 'required',
            'postId' => 'required',
            'sort' => 'required'
        ]);

        if ($output['status'] == 'success') {
            $data = [
                'title' => $request->title,
                'post_id' => $request->postId
            ];
            if (!FileGroup::where($data)->exists()) {
                FileGroup::create(array_merge($data, ['sort' => $request->sort]));
            }
            $output['status'] = 'success';
            $output['message'] = 'گروه با موفقیت ایجاد شد';
        }

        return $output;
    }

    public function update(Request $request)
    {
        $output = validate($request, [
            'title' => 'required',
            'postId' => 'required',
            'groupId' => 'required'
        ]);

        if ($output['status'] == 'success') {
            $data = [
                'id' => $request->groupId,
                'post_id' => $request->postId
            ];
            FileGroup::where($data)->update(['title' => $request->title]);
            $output['status'] = 'success';
            $output['message'] = 'گروه با ویرایش ایجاد شد';
        }

        return $output;
    }

    public function delete(Request $request)
    {
        $output = validate($request, [
            'groupId' => 'required'
        ]);

        if ($output['status'] == 'success') {

            $group = FileGroup::find($request->groupId);
            if ($group == null) {
                $output['status'] = 'error';
                $output['message'] = 'گروه حذف نشد';
            } else {
                if ($group->files->count() > 0) {
                    $output['status'] = 'error';
                    $output['message'] = 'فقط گروه های خالی مجاز به حذف کردن می باشند.';
                } else {
                    $group->delete();
                }
            }
        }

        return $output;
    }

    public function sort(Request $request)
    {
        $output = validate($request, [
            'groups' => 'required'
        ]);

        if ($output['status'] == 'success') {
            foreach ($request->groups as $i => $groupId) {
                FileGroup::where('id', $groupId)->update(['sort' => $i]);
            }
            $output['message'] = 'مرتب سازی با موفقیت انجام شد';
        }

        return $output;
    }

}
