<!DOCTYPE html>
<html lang="en" dir="{{env('SITE_RTL') == 'on'?'rtl':''}}">
<meta name="csrf-token" content="{{ csrf_token() }}">

@include('partials.admin.head')
<body class="application application-offset">
<div class="container-fluid container-application">
    @php
        $users=\Auth::user();
        $currantLang = $users->currentLanguages();
        $languages=\App\Models\Utility::languages();
        $footer_text=isset(\App\Models\Utility::settings()['footer_text']) ? \App\Models\Utility::settings()['footer_text'] : '';
    @endphp
    <div class="main-content position-relative">
        @include('partials.admin.header')
        <div class="page-content">
            @include('partials.admin.content')
        </div>
        <div class="footer pt-5 pb-4 footer-light" id="footer-main">
            <div class="row text-center text-sm-left align-items-sm-center">
                <div class="col-sm-6">
                    <p class="text-sm mb-0">{{ $footer_text }}</p>
                </div>
                <div class="col-sm-6 mb-md-0">
                    <ul class="nav justify-content-center justify-content-md-end">
                        <li class="nav-item dropdown border-right">
                            <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="h6 text-sm mb-0"><i class="fas fa-globe-asia"></i>
                                    {{Str::upper($currantLang)}}
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                @foreach($languages as $language)
                                    <a href="{{route('change.language',$language)}}" class="dropdown-item @if($language == $currantLang) active-language @endif">
                                        <span> {{Str::upper($language)}}</span>
                                    </a>
                                @endforeach
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('change.mode') }}">{{(Auth::user()->mode == 'light') ? __('Dark Mode') : __('Light Mode')}}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header align-items-center">
                    <div class="modal-title">
                        <h6 class="mb-0" id="modelCommanModelLabel"></h6>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
@include('partials.admin.footer')



@if(App\Models\Utility::getValByName('gdpr_cookie') == 'on')
<script type="text/javascript">
    
    var defaults = {
    'messageLocales': {
        /*'en': 'We use cookies to make sure you can have the best experience on our website. If you continue to use this site we assume that you will be happy with it.'*/
        'en': "{{App\Models\Utility::getValByName('cookie_text')}}"
    },
    'buttonLocales': {
        'en': 'Ok'
    },
    'cookieNoticePosition': 'bottom',
    'learnMoreLinkEnabled': false,
    'learnMoreLinkHref': '/cookie-banner-information.html',
    'learnMoreLinkText': {
      'it': 'Saperne di più',
      'en': 'Learn more',
      'de': 'Mehr erfahren',
      'fr': 'En savoir plus'
    },
    'buttonLocales': {
      'en': 'Ok'
    },
    'expiresIn': 30,
    'buttonBgColor': '#d35400',
    'buttonTextColor': '#fff',
    'noticeBgColor': 'var(--primary)',
    'noticeTextColor': '#fff',
    'linkColor': '#009fdd'
};
</script>
<script src="{{ asset('assets/js/cookie.notice.js')}}"></script>
@endif



</body>
</html>
