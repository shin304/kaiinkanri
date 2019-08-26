@extends('voyager::master')
@section('page_title','All '.$dataType->display_name_plural)
@section('content')
    <style>
        #dataTable_filter{
            display: none!important;
        }
        .search_box {
            box-shadow: 0 1px 4px rgba(192, 192, 192, 0.63);
            margin-bottom: 10px;
            background: #fff;
            padding: 10px;
        }
        input[type="text"], input[type="password"], textarea, input[type="number"], input[type="search"] {
            padding: 3px 8px 2px;
            font-size: 15px;
            border-radius: 5px;
            border: solid 1px #ccc;
        }
        .top_btn li {
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        input[type="button"] {
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        .search_box th{
            padding-bottom: 1%;
        }
        
        .search_box #search_cond_clear:hover, .top_btn li:hover, .btn_search:hover, input[type="button"]:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .search_box #search_cond_clear {
            font-size: 14px;
            height: 29.5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
       
        .btn_search {
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
            border-radius: 5px;
        }
        input[type="button"] {
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        .search_box #search_cond_clear:hover, .top_btn li:hover, .btn_search:hover, input[type="button"]:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3)!important;
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82)!important;
            cursor: pointer;
            text-shadow: 0 0px #FFF!important;
        }
    </style>
    <div class="page-content container-fluid">
    <!-- @include('voyager::alerts') -->
        <div>
            <p>一覧表示</p>
        </div>
        <div class="search_box box_border1 padding1">
                <form action="{{ URL::to('admin/oshirasekanri/search') }}" method="POST">
                    {{ csrf_field() }}
                    <table style="width: 100%;">
                        <tbody>
                        <tr>
                            <td style="width: 10%;text-align: center">件名</td>
                            <th><input  style="width: 70%"  autocomplete="off"  type="text" name="process" placeholder="名称を入力してください。" value="@if (request('process')) {{request('process')}} @endif"></th>
                        </tr>
                        <tr>
                            <td style="width: 10%;text-align: center">登録日時</td>
                            <th>
                                <input style="width: 30%" autocomplete="off" class="date" type="text" name="date_from" placeholder="から" value="@if (request('date_from')) {{date('Y-m-d',strtotime(request('date_from')))}} @endif">
                                ～
                                <input style="width: 30%" autocomplete="off" class="date" type="text" name="date_to"  placeholder="まで" value="@if (request('date_to')) {{date('Y-m-d',strtotime(request('date_to')))}} @endif" placeholder="">
                            </th>
                            
                        </tr>
                        <tr>
                            <td style="width: 10%"></td>
                            <th>
                                <button class="btn_search" type="submit" name="search_button" id="btn_search" style="height:30px;width: 150px !important;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>検索</button>

                            </th>
                            {{--<td style="position: absolute;right: 49%">--}}
                                {{--<button class="btn_search" type="submit" name="clr" id="btn_search" style="height:30px;width: 116px !important;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>クリア</button>--}}

                            {{--</td>--}}
                        </tr>
                        

                        </tbody>
                    </table>
                </form>
        </div> <!--search_box box_border1 padding1-->
        <div style="float: right;position: absolute;right: 5%;z-index: 999" >
            @if (Voyager::can('add_'.$dataType->name))
                <a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success">
                    <i class="voyager-plus"></i> 登録
                </a>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body table-responsive">
                        <table id="dataTable" class="row table table-hover">
                            <thead>
                            <tr>

                                <td>件名</td>
                                <th>登録日時</th>
                                <th>更新日</th>
                                <th style="text-align: center" class="actions">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dataTypeContent as $data)
                                <tr>
                                    <td>
                                        {{ $data->process }}
                                    </td>
                                    <td>
                                        {{ $data->register_date }}
                                    </td>
                                    <td> {{$data->update_date}}</td>
                                    <td  class="no-sort no-click" id="bread-actions">
                                        @if (Voyager::can('delete_'.$dataType->name))
                                            <a href="javascript:;" title="Delete" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $data->id }}" id="delete-{{ $data->id }}">
                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">削除</span>
                                            </a>
                                        @endif
                                        @if (Voyager::can('edit_'.$dataType->name))
                                            <a href="{{ route('voyager.'.$dataType->slug.'.edit', $data->id) }}" title="Edit" class="btn btn-sm btn-primary pull-right edit">
                                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">編集</span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if ($dataType->server_side!=null)
                        <div class="pull-left">
                        <div role="status" class="show-res" aria-live="polite">{{ $dataTypeContent->firstItem() }} から {{ $dataTypeContent->lastItem() }} まで表示しています。全件：{{ $dataTypeContent->total() }}</div>
                        </div>
                        <div class="pull-right">
                        {{ $dataTypeContent->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Are you sure you want to delete
                        this {{ strtolower($dataType->display_name_singular) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="Yes, delete this {{ strtolower($dataType->display_name_singular) }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@stop

@section('css')
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
    <link rel="stylesheet" href="{{ config('voyager.assets_path') }}/lib/css/responsive.dataTables.min.css">
    @endif
@stop

@section('javascript')
    <!-- DataTables -->
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
    <script src="{{ config('voyager.assets_path') }}/lib/js/dataTables.responsive.min.js"></script>
    @endif
    @if($isModelTranslatable)
    <script src="{{ config('voyager.assets_path') }}/js/multilingual.js"></script>
    @endif
    <script>
        $(document).ready(function () {
            var date_input=$('.date');
            date_input.datepicker({
                dateFormat:'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                step : 5,
                scrollMonth : false,
                scrollInput : false,
                todayHighlight: true,
                autoclose: true,
                endDate: Infinity,
                startDate: -Infinity,
            })
                    @if (!$dataType->server_side)
            var table = $('#dataTable').DataTable({
                    "order": []
                        @if(config('dashboard.data_tables.responsive')), responsive: true @endif
                });
            @endif

            @if ($isModelTranslatable)
            $('.side-body').multilingual();
            @endif
            var count_data={!! $dataTypeContent !!};
            if(count_data.length==0){
                $('#dataTable_info,#dataTable_paginate').hide();
            }
        });
        $('#search_cond_clear').click(function() {  // clear
            $("input[name='_c[name]'], input[name='_c[recruitment_from]'], input[name='_c[recruitment_to]'], input[name='_c[start_date_from]'], input[name='_c[start_date_to]']").val("");
            $("input[name='add_caption']").prop("checked",false);
            return false;
        });
        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) { // Save form action initial value
                deleteFormAction = form.action;
            }
            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id');

            console.log(form.action);

            $('#delete_modal').modal('show');
        });
    </script>
@stop
