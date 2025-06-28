<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        @foreach ($items as $item)
        <li class="nav-item">
            <a href="{{route($item['route'])}}" class="nav-link {{ Route::is($item['active'])? 'active' : '' }} ">
                <i class="{{$item['icon']}}"></i>
                <p>
                    {{$item['title']}}
                    @if (isset($item['badge']))
                    <span class="right badge badge-danger">{{ $item['badge'] }}</span>
                    @endif
                </p>
            </a>
        </li>
        @endforeach

        <li class="nav-header">Settings</li>
        <li class="nav-item">
            <a href="" class="nav-link">
                <i class="nav-icon fas fa-lock"></i>
                <p>Change Password</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
            </a>
        </li>
    </ul>
</nav>