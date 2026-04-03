<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        @php
            $sidebarLogo = setting('site_logo');
            $siteName = setting('site_name');
        @endphp
        <!--begin::Logo-->
        <a href="{{ route('admin.dashboard') }}">
            <img alt="Logo" src="{{ $sidebarLogo ? asset('storage/' . $sidebarLogo) : asset('admin/assets/media/logos/logo.svg') }}" class="h-25px logo" />
            <span class="fw-bold text-white ">{{ $siteName ?? 'Ravaa Invitation' }}</span>
        </a>
        <!--end::Logo-->
        <!--begin::Aside toggler-->
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="black" />
                    <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="black" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Aside toggler-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
                <div class="menu-item">
                    <div class="menu-content pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Dashboard</span>
                    </div>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect x="2" y="2" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>
                <!-- Invitations Section -->
                <div class="menu-item pt-2">
                    <div class="menu-content pt-2 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Invitations</span>
                    </div>
                </div>
                
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.invitations.index') ? 'active' : '' }}" href="{{ route('admin.invitations.index') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M22 5H2C1.4 5 1 5.4 1 6V18C1 18.6 1.4 19 2 19H22C22.6 19 23 18.6 23 18V6C23 5.4 22.6 5 22 5Z" fill="black" />
                                    <path d="M21 6H3C2.4 6 2 6.4 2 7V17C2 17.6 2.4 18 3 18H21C21.6 18 22 17.6 22 17V7C22 6.4 21.6 6 21 6ZM19 14H13C12.4 14 12 13.6 12 13C12 12.4 12.4 12 13 12H19C19.6 12 20 12.4 20 13C20 13.6 19.6 14 19 14Z" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">All Invitations</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.invitations.create') ? 'active' : '' }}" href="{{ route('admin.invitations.create') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M11 13H7C6.4 13 6 12.6 6 12C6 11.4 6.4 11 7 11H11V13ZM17 11H13V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z" fill="black" />
                                    <path d="M22 5H2C1.4 5 1 5.4 1 6V18C1 18.6 1.4 19 2 19H22C22.6 19 23 18.6 23 18V6C23 5.4 22.6 5 22 5ZM18 14H13V17C13 17.6 12.6 18 12 18C11.4 18 11 17.6 11 17V14H6C5.4 14 5 13.6 5 13C5 12.4 5.4 12 6 12H11V9C11 8.4 11.4 8 12 8C12.6 8 13 8.4 13 9V12H18C18.6 12 19 12.4 19 13C19 13.6 18.6 14 18 14Z" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Create Invitation</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.invitations.templates') ? 'active' : '' }}" href="{{ route('admin.invitations.templates') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none">
                                    <path opacity="0.3" d="M8.9 21L7.19999 22.6999C6.79999 23.0999 6.2 23.0999 5.8 22.6999L4.1 21H8.9ZM4 16.0999L2.3 17.8C1.9 18.2 1.9 18.7999 2.3 19.1999L4 20.9V16.0999ZM19.3 9.1999L15.8 5.6999C15.4 5.2999 14.8 5.2999 14.4 5.6999L9 11.0999V21L19.3 10.6999C19.7 10.2999 19.7 9.5999 19.3 9.1999Z" fill="black" />
                                    <path d="M21 15V20C21 20.6 20.6 21 20 21H11.8L18.8 14H20C20.6 14 21 14.4 21 15ZM10 21V4C10 3.4 9.6 3 9 3H4C3.4 3 3 3.4 3 4V21C3 21.6 3.4 22 4 22H9C9.6 22 10 21.6 10 21ZM7.5 18.5C7.5 19.1 7.1 19.5 6.5 19.5C5.9 19.5 5.5 19.1 5.5 18.5C5.5 17.9 5.9 17.5 6.5 17.5C7.1 17.5 7.5 17.9 7.5 18.5Z" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Invitation Templates</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.rsvp.index') ? 'active' : '' }}" href="{{ route('admin.rsvp.index') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M5 9H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V9Z" fill="black" />
                                    <path d="M21 5H19V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5H7V3C7 2.4 6.6 2 6 2C5.4 2 5 2.4 5 3V5H3C2.4 5 2 5.4 2 6V9H22V6C22 5.4 21.6 5 21 5Z" fill="black" />
                                    <rect x="8" y="12" width="8" height="2" rx="1" fill="black" />
                                    <rect x="8" y="15" width="8" height="2" rx="1" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">RSVP Responses</span>
                        {{-- @if($pendingRSVPCount ?? 0 > 0)
                        <span class="menu-badge">
                            <span class="badge badge-circle badge-danger">{{ $pendingRSVPCount }}</span>
                        </span>
                        @endif --}}
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.invitations.links') ? 'active' : '' }}" 
                    href="{{ route('admin.invitations.links') }}">
                        <span class="menu-icon">
                            <i class="bi bi-link-45deg fs-2"></i>
                        </span>
                        <span class="menu-title">Invitation Links</span>
                    </a>
                </div>

                <!-- Admin Section -->
                <div class="menu-item pt-2">
                    <div class="menu-content pt-2 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Administration</span>
                    </div>
                </div>
                
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 18H4V6H20V18Z" fill="black" />
                                    <path d="M8 8H16V10H8V8ZM8 12H14V14H8V12Z" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">User Management</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"
                        href="{{ route('admin.profile.edit') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z" fill="black" />
                                    <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">My Profile</span>
                    </a>
                </div>
 
                <!-- Settings Section -->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" 
                        href="{{ route('admin.settings.edit') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="black" />
                                    <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Settings</span>
                    </a>
                </div>
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->
</div>