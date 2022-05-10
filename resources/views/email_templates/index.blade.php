@extends('layouts.admin')
@section('page-title')
    {{__('Email Templates')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h5 d-inline-block text-white font-weight-bold mb-0 ">{{__('Email Templates')}}</h5>
    </div>
@endsection
@push('script-page')
    <script type="text/javascript">
        @can('On-Off Email Template')
        $(document).on("click", ".email-template-checkbox", function () {
            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {_token: $('meta[name="csrf-token"]').attr('content'), status: chbox.val()},
                type: 'PUT',
                success: function (response) {
                    if (response.is_success) {
                        show_toastr('Success', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('Error', response.error, 'error');
                    } else {
                        show_toastr('Error', response, 'error');
                    }
                }
            })
        });
        @endcan
    </script>
@endpush
@section('action-btn')
    {{--    <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle" data-ajax-popup="true" data-title="{{__('Create New Email Template')}}" data-url="{{route('email_template.create')}}"><i class="fas fa-plus"></i> {{__('Add')}} </a>--}}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="92%"> {{__('Name')}}</th>
                                @if(\Auth::user()->type == 'Super Admin')
                                    <th> {{__('Action')}}</th>
                                @elseif(\Auth::user()->type == 'Owner')
                                    <th> {{__('On/Off')}}</th>
                                @endcan
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($EmailTemplates as $EmailTemplate)
                                <tr>
                                    <td>{{ $EmailTemplate->name }}</td>
                                    <td class="">
                                        <a href="{{ route('manage.email.language',[$EmailTemplate->id,\Auth::user()->lang]) }}" class="edit-icon">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
