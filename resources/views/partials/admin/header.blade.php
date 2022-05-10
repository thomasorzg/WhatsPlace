@php
    $logo         =asset(Storage::url('uploads/logo/'));
    $company_logo =\App\Models\Utility::getValByName('company_logo');
    $user         = \Auth::user();
    $plan         = \App\Models\Plan::where('id', $user->plan)->first();
@endphp
<nav class="navbar navbar-main navbar-expand-lg navbar-dark bg-primary navbar-border" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand + Toggler (for mobile devices) -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main-collapse" aria-controls="navbar-main-collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- User's navbar -->
        <div class="navbar-user d-lg-none ml-auto">
            <ul class="navbar-nav flex-row align-items-center">
                <li class="nav-item dropdown dropdown-animate">
                    <a class="nav-link pr-lg-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="avatar avatar-sm rounded-circle">
                      <img alt="Image placeholder" src="{{asset(Storage::url('uploads/profile/'.(!empty($user['avatar'])?$user['avatar']:'avatar.png')))}}">
                  </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right dropdown-menu-arrow">
                        <h6 class="dropdown-header px-0">Hi, {{\Auth::user()->name}}!</h6>
                        <a href="{{route('profile')}}" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>{{__('My profile')}}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item has-icon text-danger">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>{{__('Logout')}}</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Navbar nav -->
        <div class="collapse navbar-collapse navbar-collapse-fade" id="navbar-main-collapse">
            <ul class="navbar-nav align-items-lg-center">
                <!-- Overview  -->
                <li class="nav-item">
                    <div class="d-flex align-items-center mr-5">
                        <a class="navbar-brand" href="{{ route('dashboard') }}">
                            @if(\Illuminate\Support\Facades\Auth::user()->type == 'super admin')
                                <img class="img-fluid" src="{{$logo.'/logo.png'}}" alt="Store logo" height="40px">
                            @else
                                <img class="img-fluid" src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')}}" alt="Store logo" height="40px">
                            @endif
                        </a>
                    </div>
                </li>
                <li class="border-top opacity-2 my-2"></li>
                <!-- Home  -->

                @if(Auth::user()->type == 'Owner')
                    <li class="nav-item dropdown dropdown-animate" data-toggle="hover">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{__('Dashboard')}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow p-lg-0">
                            <!-- Top dropdown menu -->
                            <div class="p-lg-4">
                                <a class="dropdown-item" href="{{route('dashboard')}}">
                                    {{__('Dashboard')}}
                                </a>
                                <a class="dropdown-item" href="{{route('storeanalytic')}}">
                                    {{__('Store Analytics')}}
                                </a>
                            </div>
                        </div>
                    </li>
                    <!-- Application menu -->
                    <li class="nav-item dropdown dropdown-animate" data-toggle="hover">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{__('Shop')}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow p-lg-0">
                            <!-- Top dropdown menu -->
                            <div class="p-lg-4">
                                <a class="dropdown-item" href="{{route('product.index')}}">
                                    {{__('Products')}}
                                </a>
                                <a href="{{route('product_categorie.index')}}" class="dropdown-item" role="button">
                                    {{__('Product Category')}}
                                </a>
                                <a href="{{route('product_tax.index')}}" class="dropdown-item" role="button">
                                    {{__('Product Tax')}}
                                </a>
                                <a href="{{route('product-coupon.index')}}" class="dropdown-item" role="button">
                                    {{__('Product Coupon')}}
                                </a>
                                @if(isset($plan) && $plan->shipping_method == 'on')
                                    <a href="{{route('shipping.index')}}" class="dropdown-item" role="button">
                                        {{__('Shipping')}}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('orders.index')}}" class="nav-link">
                            <span>{{__('Orders')}}</span>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->type == 'super admin')
                    <li class="nav-item">
                        <a href="{{route('dashboard')}}" class="nav-link">
                            <span>{{__('Dashboard')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('store-resource.index')}}" class="nav-link">
                            <span>{{__('Stores')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('coupons.index') }}" class="nav-link">
                            <span> {{ __('Coupons') }} </span>
                        </a>
                    </li>

                      <li class="nav-item">
                        <a href="{{ route('plan_request.index') }}" class="nav-link {{ request()->is('plan_request*') ? 'active' : '' }}">
                            {{__('Plan Request')}}
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('plans.index') }}" class="nav-link">
                        <span> {{ __('Plans') }} </span>
                    </a>
                </li>
                @if(Auth::user()->type == 'super admin')
                    <li class="nav-item">
                        <a href="{{route('manage.language',[$currantLang])}}" class="nav-link {{ (Request::segment(1) == 'manage-language')?'active':''}}">
                            {{__('Language')}}
                        </a>
                    </li>
                @endif
                @if(Auth::user()->type == 'super admin')
                    <li class="nav-item">
                        <a href="{{route('custom_landing_page.index')}}" class="nav-link">
                            {{__('Landing page')}}
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{route('settings')}}">
                        @if(Auth::user()->type == 'super admin')
                            {{__('Settings')}}
                        @else
                            {{__('Store Settings')}}
                        @endif
                    </a>
                </li>
                <!-- @if(Auth::user()->type == 'super admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('email_template.index')}}">
                            {{__('Email Templates')}}
                        </a>
                    </li>
                @endif -->
                <li class="border-top opacity-2 my-2"></li>
            </ul>
            <!-- Right menu -->
            <ul class="navbar-nav ml-lg-auto align-items-center float-left wsdb vhdasgvc">
                @if(Auth::user()->type == 'Owner')
                    <li class="nav-item dropdown dropdown-animate">
                        <a class="nav-link pr-lg-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="media media-pill align-items-center p-2">
                                @foreach(\Auth::user()->stores as $store)
                                    @if(\Auth::user()->current_store == $store->id)
                                        <div class="d-lg-block">
                                            <span class="mb-0 text-sm  font-weight-bold"><img src="{{$logo.'/'.(isset($favicon) && !empty($favicon)?$favicon:'favicon.png')}}" type="image" width="20px">{{ $store->name }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right dropdown-menu-arrow">
                            @foreach(Auth::user()->stores as $store)
                                @if($store->is_active)
                                    <a href="@if(Auth::user()->current_store == $store->id)#@else {{ route('change_store',$store->id) }} @endif" title="{{ $store->name }}" class="dropdown-item notify-item">
                                        @if(Auth::user()->current_store == $store->id)
                                            <i class="fas fa-check"></i>
                                        @endif
                                        <span>{{ $store->name }}</span>
                                    </a>
                                @else
                                    <a href="#" class="dropdown-item notify-item" title="{{__('Locked')}}">
                                        <i class="fas fa-lock"></i>
                                        <span>{{ $store->name }}</span>
                                        @if(isset($store->pivot->permission))
                                            @if($store->pivot->permission =='Owner')
                                                <span class="badge badge-primary">{{__($store->pivot->permission)}}</span>
                                            @else
                                                <span class="badge badge-secondary">{{__('Shared')}}</span>
                                            @endif
                                        @endif
                                    </a>
                                @endif
                            @endforeach
                            <div class="dropdown-divider"></div>
                            @auth('web')
                                @if(Auth::user()->type == 'Owner')
                                    <a href="#" data-size="lg" data-url="{{ route('store-resource.create') }}" data-ajax-popup="true" data-title="{{__('Create New Store')}}" class="dropdown-item notify-item">
                                        <i class="fa fa-plus"></i><span>{{ __('Create New Store')}}</span>
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </li>
                @endif

                <li class="nav-item dropdown dropdown-animate float-left responsive_none">
                    <a class="nav-link pr-lg-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media media-pill align-items-center">
                            <span class="avatar rounded-circle avatar_img">
                              <img alt="Image placeholder" src="{{asset(Storage::url('uploads/profile/'.(!empty($user['avatar'])?$user['avatar']:'avatar.png')))}}">
                            </span>
                            <div class="ml-2 d-none d-lg-block avatar_name">
                                <span class="mb-0 text-sm  font-weight-bold">{{\Auth::user()->name}}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right dropdown-menu-arrow">
                        <h6 class="dropdown-header px-0">{{__('Hi')}}, {{\Auth::user()->name}}!</h6>
                        <a href="{{route('profile')}}" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>{{__('My profile')}}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item has-icon text-danger">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>{{__('Logout')}}</span>
                        </a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
