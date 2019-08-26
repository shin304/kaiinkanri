@extends('voyager::master')

@section('page_title','All '.$dataType->display_name_plural)

@section('css')
    <script type="text/javascript" src="{{ config('voyager.assets_path') }}/js/vue21.min.js"></script>
@stop

@section('page_header')
    {{--<h1 class="page-title">--}}
        {{--<i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_plural }}--}}
        {{--@if (Voyager::can('add_'.$dataType->name))--}}
            {{--<a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success">--}}
                {{--<i class="voyager-plus"></i> 登録--}}
            {{--</a>--}}
        {{--@endif--}}
    {{--</h1>--}}
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <style>
        .lock , .unlock {
            margin-left : 5px;
            width: 95px !important;
        }
        .locked_row {
            background-image: linear-gradient( 45deg, #fff 25%, rgba(52, 152, 219, 0.1) 25%, rgba(52, 152, 219, 0.1) 50%, #fff 50%, #fff 75%, rgba(52, 152, 219, 0.1) 75%, rgba(52, 152, 219, 0.1) );
            background-size: 12px 12px;
        }
    </style>
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body table-responsive">
                        <div class="col-md-12" style="text-align: right; padding-right: 0px !important;">
                            <a href="javascript:;" title="Clear" class="btn btn-sm btn-danger delete" style="font-size: 11px !important;">
                                <i class="voyager-data"></i>&nbsp;<span class="hidden-xs hidden-sm">古いレコードをクリア</span>&nbsp;
                            </a>
                        </div>
                        <table id="dataTable" class="row table table-hover">
                            <thead>
                                <tr>
                                    @foreach($dataType->browseRows as $rows)
                                    <th>{{ $rows->display_name }}</th>
                                    @endforeach
                                    <th class="actions">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataTypeContent as $data)
                                <tr @if($data->is_locked == 1) class="locked_row" @endif>
                                    @foreach($dataType->browseRows as $row)
                                        <td>
                                            <?php $options = json_decode($row->details); ?>
                                            @if($row->type == 'image')
                                                <img src="@if( strpos($data->{$row->field}, 'http://') === false && strpos($data->{$row->field}, 'https://') === false){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:100px">
                                            @elseif($row->type == 'select_multiple')
                                                @if(property_exists($options, 'relationship'))

                                                    @foreach($data->{$row->field} as $item)
                                                        @if($item->{$row->field . '_page_slug'})
                                                        <a href="{{ $item->{$row->field . '_page_slug'} }}">{{ $item->{$row->field} }}</a>@if(!$loop->last), @endif
                                                        @else
                                                        {{ $item->{$row->field} }}
                                                        @endif
                                                    @endforeach

                                                    {{-- $data->{$row->field}->implode($options->relationship->label, ', ') --}}
                                                @elseif(property_exists($options, 'options'))
                                                    @foreach($data->{$row->field} as $item)
                                                     {{ $options->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                    @endforeach
                                                @endif
                                                @if ($data->{$row->field} && isset($options->relationship))
                                                    {{ $data->{$row->field}->implode($options->relationship->label, ', ') }}
                                                @endif

                                            @elseif($row->type == 'select_dropdown' && property_exists($options, 'relationship'))
                                                <?php
                                                    $relationshipListMethod = camel_case($row->field) . 'List';
                                                    if (method_exists($data, $relationshipListMethod)) {
                                                        $relationshipOptions = $data->$relationshipListMethod();
                                                    } else {
                                                        $relationshipClass = $data->{camel_case($row->field)}()->getRelated();
                                                        $relationshipOptions = $data::all();
                                                    }
                                                    $relations = $relationshipOptions->pluck($options->relationship->label,'id');
                                                    ?>
                                                    @if($data->{$row->field})
                                                        {{ $relations[$data->{$row->field}]}}
                                                    @endif

                                            @elseif($row->type == 'select_dropdown' && property_exists($options, 'options'))

                                                @if($data->{$row->field . '_page_slug'})
                                                    <a href="{{ $data->{$row->field . '_page_slug'} }}">{!! $options->options->{$data->{$row->field}} !!}</a>
                                                @else
                                                    @if (isset($options->options->{$data->{$row->field}}))
                                                    {!! $options->options->{$data->{$row->field}} !!}
                                                    @endif
                                                @endif


                                            @elseif($row->type == 'select_dropdown' && $data->{$row->field . '_page_slug'})
                                                <a href="{{ $data->{$row->field . '_page_slug'} }}">{{ $data->{$row->field} }}</a>
                                            @elseif($row->type == 'date')
                                            {{ $options && property_exists($options, 'format') ? \Carbon\Carbon::parse($data->{$row->field})->formatLocalized($options->format) : $data->{$row->field} }}
                                            @elseif($row->type == 'checkbox')
                                                @if($options && property_exists($options, 'on') && property_exists($options, 'off'))
                                                    @if($data->{$row->field})
                                                    <span class="label label-info">{{ $options->on }}</span>
                                                    @else
                                                    <span class="label label-primary">{{ $options->off }}</span>
                                                    @endif
                                                @else
                                                {{ $data->{$row->field} }}
                                                @endif
                                            @elseif($row->type == 'text')
                                                @include('voyager::multilingual.input-hidden-bread-browse')
                                                <div class="readmore">{{ strlen( $data->{$row->field} ) > 200 ? substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                            @elseif($row->type == 'text_area')
                                                @include('voyager::multilingual.input-hidden-bread-browse')
                                                <div class="readmore">{{ strlen( $data->{$row->field} ) > 200 ? substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                            @elseif($row->type == 'rich_text_box')
                                                @include('voyager::multilingual.input-hidden-bread-browse')
                                                <div class="readmore">{{ strlen( strip_tags($data->{$row->field}, '<b><i><u>') ) > 200 ? substr(strip_tags($data->{$row->field}, '<b><i><u>'), 0, 200) . ' ...' : strip_tags($data->{$row->field}, '<b><i><u>') }}</div>
                                            @else
                                                @include('voyager::multilingual.input-hidden-bread-browse')
                                                <span>{{ $data->{$row->field} }}</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="no-sort no-click" id="bread-actions">
                                        @if($data-> status != 3)
                                            @if ( $data->is_locked == 0 )
                                                <a href="javascript:;" title="Lock" class="view_request btn btn-sm btn-warning pull-right lock" data-id="{{ $data->id }}" id="lock-{{ $data->id }}">
                                                    <i class="voyager-lock"></i> <span class="hidden-xs hidden-sm">ロック</span>
                                                </a>
                                                @if (Voyager::can('delete_'.$dataType->name))
                                                    <a href="javascript:;" title="Delete" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $data->id }}" id="delete-{{ $data->id }}">
                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">削除</span>
                                                    </a>
                                                @endif
                                                @if (Voyager::can('edit_'.$dataType->name))
                                                    <a href="{{ route('voyager.'.$dataType->slug.'.show', $data->id) }}" title="Edit" class="view_request btn btn-sm btn-primary pull-right edit" data-id="{{ $data->id }}">
                                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">編集</span>
                                                    </a>
                                                @endif
                                            @else
                                                <a href="javascript:;" title="Unlock" class="view_request btn btn-sm btn-warning pull-right unlock" data-id="{{ $data->id }}" id="unlock-{{ $data->id }}">
                                                    <i class="voyager-key"></i> <span class="hidden-xs hidden-sm">ロック解除</span>
                                                </a>
                                            @endif
                                        @else
                                            @if (Voyager::can('delete_'.$dataType->name))
                                                <a href="javascript:;" title="Delete" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $data->id }}" id="delete-{{ $data->id }}">
                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">削除</span>
                                                </a>
                                            @endif
                                        @endif

                                        {{--@if (Voyager::can('read_'.$dataType->name))--}}
                                            {{--<a href="{{ route('voyager.'.$dataType->slug.'.show', $data->id) }}" title="View" class="view_request btn btn-sm btn-warning pull-right">--}}
                                                {{--<i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">詳細</span>--}}
                                            {{--</a>--}}
                                        {{--@endif--}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if (isset($dataType->server_side) && $dataType->server_side)
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

    {{--DELETE MODAL--}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i>「{{ $dataType->display_name_singular }}」を削除しますか。</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                                 value="はい">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">キャンセル</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    {{--CHANGE LOCKED MODAL--}}
    <div class="modal modal-warning fade" tabindex="-1" id="lock_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-lock"></i>「{{ $dataType->display_name_singular }}」をロックしますか。</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.'.$dataType->slug.'.lock',1) }}" id="lock_form" method="POST">
                        {{ method_field("POST") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-warning pull-right lock-confirm"
                               value="はい">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">キャンセル</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-warning fade" tabindex="-1" id="unlock_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-key"></i>「{{ $dataType->display_name_singular }}」をロック解除しますか。</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.'.$dataType->slug.'.lock',1) }}" id="unlock_form" method="POST">
                        {{ method_field("POST") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-warning pull-right unlock-confirm"
                               value="はい">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">キャンセル</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    {{--Toran add modal show detail and approve demo--}}
    <div class="modal modal-info fade" tabindex="-1" id="table_info" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-data"></i> @{{ table.name }}</h4>
                </div>
                <form id="approve_form" action="{{ route('voyager.'.$dataType->slug.'.approve') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body" style="overflow:scroll">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td>
                                        契約種別
                                    </td>
                                    <td>
                                        <select id="plan_id" name="plan_id">
                                            @foreach($listPlan as $k => $v)
                                                <option value="{{$v->id}}">{{$v->plan_name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="row in table.rows">
                                    <td><strong>@{{ row.Field }}</strong></td>
                                    <td>@{{ row.Value }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </form>
                <div class="modal-footer" style="text-align: center">
                    <button type="button" class="btn btn-primary" id="submit_modal">承認</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">戻る</button>
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
        var table = {
            rows: []
        };
        new Vue({
            el: '#table_info',
            data: {
                table: table,
            },
        });
        $(document).ready(function () {
            @if (!$dataType->server_side)
                var table = $('#dataTable').DataTable({
                    "order": []
                    @if(config('dashboard.data_tables.responsive')), responsive: true @endif
                });
            @endif

            @if ($isModelTranslatable)
                $('.side-body').multilingual();
            @endif


        });

        // delete event
        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) { // Save form action initial value
                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id');

            $('#delete_modal').modal('show');
        });

        //unlock event
        var unlockFormAction;
        $('td').on('click', '.unlock', function (e) {
            var form = $('#unlock_form')[0];

            if (!unlockFormAction) { // Save form action initial value
                unlockFormAction = form.action;
            }

            form.action = unlockFormAction.match(/\/[0-9]+$/)
                ? unlockFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : unlockFormAction + '/' + $(this).data('id') ;

            $('<input>').attr('type','hidden')
                .attr('name','mode')
                .attr('value','0')
                .appendTo(form);

            $('#unlock_modal').modal('show');
        });

        //lock event
        var lockFormAction;
        $('td').on('click', '.lock', function (e) {
            var form = $('#lock_form')[0];

            if (!lockFormAction) { // Save form action initial value
                lockFormAction = form.action;
            }

            form.action = lockFormAction.match(/\/[0-9]+$/)
                ? lockFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : lockFormAction + '/' + $(this).data('id') ;

            $('<input>').attr('type','hidden')
                        .attr('name','mode')
                        .attr('value','1')
                        .appendTo(form);

            $('#lock_modal').modal('show');
        });

        //approve event
        var approveFormAction;
        $(document).on('click', '#submit_modal', function (e) {

            $('#approve_form').submit();

        });


        //view event
        $('#dataTable').on('click', '.view_request', function (e) {
            e.preventDefault();
            table.rows = [];
            var request_id = $(this).data('id');
            var href = $(this).attr('href');
            $.get(href,function(data){
                $.each(data, function (key, val) {
                    table.rows.push({
                        Field: key,
                        Value: val
                    });
                });
                $('<input>').attr('type','hidden')
                    .attr('name','id')
                    .attr('value',request_id)
                    .appendTo($("#approve_form"));

                $('#table_info').modal('show');
            });
        });
    </script>
@stop
