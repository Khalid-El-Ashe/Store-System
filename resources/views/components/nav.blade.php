<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        @foreach ($items as $item)
            @if (isset($item['route']) && Route::has($item['route']))
            <li class="nav-item">
                <a href="{{ route($item['route']) }}" class="nav-link {{ Route::is($item['active']) ? 'active' : '' }}">
                    <i class="{{ $item['icon'] }}"></i>
                    <p>
                        {{ $item['title'] }}
                        @if (isset($item['badge']))
                        <span class="right badge badge-danger">{{ $item['badge'] }}</span>
                        @endif
                    </p>
                </a>
            </li>
            @endif
        @endforeach

        <li class="nav-header">Settings</li>
        <li class="nav-item">
            <a href="" class="nav-link">
                <i class="nav-icon fas fa-lock"></i>
                <p>Change Password</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="nav-link" style="border: none; background: none; padding: 0;">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </button>
            </form> --}}
        </li>
    </ul>
</nav>
