<?php


namespace LaraBase\Posts\Models;


use DB;
use LaraBase\CoreModel;

class Search extends CoreModel
{

    protected $table = 'search';

    protected $fillable = [
        'id',
        'user_id',
        'os',
        'keyword',
        'ip',
        'url',
        'count',
        'check',
        'server',
        'agent',
        'created_at',
        'updated_at',
    ];

    public function scopeSearch($query, $word = null)
    {
        if (isset($_GET['q'])) {
            if (!empty($_GET['q'])) {
                $query->where('keyword', 'LIKE', "%{$_GET['q']}%");
            }
        }
    }

    public function scopeCheck($query)
    {
        if (isset($_GET['check'])) {
            if (!empty($_GET['check'])) {
                $check = '1';
                if ($_GET['check'] == 'nok') {
                    $check = '0';
                }
                $query->where('check', $check);
            }
        }
    }

    public function scopeOs($query)
    {
        if (isset($_GET['os'])) {
            if (!empty($_GET['os'])) {
                $query->where('os', "{$_GET['os']}");
            }
        }
    }

    public function scopeGroup($query)
    {
        if (isset($_GET['groupBy'])) {
            if (!empty($_GET['groupBy'])) {
                $query->select("*", DB::raw("COUNT(*) as total"))->groupBy($_GET['groupBy']);
            }
        }
    }

}
