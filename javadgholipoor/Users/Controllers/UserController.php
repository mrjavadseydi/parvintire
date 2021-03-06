<?php

namespace LaraBase\Users\Controllers;

use App\Events\RegisterNotification;
use Cache;
use Carbon\Carbon;
use Hash;
use LaraBase\Auth\Controllers\AuthCore;
use LaraBase\Auth\Models\User;
use LaraBase\Auth\Models\UserVerification;
use LaraBase\CoreController;
use Illuminate\Http\Request;
use LaraBase\Roles\Models\RoleMeta;
use Mail;

class UserController extends AuthCore
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->apiSecurity($request, 'users');
        if (isset($_GET['vue'])) {
            return adminView('users.all');
        }
        $users = User::canView()->search()->sort()->paginate(usersPaginationCount());
        return adminView('users.all', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->apiSecurity($request, 'createUser');
        $passwordLength = config('authConfig.passwordLength');
        return adminView('users.create', compact('passwordLength'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->apiSecurity($request, 'createUser');
        $this->storeValidator($request);
        $verifiedAt =  "{$this->getType()}_verified_at";
        $userData = [
            $this->getType() => $request->userLogin,
            'password'       => Hash::make($request->password),
            $verifiedAt      => Carbon::now()->toDateTimeString()
        ];
        $user = User::create($userData);
        RegisterNotification::dispatch($request->userLogin, $request->password);
        $user->log(true);
        if ($request->ajax()) {
            return [
                'status' => 'success',
                'message' => 'کاربر با موفقیت افزوده شد',
                'user' => $user
            ];
        }
        session()->flash('success', 'عملیات ثبت کاربر جدید با موفقیت انجام شد');
        return redirect(route('admin.users.edit', ['id' => $user->id]));

    }

    public function storeValidator(Request $request) {

        $passwordLength = config('authConfig.passwordLength');
        $emailMaxLength = config('authConfig.emailMaxLength');

        if ($this->getType() == 'email') {

            $rules['password'] = ['required', "min:{$passwordLength}"];
            $rules['userLogin'] = ['required', 'email', "max:{$emailMaxLength}", 'unique:users,email'];
            $messages = [
                'userLogin.required'    => 'لطفا ایمیل یا موبایل خود را وارد کنید',
                'userLogin.max'         => "ایمیل نمیتواند بیشتر از {$emailMaxLength} کاراکتر باشد",
                'userLogin.unique'      => 'ایمیل قبلا در سیستم ثبت شده است',
            ];

        } else if ($this->getType() == 'mobile') {

            $rules['password'] = ['required', "min:{$passwordLength}"];
            $rules['userLogin']  = ['required', 'mobile', 'size:11', 'unique:users,mobile'];
            $messages = [
                'userLogin.required'    => 'لطفا ایمیل یا موبایل خود را وارد کنید',
                'userLogin.size'        => 'طول موبایل 11 رقم می باشد',
                'userLogin.unique'      => 'موبایل قبلا در سیستم ثبت شده است',
            ];

        }

        return $this->validate($request, $rules, $messages);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        $this->apiSecurity($request, 'showUser');
        $user = $request->user;

        $roles = [];
        foreach ($user->roles() as $role)
            $roles[] = $role->id;

        $more = [
            'avatar' => $user->avatar(),
            'birthday' => $user->birthday == null ? null : toEnglish(jDateTime('Y/m/d', strtotime($user->birthday))),
            'status' => 'success',
            'roles' => $roles,
            'walletCredit' => getWalletCredit($user->id),
            'metas' => [
                'phone' => null,
                'nationalCode' => null,
                'postalCode' => null,
                'provinceId' => null,
                'cityId' => null,
                'townId' => null,
                'regionId' => null,
            ]
        ];

        foreach ($user->metas() as $meta) {
            $more['metas'][$meta->key] = $meta->value;
        }

        $output = [
            'status' => 'success',
            'user' => array_merge($user->toArray(), $more),
            'loading' => image('loading.png')
        ];

        uploader()
            ->relation('profile', $user->id)
            ->addKey('profile', 1, 'mimes:png,jpg,jpeg,gif,webp,PNG,JPG,JPEG,GIF,WEBP|min:0|max:2048', 'profile', ['user' => $user])
            ->init();

        return response()->json($output);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $this->apiSecurity($request, 'updateUser');
        $user = User::find($id);
        $roles = auth()->user()->canSetRoles();
        $userRoles = $user->roles()->pluck('id')->toArray();
        $metas = [];
        foreach ($user->metas() as $meta) {
            $metas[$meta->key] = $meta->value;
        }
        return adminView('users.edit', compact('user', 'roles', 'userRoles', 'metas'));
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

        $this->apiSecurity($request, 'updateUser');

        $output = [
            'status' => 'error',
            'message' => 'خطا'
        ];

        if ($request->has('user'))
            $user = $request->user;
        else
            $user = User::find($id);

        $this->updateValidator($request, $user);

        if (!$request->ajax()) {
            // TODO check security
            $output['status'] = 'success';
        }

        $inputs = [
            'username',
            'mobile',
            'email',
            'name',
            'family',
            'gender'
        ];

        $userData = [];
        foreach ($inputs as $name) {
            if ($request->has($name)) {
                $userData[$name] = $request->$name;
            }
        }

        if ($request->has('password')) {
            if ($request->password != null) {
                $userData['password'] = Hash::make($request->password);
            }
        }

        if ($request->has('birthYear') && $request->has('birthMonth') && $request->has('birthDay')) {
            $userData['birthday'] = jalali_to_gregorian($request->birthYear, $request->birthMonth, $request->birthDay, '-');
        } else {
            if ($request->has('birthday')) {
                if (!empty($request->birthday)) {
                    $birthdayParts = explode('/', $request->birthday);
                    $day = intval($birthdayParts[2]);
                    $month = intval($birthdayParts[1]);
                    $year = intval($birthdayParts[0]);
                    if ($day > 0 && $month > 0 && $year > 0) {
                        $userData['birthday'] = jalali_to_gregorian($year, $month, $day, '-');
                    }
                }
            }
        }

        RoleMeta::where(['key' => 'user', 'value' => $user->id])->delete();
        if ($request->has('roles')) {
            foreach ($request->roles as $role) {
                RoleMeta::create([
                    'role_id' => $role,
                    'key'     => 'user',
                    'value'   => $user->id,
                ]);
            }
        }

        $user->update($userData);

        if ($request->has('metas')) {
            if (!empty($request->metas)) {
                foreach ($request->metas as $key => $value) {
                    if ($key != config('optionsConfig.dev')) {
                        $more = null;
                        if (isset($request->moreMetas[$key]))
                            $more = $request->moreMetas[$key];

                        if ($user->hasMeta($key)) {
                            $user->updateMeta(['value' => $value, 'more' => $more], ['key'=> $key]);
                        } else {
                            $user->addMeta($key, $value, $more);
                        }
                    }
                }

            }
        }
        session()->flash('success', 'با موفقیت بروزرسانی شد');
        $output['status']  = 'success';
        $output['message']  = 'با موفقیت بروزرسانی شد';

        if ($request->ajax()) {
            return $output;
        }

        return redirect()->back();

    }

    public function updateValidator($request, $user) {

        $rules = [];
        $messages = [];

        $passwordLength = config('authConfig.passwordLength');
        $emailMaxLength = config('authConfig.emailMaxLength');

        if (!empty($request->email))
            $rules['email'] = "email|max:{$emailMaxLength}|unique:users,email," . $user->id;

        if ($request->has('username')) {
            if (!empty($request->username)) {
                $rules['username'] = "unique:users,username," . $user->id;
            }
        }

        if ($request->has('mobile')) {
            if (!empty($request->mobile)) {
                $rules['mobile'] = "mobile|unique:users,mobile," . $user->id;
            }
        }

        if (!empty($request->password)) {
            $rules['password'] = "min:{$passwordLength}";
        }

        if (!empty($request->gender)) {
            $rules['gender'] = "int|min:1|max:2";
        }

        if ($request->has('roles')) {

            $loginedUser = auth()->user();
            $userRoles = $loginedUser->roles();
            $canSetRoles = $loginedUser->canSetRoles($userRoles)->pluck('id')->toArray();
            foreach ($request->roles as $role) {
                if (!in_array($role, $canSetRoles)) {
                    $rules['roles'] = 'false';
                    $messages['roles.false'] = 'لطفا پارامترهای استاندارد نقش ها را تغییر ندهید.';
                    break;
                }
            }

        }

        if ($request->has('birthYear') && $request->has('birthMonth') && $request->has('birthDay')) {
            $rules['birthYear']  = "int|min:1255|max:" . toEnglish(jDateTime('Y', strtotime(date("Y"))));
            $rules['birthMonth'] = "int|min:1|max:12";
            $rules['birthDay']   = "int|min:1|max:31";
            $messages['birthYear.false'] = 'سال تولد باید یک عدد باشد';
            $messages['birthMonth.false'] = 'ماه تولد باید یک عدد باشد';
            $messages['birthDay.false'] = 'روز تولد باید یک عدد باشد';
        }

        $rules = array_merge($rules, $this->addRule($request, 'metas.phone', 'phone'));
        $rules = array_merge($rules, $this->addRule($request, 'metas.nationalCode', 'nationalCode'));
        $rules = array_merge($rules, $this->addRule($request, 'metas.postalCode', 'postalCode'));

        return $this->validate($request, $rules, $messages);

    }

    public function addRule($request, $field, $validation)
    {
        if ($request->has($field)) {
            if (!empty($request->input($field))) {
                return [$field => $validation];
            }
        }
        return [];
    }

    public function destroyConfirm($id) {
        $user = User::find($id);
        return adminView('users.destroy', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $this->apiSecurity($request, 'deleteUser');

        $user = User::find($id);

        $delete = true;
        if ($user != null) {
            if ($user->hasMeta(config('optionsConfig.notDeletable'))) {
                session()->flash('danger', 'قابل حذف نیست');
                $delete = false;
            } else {
                if ($user->hasMeta(config('optionsConfig.dev'))) {
                    session()->flash('danger', 'قابل حذف نیست');
                    $delete = false;
                }
            }
            if ($delete) {
                $user->delete();
                session()->flash('success', 'با موفقیت حذف شد');
            }
        }

        if ($request->ajax()) {
            return [
                'status' => 'success',
                'message' => 'کاربر با موفقیت حذف شد'
            ];
        }

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.users.index'));

    }

    public function updateAvatar() {

    }

    public function dynamic($type) {
        dd($type);
    }

    public function verify($type, $id, Request $request) {

        if ($type == 'email')
            $this->apiSecurity($request, 'verifyEmailUser');
        else
            $this->apiSecurity($request, 'verifyMobileUser');

        $user = User::where('id', $id)->first();
        $field = "{$type}_verified_at";

        if ($user->$field == null) {
            $user->update([$field => Carbon::now()->toDateTimeString()]);
            if (UserVerification::where(['user_id' => $user->id, 'type' => $type])->exists()) {
                UserVerification::where(['user_id' => $user->id, 'type' => $type])->delete();
            }
        } else {
            $user->update([$field => null]);
        }

        if ($request->ajax()) {
            return [
                'status' => 'success',
                'message' => 'با موفقیت انجام شد',
                'user' => User::find($id)
            ];
        }

        session()->flash('success', 'با موفقیت تایید شد');
        return redirect()->back();
    }

    public function switch($switchTo) {

        if (session()->has('switcher'))
            $switcher = User::where('id', session()->get('switcher'))->first();
        else
            $switcher = auth()->user();

        if ($switcher->can('switchUser')) {

            if (session()->has('switchTo')) {

                session()->remove('switchTo');
                session()->remove('switcher');
                $switcher->login();
                return redirect(url('admin/users'));

            } else {

                session()->put('switchTo', $switchTo);
                session()->put('switcher', $switcher->id);
                $user = User::where('id', $switchTo)->first();
                $user->login();
                return redirect(url(''));

            }

        }
        else {
            return abort(401);
        }

    }

    public function users(Request $request)
    {
        $count = usersPaginationCount();
        if ($request->has('count')) {
            $count = $request->count;
        }
        $this->apiSecurity($request, 'users');
        $users = User::canView()->search()->sort()->paginate($count);
        return response()->json($users);
    }

    public function search(Request $request) {

        $this->apiSecurity($request, 'users');

        $string = null;
        if (isset($_GET['term']))
            $string = $_GET['term'];

        $users = User::whereRaw("(users.id LIKE '%{$string}%' OR users.name LIKE '%{$string}%' OR users.family LIKE '%{$string}%' OR users.username LIKE '%{$string}%' OR users.email LIKE '%{$string}%' OR users.mobile LIKE '%{$string}%')")->limit(100)->get();

        $output = [
            'items' => []
        ];

        foreach ($users as $user) {
            $output['items'][] = [
                'id'  => $user->id,
                'text' => $user->name(),
            ];
        }

        return response()->json($output);

    }

    public function block($id, Request $request)
    {
        $this->apiSecurity($request, 'blockUser');
        $user = User::find($id);
        $block = false;
        if ($user != null) {
            if (empty($user->block)) {
                $block = true;
                $user->update(['block' => 1]);
            } else {
                $user->update(['block' => null]);
            }
        }
        return [
            'status' => 'success',
            'block' => $block
        ];
    }

}
