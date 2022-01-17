<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use LaraBase\Posts\Actions\Post;

class PublishPosts extends Command
{

    // /usr/local/???/??? /???/???/artisan command:publishPosts
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:publishPosts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'change publishTime status to publish';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $now = date('Y-m-d H:i:s');
        $posts = \LaraBase\Posts\Models\Post::where('status', 'publishTime')->where('published_at', '<=', $now)->get();
        foreach ($posts as $post) {
            $post->update([
                'status' => 'publish',
                'final_status' => 'publish'
            ]);
        }
        $count = $posts->count();
        if ($count > 0) {
            telegram()->tags(['posts_published'])->message([
                "مطالب با موفقیت منتشر شدند",
                "تعداد مطالب منتشر شده :‌ " . $count
            ])->sendToGroup();
        }

    }
}
