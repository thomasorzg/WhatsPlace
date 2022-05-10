<x-guest-layout>
    <x-auth-card>

@section('page-title')
    {{__('Reset Password')}}
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-8 col-lg-5 col-xl-4">
            <div class="row justify-content-center mb-3">
                <a class="navbar-brand" href="#">
                    <img src="{{asset(Storage::url('uploads/logo/logo.png'))}}" class="auth-logo" width="250">
                </a>
            </div>
            <div class="card shadow zindex-100 mb-0">
                <div class="card-body px-md-5 py-5 ">
                    <div class="mb-4">
                        <h6 class="h3">{{__('Reset Password')}}</h6>
                        <p class="text-muted mb-0">{{__('Enter your new password below to proceed.')}}</p>
                    </div>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="form-group">
                            {{Form::label('E-Mail Address',__('E-Mail Address'),array('class' => 'form-control-label'))}}
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                        {{Form::label('Password',__('Password'),array('class' => 'form-control-label'))}}
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            {{Form::label('password-confirm',__('Confirm Password'),array('class' => 'form-control-label'))}}
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill text-white">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
</x-auth-card>
</x-guest-layout>
