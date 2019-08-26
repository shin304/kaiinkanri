@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link type="text/css" rel="stylesheet" href="/css/admin/master_menu.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@stop

@if(isset($dataTypeContent->id))
    @section('page_title','Edit '.$dataType->display_name_singular)
@else
    @section('page_title','Add '.$dataType->display_name_singular)
@endif

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> @if(isset($dataTypeContent->id)){{ 'Edit' }}@else{{ 'New' }}@endif {{ $dataType->display_name_singular }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">

                    <div class="panel-heading">
                        <h3 class="panel-title">@if(isset($dataTypeContent->id)){{ 'Edit' }}@else{{ 'Add New' }}@endif {{ $dataType->display_name_singular }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="@if(isset($dataTypeContent->id)){{ route('voyager.'.$dataType->slug.'.update', $dataTypeContent->id) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if(isset($dataTypeContent->id))
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body panel-body-custom">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- If we are editing -->
                            @if(isset($dataTypeContent->id))
                                <?php $dataTypeRows = $dataType->editRows; ?>
                            @else
                                <?php $dataTypeRows = $dataType->addRows; ?>
                            @endif

                            @foreach($dataTypeRows as $row)
                                <div class="form-group @if($row->type == 'hidden') hidden @endif
                                                        @if($row->field == 'menu_path') form-group-menu-path @endif
                                                        @if($row->field == 'sub_seq_no') form-group-sub-seq-no @endif
                                                        ">
                                    <label for="name">{{ $row->display_name }}</label>
                                    @include('voyager::multilingual.input-hidden-bread')
                                    {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach

                                    @if ($row->field == 'menu_path')
                                        <span id="display_sub_menu"></span>
                                    @endif
                                    
                                </div>
                            @endforeach
                            
                            <div class="form-source-folder">
                            @foreach ($resourceFolder as $key=>$folder)
                            <div class="form-group">
                            <label>表示内容：{{$key}}</label>
                            <input class="form-control" type="text" name="resource_content[{{$key}}]" value="{{$folder}}">
                            </div>
                            @endforeach
                            </div>
                        </div><!-- panel-body -->
                        <div class="panel-body-preview">
                            <table>
                                <thead>
                                    <th style="width: 45%">プレビューサブメニュー</th>
                                    <th style="width: 10%"></th>
                                    <th>メニュー固定</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <ul id="sequence-preview">
                                            </ul>
                                        </td>
                                        <td></td>
                                        <td class="default_menu_area">
                                            <ul id="default_menu">
                                            @foreach($defaultList as $row)
                                                <li>{{$row->menu_name_key}}</li>
                                            @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary save">Save</button>
                        </div>

                    </form>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                            enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                                 onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> Are You Sure</h4>
                </div>

                <div class="modal-body">
                    <h4>Are you sure you want to delete '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">Yes, Delete it!
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
        var params = {}
        var $image

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', function (e) {
                $image = $(this).parent().siblings('img');

                params = {
                    slug:   '{{ $dataTypeContent->getTable() }}',
                    image:  $image.data('image'),
                    id:     $image.data('id'),
                    field:  $image.parent().data('field-name'),
                    _token: '{{ csrf_token() }}'
                }

                $('.confirm_delete_name').text($image.data('image'));
                $('#confirm_delete_modal').modal('show');
            });

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $image.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing image.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });

            // custom by kieu
            $('.form-source-folder').insertAfter($('.form-group').get(0));
            $('.form-group-sub-seq-no').insertAfter($('.form-group-menu-path'));
            $('.form-group-sub-seq-no').hide();
            var item_id = @if(isset($dataTypeContent->id)) {{$dataTypeContent->id}} @else '' @endif;
            var idx = $('select[name=menu_path').val();
            var txt = $('select[name=menu_path').find('option:selected').text();
            var sub_menu = JSON.parse('{{$subMenuList}}'.replace(/&quot;/g,'"'));
            
            appendToPreview(idx, txt);//load when edit
            
            //select another parent
            $('select[name=menu_path').change(function(){
                
                var idx = $(this).val();
                var txt = $(this).find('option:selected').text();
                appendToPreview(idx, txt);
                $('input[name=sub_seq_no]').change();
            });

            $('input[name=sub_seq_no]').change(function(){
               
                var obj = $('#sequence-preview .sub-menu-edit');
                if (obj.length == 0) {
                    $('#sequence-preview').append('<li class="sub-menu sub-menu-edit"><i class="fa fa-hand-o-right" aria-hidden="true"></i></li>');
                    //pre define
                    obj = $('#sequence-preview .sub-menu-edit');
                }// gan lại
                var lstsort = $('#sequence-preview .sub-menu');
                if (lstsort.length > 1) {
                    obj.remove();
                    //pre define
                    lstsort = $('#sequence-preview .sub-menu');
                    sortSubmenu(lstsort, obj, $(this).val());
                }
                
            });

            function appendToPreview(idx, txt) {
            if (idx == 0) {
                    $('.form-group-sub-seq-no').hide();
                    $('ul#sequence-preview').html('');
                    $('ul#sequence-preview').hide();
                } else {
                    $('.form-group-sub-seq-no').show();
                    $('ul#sequence-preview').show();
                    //show preview of parent menu
                    var html = '<li>'+txt+'</li>';

                    if (sub_menu.hasOwnProperty(idx)) {
                        
                        //append submenu
                        $.each(sub_menu[idx], function( i, value ) {
                            html += '<li'+
                                    ' class="sub-menu '+((item_id==value['id'])? 'sub-menu-edit': '') +'" '
                                    + ' seqNo="'+value['sub_seq_no']+'" '
                                    +'><i class="fa '+((item_id==value['id'])? 'fa-hand-o-right': 'fa-angle-double-right') +'" '
                                    + '" aria-hidden="true"></i><b>'
                                    +value['sub_seq_no']+'</b>'+value['menu_name_key']+'</li>';
                        });
                    }
                    $('ul#sequence-preview').html(html);
                }
            }

            function sortSubmenu(lstsort, obj,seqNew){
                obj.css('background', '#B4EEB4');
                var is_edited = false;
                lstsort.each(function() {

                    if (parseInt($(this).attr('seqNo')) >= parseInt(seqNew)) {

                        obj.insertBefore($(this));
                        is_edited = true;
                        return false;
                    }
                });
                //append to bottom of list
                if (!is_edited) {
                    obj.insertAfter(lstsort.last());
                }
            }

            $('input[name=default_flag]').each(function() {
                old_id = $(this).attr('id');
                $(this).attr('id', 'def-'+old_id);
                $(this).next().attr('for', 'def-'+old_id);
            });
        });
    </script>
    @if($isModelTranslatable)
        <script src="{{ config('voyager.assets_path') }}/js/multilingual.js"></script>
    @endif
    <script src="{{ config('voyager.assets_path') }}/lib/js/tinymce/tinymce.min.js"></script>
    <script src="{{ config('voyager.assets_path') }}/js/voyager_tinymce.js"></script>
    <script src="{{ config('voyager.assets_path') }}/js/slugify.js"></script>
@stop
