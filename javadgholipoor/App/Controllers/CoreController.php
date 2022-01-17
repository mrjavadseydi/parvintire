<?php


namespace LaraBase\App\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CoreController extends \LaraBase\CoreController
{

    public function delete(Request $request) {
        $title = $request->title;
        $action = $request->action;
        $referer = $request->referer;
        return adminView('delete', compact('title', 'action', 'referer'));
    }

    public function down() {
        if (isDev()) {
            $ip = ip();
            Artisan::call('down --allow=' . $ip);
            return redirect(url('admin'));
        }
        abort(404);
    }

    public function up() {
        if (isDev()) {
            Artisan::call('up');
            return redirect(url('admin'));
        }
        abort(404);
    }

}
