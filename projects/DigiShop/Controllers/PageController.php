<?php

namespace Project\DigiShop\Controllers;

use LaraBase\Attributes\Models\AttributeRelation;
use LaraBase\Attributes\Models\AttributeValue;
use LaraBase\Auth\Models\User;
use LaraBase\Categories\Models\Category;
use LaraBase\CoreController;
use LaraBase\Options\Models\Option;
use LaraBase\Payment\Models\Transaction;
use LaraBase\Posts\Models\Post;
use LaraBase\Posts\Models\PostAttribute;
use LaraBase\Store\Models\Product;

class PageController extends CoreController
{

    public function home()
    {
        $homeSlider = [];
        $catSlider = Category::find(getOption('digishopHomeSliderCatId'));
        if ($catSlider != null) {
            $homeSlider = $catSlider->posts()->published()->get();
        }
        return templateView('pages.home', compact('homeSlider'));
    }

    public function product($id, $slug)
    {
        $post = Post::find($id);
        initPost($post, $id, $slug);
        $user = User::find($post->user_id);
        $products = $post->products();
        $product = null;
        if (isset($_GET['productId'])) {
            $product = $products['products']->where('product_id', $_GET['productId'])->first();
        }
        if ($product == null) {
            $product = $products['products']->first();
        }
        $categories = $post->categories;
        $tags = $post->tags;
        $gallery = $post->gallery();
        $comments = $post->comments();

        $brandKeyId = getOption('digishopBrandKeyId');
        $brand = AttributeValue::whereIn('id', PostAttribute::where([
            'type' => 'post',
            'post_id' => $post->id,
            'key_id' => $brandKeyId,
            'active' => '1'
        ])->pluck('value_id')->toArray())->get()->implode('title', ', ');
        $attributes = $post->attributes();

        return templateView('pages.product', compact(
            'products',
            'post',
            'categories',
            'brand',
            'gallery',
            'tags',
            'product',
            'comments',
            'user'
        ));
    }

    public function book($id, $slug)
    {
        $post = Post::find($id);
        initPost($post, $id, $slug);
        $user = User::find($post->user_id);
        $products = $post->products();
        $product = null;
        if (isset($_GET['productId'])) {
            $product = $products['products']->where('product_id', $_GET['productId'])->first();
        }
        if ($product == null) {
            $product = $products['products']->first();
        }
        $categories = $post->categories;
        $tags = $post->tags;
        $gallery = $post->gallery();
        $comments = $post->comments();

        $brandKeyId = getOption('digishopBrandKeyId');
        $brand = AttributeValue::whereIn('id', PostAttribute::where([
            'type' => 'post',
            'post_id' => $post->id,
            'key_id' => $brandKeyId,
            'active' => '1'
        ])->pluck('value_id')->toArray())->get()->implode('title', ', ');
        $attributes = $post->attributes();

        return templateView('pages.book', compact(
            'products',
            'post',
            'categories',
            'brand',
            'gallery',
            'tags',
            'product',
            'comments',
            'user'
        ));
    }

    public function podcast($id, $slug)
    {
        $post = Post::find($id);
        initPost($post, $id, $slug);
        $user = User::find($post->user_id);
        $product = Product::where('post_id', $id)->first();
        $categories = $post->categories;
        $tags = $post->tags;
        $gallery = $post->gallery();
        $comments = $post->comments();
        $attributes = $post->attributes();
        $transaction = Transaction::where(['relation' => 'course', 'relation_id' => $post->id, 'status' => '1'])->first();

        return templateView('pages.podcast', compact(
            'product',
            'post',
            'categories',
            'gallery',
            'tags',
            'product',
            'comments',
            'user',
            'transaction'
        ));
    }

    public function file($id, $slug)
    {
        $post = Post::find($id);
        initPost($post, $id, $slug);
        $user = User::find($post->user_id);
        $product = Product::where('post_id', $id)->first();
        $categories = $post->categories;
        $tags = $post->tags;
        $gallery = $post->gallery();
        $comments = $post->comments();
        $attributes = $post->attributes();
        $transaction = Transaction::where(['relation' => 'course', 'relation_id' => $post->id, 'status' => '1'])->first();

        return templateView('pages.file', compact(
            'product',
            'post',
            'categories',
            'gallery',
            'tags',
            'product',
            'comments',
            'user',
            'transaction'
        ));
    }

    public function article($id, $slug)
    {
        $post = Post::find($id);
        initPost($post, $id, $slug);
        $user = User::find($post->user_id);
        $categories = $post->categories;
        $tags = $post->tags;
        $gallery = $post->gallery();
        $comments = $post->comments();
        return templateView('pages.article', compact(
            'post',
            'categories',
            'gallery',
            'tags',
            'user',
            'comments'
        ));
    }

    public function page($id, $slug)
    {
        $post = Post::find($id);
        initPost($post, $id, $slug);
        $user = User::find($post->user_id);
        $categories = $post->categories;
        $tags = $post->tags;
        $gallery = $post->gallery();
        $comments = $post->comments();
        return templateView('pages.page', compact(
            'post',
            'categories',
            'gallery',
            'tags',
            'user',
            'comments'
        ));
    }

    public function contactUs()
    {
        return templateView('pages.contact-us');
    }

}
