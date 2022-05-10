<x-guest-layout>
    <x-auth-card>

@section('page-title')
    {{__('Login')}}
@endsection
@section('content')
    <div class="w-100">
        <div class="row justify-content-center">
            <div class="form-group auth-lang">
                <select name="language" id="language" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                    @foreach(\App\Models\Utility::languages() as $language)
                        <option @if($lang == $language) selected @endif value="{{ route('login',$language) }}">{{Str::upper($language)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-8 col-lg-4">
                <div class="row justify-content-center mb-3">
                    <a class="navbar-brand" href="#">
                        <img src="{{asset(Storage::url('uploads/logo/logo.png'))}}" class="auth-logo" width="300">
                    </a>
                </div>
                <div class="card shadow zindex-100 mb-0">
                    <div class="card-body px-md-5 py-5">
                        <div class="mb-5">
                            <h6 class="h3">{{__('Login')}}</h6>
                            <p class="text-muted mb-0">{{__('Sign in to your account to continue.')}}</p>
                        </div>
                        <span class="clearfix"></span>
                        {{Form::open(array('route'=>'login','method'=>'post','id'=>'loginForm','class'=> 'login-form' ))}}
                        <div class="form-group">
                            {{Form::label('email',__('Email'),array('class' => 'form-control-label'))}}

                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
                                @error('email')
                                <span class="invalid-email text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    {{Form::label('password',__('Password'),array('class' => 'form-control-label'))}}
                                </div>
                                <div class="mb-3">
                                    <div class="text-center">
                                        @if (Route::has('change.langPass'))
                                            <a href="{{ route('change.langPass',$lang) }}" class="small text-muted text-underline--dashed border-primary">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter Your Password')))}}
                                <div class="input-group-append">
                                <span class="input-group-text">
                                  <a href="#" data-toggle="password-text" data-target="#password">
                                    <i class="fas fa-eye"></i>
                                  </a>
                                </span>
                                </div>
                                @error('password')
                                <span class="invalid-password text-danger" role="alert">
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
                        </div>
                        <div class="form-group">
                            {{Form::submit(__('Login'),array('class'=>'btn btn-sm btn-primary btn-icon rounded-pill text-white','id'=>'saveBtn'))}}
                        </div>
                        {{Form::close()}}
                    </div>
                    @if(Utility::getValByName('signup_button') == 'on')
                    <div class="card-footer px-md-5"><small>{{__('Not registered')}}?</small>
                        <a href="{{route('register',$lang)}}" class="small font-weight-bold">{{__('Create account')}}</a>
                    </div>
                    @endif
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
