<?php

namespace LaraBase\Dashboard\Controllers;

use LaraBase\Comments\Models\Comment;
use LaraBase\CoreController;

class DashboardController extends CoreController {

    public function index() {

        can('controlPanel');

        $tickets = Comment::tickets()->pending()->get();

        return adminView('dashboard', compact(
            'tickets'
        ));

    }

}
