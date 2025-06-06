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
        'route' => 'categories.index',
        'title' => 'Categories',
        'badge' => 'New',
        'active' => 'categories.*'
    ],
    [
        'icon' => 'far fa-circle nav-icon',
        'route' => 'categories.index',
        'title' => 'Products',
        'active' => 'products.*'
    ],
    [
        'icon' => 'far fa-circle nav-icon',
        'route' => 'categories.index',
        'title' => 'Orders',
        'active' => 'orders.*'
    ],
];
