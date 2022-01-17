<?php

namespace LaraBase\App\Controllers;

use Illuminate\Http\Request;
use LaraBase\Attributes\Models\AttributeValue;
use LaraBase\Auth\Models\User;
use LaraBase\Categories\Models\Category;
use LaraBase\Posts\Models\Post;
use LaraBase\Posts\Models\PostAttribute;
use function Composer\Autoload\includeFile;

class PageController
{

    public function faq()
    {
        $categoryId = $_GET['categoryId'] ?? null;
        if ($categoryId != null)
            $posts = Post::postType('faq')->published()->categories([$categoryId])->latest()->get();
        else
            $posts = Post::postType('faq')->published()->latest()->get();

        $categories = Category::where('post_type', 'faq')->get();
        return templateView('pages.faq', compact('posts', 'categories', 'categoryId'));
    }

    public function categories()
    {
        $postType = $_GET['postType'] ?? '';
        if (empty($postType)) {
            return abort(404);
        }
        $categories = Category::where('post_type', $postType)->where('parent', null)->get();
        return templateView('pages.categories', compact('categories'));
    }

    public function category($id, $slug)
    {
        $category = Category::find($id);
        if ($category==null) {
            return abort(404);
        }
        $parents = Category::where('parent', $category->id)->get();
        $posts = Post::where('status', 'publish')->categories([$id])->paginate($_GET['count'] ?? 20);
        return templateView('pages.category', compact('category', 'parents', 'posts'));
    }

    public function singlePage($id, $slug, Request $request)
    {

        \DB::enableQueryLog();

        $post = Post::where('id', $id)->first();

        if ($post == null) {
            $post = Post::where('slug', $slug)->first();
            if ($post == null) {
                return  abort(404);
            } else {
                return redirect($post->href(), 301);
            }
        }

        if ($post->slug != $slug) {
            return redirect($post->href(), 301);
        }

        if ($post->status != 'publish')
            return abort(404);

        addVisit('post', $post->id);

        $user = $post->user();
        $tags = $post->tags;
        $categories = $post->categories;
        $gallery = $post->gallery();
        $comments = $post->comments();
        $attributes = $post->attributes(true);
        $products = $post->products();

        $product = null;
        if (isset($_GET['productId'])) {
            $product = $products['products']->where('product_id', $_GET['productId'])->first();
        }
        if ($product == null) {
            $product = $products['products']->first();
        }

        $output = [
            'status' => 'success',
            'post' => $post,
            'user' => $user,
            'tags' => $tags,
            'categories' => $categories,
            'gallery' => $gallery,
            'attributes' => $attributes,
            'comments' => $comments,
            'product' => $product,
            'products' => $products
        ];

        $type = \Route::currentRouteName();
        $view = includeTemplate("pages.{$type}");

//        $brandKeyId = getOption('digishopBrandKeyId');
//        $brand = AttributeValue::whereIn('id', PostAttribute::where([
//            'type' => 'post',
//            'post_id' => $post->id,
//            'key_id' => $brandKeyId,
//            'active' => '1'
//        ])->pluck('value_id')->toArray())->get()->implode('title', ', ');

        if ($request->method() == 'GET') {
            if (view()->exists($view)) {
                return templateView($view, $output);
            }
            abort(404);
        }

        return $output;

    }

}
