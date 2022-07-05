<!--APP-SIDEBAR-->

<div class="app-sidebar__overlay" data-toggle="sidebar"></div>

                <aside class="app-sidebar">

                    <div class="side-header">

                        <!-- <img width="70%" src="{{ asset('assets/images/popkart.png') }}" alt="logo">-->
                        <h3>Life Track</h3>

                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle ml-auto" data-toggle="sidebar" href="#"></a><!-- sidebar-toggle-->

                    </div>

                    <div class="app-sidebar__user">

                        <div class="dropdown user-pro-body text-center">

                            <div class="user-pic">

                                <img src="{{ asset('assets/images/icon.png') }}" alt="user-img" class="avatar-xl rounded-circle">

                            </div>

                            <div class="user-info">

                                <h6 class=" mb-0 text-dark">{{ ucfirst(Auth::user()->first_name) }}</h6>
                                <span class="text-muted app-sidebar__user-name text-sm">{{ ucfirst(Auth::user()->roles->first()->title) }}</span>

                            </div>

                        </div>

                    </div>

                    <div class="sidebar-navs d-flex justify-content-center">

                        <ul class="nav  nav-pills-circle text-center">

                            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Settings">

                                <a href="{{ route('dashboard.users.edit', Auth::id()) }}" class="nav-link text-center m-2">

                                    <i class="fe fe-settings"></i>

                                </a>

                            </li>

                            <!-- <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Chat">

                                <a class="nav-link text-center m-2">

                                    <i class="fe fe-mail"></i>

                                </a>

                            </li> -->

                            <!-- <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Followers">

                                <a class="nav-link text-center m-2">

                                    <i class="fe fe-user"></i>

                                </a>

                            </li> -->



           

                            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Logout">

                                <a href="{{ route('logout') }}" class="nav-link text-center m-2"  onclick="event.preventDefault();

                                                     document.getElementById('logout-form').submit();">

                                    <i class="fe fe-power"></i>

                                </a>

                                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">

                                        @csrf

                                    </form>

                            </li>

                        </ul>

                    </div>

                    <ul class="side-menu mt-3" >

                        

                        <li class="slide">

                            <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='home') }}"><i class="side-menu__icon ti-home"></i><span class="side-menu__label">Dashboard</span></a>

                           <!--  <ul class="slide-menu">

                                <li><a href="{{ url('/' . $page='home') }}" class="slide-item">Home</a></li> -->

                                <!-- <li class="sub-slide">

                                    <a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Marketing</span><i class="sub-angle fa fa-angle-right"></i></a>

                                    <ul class="sub-slide-menu">

                                        <li><a class="sub-slide-item" href="{{ route('dashboard.dashboard.index') }}">Rewards</a></li>

                                    </ul>

                                </li>

                                <li><a href="{{ url('/' . $page='index3') }}" class="slide-item">Service</a></li>

                                <li><a href="{{ url('/' . $page='index4') }}" class="slide-item">Finance</a></li>

                                <li><a href="{{ url('/' . $page='index2') }}" class="slide-item">Operation</a></li>

                                <li><a href="{{ url('/' . $page='index5') }}" class="slide-item">Support</a></li>

                                <li><a href="{{ url('/' . $page='index3') }}" class="slide-item">Delivery</a></li>

                                <li><a href="{{ url('/' . $page='index4') }}" class="slide-item">Party</a></li>

                                <li><a href="{{ url('/' . $page='index5') }}" class="slide-item">IT</a></li>

                            </ul>

                        </li> -->

                         @can('menu_access')
                         <!-- <li>

                            <a class="side-menu__item" href="{{-- route('dashboard.menus.index') --}}"><i class="side-menu__icon icon icon-list"></i><span class="side-menu__label">Menus</span></a>

                        </li> -->
                         @endcan
                        @can('log_access')

                        <!--   <li>

                            <a class="side-menu__item" href="{{-- route('dashboard.logActivity') --}}"><i class="side-menu__icon icon icon-clock"></i><span class="side-menu__label"> Users Logs</span></a>

                        </li> -->

                        @endcan

                        @can('report_access')

                       <!--  <li>

                            <a class="side-menu__item" href="#"><i class="side-menu__icon fe fe-clipboard"></i><span class="side-menu__label">Reports</span></a>

                        </li> -->

                        @endcan

                          @can('widgets_access')

                        <!-- <li>

                            <a class="side-menu__item" href="#"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Widgets</span></a>

                        </li> -->

                        @endcan

                         

                          @can('reviews_access')

                       <!--  <li>

                            <a class="side-menu__item" href="{{-- route('dashboard.review.index') --}}"><i class="side-menu__icon icon icon-star"></i><span class="side-menu__label">Reviews</span></a>

                        </li> -->

                         @can('vuser_access')

                         {{--<li>

                            <a class="side-menu__item" href="{{ route('dashboard.user-index') }}"><i class="side-menu__icon fe fe-user"></i><span class="side-menu__label">User</span></a>

                        </li>--}}

                         @endcan

                        
                        @can('vendor_settings')
                        <!-- <li>

                            <a class="side-menu__item" href="{{ route('dashboard.vendor-setting') }}"><i class="side-menu__icon icon icon-settings"></i><span class="side-menu__label">Settings</span></a>

                        </li> -->
                         @endcan

                         
                              
                     
                         

                       @can('category_access')

                        <li class="slide">

                            <a class="side-menu__item" data-toggle="slide" href="{{route('dashboard.category.index')}}"><i class="side-menu__icon fe fe-package"></i><span class="side-menu__label">Category</span></a>

                            <ul class="slide-menu">

                                

                              

                                <li><a href="{{ route('dashboard.category.index') }}" class="slide-item">Category</a></li>


                                @can('attribute_access')

                                <!-- <li><a href="{{ route('dashboard.attribute.index') }}" class="slide-item">Attribute</a></li> -->

                             <!--    <li><a href="{{ route('dashboard.attribute-value.index') }}" class="slide-item">Attribute Value</a></li> -->

                                @endcan

                            </ul>

                        </li>

                        @endcan

                        
                         <li>

                            <a class="side-menu__item" href="{{route('dashboard.journalmang.index') }}"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">Journal </span></a>

                        </li> 
                        
                        <li>

                            <a class="side-menu__item" href="{{route('dashboard.pages.index') }}"><i class="side-menu__icon fa fa-file-text-o"></i><span class="side-menu__label">Pages</span></a>

                        </li> 
                  


                        <li>

                            <a class="side-menu__item" href="{{route('dashboard.subscriptions.index')}}"><i class="side-menu__icon fe fe-file-plus"></i><span class="side-menu__label">Subscription</span></a>

                         </li>      

                         
                         <li>

                            <a class="side-menu__item" href="{{route('dashboard.activity.index')}}"><i class="side-menu__icon pe-7s-ball"></i><span class="side-menu__label">Activity</span></a>

                         </li> 
                        

                   
                           <li class="slide">

                                <a class="side-menu__item" data-toggle="slide" href="#"> <i class="side-menu__icon fe fe-user"></i><span class="side-menu__label">Users Management</span><i class="angle fa fa-angle-right"></i></a>

                                <ul class="slide-menu">

                                    @can('role_access')

                                    <li><a href="{{ route('dashboard.roles.index') }}" class="slide-item">User Roles</a></li>

                                     @endcan

                                     @can('permission_access')

                                    <li><a href="{{ route('dashboard.permissions.index')}}" class="slide-item">Role Permissions</a></li>

                                     @endcan

                                     @can('user_access')

                                    <li><a href="{{ route('dashboard.users.index') }}" class="slide-item">User</a></li>

                                     @endcan

                                </ul>

                           </li>

                         @endcan

                       @can('web_settings')

                          <li class="slide">

                            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon fe fe-settings"></i><span class="side-menu__label"> Web Settings</span><i class="angle fa fa-angle-right"></i></a>

                            <ul class="slide-menu">

                               
                               
                                <li><a href="{{route('dashboard.mails.index')}}" class="slide-item">Mail Template</a></li>

                                

                            </ul>

                        </li> 

                        @endcan 

                      

                        </li>

                           

                    </ul>

                </aside>

<!--/APP-SIDEBAR-->

