<?php

namespace LaraBase\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use LaraBase\CoreController;
use LaraBase\Payment\Models\Transaction;
use LaraBase\Posts\Models\Favorite;
use LaraBase\Posts\Models\Post;
use LaraBase\Store\Controllers\OrderController;
use LaraBase\Store\Models\Cart;
use LaraBase\Store\Models\Order;
use LaraBase\Store\Models\OrderShippingStatus;

class ProfileController extends CoreController
{

    public function profile()
    {
        $user = auth()->user();
        $day = '';
        $month = '';
        $year = '';
        if (!empty($user->birthday)) {
            $parts = explode('-', toEnglish(jDateTime('Y-m-d', strtotime($user->birthday))));
            $day = $parts[2];
            $month = $parts[1];
            $year = $parts[0];
        }
        $metas = [];
        foreach ($user->metas() as $meta) {
            $metas[$meta->key] = [
                'value' => $meta->value,
                'more' => $meta->more
            ];
        }
        return templateView('profile.profile', compact('user', 'day', 'month', 'year', 'metas'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $inputs = [
            'name',
            'family',
            'gender'
        ];

        $userData = [];
        foreach ($inputs as $name) {
            if ($request->has($name)) {
                $userData[$name] = $request->input($name);
            }
        }

        if ($request->has('birthYear') && $request->has('birthMonth') && $request->has('birthDay')) {
            $userData['birthday'] = jalali_to_gregorian($request->birthYear, $request->birthMonth, $request->birthDay, '-');
        }

        $user->update($userData);

        $output = validate($request, [
            'nationalCode' => 'required|nationalCode',
        ]);
        if($output['status'] == 'error'){
            return $output;
        }
        $ncodeMeta = $user->getMeta('nationalCode');
        if(!$ncodeMeta){
            $user->addMeta('nationalCode', $request->nationalCode);
        } elseif ($ncodeMeta->value == ''){
            $user->updateMeta(['key' => 'nationalCode', 'value' => $request->nationalCode], []);
        }

        if ($request->has('metas')) {
            if (!empty($request->metas)) {
                formValidator($request);
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
        $output['status']  = 'success';
        $output['message']  = 'با موفقیت بروزرسانی شد';

        return $output;
    }

    public function password()
    {
        $user = auth()->user();
        return templateView('profile.password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $passwordLength = config('authConfig.passwordLength');
        $output = validate($request, [
            'password' => ['required', "min:{$passwordLength}", 'confirmed']
        ]);
        if ($output['status'] == 'success') {
            $user = auth()->user();
            $user->update([
                'remember_token' => null,
                'password' => Hash::make($request->password)
            ]);
            $output['status'] = 'success';
            $output['message'] = 'رمز عبور با موفقیت تغییر کرد';
        }
        return $output;
    }

    public function orders()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->orderBy('updated_at', 'desc')->paginate(10);
        $status = config('store.orderStatus');
        $transactions = Transaction::where('relation', 'order')->whereIn('relation_id', $orders->pluck('id')->toArray())->payed()->get();
        return templateView('profile.orders', compact('user', 'orders', 'status', 'transactions'));
    }

    public function order($id)
    {
        $user = auth()->user();
        $order = Order::where('user_id', $user->id)->where('id', $id)->first();
        $orderController = new OrderController();
        $data = $orderController->cart(null, $order);
        $address = $data['address'];
        $shippings = $data['shippings'];
        if ($order == null) {
            return redirect(url(route('profile.orders')))->with('info', 'سفارش مورد نظر یافت نشد');
        }
        $statuses = [];
        foreach (OrderShippingStatus::where('order_id', $order->id)->get() as $item) {
            $statuses[$item->order_shipping_id][$item->status] = $item->created_at;
        }
        $transaction = Transaction::where('relation', 'order')->where('relation_id', $id)->payed()->get();
        return templateView('profile.order', compact('user', 'order', 'statuses', 'transaction', 'address', 'shippings'));
    }

    public function favorites()
    {
        $user = auth()->user();
        $relations = Favorite::where('user_id', $user->id)->get();
        $favorites = Post::whereIn('id', $relations->pluck('post_id')->toArray())->get();
        return templateView('profile.favorites', compact('user', 'relations', 'favorites'));
    }

}
