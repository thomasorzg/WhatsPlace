@php
    $logo=asset(Storage::url('logo/'));
    $company_favicon=App\Models\Utility::getValByName('company_favicon');
    $image_path=asset(Storage::url('custom_landing_page_image/'));
@endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{(App\Models\Utility::getValByName('title_text')) ? App\Models\Utility::getValByName('title_text') : config('app.name', 'HRMGo')}}</title>
    <link rel="icon" href="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" type="image" sizes="16x16">
    <!-- Landing External CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/font-awesome.min.css') }}">
    <link href="{{ asset('landing/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all">
    <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet" type="text/css" media="all">
    <link href="{{ asset('landing/css/responsive.css') }}" rel="stylesheet" type="text/css" media="all">
    <link href="{{ asset('landing/css/owl.carousel.min.css') }}" rel="stylesheet" type="text/css" media="all">
    <script src="{{ asset('landing/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('landing/js/script.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style type="text/css">
        .modal-backdrop.show {
            opacity: .1;
        }
    </style>
</head>
<body>
<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    $( "#custome-top-header-section" ).click(function() {
        $('.custome-top-header-section').html(`@include('custom_landing_page.custome-top-header-section')`);
        $( "#custome-top-header-section" ).hide();
        show_content('section-1','section-1');

    });
    $( "#custome-logo-part-main-back-part" ).click(function() {
        $('.custome-logo-part-main-back-part').html(`@include('custom_landing_page.custome-logo-part-main-back-part')`);
        $( "#custome-logo-part-main-back-part" ).hide();
        show_content('section-2','section-2');
    });
    $( "#custome-simple-sec-even" ).click(function() {
        $('.custome-simple-sec-even').html(`@include('custom_landing_page.custome-simple-sec-even')`);
        $( "#custome-simple-sec-even" ).hide();
        show_content('section-3','section-3');
    });

    
    function show_content(section_name,section_type){
        $.ajax({
            type:'POST',
            url: '{{ url("/LandingPage/show") }}',
            data: {'section_name':section_name,'section_type':section_type},
            success:function(data){
                var object = JSON.parse(data);
                var content = JSON.parse(object.content);
                var key = Object.keys(content);
                var section_id = object.id;
                $(`#${section_type}`).find(`#section_id`).val(section_id);

                $(".tooltip1").hover(function(e){
                    var x = $(this).position();
                    $(this).find(`#ul-${section_type}`).find('.tooltip1text').css({'top':x.top});
                    $(this).find(`#ul-${section_type}`).find('.tooltip1text').css("visibility","visible");
                    $(this).find(`#ul-${section_type}`).find('.tooltip1text').css("opacity",1);
                });
                $(".tooltip1").mouseleave(function(e){
                    $(this).find(`#ul-${section_type}`).find('.tooltip1text').css({'top':'unset'});
                    $(this).find(`#ul-${section_type}`).find('.tooltip1text').css("visibility","hidden");
                    $(this).find(`#ul-${section_type}`).find('.tooltip1text').css("opacity",0);
                });
                
                key.forEach(function(val) {
                    
                    if(val == "logo"){
                        $(`#${section_type}`).find(`#${val}`).attr('src', `{{$image_path.'/${content[val]}'}}`);
                        $(`#setting-modal-${section_type}`).find(`#${val}`).find('img').attr('src', `{{$image_path.'/${content[val]}'}}`);
                    }
                    if(val == "image"){
                        $(`#${section_type}`).find(`#${val}`).attr('src', `{{$image_path.'/${content[val]}'}}`);
                        $(`#setting-modal-${section_type}`).find(`#${val}`).find('img').attr('src', `{{$image_path.'/${content[val]}'}}`);
                    }
                    var img_change_btn = $(`#setting-modal-${section_type}`).find(`#${val}`).find('a');
                    var file_input = $(`#setting-modal-${section_type}`).find(`#${val}`).find('input');
                    img_change_btn.click(function(){ file_input.trigger('click'); });
                    file_input.change(function() {
                        var url = this.value;
                        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                        if (this.files && this.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                            var reader = new FileReader();

                            reader.onload = function (e) {
                                $(`#setting-modal-${section_type}`).find(`#${val}`).find('img').attr('src', e.target.result);
                                $(`#${section_type}`).find(`#${val}`).attr('src', e.target.result);
                            }

                            reader.readAsDataURL(this.files[0]);
                        }else{
                            $(`#setting-modal-${section_type}`).find(`#${val}`).find('img').attr('src', '/assets/no_preview.png');
                            $(`#${section_type}`).find(`#${val}`).attr('src', '/assets/no_preview.png');
                        }
                    });
                    if(val == "button"){
                        $(`#${section_type}`).find(`#${val}`).html(content[val].text);
                        $(`#${section_type}`).find(`#${val}`).attr('href', content[val].href);
                        $(`#setting-modal-${section_type}`).find(`#${val}`).find(`input[name="button[text]"]`).val(content[val].text);
                        $(`#setting-modal-${section_type}`).find(`#${val}`).find(`input[name="button[href]"]`).val(content[val].href);
                        $(`#setting-modal-${section_type}`).find(`#${val}`).find(`input[name="button[text]"]`).keyup(function() {
                            var text_value = $(`#setting-modal-${section_type}`).find(`#${val}`).find(`input[name="button[text]"]`).val();
                            $(`#${section_type}`).find(`#${val}`).html(text_value);
                        });
                        $(`#setting-modal-${section_type}`).find(`#${val}`).find('input[name="button_href"]').change(function() {
                            var href_value = $(`#setting-modal-${section_type}`).find(`#${val}`).find(`input[name="button[href]"]`).val();
                            $(`#${section_type}`).find(`#${val}`).attr('href', href_value);
                        });
                    }                  
                    if(val == "menu"){
                        var menu_html = '';
                        var menu = content[val];
                        var input_menu_html = '';
                        menu.forEach(function(val) {
                            menu_html += `<li>
                                <a href="#" id="${val.menu}">${val.menu}</a>
                            </li>`;
                            input_menu_html += `<div id="inputFormRow">
                                <div class="input-group mb-3">
                                <input type="text" name="menu_name[]" class="form-control m-input" placeholder="menu name" autocomplete="off" value="${val.menu}" id="${val.menu}">
                                <div class="input-group-append">
                                <button id="removeRow" type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </div>
                                </div></div>`;

                        });
                        $(`#${section_type}`).find(`#${val}`).html(menu_html);
                        $(`#setting-modal-${section_type}`).find(`#${val}`).html(input_menu_html);
                        menu.forEach(function(val) {
                            $(`#setting-modal-${section_type}`).find(`#${val.menu}`).keyup(function() {
                               
                                var text_value = $(`#setting-modal-${section_type}`).find(`#${val.menu}`).val();
                                
                                $(`#${section_type}`).find(`#${val.menu}`).html(text_value);
                            });
                        });
                        // remove row
                        $(document).on('click', '#removeRow', function () {
                            $(this).closest('#inputFormRow').remove();
                        });
                        $("#addRow").click(function () {
                            var html = `<div id="inputFormRow">
                                <div class="input-group mb-3">
                                <input type="text" name="menu_name[]" class="form-control m-input" placeholder="menu name" autocomplete="off">
                                <div class="input-group-append">
                                <button id="removeRow" type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </div>
                                </div>
                            </div>`;

                            $(`#setting-modal-${section_type}`).find(`#${val}`).append(html);
                        });
                    }
                    if(val == "text"){
                        var menu_key = Object.keys(content[val]);
                        menu_key.forEach(function(element) {
                            $(`#${section_type}`).find(`#${element}`).html(content[val][element]);
                            $(`#setting-modal-${section_type}`).find(`#${element}`).val(content[val][element]);
                            $(`#setting-modal-${section_type}`).find(`#${element}`).keyup(function() {
                                var text_value = $(`#setting-modal-${section_type}`).find(`#${element}`).val();
                                $(`#${section_type}`).find(`#${element}`).html(text_value);
                            });
                        });
                    }
                    if(val == "image_array"){
                        var image_array = content[val];
                        var html = ``;
                        var modal_html = ``;
                        image_array.forEach(function(element) {
                            html += `<div class="col-auto">
                                        <img src="{{$image_path.'/${element}'}}" alt="">
                                    </div>`;

                            modal_html += `<div class="form-group">
                                        <img src="{{$image_path.'/${element}'}}" class="imagepreview mb-2">
                                        <input type="file" style="display:none" name="image_array[]"/> 
                                        <a class="btn btn btn-info" href="javascript:void(0);">Change</a>
                                        </div>`;
                        });
                        $(`#${section_type}`).find(`#${val}`).html(html);
                        $(`#setting-modal-${section_type}`).find(`#${val}`).html(modal_html);
                    }
                });
            }
        });
    }

    function add_content(section_name,section_type){
        
        var form = $(`#setting-modal-${section_type}`).find('form')[0]; 
        var formData = new FormData(form);
        formData.append('section_name', section_name);
        formData.append('section_type', section_type);
        
        $.ajax({
            type:'POST',
            url: '{{ url("/LandingPage/change_conetent") }}',
            data: formData,
            contentType: false, 
            processData: false,
            success:function(data){
            }
        });
    }

    $("#sortablediv").sortable({
        /*cursor: 'move'*/
        handle: '.handle',
        /*receive : function(event, ui) {
            console.log($(this).data().sortable.currentItem);
            return true;
        },*/
        receive: function( event, ui ) {},
        update: function (event, ui) {

          var attr_id = ui.item.attr('id');
          console.log(attr_id);
        }
    });

    $( "#sortablediv" ).on( "sortreceive", function( event, ui ) {
        var attr_id = ui.item.attr('id');
        console.log(attr_id);
    });

</script>
<div class="card">
    <div class="card-body">
        <button class="btn btn-primary" data-toggle="modal" data-target="#component-modal">
        <i class="fa fa-plus"></i>
        </button>
    </div>
</div>

<div class="content" id="sortablediv">
    @if (count($get_section) > 0)
        @foreach ($get_section as $key => $value)
            @if ($value->content != "" && $value->content != null)
                <div class="section_div_{{$value->id}} tooltip1 ui-widget-content" id="{{$value->id}}">
                    @include('custom_landing_page.custome-top-header-section')
                    <script>
                        show_content('{{$value->section_name}}','{{$value->section_type}}');
                    </script>
                </div>
            @endif
        @endforeach
    @endif
    
    <!-- <div class="custome-top-header-section tooltip1 ui-widget-content" id="item-1">
        
    </div>
    <div class="custome-logo-part-main-back-part tooltip1 ui-widget-content" id="item-2">
        
    </div>
    <div class="custome-simple-sec-even tooltip1 ui-widget-content" id="item-3">
        
    </div>
    <div class="custome-simple-sec-odd tooltip1 ui-widget-content" id="item-4">
        
    </div>
    <div class="custome-features-inner-part tooltip1 ui-widget-content" id="item-5">
        
    </div>
    <div class="custome-container-our-system-div tooltip1 ui-widget-content" id="item-6">
        
    </div>
    <div class="custome-testimonials-section tooltip1 ui-widget-content" id="item-7">
        
    </div>
    <div class="custome-subscribe-part tooltip1 ui-widget-content" id="item-8">
        
    </div>
    <div class="custome-social-links tooltip1 ui-widget-content" id="item-9">
        
    </div>
    <div class="custome-footer-section tooltip1 ui-widget-content" id="item-10">
        
    </div> -->

</div>

<div class="modal right fade" id="component-modal" tabindex="-1" role="dialog" aria-labelledby="component-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                
                <h4 class="modal-title" id="myModalLabel2"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <img src="{{asset('storage/Landing_page_component_image/top-header-section.png')}}" id="custome-top-header-section">
                <img src="{{asset('storage/Landing_page_component_image/logo-part-main-back-part.png')}}" id="custome-logo-part-main-back-part">
                <img src="{{asset('storage/Landing_page_component_image/simple-sec-even.png')}}" id="custome-simple-sec-even">
                <img src="{{asset('storage/Landing_page_component_image/simple-sec-odd.png')}}" id="custome-simple-sec-odd">
                <img src="{{asset('storage/Landing_page_component_image/features-inner-part.png')}}" id="custome-features-inner-part">
                <img src="{{asset('storage/Landing_page_component_image/container-our-system-div.png')}}" id="custome-container-our-system-div">
                <img src="{{asset('storage/Landing_page_component_image/testimonials-section.png')}}" id="custome-testimonials-section">
                <img src="{{asset('storage/Landing_page_component_image/subscribe-part.png')}}" id="custome-subscribe-part')">
                <img src="{{asset('storage/Landing_page_component_image/social-links.png')}}" id="custome-social-links">
                <img src="{{asset('storage/Landing_page_component_image/footer-section.png')}}" id="custome-footer-section">
            </div>
        </div>
    </div>
</div>



</body>
</html>
