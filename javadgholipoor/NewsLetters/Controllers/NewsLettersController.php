<?php

namespace LaraBase\NewsLetters\Controllers;

use Illuminate\Http\Request;
use LaraBase\CoreController;
use LaraBase\NewsLetters\Models\NewsLetter;

class NewsLettersController extends CoreController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Comment::where('type', '1')->latest()->paginate(20);
        return adminView('comments.all', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = [];
        $rules = [];
        $messages = [];

        $falseError = true;

        if ($request->has('email')) {
            if (!empty($request->email)) {
                $falseError = false;
            }
        }

        if ($request->has('mobile')) {
            if (!empty($request->mobile)) {
                $falseError = false;
            }
        }

        if ($falseError) {
            $rules['false'] = 'required';
            $messages['false.required'] = 'لطفا ایمیل یا موبایل خود را وارد کنید.';
        }

        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        }

        if ($request->has('name')) {
            if (!empty($request->name)) {
                $data['name'] = $request->name;
            }
        }

        if ($request->has('group')) {
            if (!empty($request->group)) {
                $data['group'] = $request->group;
            }
        }

        if ($request->has('email')) {
            if (!empty($request->email)) {
                $rules['email'] = 'email';
            }
        }

        if ($request->has('mobile')) {
            if (!empty($request->mobile)) {
                $rules['mobile'] = 'mobile';
            }
        }

        $output = validate($request, $rules, $messages);

        $whereEmail = ['value' => $request->email, 'type' => '1'];
        $emailData = array_merge($data, $whereEmail);
        $whereMobile = ['value' => $request->mobile, 'type' => '2'];
        $mobileData = array_merge($data, $whereMobile);

        if ($request->ajax()) {

            if ($output['status'] == 'success') {

                $output['message'] = 'ثبت نام در خبرنامه با موفقیت انجام شد.';

                if ($request->has('email')) {
                    if (!empty($request->email)) {
                        if (!NewsLetter::where($whereEmail)->exists()) {
                            NewsLetter::create($emailData);
                        }
                    }
                }

                if ($request->has('mobile')) {
                    if (!empty($request->mobile)) {
                        if (!NewsLetter::where($whereMobile)->exists()) {
                            NewsLetter::create($mobileData);
                        }
                    }
                }

            }

            return $output;

        } else {

            if ($request->has('email')) {
                if (!empty($request->email)) {
                    if (!NewsLetter::where($whereEmail)->exists()) {
                        NewsLetter::create($emailData);
                    }
                }
            }

            if ($request->has('mobile')) {
                if (!empty($request->mobile)) {
                    if (!NewsLetter::where($whereMobile)->exists()) {
                        NewsLetter::create($mobileData);
                    }
                }
            }

            return redirect()->back()->with('success', 'ثبت نام در خبرنامه با موفقیت انجام شد.');

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function destroyConfirm($id) {
        $record = Comment::find($id);
        return adminView('comments.destroy', compact('record'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        can('deleteComment');
        $comment = Comment::find($id);
        if ($comment != null) {
            $post = \LaraBase\Posts\Models\Post::find($comment->post_id);
            $user = auth()->user();
            if ($user->checkPostTypePermission($post->post_type, 'comment_deleteComment', true)) {
                $comment->delete();
            }
        }

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.tags.index'));
    }

    public function publish($id) {
        $comment = Comment::find($id);
        $type = 'error';
        $message = 'نظر یافت نشد';
        if ($comment != null) {
            $post = \LaraBase\Posts\Models\Post::find($comment->post_id);
            $user = auth()->user();
            $message = 'شما اجازه انجام این کار را ندارید.';
            if ($user->checkPostTypePermission($post->post_type, 'comment_publishComment', true)) {
                $type = 'success';
                $comment->update(['status' => '2']);
            }
        }
        return redirect()->back()->with($type, $message);
    }

}
