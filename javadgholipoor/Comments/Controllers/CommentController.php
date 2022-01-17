<?php

namespace LaraBase\Comments\Controllers;

use App\Events\TicketNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaraBase\Auth\Models\User;
use LaraBase\Comments\Models\Comment;
use LaraBase\Comments\Models\CommentMeta;
use LaraBase\CoreController;
use LaraBase\Posts\Models\Post;
use LaraBase\Roles\Models\Role;

class CommentController extends CoreController
{

    public function type()
    {
        $type = 1;
        $routeName = explode('.', Route::currentRouteName());
        if ($routeName[0] == 'admin' && $routeName[1] == 'tickets') {
            $type = 2;
        }
        return $type;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->type() == 1) {
            return $this->commentsIndex();
        } else {
            return $this->ticketsIndex();
        }

    }

    public function commentsIndex()
    {
        can('comments');
        $records = Comment::comments()->status()->latest()->paginate(20);
        return adminView('comments.all', compact('records'));
    }

    public function ticketsIndex()
    {
        can('tickets');
        $records = Comment::tickets()->whereNull('parent')->status()->latest()->paginate(20);
        return adminView('tickets.all', compact('records'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = [
            'ip' => ip(),
            'comment' => $request->comment
        ];

        $lang = app()->getLocale();

        foreach ([
             'name',
             'subject',
         ] as $input) {
            if ($request->has($input)) {
                if (!empty($request->$input)) {
                    $data[$input] = $request->$input;
                }
            }
        }

        $user = null;
        if (auth()->check()) {
            $user = auth()->user();
            $data['user_id'] = $user->id;
        }

        $messages = [];
        $rules = [
            'type' => 'required',
            'comment' => 'required',
        ];

        if ($request->has('email')) {
            if (!empty($request->email)) {
                $data['email'] = $request->email;
                $rules['email'] = 'email';
            }
        }

        if ($request->has('mobile')) {
            if (!empty($request->mobile)) {
                $data['mobile'] = $request->mobile;
                $rules['mobile'] = 'mobile';
            }
        }

        if ($lang == 'fa')
            $message = 'نظر شما با موفقیت ثبت شد و پس از تایید منتشر خواهد شد.';
        else
            $message = 'Your comment has been successfully submitted and will be published after approval.';

        $log = true;

        if ($request->type == '1') {

            $rules['post_id'] = 'required';

            $data['type'] = '1';
            $data['post_id'] = $request->post_id;
            $post = \LaraBase\Posts\Models\Post::find($request->post_id);
            if ($post != null) {
                if ($user != null) {
                    if (!$user->checkPostTypePermission($post->post_type, 'comment_edit', true)) {
                        if (isset($data['status'])) {
                            unset($data['status']);
                        }
                    }
                }
            }

        } else if ($request->type == '2') {

            $rules['subject'] = 'required';

            $data['type'] = '2';

            if ($request->has('department')) {
                $data['department'] = $request->department;
            }

            if ($request->has('priority')) {
                if (in_array($request->priority, [1, 2, 3])) {
                    $data['priority'] = $request->priority;
                }
            }

            if ($user != null) {

                $department = false;

                if ($request->has('department')) {
                    $roleDepartment = Role::where(['id', $request->department])->first();
                    if ($roleDepartment != null) {
                        if ($user->hasRole($roleDepartment->name)) {
                            $department = true;
                        }
                    }
                }

                if ($department == false) {
                    unset($data['status']);
                }

            }

            if ($request->has('email')) {
                $rules['email'] = 'email';
            }
            if ($request->has('mobile')) {
                $rules['mobile'] = 'mobile';
            }

            $checkEmailAndMobile = true;
            if ($request->has('parent')) {
                if (!empty($request->parent)) {
                    $checkEmailAndMobile = false;
                }
            }

            if ($checkEmailAndMobile) {
                if (!$request->has('email') && !$request->has('email')) {
                    $rules['false'] = 'required';
                    if ($lang == 'fa')
                        $messages['false.required'] = 'لطفا ایمیل یا موبایل خود را وارد کنید.';
                    else
                        $messages['false.required'] = 'Please enter your email or mobile.';

                }
            }

            if ($lang == 'fa')
                $message = 'درخواست شما با موفقیت ثبت شد و در اولین فرصت پاسخ داده خواهد شد.';
            else
                $message = 'Your request has been successfully submitted and will be answered as soon as possible.';

        }

        $output = validate($request, $rules, $messages);

        if (auth()->check()) {
            if ($request->has('parent')) {
                if (!empty($request->parent)) {
                    if (Comment::where('id', $request->parent)->exists()) {
                        $parent = Comment::find($request->parent);
                        if ($parent != null) {

                            $data['parent'] = $parent->id;

                            if ($parent->user_id == $user->id) { // اگر پاسخ دهنده خود کاربر بود
                                if ($request->has('type')) {
                                    if ($request->type == '2') {
                                        $parent->update(['status' => 1]);
                                    }
                                }
                            } else {

                                if ($request->has('type')) { // اگر پاسخ دهنده یکی از پشتیبان ها بود
                                    if ($request->type == '2') { // اگر نوع تیکت بود
                                        if ($user->can('updateTicket')) {
                                            $log = false;
                                            $parent->update(['status' => 2]);
                                            TicketNotification::dispatch($parent, $data['comment']);
                                        }
                                    }
                                    // TODO پاسخ برای نظرات هم بررسی شود
                                }

                            }

                        }
                    }
                }
            }
        }

        if ($request->ajax()) {
            if ($output['status'] == 'success') {
                $output['message'] = $message;
                $this->createComment($data, $request, $log);
            }
            return $output;
        } else {
            $this->createComment($data, $request, $log);
            return redirect()->back()->with('success', $message);
        }

    }

    public function createComment($data, $request, $log = true)
    {
        $comment = Comment::create($data);
        if ($log)
            telegram()->tags(['new_ticket', 'new_comment'])->message("📣 یک اعلان جدید در سیستم ثبت شده است")->sendToGroup();
        // TODO بررسی شود که کلید های سیستمی ذخیره سازی نشود
        if ($request->has('metas')) {

            $mores = [];
            if ($request->has('more')) {
                $mores = $request->more;
            }

            foreach ($request->metas as $key => $value) {

                $option = [
                    'comment_id' => $comment->id,
                    'key' => $key,
                    'value' => $value
                ];

                if (isset($mores[$key])) {
                    $option['more'] = $mores[$key];
                }

                CommentMeta::create($option);

            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = Comment::find($id);
        if ($this->type() == 1) {
            return $this->editComment($id, $comment);
        } else {
            return $this->editTicket($id, $comment);
        }
    }

    public function editComment($id, $comment)
    {

        can('updateComment');

    }

    public function editTicket($id, $ticket)
    {

        can('updateTicket');

        $user = null;
        if ($ticket->user_id != null) {
            $user = User::find($ticket->user_id);
        }

        $name = $ticket->name;
        $attachments = $ticket->attachments();

        $tickets = [];
        foreach (Comment::where('parent', $id)->get() as $item) {
            $userName = $ticket->name;
            $type = 'reply';
            if ($ticket->user_id != $item->user_id) {
                $userName = auth()->user()->name();
                $type = 'answer';
            }
            $strtotime = strtotime($item->created_at);
            $tickets[] = [
                'user_id' => $item->user_id,
                'name' => $userName,
                'comment' => $item->comment,
                'attachments' => $item->attachments(),
                'type' => $type,
                'timestamp' => $strtotime,
                'date' => jDateTime('Y/m/d', $strtotime),
                'time' => jDateTime('H:i', $strtotime),
                'metas' => $item->metas()
            ];
        }

        return adminView('tickets.edit', compact('ticket', 'name', 'tickets'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function destroyConfirm($id)
    {
        $record = Comment::find($id);
        return adminView('comments.destroy', compact('record'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $comment = Comment::find($id);
        if ($comment != null) {
            if ($comment->type == 1) {
                can('deleteComment');
                $post = Post::find($comment->post_id);
                if ($post != null) {
                    $user = auth()->user();
                    if ($user->checkPostTypePermission($post->post_type, 'comment_delete', true)) {
                        $comment->delete();
                    }
                }
            } else {
                can('deleteTicket');
                $comment->delete();
            }
        }

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.tags.index'));
    }

    public function publish($id)
    {
        $comment = Comment::find($id);
        $type = 'error';
        $message = 'نظر یافت نشد';
        if ($comment != null) {
            $post = \LaraBase\Posts\Models\Post::find($comment->post_id);
            if ($post != null) {
                $user = auth()->user();
                $message = 'شما اجازه انجام این کار را ندارید.';
                if ($user->checkPostTypePermission($post->post_type, 'comment_publish', true)) {
                    $type = 'success';
                    $comment->update(['status' => '2']);
                    $message = 'نظر با موفقیت منتشر شد';
                }
            } else {
                $message = 'مطلب یافت نشد';
            }
        }
        return redirect()->back()->with($type, $message);
    }

}
