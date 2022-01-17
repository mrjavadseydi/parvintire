<?php

namespace LaraBase\Seo\Controllers;

use LaraBase\Categories\Models\Category;
use LaraBase\CoreController;
use LaraBase\Posts\Models\Post;
use LaraBase\Posts\Models\PostType;

class SitemapController extends CoreController
{

	private $createdAtField = 'created_at';
	private $publishFeild   = 'status';
	private $publishValue   = 'publish';
	private $count   = 500;
	private $sitemap = [];
	private $postTypes  = [];
	private $orderBy = 'updated_at';
	private $ordering = 'desc';

	public function __construct() {
        $this->postTypes = PostType::where('sitemap', '1')->pluck('type')->toArray();
	}

    public function index() {
		$this->sitemap[] =  '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$this->sitemap[] =  "\n";

		$defaultLastMod = date('Y/m/dTH:i:s+03:00');

        {
            $categoryLastMod = $defaultLastMod;
            $lastCategory = Category::orderBy($this->orderBy, $this->ordering)->first();
            if ($lastCategory != null)
                $categoryLastMod = str_replace(' ', 'T', $lastCategory->updated_at ?? $lastCategory->created_at) . '+03:00';

            // categories
            $loc = url("/sitemap/all/categories.xml");
            $this->sitemap[] = "\t<sitemap>\n";
            $this->sitemap[] =  "\t\t<loc>{$loc}</loc>\n";
            $this->sitemap[] =  "\t\t<lastmod>{$categoryLastMod}</lastmod>\n";
            $this->sitemap[] =  "\t</sitemap>\n";
        }

		$startDateTime = $this->dateTimeParts($this->firstPost()); // اولین پست ایجاد شده
		$endDateTime  = $this->dateTimeParts($this->lastPost());   // آخرین پست ایجاد شده
		for ($y = $startDateTime['Y']; $y <= $endDateTime['Y']; $y++) {

			$firstDateTimeMonth = $startDateTime['M'];
			$lastDateTimeMonth  = $endDateTime['M'];

            for ($m = 1; $m <= 12; $m++) {

				if ($m < 10) {
					$m = str_replace("0", "", $m);
					$m = "0" . $m;
				}

				// تعداد تمام پست ها بر اساس ماه
				$like = "%".$y."-".$m."%";
				$postCount = $this->postsCount($like);
				$pages = ($postCount / $this->count);

				if ($postCount > 0) {
					for ($p = 0; $p <= $pages; $p++) {
                        $lastMod = $defaultLastMod;
                        $lastPost = $this->sitemapLastPost($like, $p);
                        if ($lastPost != null)
                            $lastMod = str_replace(' ', 'T', $lastPost->updated_at ?? $lastPost->created_at) . '+03:00';
                        $loc = url("/sitemap/{$y}-{$m}/{$p}.xml");
						$this->sitemap[] = "\t<sitemap>\n";
						$this->sitemap[] =  "\t\t<loc>{$loc}</loc>\n";
						$this->sitemap[] =  "\t\t<lastmod>{$lastMod}</lastmod>\n";
						$this->sitemap[] =  "\t</sitemap>\n";
					}
				}

			}

		}

		$this->sitemap[] =  '</sitemapindex>';
		$build = "";
		foreach ($this->sitemap as $record) {
			$build .= $record;
		}
		return response($build)->header('Content-Type', 'text/xml');
	}

	public function urls($yearMonth, $offset) {

        $this->sitemap[] = "<?xml version='1.0' encoding='UTF-8' standalone=\"yes\"?>\n<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9' xmlns:xhtml='http://www.w3.org/1999/xhtml'>\n";

        if ($offset == 'categories') {
            $categories = Category::whereIn('post_type', PostType::where('sitemap', 1)->pluck('type')->toArray())->get();
            foreach ($categories as $category) {
                $lastMod = str_replace(" ", "T", $category->updated_at) . "+03:00";
                $url = url("categories/{$category->id}/{$category->slug}");
                $this->sitemap[] = "\t<url>\n";
                $this->sitemap[] = "\t\t<loc>{$url}</loc>\n";
                $this->sitemap[] = "\t\t<lastmod>{$lastMod}</lastmod>\n";
                $this->sitemap[] = "\t</url>\n";
            }
        } else {
            // پست های هرماه با محدودیت 500تایی براساس هر ماه
            $posts = $this->posts($yearMonth, $offset);
            foreach ($posts as $post) {
                $lastMod = str_replace(" ", "T", $post->updated_at) . "+03:00";
                $this->sitemap[] = "\t<url>\n";
                $this->sitemap[] = "\t\t<loc>{$post->href()}</loc>\n";
                $this->sitemap[] = "\t\t<lastmod>{$lastMod}</lastmod>\n";
                $this->sitemap[] = "\t</url>\n";
            }
        }

		$this->sitemap[] = "</urlset>";
		$build = "";
		foreach ($this->sitemap as $record) {
			$build .= $record;
		}

        header("Content-type: text/xml");
		echo $build;

	}

	public function firstPost() {
	    $firstPost = Post::whereIn('post_type', $this->postTypes)->orderBy('created_at', 'asc')->first();
	    if ($firstPost != null) {
            return $firstPost->created_at;
        }
	}

	public function lastPost() {
	    $lastPost = Post::whereIn('post_type', $this->postTypes)->orderBy('created_at', 'desc')->first();
	    if ($lastPost != null) {
            return $lastPost->updated_at;
        }
	}

	public function postsCount($like) {
		return Post::whereIn('post_type', $this->postTypes)->where([
			[$this->createdAtField, 'LIKE', $like],
			$this->publishFeild => $this->publishValue
		])->count();
	}

	public function sitemapLastPost($like, $offset) {
		return Post::whereIn('post_type', $this->postTypes)->where([
			[$this->createdAtField, 'LIKE', "%{$like}%"],
			$this->publishFeild => $this->publishValue
		])->limit($this->count)->offset($offset)->orderBy($this->orderBy, $this->ordering)->first();
	}

	public function posts($like, $offset) {
		return Post::whereIn('post_type', $this->postTypes)->where([
			[$this->createdAtField, 'LIKE', "%{$like}%"],
			$this->publishFeild => $this->publishValue
		])->limit($this->count)->offset($offset)->get();
	}

	public function dateTimeParts($dateTime) {
	    if (!empty($dateTime)) {
            $parts = explode(' ', $dateTime);
            $dateParts = explode('-', $parts[0]);
            $timeParts = explode(':', $parts[1]);
            return [
                'Y' => $dateParts[0],
                'M' => $dateParts[1],
                'D' => $dateParts[2],
                'H' => $timeParts[0],
                'i' => $timeParts[1],
                's' => $timeParts[2],
            ];
        }
	}

    public function robots()
    {
        $robots = getOption('robots');
        $response = 'User-agent: *';
        $response .= "\n";
        $response .= 'Disallow: /admin';
        $response .= "\n";
        $response .= 'Disallow: /larabase';
        if (!empty($robots)) {
            foreach (explode('|', $robots) as $item) {
                $response .= "\n";
                $response .= $item;
            }
        }
        $response .= "\n";
        $response .= "\n";
        $response .= "Sitemap: " . route('sitemap');
        return response($response)->header('Content-Type' , 'text/plain');
    }

}
