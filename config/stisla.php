<?php

return [
    'menus' => [
        [
            'group_name' => 'Navigasi',
            'menus' => [
                [
                    'menu_name' => 'Beranda',
                    'route_name' => 'dashboard.index',
                    'icon' => 'fas fa-fire',
                    'permission' => null,
                    'is_active_if_url_includes' => 'dashboard*'
                ],
                [
                    'menu_name' => 'Contoh CRUD',
                    'route_name' => 'crud-examples.index',
                    'icon' => 'fas fa-atom',
                    'permission' => 'Contoh CRUD',
                    'is_active_if_url_includes' => 'crud-examples*',
                    'is_mockup' => true
                ],
                [
                    'menu_name' => 'Contoh CRUD Yajra',
                    'route_name' => 'crud-examples.index-yajra',
                    'icon' => 'fas fa-atom',
                    'permission' => 'Contoh CRUD Yajra',
                    'is_active_if_url_includes' => 'yajra-crud-examples*',
                    'is_mockup' => true
                ],
                [
                    'menu_name' => 'Contoh CRUD Ajax',
                    'route_name' => 'crud-examples.index-ajax',
                    'icon' => 'fas fa-atom',
                    'permission' => 'Contoh CRUD Yajra',
                    'is_active_if_url_includes' => 'ajax-crud-examples*',
                    'is_mockup' => true
                ],
                [
                    'menu_name' => 'Contoh CRUD Ajax Yajra',
                    'route_name' => 'crud-examples.index-ajax-yajra',
                    'icon' => 'fas fa-atom',
                    'permission' => 'Contoh CRUD Ajax Yajra',
                    'is_active_if_url_includes' => 'yajra-ajax-crud-examples*',
                    'is_mockup' => true
                ],
                [
                    'menu_name' => 'Bank',
                    'route_name' => 'banks.index',
                    'icon' => 'fas fa-university',
                    'permission' => 'Bank',
                    'is_active_if_url_includes' => 'banks*',
                ],
                [
                    'menu_name' => 'Deposito',
                    'route_name' => 'bank-deposits.index',
                    'icon' => 'fas fa-dollar',
                    'permission' => 'Deposito Bank',
                    'is_active_if_url_includes' => 'bank-deposits*',
                ],
                [
                    'menu_name' => 'Riwayat Deposito',
                    'route_name' => 'bank-deposit-histories.index',
                    'icon' => 'fas fa-dollar',
                    'permission' => 'Riwayat Deposito Bank',
                    'is_active_if_url_includes' => 'bank-deposit-histories*',
                ],
                [
                    'menu_name' => 'Stisla Example',
                    'route_name' => null,
                    'icon' => 'fas fa-caret-square-down',
                    'permission' => null,
                    'is_active_if_url_includes' => null,
                    'is_mockup' => true,
                    'childs' => [
                        [
                            'menu_name' => 'Datatable',
                            'route_name' => 'datatable.index',
                            'icon' => 'fas fa-table',
                            'permission' => null,
                            'is_active_if_url_includes' => 'datatable*',
                            'is_mockup' => true
                        ],
                        [
                            'menu_name' => 'Form',
                            'route_name' => 'form.index',
                            'icon' => 'fas fa-file-alt',
                            'permission' => null,
                            'is_active_if_url_includes' => 'form*',
                            'is_mockup' => true
                        ],
                        [
                            'menu_name' => 'Chart JS',
                            'route_name' => 'chart-js.index',
                            'icon' => 'fas fa-chart-line',
                            'permission' => null,
                            'is_active_if_url_includes' => 'chart-js*',
                            'is_mockup' => true
                        ],
                        [
                            'menu_name' => 'Pricing',
                            'route_name' => 'pricing.index',
                            'icon' => 'fas fa-dollar',
                            'permission' => null,
                            'is_active_if_url_includes' => 'pricing*',
                            'is_mockup' => true
                        ],
                        [
                            'menu_name' => 'Invoice',
                            'route_name' => 'invoice.index',
                            'icon' => 'fas fa-dollar',
                            'permission' => null,
                            'is_active_if_url_includes' => 'invoice*',
                            'is_mockup' => true
                        ]
                    ]
                ]
            ]
        ],

        [
            'group_name' => 'Menu Lainnya',
            'menus' => [
                [
                    'menu_name' => 'Manajemen Pengguna',
                    'route_name' => null,
                    'icon' => 'fas fa-users',
                    'permission' => null,
                    'is_active_if_url_includes' => 'dashboard*',
                    'childs' => [
                        [
                            'menu_name' => 'Pengguna',
                            'route_name' => 'user-management.users.index',
                            'icon' => null,
                            'permission' => 'Pengguna',
                            'is_active_if_url_includes' => 'user-management/users*'
                        ],
                        [
                            'menu_name' => 'Role',
                            'route_name' => 'user-management.roles.index',
                            'icon' => null,
                            'permission' => 'Role',
                            'is_active_if_url_includes' => 'user-management/roles*'
                        ],
                        [
                            'menu_name' => 'Permission',
                            'route_name' => 'user-management.permissions.index',
                            'icon' => null,
                            'permission' => 'Permission',
                            'is_active_if_url_includes' => 'user-management/permissions*'
                        ],
                        [
                            'menu_name' => 'Group Permission',
                            'route_name' => 'user-management.permission-groups.index',
                            'icon' => null,
                            'permission' => 'Group Permission',
                            'is_active_if_url_includes' => 'user-management/permission-groups*'
                        ]
                    ]
                ],

                [
                    'menu_name' => 'Manajemen Menu',
                    'uri' => 'menu-managements',
                    'icon' => 'fas fa-bars',
                    'permission' => null,
                    'is_blank' => false,
                    'is_active_if_url_includes' => 'menu-managements*',
                    'childs' => [
                        [
                            'menu_name' => 'Menu',
                            'route_name' => 'menu-managements.index',
                            'icon' => null,
                            'permission' => 'Menu',
                            'is_active_if_url_includes' => 'menu-managements*'
                        ],
                        [
                            'menu_name' => 'Grup Menu',
                            'route_name' => 'group-menus.index',
                            'icon' => null,
                            'permission' => 'Grup Menu',
                            'is_active_if_url_includes' => 'group-menus*'
                        ]
                    ]
                ],
                [
                    'menu_name' => 'Manajemen File',
                    'uri' => 'file-managers',
                    'icon' => 'fas fa-folder',
                    'permission' => 'Manajemen File',
                    'is_blank' => true,
                    'is_active_if_url_includes' => 'file-managers*'
                ],
                [
                    'menu_name' => 'Notifikasi',
                    'route_name' => 'notifications.index',
                    'icon' => 'fas fa-bell',
                    'permission' => 'Notifikasi',
                    'is_active_if_url_includes' => 'notifications*'
                ],
                [
                    'menu_name' => 'Log',
                    'route_name' => 'activity-logs.index',
                    'icon' => 'fas fa-clock-rotate-left',
                    'permission' => null,
                    'is_active_if_url_includes' => 'activity-logs*',
                    'childs' => [
                        [
                            'menu_name' => 'Log Aktivitas',
                            'route_name' => 'activity-logs.index',
                            'icon' => 'fas fa-clock-rotate-left',
                            'permission' => 'Log Aktivitas',
                            'is_active_if_url_includes' => 'activity-logs*'
                        ],
                        [
                            'menu_name' => 'Log Request',
                            'route_name' => 'request-logs.index',
                            'icon' => 'fas fa-clock-rotate-left',
                            'permission' => 'Log Request',
                            'is_active_if_url_includes' => 'request-logs*'
                        ],
                        [
                            'menu_name' => 'Laravel Log Viewer',
                            'route_name' => 'logs.index',
                            'icon' => 'fas fa-circle-exclamation',
                            'permission' => 'Laravel Log Viewer',
                            'is_active_if_url_includes' => 'logs*',
                            'is_blank' => true
                        ]
                    ]
                ],

                [
                    'menu_name' => 'Profil',
                    'route_name' => 'profile.index',
                    'icon' => 'fas fa-user',
                    'permission' => 'Profil',
                    'is_active_if_url_includes' => 'profile*'
                ],
                [
                    'menu_name' => 'Pengaturan',
                    'route_name' => 'settings.all',
                    'icon' => 'fas fa-cogs',
                    'permission' => 'Pengaturan',
                    'is_active_if_url_includes' => 'settings*'
                ],
                [
                    'menu_name' => 'Ubuntu',
                    'route_name' => 'ubuntu.index',
                    'icon' => 'fab fa-ubuntu',
                    'permission' => 'Ubuntu',
                    'is_active_if_url_includes' => 'ubuntu*'
                ],
                [
                    'menu_name' => 'Backup Database',
                    'route_name' => 'backup-databases.index',
                    'icon' => 'fas fa-database',
                    'permission' => 'Backup Database',
                    'is_active_if_url_includes' => 'backup-databases*'
                ],
                [
                    'menu_name' => 'Keluar',
                    'route_name' => 'logout',
                    'icon' => 'fas fa-sign-out-alt',
                    'permission' => null,
                    'is_active_if_url_includes' => null
                ]
            ]
        ]
    ],

    'permissions' => [
        [
            'name' => 'Profil',
            'roles' => ['superadmin', 'admin', 'user'],
            'group' => 'Profil'
        ],
        [
            'name' => 'Profil Ubah',
            'roles' => ['superadmin', 'admin', 'user'],
            'group' => 'Profil'
        ],

        [
            'name' => 'Log Aktivitas',
            'roles' => ['superadmin', 'admin'],
            'group' => 'Log Aktivitas'
        ],
        [
            'name' => 'Log Aktivitas Ekspor',
            'roles' => ['superadmin', 'admin'],
            'group' => 'Log Aktivitas'
        ],

        [
            'name' => 'Log Request',
            'roles' => ['superadmin', 'admin'],
            'group' => 'Log Request'
        ],
        [
            'name' => 'Log Request Ekspor',
            'roles' => ['superadmin', 'admin'],
            'group' => 'Log Request'
        ],

        [
            'name' => 'Role',
            'roles' => ['superadmin'],
            'group' => 'Role'
        ],
        [
            'name' => 'Role Tambah',
            'roles' => ['superadmin'],
            'group' => 'Role'
        ],
        [
            'name' => 'Role Impor Excel',
            'roles' => ['superadmin'],
            'group' => 'Role'
        ],
        [
            'name' => 'Role Ubah',
            'roles' => ['superadmin'],
            'group' => 'Role'
        ],
        [
            'name' => 'Role Detail',
            'roles' => ['superadmin'],
            'group' => 'Role'
        ],
        [
            'name' => 'Role Hapus',
            'roles' => ['superadmin'],
            'group' => 'Role'
        ],
        [
            'name' => 'Role Ekspor',
            'roles' => ['superadmin', 'admin'],
            'group' => 'Role'
        ],

        [
            'name' => 'Permission',
            'roles' => ['superadmin'],
            'group' => 'Permission'
        ],
        [
            'name' => 'Permission Tambah',
            'roles' => ['superadmin'],
            'group' => 'Permission'
        ],
        [
            'name' => 'Permission Impor Excel',
            'roles' => ['superadmin'],
            'group' => 'Permission'
        ],
        [
            'name' => 'Permission Ubah',
            'roles' => ['superadmin'],
            'group' => 'Permission'
        ],
        [
            'name' => 'Permission Detail',
            'roles' => ['superadmin'],
            'group' => 'Permission'
        ],
        [
            'name' => 'Permission Hapus',
            'roles' => ['superadmin'],
            'group' => 'Permission'
        ],
        [
            'name' => 'Permission Ekspor',
            'roles' => ['superadmin', 'admin'],
            'group' => 'Permission'
        ],

        [
            'name' => 'Group Permission',
            'roles' => ['superadmin'],
            'group' => 'Group Permission'
        ],
        [
            'name' => 'Group Permission Tambah',
            'roles' => ['superadmin'],
            'group' => 'Group Permission'
        ],
        [
            'name' => 'Group Permission Impor Excel',
            'roles' => ['superadmin'],
            'group' => 'Group Permission'
        ],
        [
            'name' => 'Group Permission Ubah',
            'roles' => ['superadmin'],
            'group' => 'Group Permission'
        ],
        [
            'name' => 'Group Permission Detail',
            'roles' => ['superadmin'],
            'group' => 'Group Permission'
        ],
        [
            'name' => 'Group Permission Hapus',
            'roles' => ['superadmin'],
            'group' => 'Group Permission'
        ],
        [
            'name' => 'Group Permission Ekspor',
            'roles' => ['superadmin', 'admin'],
            'group' => 'Group Permission'
        ],

        [
            'name' => 'Pengguna',
            'roles' => ['superadmin'],
            'group' => 'Pengguna'
        ],
        [
            'name' => 'Pengguna Tambah',
            'roles' => ['superadmin'],
            'group' => 'Pengguna'
        ],
        [
            'name' => 'Pengguna Impor Excel',
            'roles' => ['superadmin'],
            'group' => 'Pengguna'
        ],
        [
            'name' => 'Pengguna Ubah',
            'roles' => ['superadmin'],
            'group' => 'Pengguna'
        ],
        [
            'name' => 'Pengguna Detail',
            'roles' => ['superadmin'],
            'group' => 'Pengguna'
        ],
        [
            'name' => 'Pengguna Hapus',
            'roles' => ['superadmin'],
            'group' => 'Pengguna'
        ],
        [
            'name' => 'Pengguna Ekspor',
            'roles' => ['superadmin'],
            'group' => 'Pengguna'
        ],
        [
            'name' => 'Pengguna Force Login',
            'roles' => ['superadmin'],
            'group' => 'Pengguna'
        ],

        [
            'name' => 'Pengaturan',
            'roles' => ['superadmin', 'admin'],
            'group' => 'Pengaturan'
        ],

        [
            'name' => 'Reset Sistem',
            'roles' => ['superadmin'],
            'group' => 'Pengaturan'
        ],

        [
            'name' => 'Manajemen File',
            'roles' => ['superadmin', 'admin'],
            'group' => 'Manajemen File'
        ],

        [
            'name' => 'Ubuntu',
            'roles' => ['superadmin'],
            'group' => 'Ubuntu'
        ],

        [
            'name' => 'Backup Database',
            'roles' => ['superadmin'],
            'group' => 'Backup Database'
        ],

        [
            'name' => 'Laravel Log Viewer',
            'roles' => ['superadmin'],
            'group' => 'Laravel Log Viewer'
        ],

        [
            'name' => 'Notifikasi',
            'roles' => ['superadmin', 'admin'],
            'group' => 'Notifikasi'
        ],

        [
            'name' => 'Menu',
            'roles' => ['superadmin'],
            'group' => 'Menu'
        ],
        [
            'name' => 'Menu Tambah',
            'roles' => ['superadmin'],
            'group' => 'Menu'
        ],
        [
            'name' => 'Menu Ubah',
            'roles' => ['superadmin'],
            'group' => 'Menu'
        ],
        [
            'name' => 'Menu Detail',
            'roles' => ['superadmin'],
            'group' => 'Menu'
        ],
        [
            'name' => 'Menu Hapus',
            'roles' => ['superadmin'],
            'group' => 'Menu'
        ],
        [
            'name' => 'Menu Ekspor',
            'roles' => ['superadmin'],
            'group' => 'Menu'
        ],
        [
            'name' => 'Menu Impor Excel',
            'roles' => ['superadmin'],
            'group' => 'Menu'
        ],

        [
            'name' => 'Grup Menu',
            'roles' => ['superadmin'],
            'group' => 'Grup Menu'
        ],
        [
            'name' => 'Grup Menu Tambah',
            'roles' => ['superadmin'],
            'group' => 'Grup Menu'
        ],
        [
            'name' => 'Grup Menu Ubah',
            'roles' => ['superadmin'],
            'group' => 'Grup Menu'
        ],
        [
            'name' => 'Grup Menu Detail',
            'roles' => ['superadmin'],
            'group' => 'Grup Menu'
        ],
        [
            'name' => 'Grup Menu Hapus',
            'roles' => ['superadmin'],
            'group' => 'Grup Menu'
        ],

        // contoh crud
        [
            'name' => 'Contoh CRUD',
            'roles' => ['superadmin', 'admin', 'user'],
            'group' => 'Contoh CRUD'
        ],
        [
            'name' => 'Contoh CRUD Tambah',
            'roles' => ['superadmin', 'admin', 'user'],
            'group' => 'Contoh CRUD'
        ],
        [
            'name' => 'Contoh CRUD Impor Excel',
            'roles' => ['superadmin', 'admin', 'user'],
            'group' => 'Contoh CRUD'
        ],
        [
            'name' => 'Contoh CRUD Ubah',
            'roles' => ['superadmin', 'admin', 'user'],
            'group' => 'Contoh CRUD'
        ],
        [
            'name' => 'Contoh CRUD Detail',
            'roles' => ['superadmin', 'admin', 'user'],
            'group' => 'Contoh CRUD'
        ],
        [
            'name' => 'Contoh CRUD Hapus',
            'roles' => ['superadmin', 'admin', 'user'],
            'group' => 'Contoh CRUD'
        ],
        [
            'name' => 'Contoh CRUD Ekspor',
            'roles' => ['superadmin', 'admin', 'user'],
            'group' => 'Contoh CRUD'
        ],
        [
            'name' => 'Contoh CRUD Yajra',
            'roles' => ['superadmin', 'admin', 'user'],
            'group' => 'Contoh CRUD'
        ],
        [
            'name' => 'Contoh CRUD Ajax Yajra',
            'roles' => ['superadmin', 'admin', 'user'],
            'group' => 'Contoh CRUD'
        ],

        // bank
        [
            'name' => 'Bank',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Bank'
        ],
        [
            'name' => 'Bank Tambah',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Bank'
        ],
        [
            'name' => 'Bank Impor Excel',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Bank'
        ],
        [
            'name' => 'Bank Ubah',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Bank'
        ],
        [
            'name' => 'Bank Detail',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Bank'
        ],
        [
            'name' => 'Bank Hapus',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Bank'
        ],
        [
            'name' => 'Bank Ekspor',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Bank'
        ],
        // [
        //     'name' => 'Bank Yajra',
        //     'roles' => ['superadmin', 'admin', 'banker'],
        //     'group' => 'Bank'
        // ],
        // [
        //     'name' => 'Bank Ajax Yajra',
        //     'roles' => ['superadmin', 'admin', 'banker'],
        //     'group' => 'Bank'
        // ]

        // deposito bank
        [
            'name' => 'Deposito Bank',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Deposito Bank'
        ],
        [
            'name' => 'Deposito Bank Tambah',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Deposito Bank'
        ],
        [
            'name' => 'Deposito Bank Impor Excel',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Deposito Bank'
        ],
        [
            'name' => 'Deposito Bank Ubah',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Deposito Bank'
        ],
        [
            'name' => 'Deposito Bank Detail',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Deposito Bank'
        ],
        [
            'name' => 'Deposito Bank Hapus',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Deposito Bank'
        ],
        [
            'name' => 'Deposito Bank Ekspor',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Deposito Bank'
        ],
        // [
        //     'name' => 'Deposito Bank Yajra',
        //     'roles' => ['superadmin', 'admin', 'banker'],
        //     'group' => 'Bank'
        // ],
        // [
        //     'name' => 'Deposito Bank Ajax Yajra',
        //     'roles' => ['superadmin', 'admin', 'banker'],
        //     'group' => 'Bank'
        // ]

        // riwayat deposito bank
        [
            'name' => 'Riwayat Deposito Bank',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Riwayat Deposito Bank'
        ],
        // [
        //     'name' => 'Riwayat Deposito Bank Tambah',
        //     'roles' => ['superadmin', 'admin', 'banker'],
        //     'group' => 'Riwayat Deposito Bank'
        // ],
        // [
        //     'name' => 'Riwayat Deposito Bank Impor Excel',
        //     'roles' => ['superadmin', 'admin', 'banker'],
        //     'group' => 'Riwayat Deposito Bank'
        // ],
        // [
        //     'name' => 'Riwayat Deposito Bank Ubah',
        //     'roles' => ['superadmin', 'admin', 'banker'],
        //     'group' => 'Riwayat Deposito Bank'
        // ],
        // [
        //     'name' => 'Riwayat Deposito Bank Detail',
        //     'roles' => ['superadmin', 'admin', 'banker'],
        //     'group' => 'Riwayat Deposito Bank'
        // ],
        // [
        //     'name' => 'Riwayat Deposito Bank Hapus',
        //     'roles' => ['superadmin', 'admin', 'banker'],
        //     'group' => 'Riwayat Deposito Bank'
        // ],
        [
            'name' => 'Riwayat Deposito Bank Ekspor',
            'roles' => ['superadmin', 'admin', 'banker'],
            'group' => 'Riwayat Deposito Bank'
        ],
        // [
        //     'name' => 'Riwayat Deposito Bank Yajra',
        //     'roles' => ['superadmin', 'admin', 'banker'],
        //     'group' => 'Bank'
        // ],
        // [
        //     'name' => 'Riwayat Deposito Bank Ajax Yajra',
        //     'roles' => ['superadmin', 'admin', 'banker'],
        //     'group' => 'Bank'
        // ]
    ],

    'roles' => [
        'superadmin',
        'admin',
        'banker',
        'user'
    ],

    'use_setting' => '1',

    'settings' => [
        ['key' => 'application_name', 'value' => 'Laravel 11 Template'],
        ['key' => 'application_version', 'value' => '1.0.0'],
        ['key' => 'app_description', 'value' => 'Deskripsi sistem tulis disini'],
        ['key' => 'city', 'value' => 'Jember'],
        ['key' => 'company_name', 'value' => 'PT Anam Developer'],
        ['key' => 'country', 'value' => 'Indonesia'],
        [
            'key' => 'favicon',
            'value' => 'assets/icons/favicon.ico',
            'is_url' => true
        ],
        ['key' => 'logo', 'value' => 'assets/images/logo.png', 'is_url' => true],
        ['key' => 'since', 'value' => '2021'],

        [
            'key' => 'meta_author',
            'value' => 'Hairul Anam'
        ],
        ['key' => 'meta_description', 'value' => 'PT Anam Developer'],
        [
            'key' => 'meta_keywords',
            'value' => 'Sistem Informasi, Pemrograman, Github, PHP, Laravel, Stisla, Heroku, Gitlab, MySQL'
        ],

        [
            'key' => 'stisla_bg_home',
            'value' => 'stisla/assets/img/unsplash/andre-benz-1214056-unsplash.jpg',
            'is_url' => true
        ],
        [
            'key' => 'stisla_bg_login',
            'value' => 'stisla/assets/img/unsplash/eberhard-grossgasteiger-1207565-unsplash.jpg',
            'is_url' => true
        ],
        ['key' => 'stisla_sidebar_mini', 'value' => '0'],
        ['key' => 'stisla_login_template', 'value' => 'default'],
        ['key' => 'stisla_skin', 'value' => 'style'],

        ['key' => 'mail_provider', 'value' => 'mailtrap'],
        ['key' => 'mail_from_address', 'value' => 'anamkun@laraveltemplate.com'],
        ['key' => 'mail_from_name', 'value' => 'Superadmin'],

        ['key' => 'mail_mailtrap_host', 'value' => 'sandbox.smtp.mailtrap.io'],
        ['key' => 'mail_mailtrap_port', 'value' => '2525'],
        ['key' => 'mail_mailtrap_username', 'value' => '10c9ffc0387d2a'],
        ['key' => 'mail_mailtrap_password', 'value' => '11fd65a07d9f25'],
        ['key' => 'mail_mailtrap_encryption', 'value' => 'tls'],

        ['key' => 'mail_host', 'value' => 'smtp'],
        ['key' => 'mail_port', 'value' => '2525'],
        ['key' => 'mail_username', 'value' => '809d58dfa23ade'],
        ['key' => 'mail_password', 'value' => 'e9d1aa54a61db1'],
        ['key' => 'mail_encryption', 'value' => 'tls'],

        ['key' => 'mail_mailgun_domain', 'value' => 'test'],
        ['key' => 'mail_mailgun_api_key', 'value' => 'test'],

        ['key' => 'is_login_must_verified', 'value' => '1'],
        ['key' => 'is_active_register_page', 'value' => '1'],

        ['key' => 'is_forgot_password_send_to_email', 'value' => '1'],
        ['key' => 'google_captcha_site_key', 'value' => 'default_site_key'],
        ['key' => 'google_captcha_secret', 'value' => 'default_secret'],
        ['key' => 'is_google_captcha_login', 'value' => '1'],
        ['key' => 'is_google_captcha_register', 'value' => '1'],
        ['key' => 'is_google_captcha_forgot_password', 'value' => '1'],
        ['key' => 'is_google_captcha_reset_password', 'value' => '1'],

        ['key' => 'is_login_with_google', 'value' => '1'],
        ['key' => 'is_login_with_facebook', 'value' => '1'],
        ['key' => 'is_login_with_twitter', 'value' => '1'],
        ['key' => 'is_login_with_github', 'value' => '1'],

        ['key' => 'is_register_with_google', 'value' => '1'],
        ['key' => 'is_register_with_facebook', 'value' => '1'],
        ['key' => 'is_register_with_twitter', 'value' => '1'],
        ['key' => 'is_register_with_github', 'value' => '1'],

        ['key' => 'sso_google_client_id', 'value' => '-'],
        ['key' => 'sso_google_client_secret', 'value' => '-'],
        ['key' => 'sso_google_redirect', 'value' => '/auth/social/google/callback', 'is_url' => true],

        ['key' => 'sso_facebook_client_id', 'value' => '-'],
        ['key' => 'sso_facebook_client_secret', 'value' => '-'],
        ['key' => 'sso_facebook_redirect', 'value' => '/auth/social/facebook/callback', 'is_url' => true],

        ['key' => 'sso_twitter_client_id', 'value' => '-'],
        ['key' => 'sso_twitter_client_secret', 'value' => '-'],
        ['key' => 'sso_twitter_redirect', 'value' => '/auth/social/twitter/callback', 'is_url' => true],

        ['key' => 'sso_github_client_id', 'value' => '-'],
        ['key' => 'sso_github_client_secret', 'value' => '-'],
        ['key' => 'sso_github_redirect', 'value' => '/auth/social/github/callback', 'is_url' => true]
    ],

    'settings2' => [
        ['key' => 'application_name', 'value' => 'Laravel 11 Template'],
        ['key' => 'application_version', 'value' => '1.0.0'],
        ['key' => 'app_description', 'value' => 'Ini hanyalah sistem biasa'],
        ['key' => 'city', 'value' => 'Jember'],
        ['key' => 'company_name', 'value' => '[Nama Perusahaannya]'],
        ['key' => 'country', 'value' => 'Indonesia'],
        ['key' => 'app_is_demo', 'value' => '0'],
        [
            'key' => 'favicon',
            'value' => 'assets/icons/favicon.ico',
            'is_url' => true
        ],
        ['key' => 'logo', 'value' => 'assets/images/logo.png', 'is_url' => true],
        ['key' => 'since', 'value' => '2021'],

        [
            'key' => 'meta_author',
            'value' => 'Hairul Anam'
        ],
        ['key' => 'meta_description', 'value' => 'PT Anam Developer'],
        [
            'key' => 'meta_keywords',
            'value' => 'Sistem Informasi, Pemrograman, Github, PHP, Laravel, Stisla, Heroku, Gitlab, MySQL'
        ],

        [
            'key' => 'stisla_bg_home',
            'value' => 'stisla/assets/img/unsplash/andre-benz-1214056-unsplash.jpg',
            'is_url' => true
        ],
        [
            'key' => 'stisla_bg_login',
            'value' => 'stisla/assets/img/unsplash/eberhard-grossgasteiger-1207565-unsplash.jpg',
            'is_url' => true
        ],
        ['key' => 'stisla_sidebar_mini', 'value' => '0'],
        ['key' => 'stisla_login_template', 'value' => 'default'],
        ['key' => 'stisla_skin', 'value' => 'style'],

        ['key' => 'mail_provider', 'value' => 'mailtrap'],
        ['key' => 'mail_from_address', 'value' => 'anamkun@laraveltemplate.com'],
        ['key' => 'mail_from_name', 'value' => 'Superadmin'],

        ['key' => 'mail_mailtrap_host', 'value' => 'sandbox.smtp.mailtrap.io'],
        ['key' => 'mail_mailtrap_port', 'value' => '2525'],
        ['key' => 'mail_mailtrap_username', 'value' => '10c9ffc0387d2a'],
        ['key' => 'mail_mailtrap_password', 'value' => '11fd65a07d9f25'],
        ['key' => 'mail_mailtrap_encryption', 'value' => 'tls'],

        ['key' => 'mail_host', 'value' => 'smtp'],
        ['key' => 'mail_port', 'value' => '2525'],
        ['key' => 'mail_username', 'value' => '809d58dfa23ade'],
        ['key' => 'mail_password', 'value' => 'e9d1aa54a61db1'],
        ['key' => 'mail_encryption', 'value' => 'tls'],

        ['key' => 'mail_mailgun_domain', 'value' => 'test'],
        ['key' => 'mail_mailgun_api_key', 'value' => 'test'],

        ['key' => 'is_login_must_verified', 'value' => '0'],
        ['key' => 'is_active_register_page', 'value' => '0'],

        ['key' => 'is_forgot_password_send_to_email', 'value' => '0'],
        ['key' => 'google_captcha_site_key', 'value' => 'default_site_key'],
        ['key' => 'google_captcha_secret', 'value' => 'default_secret'],
        ['key' => 'is_google_captcha_login', 'value' => '0'],
        ['key' => 'is_google_captcha_register', 'value' => '0'],
        ['key' => 'is_google_captcha_forgot_password', 'value' => '0'],
        ['key' => 'is_google_captcha_reset_password', 'value' => '0'],

        ['key' => 'is_login_with_google', 'value' => '0'],
        ['key' => 'is_login_with_facebook', 'value' => '0'],
        ['key' => 'is_login_with_twitter', 'value' => '0'],
        ['key' => 'is_login_with_github', 'value' => '0'],

        ['key' => 'is_register_with_google', 'value' => '0'],
        ['key' => 'is_register_with_facebook', 'value' => '0'],
        ['key' => 'is_register_with_twitter', 'value' => '0'],
        ['key' => 'is_register_with_github', 'value' => '0'],

        ['key' => 'sso_google_client_id', 'value' => '-'],
        ['key' => 'sso_google_client_secret', 'value' => '-'],
        ['key' => 'sso_google_redirect', 'value' => '/auth/social/google/callback', 'is_url' => true],

        ['key' => 'sso_facebook_client_id', 'value' => '-'],
        ['key' => 'sso_facebook_client_secret', 'value' => '-'],
        ['key' => 'sso_facebook_redirect', 'value' => '/auth/social/facebook/callback', 'is_url' => true],

        ['key' => 'sso_twitter_client_id', 'value' => '-'],
        ['key' => 'sso_twitter_client_secret', 'value' => '-'],
        ['key' => 'sso_twitter_redirect', 'value' => '/auth/social/twitter/callback', 'is_url' => true],

        ['key' => 'sso_github_client_id', 'value' => '-'],
        ['key' => 'sso_github_client_secret', 'value' => '-'],
        ['key' => 'sso_github_redirect', 'value' => '/auth/social/github/callback', 'is_url' => true]
    ],

    'users' => [
        [
            'name'              => 'Hairul Anam Superadmin',
            'email'             => 'superadmin@laraveltemplate.com',
            'password'          => 'superadmin',
            'roles'             => ['superadmin', 'admin'],
            'email_verified_at' => '2021-04-06 04:06:00',
            'is_locked'         => 1,
            'phone_number'      => '6285322778935',
            'birth_date'        => '1998-04-08',
            'address'           => 'Jember'
        ],
        [
            'name'              => 'Hairul Anam Admin',
            'email'             => 'admin@laraveltemplate.com',
            'password'          => 'admin',
            'roles'             => ['admin'],
            'email_verified_at' => '2021-04-06 04:06:00',
            'phone_number'      => '6285322778935',
            'birth_date'        => '1998-04-08',
            'address'           => 'Jember'
        ],
        [
            'name'              => 'Hairul Anam User',
            'email'             => 'user@laraveltemplate.com',
            'password'          => 'user',
            'roles'             => ['user'],
            'email_verified_at' => '2021-04-06 04:06:00',
            'phone_number'      => '6285322778935',
            'birth_date'        => '1998-04-08',
            'address'           => 'Jember'
        ],
        [
            'name'              => 'Hairul Anam Banker',
            'email'             => 'banker@laraveltemplate.com',
            'password'          => 'banker',
            'roles'             => ['banker'],
            'email_verified_at' => '2021-04-06 04:06:00',
            'phone_number'      => '6285322778935',
            'birth_date'        => '1998-04-08',
            'address'           => 'Jember'
        ]
    ]


];
