<?php

namespace LaraBase\FileStore\Controllers;

use Illuminate\Http\Request;
use LaraBase\CoreController;
use LaraBase\Payment\Models\Transaction;
use LaraBase\Posts\Models\Post;
use LaraBase\Store\Models\Product;

class PaymentController extends CoreController
{

    public function payment(Request $request)
    {

        $output = validate($request, ['postId' => 'required']);

        if ($output['status'] == 'success') {
            $output['status'] = 'error';

            if (auth()->check()) {

                $postId = $request->postId;
                $post = Post::find($postId);

                if ($post == null) {
                    $output['message'] = 'لطفا پارامترهای استاندارد را تغییر ندهید';
                } else {

                    if (Transaction::where(['relation' => 'course', 'relation_id' => $post->id, 'status' => '1'])->exists()) {
                        $output['message'] = 'قبلا پرداخت شده است';
                    } else {
                        $userId = auth()->id();
                        $product = Product::where('post_id', $postId)->first();
                        $transaction = addTransaction($userId, 'course', $postId, toIRR($product->price()), $request->gateway ?? null);
                        $output['url'] = $transaction->url();
                        $output['status'] = 'success';
                    }

                }

            } else {
                $login = '<a href="'.route('login').'" clss="text-danger">وارد سایت شوید</a>';
                $register = '<a href="'.route('register').'" clss="text-success">ثبت نام</a>';
                $output['message'] = "لطفا برای انجام پرداخت ابتدا {$login} و یا در سایت {$register} کنید";
            }

        }

        return $output;

    }

}
