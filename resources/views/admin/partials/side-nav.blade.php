<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <a href="{{ setRoute('admin.dashboard') }}" class="sidebar-main-logo">
                <img src="{{ get_logo($basic_settings) }}" data-white_img="{{ get_logo($basic_settings,'white') }}"
                data-dark_img="{{ get_logo($basic_settings,'dark') }}" alt="logo">
            </a>
            <button class="sidebar-menu-bar">
                <i class="fas fa-exchange-alt"></i>
            </button>
        </div>
        <div class="sidebar-user-area">
            <div class="sidebar-user-thumb">
                <a href="{{ setRoute('admin.profile.index') }}"><img src="{{ get_image(Auth::user()->image,'admin-profile','profile') }}" alt="user"></a>
            </div>
            <div class="sidebar-user-content">
                <h6 class="title">{{ Auth::user()->fullname }}</h6>
                <span class="sub-title">{{ Auth::user()->getRolesString() }}</span>
            </div>
        </div>
        @php
            $current_route = Route::currentRouteName();
        @endphp
        <div class="sidebar-menu-wrapper">
            <ul class="sidebar-menu">

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.dashboard',
                    'title'     => "Dashboard",
                    'icon'      => "menu-icon las la-rocket",
                ])
                
                {{-- Section Default --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => "Default",
                    'group_links'       => [
                        [
                            'title'     => "Setup Currency",
                            'route'     => "admin.currency.index",
                            'icon'      => "menu-icon las la-coins",
                        ],
                        [
                            'title'     => "Setup Area",
                            'route'     => "admin.area.index",
                            'icon'      => "menu-icon las la-coins",
                        ],
                        [
                            'title'     => "Parlour List",
                            'route'     => "admin.parlour.list.index",
                            'icon'      => "menu-icon las la-coins",
                        ]
                    ]
                ])

                {{-- Interface Panel --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => "Interface Panel",
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => "User Care",
                                'icon'      => "menu-icon las la-user-edit",
                                'links'     => [
                                    [
                                        'title'     => "Active Users",
                                        'route'     => "admin.users.active",
                                    ],
                                    [
                                        'title'     => "Email Unverified",
                                        'route'     => "admin.users.email.unverified",
                                    ],
                                    [
                                        'title'     => "KYC Unverified",
                                        'route'     => "admin.users.kyc.unverified", 
                                    ],
                                    [
                                        'title'     => "All Users",
                                        'route'     => "admin.users.index",
                                    ],
                                    [
                                        'title'     => "Email To Users",
                                        'route'     => "admin.users.email.users",
                                    ],
                                    [
                                        'title'     => "Banned Users",
                                        'route'     => "admin.users.banned",
                                    ]
                                ],
                            ],
                            [
                                'title'             => "Admin Care",
                                'icon'              => "menu-icon las la-user-shield",
                                'links'     => [
                                    [
                                        'title'     => "All Admin",
                                        'route'     => "admin.admins.index",
                                    ],
                                    [
                                        'title'     => "Admin Role",
                                        'route'     => "admin.admins.role.index",
                                    ],
                                    [
                                        'title'     => "Role Permission",
                                        'route'     => "admin.admins.role.permission.index", 
                                    ],
                                    [
                                        'title'     => "Email To Admin",
                                        'route'     => "admin.admins.email.admins",
                                    ]
                                ],
                            ],
                        ],

                    ]
                ])

                {{-- Section Settings --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => "Settings",
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => "Web Settings",
                                'icon'      => "menu-icon lab la-safari",
                                'links'     => [
                                    [
                                        'title'     => "Basic Settings",
                                        'route'     => "admin.web.settings.basic.settings",
                                    ],
                                    [
                                        'title'     => "Image Assets",
                                        'route'     => "admin.web.settings.image.assets",
                                    ],
                                    [
                                        'title'     => "Setup SEO",
                                        'route'     => "admin.web.settings.setup.seo", 
                                    ]
                                ],
                            ],
                            [
                                'title'             => "App Settings",
                                'icon'              => "menu-icon las la-mobile",
                                'links'     => [
                                    [
                                        'title'     => "Splash Screen",
                                        'route'     => "admin.app.settings.splash.screen",
                                    ],
                                    [
                                        'title'     => "Onboard Screen",
                                        'route'     => "admin.app.settings.onboard.screens",
                                    ],
                                    [
                                        'title'     => "App URLs",
                                        'route'     => "admin.app.settings.urls", 
                                    ],
                                ],
                            ],
                        ],
                    ]
                ])
                
                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.languages.index',
                    'title'     => "Languages",
                    'icon'      => "menu-icon las la-language",
                ])

                {{-- Verification Center --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => "Verification Center",
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => "Setup Email",
                                'icon'      => "menu-icon las la-envelope-open-text",
                                'links'     => [
                                    [
                                        'title'     => "Email Method",
                                        'route'     => "admin.setup.email.config",
                                    ],
                                    // [
                                    //     'title'     => "Default Template",
                                    //     'route'     => "admin.setup.email.template.default",
                                    // ]
                                ],
                            ]
                        ],

                    ]
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.setup.kyc.index',
                    'title'     => "Setup KYC",
                    'icon'      => "menu-icon las la-clipboard-list",
                ])

                @if (admin_permission_by_name("admin.setup.sections.section"))
                    <li class="sidebar-menu-header">Setup Web Content</li>
                    @php
                        $current_url = URL::current();

                        $setup_section_childs  = [
                            setRoute('admin.setup.sections.section','slider'),
                            setRoute('admin.setup.sections.section','how-it-work'),
                            setRoute('admin.setup.sections.section','testimonial'),
                            setRoute('admin.setup.sections.section','statistic'),
                            setRoute('admin.setup.sections.section','photo-gallery'),
                            setRoute('admin.setup.sections.section','download-app'),
                            setRoute('admin.setup.sections.section','footer'),
                            setRoute('admin.setup.sections.section','contact'),
                            setRoute('admin.setup.sections.section','about'),
                            setRoute('admin.setup.sections.section','faq'),
                            setRoute('admin.setup.sections.section','service'),
                            setRoute('admin.setup.sections.section','blog'),
                        ];
                    @endphp

                    <li class="sidebar-menu-item sidebar-dropdown @if (in_array($current_url,$setup_section_childs)) active @endif">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-terminal"></i>
                            <span class="menu-title">{{ __("Setup Section") }}</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class="sidebar-menu-item">
                                <a href="{{ setRoute('admin.setup.sections.section','slider') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','slider')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Slider Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','how-it-work') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','how-it-work')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("How Its Work Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','testimonial') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','testimonial')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Testimonial Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','statistic') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','statistic')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Statistic Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','photo-gallery') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','photo-gallery')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Photo Gallery Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','download-app') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','download-app')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Download App Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','footer') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','footer')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Footer Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','contact') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','contact')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Contact Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','about') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','about')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("About Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','faq') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','faq')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Faq Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','service') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','service')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Service Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','blog') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','blog')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Blog Section") }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.setup.pages.index',
                    'title'     => "Setup Pages",
                    'icon'      => "menu-icon las la-file-alt",
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => "admin.contact.index",
                    'title'     => "Contact Messages",
                    'icon'      => "menu-icon las la-sms",
                ])
                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.extensions.index',
                    'title'     => "Extensions",
                    'icon'      => "menu-icon las la-puzzle-piece",
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.usefull.links.index',
                    'title'     => "Useful Links",
                    'icon'      => "menu-icon las la-link",
                ])

                @if (admin_permission_by_name("admin.payment.gateway.view"))
                    <li class="sidebar-menu-header">Payment Methods</li>
                    @php
                        $payment_add_money_childs  = [
                            setRoute('admin.payment.gateway.view',['add-money','automatic']),
                            setRoute('admin.payment.gateway.view',['add-money','manual']),
                        ]
                    @endphp
                    <li class="sidebar-menu-item sidebar-dropdown @if (in_array($current_url,$payment_add_money_childs)) active @endif">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-funnel-dollar"></i>
                            <span class="menu-title">Add Money</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class="sidebar-menu-item">
                                <a href="{{ setRoute('admin.payment.gateway.view',['add-money','automatic']) }}" class="nav-link @if ($current_url == setRoute('admin.payment.gateway.view',['add-money','automatic'])) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">Automatic</span>
                                </a>
                                <a href="{{ setRoute('admin.payment.gateway.view',['add-money','manual']) }}" class="nav-link @if ($current_url == setRoute('admin.payment.gateway.view',['add-money','manual'])) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">Manual</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-menu-item @if ($current_url == setRoute('admin.payment.gateway.view',['money-out','manual'])) active @endif">
                        <a href="{{ setRoute('admin.payment.gateway.view',['money-out','manual']) }}">
                            <i class="menu-icon las la-print"></i>
                            <span class="menu-title">Money Out</span>
                        </a>
                    </li>
                @endif

                {{-- Notifications --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => "Notification",
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => "Push Notification",
                                'icon'      => "menu-icon las la-bell",
                                'links'     => [
                                    [
                                        'title'     => "Setup Notification",
                                        'route'     => "admin.push.notification.config",
                                    ],
                                    [
                                        'title'     => "Send Notification",
                                        'route'     => "admin.push.notification.index",
                                    ]
                                ],
                            ]
                        ],

                    ]
                ])

                @php
                    $bonus_routes = [
                        'admin.cookie.index',
                        'admin.server.info.index',
                        'admin.cache.clear',
                    ];
                @endphp 

                @if (admin_permission_by_name_array($bonus_routes))   
                    <li class="sidebar-menu-header">Bonus</li>
                @endif

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.cookie.index',
                    'title'     => "GDPR Cookie",
                    'icon'      => "menu-icon las la-cookie-bite",
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.server.info.index',
                    'title'     => "Server Info",
                    'icon'      => "menu-icon las la-sitemap",
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.cache.clear',
                    'title'     => "Clear Cache",
                    'icon'      => "menu-icon las la-broom",
                ])
            </ul>
        </div>
    </div>
</div>
