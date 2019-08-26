@extends('voyager::master')

@section('page_title','All '.$dataType->display_name_plural)

@section('page_header')

    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_plural }}
        @if (Voyager::can('add_'.$dataType->name))
            <a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success">
                <i class="voyager-plus"></i> 登録
            </a>
        @endif
    </h1>

    @include('voyager::multilingual.language-selector')
@stop

@section('content')

    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <form role="form"
              class=""
              action="{{ route('voyager.holiday.execute') }}"
              method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <table width="45%" style="margin-left: 20px;">
                <tr>
                    <td align="center" width="20%">
                        <label class=""> CSV入力</label>
                    </td>
                    <td width="18%">
                        <input type="file" id="file" name="csv_file" multiple="multiple" class="inputfile" />
                        {{ csrf_field() }}
                    </td>
                    <td width="7%">
                        <button type="submit" class="btn btn-success btn-add-new"><i class="glyphicon glyphicon-floppy-disk"></i> 取込</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body table-responsive">
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
                                <tr>
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
                                        @if (Voyager::can('read_'.$dataType->name))
                                            <a href="{{ route('voyager.'.$dataType->slug.'.show', $data->id) }}" title="View" class="btn btn-sm btn-warning pull-right">
                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">詳細</span>
                                            </a>
                                        @endif
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
        </form>
    </div>

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
    <style>
        .page-title {
            height: 70px;
        }
    </style>
@stop
