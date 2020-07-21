<?php


namespace LaraBase\App\Controllers;


use LaraBase\Categories\Models\Category;
use LaraBase\Posts\Models\Post;

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
        $posts = Post::pulished()->categories([$id])->paginate($_GET['count'] ?? 20);
        return templateView('pages.category', compact('category', 'parents', 'posts'));
    }

}
