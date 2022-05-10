<script src="{{ asset('assets/js/site.core.js')}}"></script>
<!-- Page JS -->
<script src="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.js')}}"></script>
<script src="{{ asset('assets/libs/progressbar.js/dist/progressbar.min.js')}}"></script>
<script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js')}}"></script>

<script src="{{ asset('assets/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('assets/libs/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js')}}"></script>
<script src="{{ asset('assets/libs/moment/min/moment.min.js')}}"></script>
<script src="{{ asset('assets/libs/@fancyapps/fancybox/dist/jquery.fancybox.min.js')}}"></script>
<script src="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.js')}}"></script>
<script src="{{ asset('assets/libs/flatpickr/dist/flatpickr.min.js')}}"></script>
<script src="{{ asset('assets/libs/quill/dist/quill.min.js')}}"></script>
<script src="{{ asset('assets/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{ asset('assets/libs/autosize/dist/autosize.min.js')}}"></script>
<!-- Site JS -->
<script src="{{ asset('assets/js/site.js')}}"></script>

<script src="{{ asset('assets/js/letter.avatar.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/custom.js')}}"></script>


@if(Session::has('success'))
    <script>
        show_toastr('{{__('Success')}}', '{!! session('success') !!}', 'success');
    </script>
    {{ Session::forget('success') }}
@endif
@if(Session::has('error'))
    <script>
        show_toastr('{{__('Error')}}', '{!! session('error') !!}', 'error');
    </script>
    {{ Session::forget('error') }}
@endif
@stack('script-page')
