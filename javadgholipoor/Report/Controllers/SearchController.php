<?php

namespace LaraBase\Report\Controllers;

use DB;
use LaraBase\CoreController;
use LaraBase\Posts\Models\Search;

class SearchController extends CoreController
{

    public function search()
    {
        $orderBy = $_GET['orderBy'] ?? 'id';
        if ($orderBy == 'total') {
            if (empty($_GET['groupBy'] ?? '')) {
                $orderBy = 'id';
            }
        }
        $records = Search::check()->os()->search()->orderBy($orderBy, $_GET['ordering'] ?? 'desc')->group()->paginate(30);
        return adminView('reports.search', compact('records'));
    }

    public function searchCheck($keyword, $check)
    {
        Search::where('keyword', $keyword)->update(['check' => $check]);
        return redirect()->back()->with('success', 'با موفقیت انجام شد');
    }

}
