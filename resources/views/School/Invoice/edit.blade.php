<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
$(function() {
    $("#btn_add_row").click(function() {
        // コピー作成
        var tbl_item = $("#tbl_clone tbody > tr").clone(true).appendTo($(".adjust_name_table > tbody"));
        var adjust_count = $(".template_custom_item").length;
        // 名前を変更
        var student_id = $("input[name=current_student]").val();
        tbl_item.find("[name=template_custom_item_id]").attr("value", adjust_count);
        tbl_item.find("[name=template_custom_item_id]").attr("name", "custom_item[" + student_id + "]["+adjust_count+"][id]");
        tbl_item.find("[name=template_custom_item_name]").attr("name", "custom_item[" + student_id + "]["+adjust_count+"][name]");
        tbl_item.find("[name=template_custom_item_price]").attr("name", "custom_item[" + student_id + "]["+adjust_count+"][price]");
        return false;
    });

    $("#btn_submit").click(function () {
        $("#action_form").submit();
    })

    // js count amount
    $(".item_except").click(function (e) {
       // if($(this).is(":checked")){
            set_total_price();
       /// }
    })
    function set_total_price() {
        var discount_sum = 0;
        $('input[name="discount_price[]"]').each(function(){
            var val = $(this).val();
            if (val != "" && !isNaN(val)) {
                discount_sum += parseInt(val);
            }
        });
        $("#discount_price").text(String(discount_sum).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));

        var price_sum = 0;
        $('input[name*="custom_item_price"], input[name*="class_price"], input[name*="course_price"], input[name*="program_price"]').each(function(){
            var val = $(this).val();
            if (val != "" && !isNaN(val)) {
                price_sum += parseInt(val);
            }
        });

        var _class_except_count = 0;
        $('input[name*="_class_except"]').each(function(){
            _class_except_count++;
            if($(this).val() == 1) {
                var class_price_count = 0;
                $('input[name*="class_price"]').each(function() {
                    class_price_count++;
                    if(_class_except_count == class_price_count) {
                        var val = $(this).val();
                        if (val != "" && !isNaN(val)) {
                            price_sum -= parseInt(val);
                        }
                        return false;
                    }
                });
            }
        });
        var _course_except_count = 0;
        $('input[name*="_course_except"]').each(function(){
            _course_except_count++;
            if($(this).val() ==1) {
                var course_price_count = 0;
                $('input[name*="course_price"]').each(function() {
                    course_price_count++;
                    if(_course_except_count == course_price_count) {
                        var val = $(this).val();
                        if (val != "" && !isNaN(val)) {
                            price_sum -= parseInt(val);
                        }
                        return false;
                    }
                });
            }
        });
        var _program_except_count = 0;
        $('input[name*="_program_except"]').each(function(){
            _program_except_count++;
            if($(this).val() ==1) {
                var program_price_count = 0;
                $('input[name*="program_price"]').each(function() {
                    program_price_count++;
                    if(_program_except_count == program_price_count) {
                        var val = $(this).val();
                        if (val != "" && !isNaN(val)) {
                            price_sum -= parseInt(val);
                        }
                        return false;
                    }
                });
            }
        });

        var amount = price_sum - discount_sum;
        $("[name=amount]").text(String(amount).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));

        var tax_price = 0;
        var amount_tax = 0;
        var def_tax = $("input[name=sales_tax_rate]").val();
        if (!def_tax) def_tax = 0;
        var sales_tax_rate = parseFloat(def_tax);
        if ($("input[name=amount_display_type]").val() == "0") {
            tax_price = Math.floor(amount * (sales_tax_rate * 100) / ((sales_tax_rate * 100) + 100));
            amount_tax = amount;
        } else {
            tax_price = Math.floor(amount * sales_tax_rate);
            amount_tax = amount + tax_price;
        }
        $("[name=tax_price]").text(String(tax_price).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));
        $("[name=amount_tax]").text(String(amount_tax).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));

        return false;
    }
    $("#btn_return").click(function () {
        $("#frm_return").submit();
    })
});
</script>
<style>
    #show_preview:hover, #btn_return:hover, #btn_add_row:hover, .remove_custom_item:hover {
        background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
        box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
        cursor: pointer;
        text-shadow: 0 0px #FFF;
    }
    #show_preview, #btn_return, .remove_custom_item, #btn_add_row {
        color: #595959;
        height: 30px;
        border-radius: 5px;
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        /*font-size: 14px;*/
        font-weight: normal;
        text-shadow: 0 0px #FFF;
    }
    .btn5 {
        border: solid 1px #ccc;
    }
</style>
@include('_parts.invoice.invoice_menu')

	<h3 id="content_h3" class="box_border1">{{$lan::get('detailed_information_title')}}</h3>
	<div id="section_content">
		{{--@if( $request['action_message'])
		<div class="alart_box box_shadow">
			<ul class="message_area">
				<li class="@if( $request['action_status'] == 'OK')
								info_message
							@else 
								error_message
							@endif">
					{{$request['action_message']}}
				</li>
			</ul>
			<div id="data_table"></div>
		</div>
		@endif--}}

        <form id="action_form" method="post" action="/school/invoice/completeEditInvoice">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$request['id']}}"/>
            <input type="hidden" name="invoice_year_month" value="{{$request['invoice_year_month']}}"/>
            <input type="hidden" name="current_student" value="{{$item_list['current_student']}}"/>
            <input type="hidden" name="preview_bool" value=""/>
			<div id="center_content_main">
				@include('_parts.invoice.invoice_input')
				<br/>
                <input class="btn_green" id="btn_submit" type="submit" name="confirm_button" value="{{$lan::get('register_title')}}" /><br/>
                <br/><button id="btn_return" class="submit3" type="button"><i class="glyphicon glyphicon-circle-arrow-left" style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
				<div id="section_content_in">
				</div>
			</div>

		</form>
	</div>
    <form action="/school/invoice/detail?id={{array_get($item_list,'id')}}&invoice_year_month={{array_get($heads,'invoice_year_month')}}" method="post" id="frm_return">
        {{ csrf_field() }}
    </form>
@stop
<div id="dialog-confirm"  style="display: none;">
	{{$lan::get('confirm_delete_title')}}
</div>

