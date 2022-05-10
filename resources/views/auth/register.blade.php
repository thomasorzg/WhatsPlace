<x-guest-layout>
    <x-auth-card>
@section('page-title')
    {{__('Register')}}
@endsection
@section('content')
    <div class="w-100">
        <div class="row justify-content-center">
            <div class="form-group auth-lang">
                <select name="language" id="language" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                    @foreach(\App\Models\Utility::languages() as $language)
                        <option @if($lang == $language) selected @endif value="{{ route('register',$language) }}">{{Str::upper($language)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-8 col-lg-5">
                <div class="row justify-content-center mb-3">
                    <a class="navbar-brand" href="#">
                        <img src="{{asset(Storage::url('uploads/logo/logo.png'))}}" class="auth-logo" width="250">
                    </a>
                </div>
                <div class="card shadow zindex-100 mb-0">
                    {{Form::open(array('route'=>'register','method'=>'post','id'=>'loginForm'))}}
                    <div class="card-body px-md-5 py-5">
                        <div class="mb-4">
                            <h6 class="h3">{{__('Create account')}}</h6>
                            <p class="text-muted mb-0">{{__("Don't have an account? Create your account, it takes less than a minute")}}</p>
                        </div>
                        <span class="clearfix"></span>
                        <div class="form-group">
                            <label class="form-control-label">{{__('Name')}}</label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Your Name')))}}
                            </div>
                            @error('name')
                            <span class="error invalid-name text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('Store Name')}}</label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-store"></i></span>
                                </div>
                                {{Form::text('store_name',null,array('class'=>'form-control','placeholder'=>__('Enter Store Name')))}}
                            </div>
                            @error('name')
                            <span class="error invalid-name text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('Email')}}</label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-envelope"></i></span>
                                </div>
                                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
                            </div>
                            @error('email')
                            <span class="error invalid-email text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-control-label">{{__('Password')}}</label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                {{Form::password('password',array('class'=>'form-control','id'=>'input-password','placeholder'=>__('Enter Your Password')))}}
                                <div class="input-group-append">
                            <span class="input-group-text">
                            <a href="#" data-toggle="password-text" data-target="#input-password">
                                <i class="fas fa-eye"></i>
                            </a>
                            </span>
                                </div>
                            </div>
                            @error('password')
                            <span class="error invalid-password text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">{{__('Confirm password')}}</label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                {{Form::password('password_confirmation',array('class'=>'form-control','id'=>'confirm-input-password','placeholder'=>__('Enter Your Confirm Password')))}}
                                <div class="input-group-append">
                            <span class="input-group-text">
                            <a href="#" data-toggle="password-text" data-target="#confirm-input-password">
                                <i class="fas fa-eye"></i>
                            </a>
                            </span>
                                </div>
                            </div>
                            @error('password_confirmation')
                            <span class="error invalid-password_confirmation text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                        @if(env('RECAPTCHA_MODULE') == 'yes')
                                <div class="form-group col-lg-12 col-md-12 mt-3">
                                        {!! NoCaptcha::display() !!}
                                        @error('g-recaptcha-response')
                                        <span class="small text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                            @endif
                        <div class="mt-4">
                            {{Form::submit(__('Create my account'),array('class'=>'btn btn-sm btn-primary btn-icon rounded-pill','id'=>'saveBtn'))}}
                        </div>
                    </div>
                    <div class="card-footer px-md-5"><small>{{__('Already have an acocunt?')}}</small>
                        <a href="{{ route('login') }}" class="small font-weight-bold">{{__('Login')}}</a>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
@if(env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
@endif
@endpush
</x-auth-card>
</x-guest-layout>
