<?php

namespace LaraBase\App\Controllers;

use LaraBase\CoreController;

class SidebarController extends CoreController
{

    private $user = null;

    private $sidebar = [];

    private $panel = [
        'title' => 'پیشخوان',
        'icon' => 'fad fa-tachometer',
        'href' => '/admin'
    ];

    private $users = [
        'title' => 'کاربران',
        'icon' => 'fad fa-users',
        'permission' => 'users',
        'href' => '/admin/users',
        'treeview' => [
            [
                'title' => 'افزودن کاربر',
                'icon' => 'fad fa-plus',
                'href' => '/admin/users/create',
                'permission' => 'createUser',
            ],
            [
                'title' => 'کاربران',
                'icon' => 'fad fa-users',
                'href' => '/admin/users',
                'permission' => 'users',
            ],
            [
                'title' => 'مجوز ها',
                'icon' => 'fad fa-badge-check',
                'href' => '/admin/permissions',
                'permission' => 'permissions',
            ],
            [
                'title' => 'نقش ها',
                'icon' => 'fad fa-user-tie',
                'href' => '/admin/roles',
                'permission' => 'roles',
            ],
        ]
    ];

    public function sidebar()
    {
        $this->user = auth()->user();
        $this->add($this->panel);
        $this->add($this->users);
        return $this->sidebar;
    }

    public function add($data)
    {

        if (!$this->can($data['permission'] ?? '')) {
            $data = [];
        }

        if (isset($data['treeview'])) {
            foreach ($data['treeview'] as $i => $item) {
                if (!$this->can($item['permission'] ?? '')) {
                    unset($data['treeview'][$i]);
                } else {
                    if (isset($item['treeview'])) {
                        foreach ($item['treeview'] as $j => $item2) {
                            if (!$this->can($item2['permission'] ?? '')) {
                                unset($data['treeview'][$i]['treeview'][$j]);
                            }
                        }
                    }
                }
            }
        }

        if (!empty($data)) {
            $this->sidebar[] = $data;
        }

    }

    public function can($permission)
    {
        if (empty($permission))
            return  true;

        if ($this->user->can($permission))
            return true;

        return false;
    }

}
