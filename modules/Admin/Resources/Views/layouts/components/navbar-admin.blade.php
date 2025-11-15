@if (!isset($_COOKIE['k_language']) || $_COOKIE['k_language'] == 'undefined')
    @php
        $cookie_name = 'k_language';
        $cookie_value = 'id';
        setcookie($cookie_name, $cookie_value, time() + 86400 * 30, '/');
    @endphp
@endif

@section('navbars')
    <div id="kt_aside" class="aside card" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_toggle">
        <!--begin::Aside menu-->
        <div class="aside-menu flex-column-fluid px-4">
            <!--begin::Aside Menu-->
            <div class="hover-scroll-overlay-y mh-100 my-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="{default: '#kt_aside_footer', lg: '#kt_header, #kt_aside_footer'}" data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu" data-kt-scroll-offset="{default: '5px', lg: '75px'}">
                <!--begin::Menu-->
                <div class="menu-column menu-rounded menu-sub-indention fw-semibold fs-6 menu" id="#kt_aside_menu" data-kt-menu="true">
                    <div class="menu-item pt-3">
                        <a class="menu-link" href="{{ route('admin::dashboard-cms') }}">
                            <span class="menu-icon">
                                <i class="mdi mdi-view-dashboard fs-2"></i>
                            </span>
                            <span class="menu-title">
                                Dashboard
                            </span>
                        </a>
                    </div>
                    @if (env('BUG') == 0)
                        @if (@$_COOKIE['k_status'] == 1)
                            <!--begin:Menu item-->

                            @php
                                $kount_menu = json_decode(get_menu_order(), false);
                                $showing = '';
                                $showing_child = '';
                                //{{ isset(get_menu_text($_GET['id_menu'])[$_GET['id_menu']]) && get_menu_text($_GET['id_menu'])[$_GET['id_menu']] == $val->id ? 'hover show' : '' }}
                            @endphp


                            @foreach ($kount_menu as $index => $val)
                                @if (isset($val->children) && isset(get_needed($val->id)[0]->type))
                                    <div data-kt-menu-trigger="click"
                                        class="menu-item menu-accordion {{ isset($_GET['id_menu']) && isset(get_menu_text($_GET['id_menu'])[$_GET['id_menu']]) && get_menu_text($_GET['id_menu'])[$_GET['id_menu']] == $val->id ? 'hover show' : (isset($_GET['cat_id']) && isset(get_menu_text($_GET['cat_id'])[$_GET['cat_id']]) && get_menu_text($_GET['cat_id'])[$_GET['cat_id']] == $val->id ? 'hover show' : (!empty(extras_custom(parse_url($_SERVER['REQUEST_URI'])['path'])->id) && get_menu_text(extras_custom(parse_url($_SERVER['REQUEST_URI'])['path'])->id) !== '' ? (isset(extras_custom(parse_url($_SERVER['REQUEST_URI'])['path'])->id) && get_menu_text(extras_custom(parse_url($_SERVER['REQUEST_URI'])['path'])->id)[extras_custom(parse_url($_SERVER['REQUEST_URI'])['path'])->id] == $val->id ? 'hover show' : '') : '')) }} pt-3">
                                        <!--begin:Menu link-->
                                        <span class="menu-link">
                                            @if (isset(get_needed(@$val->id)[0]->icon))
                                                <span class="menu-icon">
                                                    <i class="{{ get_needed(@$val->id)[0]->icon }} fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                </span>
                                            @endif
                                            <span class="menu-title">
                                                @if (isset(get_needed($val->id)[0]->type) && isset(json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']]) && get_needed($val->id)[0]->type == '1')
                                                    {{ json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']] }}
                                                @elseif(isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '5')
                                                    {{ json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']] }}
                                                @endif

                                            </span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <!--end:Menu link-->
                                        <!--begin:Menu sub-->
                                        <div class="menu-sub menu-sub-accordion">

                                            @if (isset($val->children))
                                                @foreach ($val->children as $indext => $valuet)
                                                    {{-- <span class="menu-link">
                        <span class="menu-bullet">
                          <span class="{{get_needed($valuet->id)[0]->icon}}"></span>
                        </span>
                        <span class="menu-title">{{get_needed($valuet->id)[0]->title}}</span>
                        <span class="menu-arrow"></span>
                      </span> --}}

                                                    @if ((isset(get_needed($valuet->id)[0]->type) && isset(json_decode(get_needed($valuet->id)[0]->title, true)[@$_COOKIE['k_language']]) && get_needed($valuet->id)[0]->type == '2') || (get_needed($valuet->id)[0]->type == '7' && isset(json_decode(get_needed($valuet->id)[0]->title, true)[@$_COOKIE['k_language']])))
                                                        <!--begin:Menu item-->
                                                        <div class="menu-item pt-3">
                                                            <!--begin:Menu link-->
                                                            <a class="menu-link {{ isset($_GET['id_menu']) && $_GET['id_menu'] == $valuet->id ? 'active' : '' }}" href="{{ route('admin::builder.posting.index', ['id_menu' => $valuet->id]) }}">
                                                                <span class="menu-bullet">
                                                                    <span class="{{ get_needed($valuet->id)[0]->icon }}"></span>
                                                                </span>
                                                                <span class="menu-title">{{ json_decode(get_needed($valuet->id)[0]->title, true)[@$_COOKIE['k_language']] }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @elseif(isset(get_needed($valuet->id)[0]->type) && get_needed($valuet->id)[0]->type == '3')
                                                        <div class="menu-item pt-3">
                                                            <!--begin:Menu link-->
                                                            <a class="menu-link {{ isset($_GET['cat_id']) && $_GET['cat_id'] == $valuet->id ? 'active' : '' }}" href="{{ route('admin::builder.category.index', ['cat_id' => $valuet->id]) }}">
                                                                <span class="menu-bullet">
                                                                    <span class="{{ get_needed($valuet->id)[0]->icon }}"></span>
                                                                </span>
                                                                <span class="menu-title">{{ get_needed($valuet->id)[0]->title }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @elseif(isset(get_needed($valuet->id)[0]->type) && get_needed($valuet->id)[0]->type == '4')
                                                        <div class="menu-item pt-3">
                                                            <!--begin:Menu link-->
                                                            <a class="menu-link {{ isset($_GET['id_menu']) && $_GET['id_menu'] == $valuet->id ? 'active' : '' }}" href="{{ route('admin::builder.posting.index', ['id_menu' => $valuet->id]) }}">
                                                                <span class="menu-bullet">
                                                                    <span class="{{ get_needed($valuet->id)[0]->icon }}"></span>
                                                                </span>
                                                                <span class="menu-title">{{ json_decode(get_needed($valuet->id)[0]->title, true)[@$_COOKIE['k_language']] }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @elseif(isset(get_needed($valuet->id)[0]->type) && get_needed($valuet->id)[0]->type == '5')
                                                        <!--end:Menu item-->
                                                        <div class="menu-item pt-3">
                                                            <!--begin:Menu link-->
                                                            <a class="menu-link {{ isset($_GET['id_menu']) && $_GET['id_menu'] == $valuet->id ? 'active' : (isset(extras_custom(parse_url($_SERVER['REQUEST_URI'])['path'])->id) && extras_custom(parse_url($_SERVER['REQUEST_URI'])['path'])->id == $valuet->id ? 'active' : '') }}" href="{{ url(get_menu_id($valuet->id)->custom_links) }}">
                                                                <span class="menu-bullet">
                                                                    <span class="{{ get_needed($valuet->id)[0]->icon }}"></span>
                                                                </span>
                                                                <span class="menu-title">{{ json_decode(get_needed($valuet->id)[0]->title, true)[@$_COOKIE['k_language']] }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @elseif(isset(get_needed($valuet->id)[0]->type) && get_needed($valuet->id)[0]->type == '8')
                                                        <!--end:Menu item-->
                                                        <div class="menu-item pt-3">
                                                            <!--begin:Menu link-->
                                                            <a class="menu-link {{ isset($_GET['id_menu']) && $_GET['id_menu'] == $valuet->id ? 'active' : '' }}" href="{{ route('admin::builder.posting.index', ['id_menu' => $valuet->id]) }}">
                                                                <span class="menu-bullet">
                                                                    <span class="{{ get_needed($valuet->id)[0]->icon }}"></span>
                                                                </span>
                                                                <span class="menu-title">{{ json_decode(get_needed($valuet->id)[0]->title, true)[@$_COOKIE['k_language']] }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @elseif(isset(get_needed($valuet->id)[0]->type) && get_needed($valuet->id)[0]->type == '9')
                                                        <div class="menu-item pt-3">
                                                            <!--begin:Menu link-->
                                                            <a class="menu-link {{ isset($_GET['id_menu']) && $_GET['id_menu'] == $valuet->id ? 'active' : '' }}" href="{{ route('admin::builder.posting.index', ['id_menu' => $valuet->id]) }}">
                                                                <span class="menu-bullet">
                                                                    <span class="{{ get_needed($valuet->id)[0]->icon }}"></span>
                                                                </span>
                                                                <span class="menu-title">{{ json_decode(get_needed($valuet->id)[0]->title, true)[@$_COOKIE['k_language']] }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @else
                                                        <div class="" style="" kt-hidden-height="125">

                                                            <div data-kt-menu-trigger="click"
                                                                class="menu-item menu-accordion {{ isset($_GET['id_menu']) && isset(get_menu_text($_GET['id_menu'], true)[$_GET['id_menu']]) && get_menu_text($_GET['id_menu'], true)[$_GET['id_menu']] == $valuet->id ? 'hover show' : (isset($_GET['cat_id']) && isset(get_menu_text($_GET['cat_id'], true)[$_GET['cat_id']]) && get_menu_text($_GET['cat_id'], true)[$_GET['cat_id']] == $valuet->id ? 'hover show' : (!empty(extras_custom(parse_url($_SERVER['REQUEST_URI'])['path'])->id) ? '' : (isset(extras_custom(parse_url($_SERVER['REQUEST_URI'])['path'])->id) && get_menu_text(extras_custom(parse_url($_SERVER['REQUEST_URI'])['path'])->id)[extras_custom(parse_url($_SERVER['REQUEST_URI'])['path'])->id] == $valuet->id ? 'hover show' : ''))) }}">
                                                                <span class="menu-link">
                                                                    <span class="menu-bullet">
                                                                        <span class="{{ get_needed($valuet->id)[0]->icon }}"></span>
                                                                    </span>
                                                                    <span class="menu-title">{{ json_decode(get_needed($valuet->id)[0]->title, true)[@$_COOKIE['k_language']] }}</span>
                                                                    <span class="menu-arrow"></span>
                                                                </span>

                                                                <div class="menu-sub menu-sub-accordion">
                                                                    <!--begin:Menu item-->
                                                                    @if (isset($valuet->children))
                                                                        @foreach ($valuet->children as $key => $vl)
                                                                            <div class="menu-item">
                                                                                <!--begin:Menu link-->
                                                                                @if ((isset(get_needed($vl->id)[0]->type) && get_needed($vl->id)[0]->type == '2') || get_needed($vl->id)[0]->type == '7')
                                                                                    <a class="menu-link {{ isset($_GET['id_menu']) && $_GET['id_menu'] == $vl->id ? 'active' : '' }}" href="{{ route('admin::builder.posting.index', ['id_menu' => $vl->id]) }}">
                                                                                        <span class="menu-bullet">
                                                                                            <span class="{{ get_needed($vl->id)[0]->icon }}"></span>
                                                                                        </span>
                                                                                        <span class="menu-title">{{ json_decode(get_needed($vl->id)[0]->title, true)[@$_COOKIE['k_language']] }}</span>
                                                                                    </a>
                                                                                @elseif(isset(get_needed($vl->id)[0]->type) && get_needed($vl->id)[0]->type == '3')
                                                                                    <a class="menu-link {{ isset($_GET['cat_id']) && $_GET['cat_id'] == $vl->id ? 'active' : '' }}" href="{{ route('admin::builder.category.index', ['cat_id' => $vl->id]) }}">
                                                                                        <span class="menu-bullet">
                                                                                            <span class="{{ get_needed($vl->id)[0]->icon }}"></span>
                                                                                        </span>
                                                                                        <span class="menu-title">{{ get_needed($vl->id)[0]->title }}</span>
                                                                                    </a>
                                                                                @elseif(isset(get_needed($vl->id)[0]->type) && get_needed($vl->id)[0]->type == '4')
                                                                                    <a class="menu-link {{ isset($_GET['id_menu']) && $_GET['id_menu'] == $vl->id ? 'active' : '' }}" href="{{ route('admin::builder.posting.index', ['id_menu' => $vl->id]) }}">
                                                                                        <span class="menu-bullet">
                                                                                            <span class="{{ get_needed($vl->id)[0]->icon }}"></span>
                                                                                        </span>
                                                                                        <span class="menu-title">{{ json_decode(get_needed($vl->id)[0]->title, true)[@$_COOKIE['k_language']] }}</span>
                                                                                    </a>
                                                                                @elseif(isset(get_needed($vl->id)[0]->type) && get_needed($vl->id)[0]->type == '5')
                                                                                    <a class="menu-link {{ isset($_GET['id_menu']) && $_GET['id_menu'] == $vl->id ? 'active' : '' }}" href="{{ url(get_menu_id($vl->id)->custom_links) }}">
                                                                                        <span class="menu-bullet">
                                                                                            <span class="{{ get_needed($vl->id)[0]->icon }}"></span>
                                                                                        </span>
                                                                                        <span class="menu-title">{{ json_decode(get_needed($vl->id)[0]->title, true)[@$_COOKIE['k_language']] }}</span>
                                                                                    </a>
                                                                                @elseif(isset(get_needed($vl->id)[0]->type) && get_needed($vl->id)[0]->type == '9')
                                                                                    <a class="menu-link {{ isset($_GET['id_menu']) && $_GET['id_menu'] == $vl->id ? 'active' : '' }}" href="{{ route('admin::builder.posting.index', ['id_menu' => $vl->id]) }}">
                                                                                        <span class="menu-bullet">
                                                                                            <span class="{{ get_needed($vl->id)[0]->icon }}"></span>
                                                                                        </span>
                                                                                        <span class="menu-title">{{ json_decode(get_needed($vl->id)[0]->title, true)[@$_COOKIE['k_language']] }}</span>
                                                                                    </a>
                                                                                @endif

                                                                                <!--end:Menu link-->
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                    <!--end:Menu item-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="menu-item pt-3">
                                                    @if (isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type != '1')
                                                        <a class="menu-link" href="{{ url(get_menu_id($val->id)->custom_links) }}">
                                                        @else
                                                            <a class="menu-link" href="{{ route('admin::builder.posting.index', ['id_menu' => $val->id]) }}">
                                                    @endif

                                                    @if (isset(get_needed(@$val->id)[0]->icon))
                                                        <span class="menu-icon">
                                                            <i class="{{ get_needed(@$val->id)[0]->icon }} fs-2">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                                <span class="path3"></span>
                                                                <span class="path4"></span>
                                                            </i>
                                                        </span>
                                                        <span class="menu-title">
                                                            @if (isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '1')
                                                                {{ json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']] }}
                                                            @elseif(isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '5')
                                                                {{ json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']] }}
                                                            @elseif(isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '6')
                                                                {{ json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']] }}
                                                            @elseif(isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '7')
                                                                {{ json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']] }}
                                                            @endif
                                                        </span>
                                                    @endif
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <!--end:Menu sub-->
                                    </div>
                                @else
                                    <div class="menu-item pt-2">
                                        @if (isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type != '1')
                                            <a class="menu-link {{ isset(parse_url($_SERVER['REQUEST_URI'])['path']) && parse_url($_SERVER['REQUEST_URI'])['path'] == '/' . get_menu_id($val->id)->custom_links ? 'active' : '' }}" href="{{ url(get_menu_id($val->id)->custom_links) }}">
                                            @else
                                                <a class="menu-link" href="{{ route('admin::builder.posting.index', ['id_menu' => $val->id]) }}">
                                        @endif

                                        <span class="menu-icon">
                                            @if (isset(get_needed(@$val->id)[0]->icon))
                                                <i class="{{ get_needed(@$val->id)[0]->icon }} fs-2"></i>
                                            @endif
                                        </span>

                                        <span class="menu-title">
                                            @if (isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '1')
                                                {{ json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']] }}
                                            @elseif(isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '2')
                                                {{ json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']] }}
                                            @elseif(isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '5')
                                                {{ json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']] }}
                                            @elseif(isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '6')
                                                {{ json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']] }}
                                            @endif
                                        </span>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="menu-item pt-3">
                                <a class="menu-link" href="{{ route('admin::builder.menu.index') }}">
                                    <span class="menu-icon">
                                        <i class="mdi mdi-menu-open fs-2"></i>
                                    </span>
                                    <span class="menu-title">
                                        Menu Builder
                                    </span>
                                </a>
                            </div>

                            <div class="menu-item pt-3">
                                <a class="menu-link" href="{{ route('admin::builder.order.index') }}">
                                    <span class="menu-icon">
                                        <i class="mdi mdi-sort fs-2"></i>
                                    </span>
                                    <span class="menu-title">
                                        Menu Order
                                    </span>
                                </a>
                            </div>

                            <div class="menu-item pt-3">
                                <a class="menu-link" href="{{ route('admin::builder.role.index') }}">
                                    <span class="menu-icon">
                                        <i class="mdi mdi-cog-box fs-2"></i>
                                    </span>
                                    <span class="menu-title">
                                        Menu Role
                                    </span>
                                </a>
                            </div>

                            <div class="menu-item pt-3">
                                <a class="menu-link" href="{{ route('admin::configure.categoryzation_name.index') }}">
                                    <span class="menu-icon">
                                        <i class="mdi mdi-application-edit fs-2"></i>
                                    </span>
                                    <span class="menu-title">
                                        Category
                                    </span>
                                </a>
                            </div>

                            <div class="menu-item pt-3">
                                <a class="menu-link" href="{{ route('admin::configure.tags.index') }}">
                                    <span class="menu-icon">
                                        <i class="mdi mdi-tag fs-2"></i>
                                    </span>
                                    <span class="menu-title">
                                        Tags
                                    </span>
                                </a>
                            </div>

                            <div class="menu-item pt-3">
                                <a class="menu-link" href="{{ route('admin::custom.contact.index') }}">
                                    <span class="menu-icon">
                                        <i class="mdi mdi-phone fs-2"></i>
                                    </span>
                                    <span class="menu-title">
                                        Contact
                                    </span>
                                </a>
                            </div>

                            <div class="menu-item pt-3">
                                <a class="menu-link" href="{{ route('admin::custom.news_subcribe.index') }}">
                                    <span class="menu-icon">
                                        <i class="mdi mdi-newspaper-variant fs-2"></i>
                                    </span>
                                    <span class="menu-title">
                                        Newslatter & Subscription
                                    </span>
                                </a>
                            </div>

                            <div class="menu-item pt-3">
                                <a class="menu-link" href="{{ route('admin::custom.site_configuration.index') }}">
                                    <span class="menu-icon">
                                        <i class="mdi mdi-web fs-2"></i>
                                    </span>
                                    <span class="menu-title">
                                        Site Configuration
                                    </span>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
                <!--end::Menu-->
            </div>
        </div>
        <!--end::Aside menu-->
    </div>
@endsection
