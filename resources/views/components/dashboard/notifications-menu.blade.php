<div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->

    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            @if ($newCount)
            <span class="badge badge-warning navbar-badge">{{ $newCount }}</span>
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-header">{{$newCount}} Notifications</span>
            <div class="dropdown-divider"></div>
            @foreach ($notifications as $notification)
            <a href="{{ $notification->data['url'] }}?notification_id={{$notification->id}}" class="dropdown-item @if ($notification->unread()) text-bold @endif">
                <i class="{{$notification->data['icon']}} mr-2"></i>
                <p>{{$notification->data['body']}}</p>
                <span class="float-right text-muted text-sm">{{ $notification->created_at->longAbsoluteDiffForHumans()
                    }}</span>
            </a>
            <div class="dropdown-divider"></div>
            @endforeach
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
    </li>
</div>
