<?php

namespace LaraBase\Store\Controllers;

use App\Events\NewOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use LaraBase\Auth\Models\User;
use LaraBase\CoreController;
use LaraBase\Payment\Models\Transaction;
use LaraBase\Posts\Models\Post;
use LaraBase\Store\Models\Address;
use LaraBase\Store\Models\Order;
use LaraBase\Store\Models\OrderShipping;
use LaraBase\Store\Models\OrderShippingStatus;
use LaraBase\Store\Models\Product;
use LaraBase\Store\Models\ProductAttribute;
use LaraBase\Store\Models\Shipping;
use LaraBase\World\models\City;
use LaraBase\World\models\Province;
use LaraBase\World\models\Region;
use LaraBase\World\models\Shingle;
use LaraBase\World\models\Town;

class OrderController extends CoreController
{

    public function index()
    {
        can('orders');
        $title = 'سفارش ها';
        $records = Order::status()->latest()->paginate(50);
        $users = User::whereIn('id', $records->pluck('user_id')->toArray())->get();
        return adminView('orders.all', compact('records', 'users', 'title'));
    }

    public function suspends()
    {
        can('orders');
        $title = 'سفارش ها';
        $records = Order::status(1)->whereDoesntHave('shippingStatuses', function ($query) {
            $query->where('status', '4');
        })->with([
            'shippingStatus' => function ($query) {
                $query->orderBy('status', 'desc');
            },
            'transactions' => function($query) {
                $query->where('status', '1')->first();
            }
        ])->paginate(50);
        $users = User::whereIn('id', $records->pluck('user_id')->toArray())->get();
        return adminView('orders.suspends', compact('records', 'users', 'title'));
    }

    public function edit($orderId)
    {
        can('updateOrder');
        $order = Order::find($orderId);
        $transaction = $order->transaction();
        $orderController = new OrderController();
        $data = $orderController->cart(null, $order);
        $address = $data['address'];
        $shippings = $data['shippings'];
        $userr = $address ? User::find($address->user_id) : null;
        //dd($shippings);
        $statuses = [];
        foreach (OrderShippingStatus::where('order_id', $order->id)->get() as $item) {
            $statuses[$item->order_shipping_id][$item->status] = $item->created_at;
        }
        return adminView('orders.edit', compact('userr', 'order', 'address', 'shippings', 'transaction', 'statuses'));
    }

    public function setStatus(Request $request)
    {
        can('updateOrder');

        $output = validate($request, [
            'orderId' => 'required',
            'status' => 'required',
            'shippingId' => 'required'
        ]);

        if ($output['status'] == 'success') {
            $output['status'] = 'error';
            $output['message'] = 'لطفا پارامترهای استاندارد را تغییر ندهید';
            $order = Order::find($request->orderId);
            if ($order != null) {
                if (in_array($request->status, [1, 2, 3, 4])) {
                    $where = ['order_id' => $order->id, 'order_shipping_id' => $request->shippingId];
                    $newStatus = intval($request->status);
                    $lastStatus = intval(OrderShippingStatus::where($where)->max('status')) ?? 1;
                    if ($newStatus == 1) {
                        $output['message'] = 'این وضعیت نمیتواند غیر فعال شود';
                    } else {
                        if ($lastStatus == $newStatus) {
                            $active = false;
                            $output['message'] = 'وضعیت با موفقیت غیرفعال شد';
                            $output['status'] = 'success';
                            OrderShippingStatus::where(array_merge($where, ['status' => $newStatus]))->delete();
                        } else {
                            if ($lastStatus > $newStatus) {
                                $output['message'] = 'ابتدا وضعیت های بعدی را غیرفعال کنید';
                            } else {
                                if (($newStatus-$lastStatus) > 1) {
                                    $output['message'] = 'لطفا وضعیت ها را به ترتیب اعمال کنید';
                                } else {
                                    if ($newStatus == 3) {
                                        $output = validate($request, ['trackingCode' => 'required']);
                                        if ($output['status'] != 'success') {
                                            return $output;
                                        }
                                        OrderShipping::where('id', $request->shippingId)->update(['tracking_code' => $request->trackingCode]);
                                        $address = $order->address();
                                        $user = User::find($order->user_id);
                                        if(checkMobile($user->mobile)) {
                                            // sms()->numbers([$user->mobile])->sendPattern('sendOrder', [
                                            //     'id' => $request->orderId . '-' . $request->shippingId,
                                            //     'name' => $address->name_family,
                                            //     'trackingCode' => $request->trackingCode
                                            // ]);
                                            $patternValues = [
                                                'id' => $request->orderId . '-' . $request->shippingId,
                                                'name' => $address->name_family ?? 'تحویل گیرنده',
                                                'trackingCode' => $request->trackingCode
                                            ];
                                            $bulkID = \IPPanel::sendPattern(
                                                config('smspatterns.sendOrder'),
                                                config('smspatterns.sender'),
                                                $user->mobile,
                                                $patternValues
                                            );
                                        }
                                    }
                                    $output['status'] = 'success';
                                    $output['message'] = 'وضعیت با موفقیت فعال شد';
                                    $adDateTime = Carbon::now()->toDateTimeString();
                                    $dateTime = jDateTime('H:i:s Y/m/d', strtotime($adDateTime));
                                    OrderShippingStatus::create(array_merge($where, [
                                        'status' => $newStatus,
                                        'created_at' => $adDateTime
                                    ]));
                                }
                            }
                        }
                    }
                    $output['dateTime'] = $dateTime ?? '';
                    $output['statusCode'] = $newStatus;
                    $output['shippingId'] = $request->shippingId;
                    $output['active'] = $active ?? true;
                }
            }
        }

        return $output;

    }

    public function hash()
    {
        $key = 'orderHash';
        $hash = generateUniqueToken();
        if (hasCookie($key)) {
            return getCookie($key);
        }
        setCookiee($key, $hash);
        return $hash;
    }

    public function changeOrder($orderId)
    {
        $user = auth()->user();
        if ($user != null) {
            $order = Order::where(['id' => $orderId, 'user_id' => $user->id])->first();
            if ($order != null) {
                if ($order->status == 4) {
                    $activeOrder = Order::where(['user_id' => $user->id, 'status' => 0])->first();
                    if ($activeOrder != null) {
                        $activeOrder->update(['status' => '4']);
                    }
                    $order->update(['status' => '0']);
                    return redirect(route('cart.payment'));
                }
            }
        }
        return abort(404);
    }

    public function order($onlyGet = false)
    {

        $status = '0';
        $hash = $this->hash();
        $order = Order::where(['hash' => $hash, 'status' => $status])->whereNull('user_id')->first();

        if (auth()->check()) {

            $userId = auth()->id();
            $userIdOrder = Order::where(['user_id' => $userId, 'status' => $status])->first();

            if ($userIdOrder == null) {

                $address = Address::where([
                    'user_id' => auth()->id(),
                    'status' => '1'
                ])->first();

                if ($order == null) {
                    if (!$onlyGet) {
                        $order = Order::create([
                            'user_id' => $userId,
                            'address_id' => $address == null ? null : $address->id
                        ]);
                    }
                } else {
                    $order->update([
                        'user_id' => $userId,
                        'address_id' => $address == null ? null : $address->id
                    ]);
                }

                $order = Order::where(['user_id' => $userId, 'status' => $status])->first();

            } else {

                if ($order == null) {
                    $order = Order::where(['user_id' => $userId, 'status' => $status])->first();
                } else {

                    if ($userIdOrder->id != $order->id)
                        $userIdOrder->update(['status' => '4']);

                    $order->update(['user_id' => $userId]);

                }

            }

        } else {
            if ($order == null) {
                if (!$onlyGet) {
                    $order = Order::create([
                        'hash' => $hash
                    ]);
                }
            }
        }

        return $order;

    }

    function addToCart(Request $request)
    {

        $output = validate($request, [
            'productId' => 'required|int'
        ]);

        if ($output['status'] == 'success') {

            $order = $this->order();
            $output = array_merge($output, $order->addCart($request->productId));
            $output = array_merge($output, $this->cart($request));

        }

        return $output;

    }

    function deleteFromCart(Request $request)
    {

        $output = validate($request, [
            'productId' => 'required|int'
        ]);

        if ($output['status'] == 'success') {

            $order = $this->order();
            $output = array_merge($output, $order->deleteCart($request->productId));
            $output = array_merge($output, $this->cart($request));
            $output['step'] = 'cart';

        }

        return $output;

    }

    public function renderView($view, $shippings, $product)
    {
        return templateView($view, compact('shippings', 'product'))->render();
    }

    public function calculateShipping($order)
    {
        $siteCurrency = siteCurrency('more');
        $shippings = [];
        $carts = $order->carts();
        $orderShippings = $order->shippings();
        $getShippings = Shipping::whereIn('id', $orderShippings->pluck('shipping_id')->toArray())->get();
        $products = Product::whereIn('product_id', $carts->pluck('product_id')->toArray())->get();
        $posts = Post::whereIn('id', $products->plucK('post_id')->toArray())->get();
        $postage = 0;
        foreach ($order->shippings() as $shipping) {
            // dd($shipping, $order, $order->address());
            $shippingId = $shipping->id;
            $thisCarts = $carts->where('order_shipping_id', $shippingId)->filter();
            $shippings[$shippingId] = [
                'shipping' => $getShippings->where('id', $shipping->shipping_id)->first(),
                'orderShipping' => $shipping,
                'cartsPrice' => 0,
                'cartsDiscount' => 0
            ];

            $cartsPrice = 0;
            foreach ($thisCarts as $cart) {
                $product = $products->where('product_id', $cart->product_id)->first();
                $shippings[$shippingId]['carts'][] = [
                    'cart' => $cart,
                    'product' => $product,
                    'post' => $posts->where('id', $product->post_id)->first()
                ];
                $cartsPrice += $cart->count * $cart->price;
            }

            if ($order->type=="at_home"){
                $cartsPrice = $cartsPrice + round($cartsPrice * 0.02);
            }
            $shippings[$shippingId]['cartsPrice'] = $cartsPrice;
            $shippings[$shippingId]['toFreePostage'] = convertPrice($shipping->free_postage - $cartsPrice);

            // $usePostage = true;

            // $shippings[$shippingId]['postage'] = 'وابسته به آدرس';
            $shippings[$shippingId]['postage'] = 'رایگان';

            // dd($shipping, $order->address_id != null, needs_address($order->type),$shipping->postage > 0, needs_postage($order->type));
            if ($order->address_id != null && needs_address($order->type)) {
                if ($shipping->postage > 0 && needs_postage($order->type)) {
                    $shippings[$shippingId]['postage'] = number_format(convertPrice($shipping->postage)) . ' ' . $siteCurrency;
                    $postage += $shipping->postage;
                }
            }
            if ($order->type=="none"){

            }
            // if ($usePostage && needs_postage($order->type))
            //     $postage += $shipping->postage;

            // if ($shipping->free_postage - $cartsPrice <= 0) {
            //     $shippings[$shippingId]['postage'] = 'رایگان';
            //     $usePostage = false;
            // }
            // dd($postage);


        }
        return compact('shippings','carts', 'postage');
    }

    public function calculateTax($carts)
    {
        $productsPrice = $cartDiscount = 0;
        $tax = 0;

        foreach ($carts as $c) {
            $cCount = $c->count;
            $cDiscount = $c->discount;
            $productsPrice += $cCount * ($c->price + $cDiscount);
            $cartDiscount += $cCount * $cDiscount;
            if($c->product->tax)
                $tax += (int)((($c->total_price) * ($c->product->tax->percent))/100);
        }

        return compact('tax', 'productsPrice', 'cartDiscount');
    }

    public function calculatePayable($productsPrice, $cartDiscount, $postage, $tax)
    {
        return ($productsPrice - $cartDiscount) + $postage + $tax;
    }

    public function calculateOrderDetails($order)
    {
        $res1 = $this->calculateShipping($order);
        $shippings = $res1['shippings'];
        $carts = $res1['carts'];

        $res2 = $this->calculateTax($carts);
        $tax = $res2['tax'];
        $productsPrice = $res2['productsPrice'];
        $cartDiscount = $res2['cartDiscount'];

        $payablePrice = $this->calculatePayable($productsPrice, $cartDiscount, $res1['postage'], $tax);

        $count = $carts->count();
        $sumCount = $carts->sum('count');

        $result = compact(array_keys(get_defined_vars()));
        unset($result['res1'], $result['res2']);
        return $result;
    }

    public function cart($request, $order = null)
    {

        if ($order == null)
            $order = $this->order(true);

        if ($order != null) {
            $res = $this->calculateOrderDetails($order);
            $shippings = $res['shippings'];
            $product = null;
            if ($request != null) {
                $product = Product::where('product_id', $request->productId)->first();
            }
            if (isset($request->view1)) {
                $html1 = $this->renderView($request->view1, $shippings, $product);
                $view1 = true;
            }
            if (isset($request->view2)) {
                $html2 = $this->renderView($request->view2, $shippings, $product);
                $view2 = true;
            }

        }

        // dd($shippings);
        return [
            'order' => $order,
            'shippings' => $shippings ?? [],
            'carts' => $res['carts'] ?? null,
            'productsCount' => $res['count'] ?? 0,
            'productsSumCount' => $res['sumCount'] ?? 0,
            'productsPrice' => convertPrice($res['productsPrice'] ?? 0),
            'cartDiscount' => convertPrice($res['cartDiscount'] ?? 0),
            'payablePrice' => convertPrice($res['payablePrice'] ?? 0),
            'tax' => convertPrice($res['tax'] ?? 0),
            'payablePriceRial' => $res['payablePrice'] ?? 0,
            'html1' => $html1 ?? '',
            'html2' => $html2 ?? '',
            'view1' => $view1 ?? false,
            'view2' => $view2 ?? false,
            'address' => $order == null ? null : $order->address()
        ];

    }

    public function address(Request $request)
    {

        if (!auth()->check()) {
            return [
                'status' => 'error',
                'message' => 'لطفا ابتدا وارد سایت شوید'
            ];
        }

        $output = validate($request, ['provinceId' => 'required']);
        if ($output['status'] == 'error')
            return $output;

        $province = Province::find($request->provinceId);
        if ($province->active_postage != '1') {
            return [
                'status' => 'error',
                'message' => "در حال حاضر ارسال به استان {$province->name} غیرفعال می باشد"
            ];
        }

        $output = validate($request, ['cityId' => 'required']);
        if ($output['status'] == 'error')
            return $output;

        $city = City::find($request->cityId);
        if ($city->active_postage != '1') {
            return [
                'status' => 'error',
                'message' => "در حال حاضر ارسال به شهرستان {$city->name} غیرفعال می باشد"
            ];
        }

        if (Town::where(['city_id' => $city->id, 'active_postage' => '1'])->count() > 0) {

            $output = validate($request, ['townId' => 'required']);
            if ($output['status'] == 'error')
                return $output;

            $town = Town::find($request->townId);
            if ($town->active_postage != '1') {
                return [
                    'status' => 'error',
                    'message' => "در حال حاضر ارسال به شهر {$town->name} غیرفعال می باشد"
                ];
            }

            if (Region::where(['town_id' => $town->id, 'active_postage' => '1'])->count() > 0) {

                $output = validate($request, ['regionId' => 'required']);
                if ($output['status'] == 'error')
                    return $output;

                $region = Region::find($request->regionId);
                if ($region->active_postage != '1') {
                    return [
                        'status' => 'error',
                        'message' => "در حال حاضر ارسال به محله {$town->name} غیرفعال می باشد"
                    ];
                }

            }

        }

        $request->request->add([
            'mobile' => toEnglish($request->mobile) ?? '',
            'postalCode' => toEnglish($request->postalCode) ?? ''
        ]);

        $output = validate($request, [
            'address' => 'required',
            'postalCode' => 'required|postalCode',
            'nationalCode' => 'required|nationalCode',
            'nameFamily' => 'required',
            'mobile' => 'required|mobile',
        ]);

        if ($output['status'] == 'success') {

            $user = auth()->user();
            $ncodeMeta = $user->getMeta('nationalCode');
            if(!$ncodeMeta){
                $user->addMeta('nationalCode', $request->nationalCode);
            } elseif ($ncodeMeta->value == ''){
                $user->updateMeta(['key' => 'nationalCode', 'value' => $request->nationalCode], []);
            }

            $where = [
                'user_id' => auth()->id(),
                'province_id' => $request->provinceId,
                'city_id' => $request->cityId,
                'town_id' => $request->townId,
                'region_id' => $request->regionId,
                'postal_code' => toEnglish($request->postalCode),
                'status' => '1'
            ];

            $data = [
                'mobile' => $request->mobile,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'name_family' => $request->nameFamily,
            ];

            $address = Address::where($where)->first();

            if ($address == null) {
                $address = Address::create(array_merge($where, $data));
            } else {
                if ($address->success_orders > 0) {
                    $address->update(['status' => '0']);
                    $address = Address::create(array_merge($where, $data));
                } else {
                    $address->update($data);
                }
            }

            $order = $this->order(true);
            $order->update(['address_id' => $address->id]);
            $shingles = $address->shingles();
            foreach ($order->shippings() as $shipping) {
                $shipping_world = null;
                foreach(['region', 'town', 'city', 'province', 'country'] as $world){
                    if($shipping_world !== null) break;
                    $world_id = $world . '_id';
                    $shipping_world = $shipping->shipping->shipping_worlds()->where(['relation' => $world, 'relation_id' => $address->$world_id])->first();
                }
                $count_of_shipping = $order->carts()->where('order_shipping_id', $shipping->id)->sum('count');
                $postage = $count_of_shipping * (($shipping_world == null) ? 390000 : (int)$shipping_world->world->postage);
                // dd($postage);
                // فک کنم اینجا شینگل پیدا نمیشه و قیمت پیشفرض بالا اعمال میشه
                if ($shingles != null) {
                    $shingle = $shingles->where('shipping_id', $shipping->shipping_id)->first();
                    if ($shingle != null) {
                        $postage = $shingle->postage;
                    }
                }
                // dd($postage);
                $shipping->update(['postage' => $postage]);
            }

            $output['message'] = 'آدرس با موفقیت ثبت شد';

        }

        $output = array_merge($output, $this->cart($request));
        $output['step'] = 'payment';

        return $output;

    }
    public function more(Request $request)
    {

        if (!auth()->check()) {
            return [
                'status' => 'error',
                'message' => 'لطفا ابتدا وارد سایت شوید'
            ];
        }
        $request->request->add([
            'mobile' => toEnglish($request->mobile) ?? '',
        ]);

        $output = validate($request, [
            'nationalCode' => 'required|nationalCode',
            'nameFamily' => 'required',
        ]);

        if ($output['status'] == 'success') {
            $user = auth()->user();
            $ncodeMeta = $user->getMeta('nationalCode');
            if(!$ncodeMeta){
                $user->addMeta('nationalCode', $request->nationalCode);
            } elseif ($ncodeMeta->value == ''){
                $user->updateMeta(['key' => 'nationalCode', 'value' => $request->nationalCode], []);
            }
            $user->update(['name' => $request->nameFamily]);
            $output['message'] = 'مشخصات با موفقیت ثبت شد';

        }

        $output = array_merge($output, $this->cart($request));
        $output['step'] = 'payment';

        return $output;

    }

    public function payment(Request $request)
    {

        $cart = $this->cart($request);
        $token = generateUniqueToken();
        $userId = auth()->id();

        Transaction::where([
            'user_id' => $userId,
            'relation' => 'order',
            'relation_id' => $cart['order']->id,
            'status' => '0'
        ])->update(['status' => '3']);

        $trans = Transaction::create([
            'user_id' => $userId,
            'relation' => 'order',
            'relation_id' => $cart['order']->id,
            'gateway' => needs_gateway($cart['order']->type) ? $request->gateway : 'home',
            'price' => $cart['payablePriceRial'],
            'token' => $token,
            'ip' => ip(),
        ]);

        return redirect(url("payment/request/{$trans->id}/{$token}"));

    }

    public function getProduct(Request $request)
    {
        $output = validate($request, ['attributes' => 'required', 'postId' => 'required']);
        $output['view1'] = false;
        $output['view2'] = false;
        if ($output['status'] == 'success') {
            $post = Post::find($request->postId);
            $attributes = $request->input('attributes');
            $products = $post->products();
            $whereRaw = [];
            foreach ($attributes as $ak) {
                $part = explode('-', $ak);
                $whereRaw[] = "(`attribute_id` = {$part[0]} AND `key_id` = {$part[1]})";
            }
            $record = ProductAttribute::whereIn('product_id', $products['products']->pluck('product_id')->toArray())->whereRaw("(" . implode(' OR ', $whereRaw) . ")")->groupBy('product_id')->selectRaw("*, COUNT(*) as c")->orderBy('c', 'desc')->first();
            if ($record->c == count($attributes)) {
                if ($record != null) {
                    $product = Product::where('product_id', $record->product_id)->first();
                    $output['product'] = $product;
                    $output['price'] = $product->price();
                    $output['discount'] = $product->discount();
                    $output['totalPrice'] = $output['price'] + $output['discount'];
                    $output['percent'] = intval(100 - ($output['price'] * 100 / ($output['price'] + $output['discount'])));
                    if (isset($request->view1)) {
                        $output['html1'] = templateView($request->view1, compact('post', 'product', 'products'))->render();
                        $output['view1'] = true;
                    }
                    if (isset($request->view2)) {
                        $output['html2'] = templateView($request->view2, compact('post', 'product', 'products'))->render();
                        $output['view2'] = true;
                    }
                }
            } else {
                $output['status'] = 'error';
            }
        }
        return $output;
    }


    public function cancelOrder(Request $request)
    {
        can('updateOrder');

        $output = validate($request, [
            'orderId' => 'required',
        ]);

        if ($output['status'] == 'success') {
            $order = Order::find($request->orderId);
            if ($order != null) {
                $order->status = '3';
                $order->update();
                $user = \LaraBase\Auth\Models\User::find($order->user_id);
                if (checkMobile($user->mobile)) {
                    $patternValues = [
                        'name' => $user->name ?? 'کاربر',
                    ];
                    $bulkID = \IPPanel::sendPattern(
                        config('smspatterns.cancelOrder'),
                        config('smspatterns.sender'),
                        $user->mobile,
                        $patternValues
                    );
                }
                return $output;
            }
        }

        return $output;
    }

    public function setType(Request $request)
    {
        $order = $this->order(true);
        $order->type = $request->order_type;
        $order->save();
        return response()->json([
            'order_type' => $order->type,
            'needs_address' => needs_address($order->type),
        ]);
    }
}
