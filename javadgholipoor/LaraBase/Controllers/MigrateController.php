<?php

namespace LaraBase\LaraBase\Controllers;

use LaraBase\CoreController;

class MigrateController extends CoreController {

    public function migrate() {
        if (isDev()) {
            \Artisan::call('migrate');
            \Artisan::call('cache:clear');
            session()->flash('success', 'تازه سازی پایگاه با موفقیت انجام شد');
        } else {
            session()->flash('success', 'شما اجازه انجام این کار را ندارید');
        }
        return redirect(route('admin.dashboard'));
    }

}
