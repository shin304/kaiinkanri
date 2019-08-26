<script type="text/javascript">
    $(function () {
//        $(".tablesorter").tablesorter({
//            // initialization
//            headers: {
//                0: { sorter: false},
//                5: { sorter: false},
//                6: { sorter: false},
//            },
//        }).bind("sortEnd",
//            function(sorter) {
//                currentSort = sorter.target.config.sortList;
//            }
//        );
        $(document).on("click", ".btn_create_invoice", function () {
            var link = $(this).data('href');
            java_post(link);
            return false;
        })
        $(".sort_parent_name").click(function (e) {
            e.preventDefault();
            sort_table("parent_name",$(this));
        })
        $(".sort_student_name").click(function (e) {
            e.preventDefault();
            sort_table("student_name",$(this));
        })
        $(".sort_student_type").click(function (e) {
            e.preventDefault();
            sort_table("student_type",$(this));
        })
        $(".sort_student_number").click(function (e) {
            e.preventDefault();
            sort_table("student_number",$(this));
        })
        $(".sort_payment_method").click(function (e) {
            e.preventDefault();
            sort_table("payment_method",$(this));
        })

        function sort_table(className,ele){

            if(ele.children().hasClass("fa-chevron-down")){
                ele.children().removeClass("fa-chevron-down");
                ele.children().addClass("fa-chevron-up");
            }else if(ele.children().hasClass("fa-chevron-up")){
                ele.children().removeClass("fa-chevron-up");
                ele.children().addClass("fa-chevron-down");
            }
            var arr_header=[];
            $(".content_detail tbody tr").each(function () {
                arr_header.push([$(this).find('.'+className).text(),$(this)]);
            });
            if(ele.data("sort")==1){
                ele.data("sort",2);
                arr_header = arr_header.sort(function(a,b) {
                    return (a[0] === b[0]) ? 0 : (a[0] > b[0]) ? -1 : 1
                });
            }else{
                ele.data("sort",1);
                arr_header = arr_header.sort(function(a,b) {
                    return (a[0] === b[0]) ? 0 : (a[0] < b[0]) ? -1 : 1
                });
            }

            $(".content_detail tbody").html('');
            arr_header.forEach(function (value) {
                $(".content_detail tbody").append(value[1]);
            });
        }
    })
</script>
<style>
    table.tablesorter thead tr .header{
        background-image: url(/css/school/images/tablesorter-bg.gif);
    }
</style>
<div class="content_detail">
	<table class="table_list body_scroll_table" style=" display:inline-block;">
		<thead>
			<tr>
				<th style="width:200px;" class="text_title sort_parent_name">{{$lan::get('guardian_fullname_title')}}<i style="font-size:12px;" class="fa fa-chevron-down"></i></th>
                <th style="width:150px;" class="text_title sort_student_type">{{$lan::get('dp_student_type')}} <i style="font-size:12px;" class="fa fa-chevron-down"></i></th>
                <th style="width:180px;" class="text_title sort_student_name">{{$lan::get('member_name_title')}} <i style="font-size:12px;" class="fa fa-chevron-down"></i></th>
				<th style="width:120px;" class="text_title sort_student_number">{{$lan::get('membership_number_title')}} <i style="font-size:12px;" class="fa fa-chevron-down"></i></th>
				<th style="width:150px;" class="text_title sort_payment_method">{{$lan::get('dp_payment_method')}} <i style="font-size:12px;" class="fa fa-chevron-down"></i></th>
				<th style="width:50px;" class="text_title">&nbsp;</th>
			</tr>
		</thead>
		<tbody style="height: 400px; display: block;overflow-y: auto;  overflow-x: hidden; ">
		@if(isset($parent_list))
			@foreach ($parent_list as $idx => $row)
                    <tr class="table_row">
                        <td style="width:200px;">{{array_get($row, 'parent_name')}}<span style="display:none" class="parent_name">{{array_get($row,'parent_name_kana')}}</span></td>
                        <td style="width:180px;" class="student_name">
                            {{array_get($row, 'student_name')}}<br/>
                        </td>
                        <td style="width:120px;" class="student_number">
                            {{array_get($row, 'student_no')}}<br/>
                        </td>
                        <td style="width:150px;" class="student_type">{{array_get($row, 'student_type')}}</td>
                        <td style="width:150px;">
                            <li class= "payment_method" style = "text-align : center ; list-style-type: none; margin : auto; width : 120px; border-radius: 5px;background-color: {{$invoice_background_color[$row['invoice_type']]['top']}} ; background: linear-gradient(to bottom, {{$invoice_background_color[$row['invoice_type']]['top']}} 0%, {{$invoice_background_color[$row['invoice_type']]['bottom']}} 100%); color :white ; font-weight: 500" >
                                {{array_get($row, 'payment_method')}}
                            </li>
                        </td>
                        <td style="width:80px; text-align: center;">
                            <button class="btn_create_invoice" type="button" data-href="{{$_app_path}}invoice/entry?parent_id={{array_get($row, 'parent_id')}}&invoice_year_month={{array_get($request, 'invoice_year_month')}}&invoice_type={{array_get($row, 'invoice_type')}}">{{$lan::get('selection_title')}}</button>
                        </td>
                    </tr>
            @endforeach
        @endif
        @if(!isset($parent_list))
            <tr class="table_row">
                <td class="error_row">{{$lan::get('no_guardian_created_invoice_title')}}</td>
            </tr>
        @endif
		</tbody>
	</table>
</div>
