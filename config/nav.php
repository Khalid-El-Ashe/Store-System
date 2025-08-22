<?php
return [
    [
        'icon' => 'nav-icon fas fa-tachometer-alt',
        'route' => 'index',
        'title' => 'Dashboard',
        'active' => 'index'
    ],
    [
        'icon' => 'far fa-circle nav-icon',
        'route' => 'dashboard.categories.index',
        'title' => 'Categories',
        'badge' => 'New',
        'active' => 'dashboard.categories.*',
        'ability' => 'categories.view'
    ],
    [
        'icon' => 'far fa-circle nav-icon',
        'route' => 'dashboard.products.index',
        'title' => 'Products',
        'active' => 'dashboard.products.*',
        'ability' => 'products.view'
    ],
    [
        'icon' => 'far fa-circle nav-icon',
        'route' => 'dashboard.categories.index',
        'title' => 'Orders',
        'active' => 'dashboard.orders.*',
        'ability' => 'orders.view'
    ],
    [
        'icon' => 'far fa-shield nav-icon',
        'route' => 'dashboard.roles.index',
        'title' => 'Roles',
        'active' => 'dashboard.roles.*',
        'ability' => 'roles.view'
    ],
];
