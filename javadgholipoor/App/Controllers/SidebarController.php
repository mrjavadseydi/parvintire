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
                'title' => 'افزودن نقش',
                'icon' => 'fad fa-plus',
                'href' => '/admin/roles/create',
                'permission' => 'roles',
            ],
            [
                'title' => 'نقش ها',
                'icon' => 'fad fa-user-tie',
                'href' => '/admin/roles',
                'permission' => 'roles',
            ],
        ]
    ];

    private $settings = [
        'title' => 'تنظیمات',
        'icon' => 'fad fa-cog',
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

    private $postTypes = [
        'title' => 'پست تایپ ها',
        'icon' => 'fad fa-paste',
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

    private $tags = [
        'title' => 'برچسب ها',
        'icon' => 'fad fa-hashtag',
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

    private $attributes = [
        'title' => 'ویژگی ها',
        'icon' => 'fad fa-sparkles',
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

    private $menus = [
        'title' => 'فهرست ها',
        'icon' => 'fad fa-bars',
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

    private $orders = [
        'title' => 'سفارش ها',
        'icon' => 'fad fa-shopping-cart',
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

    private $transactions = [
        'title' => 'تراکنش ها',
        'icon' => 'fad fa-money-bill-wave',
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

    private $reports = [
        'title' => 'گزارشات',
        'icon' => 'fad fa-analytics',
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

    private $tickets = [
        'title' => 'تیکت ها',
        'icon' => 'fad fa-user-headset',
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

    private $comments = [
        'title' => 'نظرات',
        'icon' => 'fad fa-comments',
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
        $this->add($this->postTypes);
        $this->add($this->tags);
        $this->add($this->attributes);
        $this->add($this->menus);
        $this->add($this->orders);
        $this->add($this->transactions);
        $this->add($this->reports);
        $this->add($this->tickets);
        $this->add($this->comments);
        $this->add($this->users);
        $this->add($this->settings);
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
