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

}
