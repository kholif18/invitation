<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="{{ route('admin.dashboard') }}">
            <img alt="Logo" src="{{ asset('admin/assets/media/logos/logo-1-dark.svg') }}" class="h-25px logo" />
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
                    <a class="menu-link active" href="{{ route('admin.dashboard') }}">
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

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.invitations.statistics') ? 'active' : '' }}" 
                    href="{{ route('admin.invitations.statistics') }}">
                        <span class="menu-icon">
                            <i class="bi bi-graph-up fs-2"></i>
                        </span>
                        <span class="menu-title">Link Statistics</span>
                    </a>
                </div>

                <!-- Settings Section -->
                <div class="menu-item pt-2">
                    <div class="menu-content pt-2 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Settings</span>
                    </div>
                </div>
                
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('settings.general') ? 'active' : '' }}" href="#">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" fill="black" />
                                    <path d="M19.4 15.1L18.8 13.9C18.9 13.3 19 12.7 19 12C19 11.3 18.9 10.7 18.8 10.1L19.4 8.9C19.7 8.3 19.5 7.6 18.9 7.3L16.7 6.1C16.1 5.8 15.4 6 15.1 6.6L14.5 7.8C13.9 7.4 13.2 7.1 12.5 7L12.1 5.7C11.9 5 11.2 4.6 10.5 4.8L8.3 5.4C7.6 5.6 7.2 6.3 7.4 7L7.8 8.3C7.1 8.7 6.5 9.2 6 9.7L4.8 9.1C4.2 8.8 3.5 9 3.2 9.6L2 11.8C1.7 12.4 1.9 13.1 2.5 13.4L3.7 14C3.6 14.6 3.5 15.2 3.5 16C3.5 16.8 3.6 17.4 3.7 18L2.5 18.6C1.9 18.9 1.7 19.6 2 20.2L3.2 22.4C3.5 23 4.2 23.2 4.8 22.9L6 22.3C6.6 22.7 7.3 23.1 8 23.3L8.4 24.5C8.6 25.2 9.3 25.6 10 25.4L12.2 24.8C12.9 24.6 13.3 23.9 13.1 23.2L12.7 21.9C13.4 21.6 14.1 21.2 14.7 20.7L15.9 21.3C16.5 21.6 17.2 21.4 17.5 20.8L18.7 18.6C19 18 18.8 17.3 18.2 17L16.9 16.4C17.3 15.8 17.7 15.1 18 14.4L19.4 15.1Z" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">General Settings</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('settings.notifications') ? 'active' : '' }}" href="#">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M12 22C13.1 22 14 21.1 14 20H10C10 21.1 10.9 22 12 22ZM18 16V11C18 7.9 16.4 5.3 13.5 4.7C13.2 3.2 11.9 2 10.3 2C8.7 2 7.4 3.2 7.1 4.7C4.2 5.3 2.6 7.9 2.6 11V16L0.6 18H23.4L21.4 16H18Z" fill="black" />
                                    <path d="M18 16V11C18 7.9 16.4 5.3 13.5 4.7C13.2 3.2 11.9 2 10.3 2C8.7 2 7.4 3.2 7.1 4.7C4.2 5.3 2.6 7.9 2.6 11V16L0.6 18H23.4L21.4 16H18Z" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Notification Settings</span>
                    </a>
                </div>
                
                {{-- @if(auth()->user()->isAdmin()) --}}
                <!-- Admin Section -->
                <div class="menu-item pt-5">
                    <div class="menu-content pt-8 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Administration</span>
                    </div>
                </div>
                
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="#">
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
                    <a class="menu-link {{ request()->routeIs('admin.system') ? 'active' : '' }}" href="#">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4Z" fill="black" />
                                    <path d="M12 8C10.3 8 9 9.3 9 11C9 12.7 10.3 14 12 14C13.7 14 15 12.7 15 11C15 9.3 13.7 8 12 8ZM12 12C11.4 12 11 11.6 11 11C11 10.4 11.4 10 12 10C12.6 10 13 10.4 13 11C13 11.6 12.6 12 12 12Z" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">System Status</span>
                    </a>
                </div>
                {{-- @endif --}}

                <!-- Account -->
                <div class="menu-item pt-5">
                    <div class="menu-content pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Account</span>
                    </div>
                </div>

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
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
                        <span class="menu-title">Profile</span>
                    </a>
                </div>

                <div class="menu-item">
                    <div class="menu-content">
                        <div class="separator mx-1 my-4"></div>
                    </div>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/coding/cod003.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M16.95 18.9688C16.75 18.9688 16.55 18.8688 16.35 18.7688C15.85 18.4688 15.75 17.8688 16.05 17.3688L19.65 11.9688L16.05 6.56876C15.75 6.06876 15.85 5.46873 16.35 5.16873C16.85 4.86873 17.45 4.96878 17.75 5.46878L21.75 11.4688C21.95 11.7688 21.95 12.2688 21.75 12.5688L17.75 18.5688C17.55 18.7688 17.25 18.9688 16.95 18.9688ZM7.55001 18.7688C8.05001 18.4688 8.15 17.8688 7.85 17.3688L4.25001 11.9688L7.85 6.56876C8.15 6.06876 8.05001 5.46873 7.55001 5.16873C7.05001 4.86873 6.45 4.96878 6.15 5.46878L2.15 11.4688C1.95 11.7688 1.95 12.2688 2.15 12.5688L6.15 18.5688C6.35 18.8688 6.65 18.9688 6.95 18.9688C7.15 18.9688 7.35001 18.8688 7.55001 18.7688Z" fill="black" />
                                    <path opacity="0.3" d="M10.45 18.9687C10.35 18.9687 10.25 18.9687 10.25 18.9687C9.75 18.8687 9.35 18.2688 9.55 17.7688L12.55 5.76878C12.65 5.26878 13.25 4.8687 13.75 5.0687C14.25 5.1687 14.65 5.76878 14.45 6.26878L11.45 18.2688C11.35 18.6688 10.85 18.9687 10.45 18.9687Z" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Changelog v8.0.25</span>
                    </a>
                </div>
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->
</div>