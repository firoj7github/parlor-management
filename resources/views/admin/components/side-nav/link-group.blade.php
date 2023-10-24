@php
    $current_route = Route::currentRouteName();
@endphp
@if (isset($group_links) && is_array($group_links))

    @php
        $collect_routes = [];
        $d_routes = data_get($group_links,"dropdown.*.links.*.route") ?? [];
        $l_routes = data_get($group_links,"links.*.route") ?? [];
        $n_routes = data_get($group_links,"*.route") ?? [];
        $collect_routes = array_merge($collect_routes,$d_routes,$l_routes,$n_routes);
        $t_access_permission = admin_permission_by_name_array($collect_routes);
    @endphp

    @if ($t_access_permission === true)
        <li class="sidebar-menu-header">{{ $group_title ?? "" }}</li>
    @endif

    @foreach ($group_links ?? [] as $key => $group_item)

        @if ($key == "dropdown")
            @php
                $dropdown_items = [];
            @endphp
            @foreach ($group_item as $item)
                @if (isset($item['links']) && count($item['links']) > 0)
                    @php
                        $routes = Arr::pluck($item['links'],"route");
                        $access_permission = admin_permission_by_name_array($routes);
                        if($access_permission == true) {

                            $dropdown_items[] = [
                                'title'     => $item['title'],
                                'links'     => $item['links'],
                                'routes'    => $routes,
                                'icon'      => $item['icon'] ?? "",
                            ];
                        }
                    @endphp
                @endif
            @endforeach

            @foreach ($dropdown_items as $item)
                <li class="sidebar-menu-item sidebar-dropdown @if (in_array($current_route,$item['routes'])) active @endif">
                    <a href="javascript:void(0)">
                        <i class="{{ $item['icon'] ?? "" }}"></i>
                        <span class="menu-title">{{ $item['title'] ?? "" }}</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li class="sidebar-menu-item">
                            @foreach ($item['links'] as $nav_item)
                                @include('admin.components.side-nav.dropdown-link',[
                                    'title'         => $nav_item['title'],
                                    'route'         => $nav_item['route'],
                                ])
                            @endforeach
                        </li>
                    </ul>
                </li>
            @endforeach
        @elseif ($key == "links")
            @foreach ($group_item as $link)
                @php
                    $access_permission = admin_permission_by_name($link['route']);
                @endphp

                @if (isset($access_permission) && $access_permission === true)
                    @include('admin.components.side-nav.link',[
                        'title'     => $link['title'],
                        'route'     => $link['route'],
                        'icon'      => $link['icon'],
                    ])
                @endif
            @endforeach
        @else
            @php
                $access_permission = admin_permission_by_name($group_item['route']);
            @endphp

            @if (isset($access_permission) && $access_permission === true)
                @include('admin.components.side-nav.link',[
                    'title'     => $group_item['title'],
                    'route'     => $group_item['route'],
                    'icon'      => $group_item['icon'],
                ])
            @endif   
        @endif
    @endforeach

@endif