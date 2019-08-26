<style>
    a {
    text-decoration: underline;
    color: blue;
    }
</style>
<script type="text/javascript">
	$(function() {

		function getArrearList(pschool_id, select_invoice_type){
			
		 	$.ajax({
		 		type: "POST",
	   	    	url: "{{$_app_path}}invoice/getarrearlist",
	 		    dataType: "json",
	   	    	data: {
	   		   		"pschool_id": pschool_id,
	   		   		"invoice_type": select_invoice_type
	  	 		},
	      		success: function(j_data){
	      			
	      			if( j_data.result == 'OK'){
	      				
						var id = 0;
						var td_str = "";
						var parent_name = "";
						var student = "";
						var school_year = "";
						var school_category = "";
						var school = "";
						var invoice_year_month = "";
						var amount = "";
						var payment_method = "";
						var link = "";

						var item_cnt = 0;
						for(var idx in j_data){
							if( j_data[idx].id != id ){
								if( id != 0 ){
									var append_data = '<tr>' + 
									'<td style="width:100px;">' + parent_name + '</td>' +
									'<td style="width:130px;">' + student + '</td>' +
									'<td style="width:80px;text-align:center;">' + school + '</td>' +
									'<td style="width:95px;text-align:center;">' + invoice_year_month + '</td>' +
									'<td style="width:100px;text-align:right;">' + amount + '</td>' + 
									'<td style="width:80px;text-align:center;">' + payment_method + '</td>' + 
									'<td style="width:160px;text-align:center;">' + link + '</td>' + 
									'</tr>';
									$('#arrear_list').append(append_data);
								}
								parent_name = '<a  href="' + {{$_app_path}} + "parent/detail?student_id=&orgparent_id=" + j_data[idx].parent_id + '">'
												+  j_data[idx].parent_name + "</a>";
								if( j_data[idx].student_no != null && j_data[idx].student_no != "" && j_data[idx].student_id != null){
									student      = '<a  href="' + {{$_app_path}} + "student/detail?id=" + j_data[idx].student_id + '">'
									              + j_data[idx].student_no + " " + j_data[idx].student_name + "<br></a>";
								} else {
									student      = '<a  href="' + {{$_app_path}} + "student/detail?id=" + j_data[idx].student_id + '">'
						              +  j_data[idx].student_name + "<br></a>";
								}
								category     = j_data[idx].school_category_name;
								year         = j_data[idx].school_year + "{{$lan::get('year_title')}}";
								school       = category + year;

								var str_year_month = new String(j_data[idx].invoice_year_month);
////							invoice_year_month = j_data[idx].invoice_year_month.substr(0,4) + "年" + j_data[idx].invoice_year_month.substr(5,2) + "月";
								invoice_year_month = str_year_month.substr(0,4) + "{{$lan::get('year_title')}}" + str_year_month.substr(5,2) + "{{$lan::get('month_title')}}";
								
								if( j_data[idx].amount_display_type == "0"){
									amount = new Intl.NumberFormat().format(j_data[idx].amount);
								} else {
									var calc_amount = Math.floor(j_data[idx].amount * j_data[idx].sales_tax_rate) + parseInt(j_data[idx].amount);
									amount = new Intl.NumberFormat().format(calc_amount);
									
								}
								payment_method = j_data[idx].payment_method;
								
								link = '<a  href="' + {{$_app_path}} + "invoice/detail?id=" + j_data[idx].id + '">' +
										j_data[idx].workflow_status + "</a>";
								
								
							} else {
////							student    += "<BR/>" + j_data[idx].student_no + " " + j_data[idx].student_name;
								if( j_data[idx].student_no != null && j_data[idx].student_no != "" && j_data[idx].student_id != null){
									student      += '<a  href="' + {{$_app_path}} + "student/detail?id=" + j_data[idx].student_id + '">'
									              + j_data[idx].student_no + " " + j_data[idx].student_name + "<br></a>";
								} else {
									student     += '<a  href="' + {{$_app_path}} + "student/detail?id=" + j_data[idx].student_id + '">'
						              +  j_data[idx].student_name + "<br></a>";
								}
								category   = j_data[idx].school_category_name;
								year       = j_data[idx].school_year + "{{$lan::get('year_title')}}";
								school     += "<BR/>" + category + year;
							}
							item_cnt++;

							id = j_data[idx].id;
						}
						if(item_cnt < 1 ){
							var nothing = '<tr class="table_row">' +
										  '<td class="error_row">{{$lan::get('information_displayed_title')}}</td>' +
										  '</tr>';
							$('#arrear_list').append(nothing);
						}
					} else {
	      				alert("{{$lan::get('error_read_data_title')}}");
	      			// エラー表示
	      			}
	        	}
			});
		}
		
		
		$('#arrear_check').change(function() {
			
			if($(this).prop('checked')) {
				$('#arrear_list').show();

				$( '#arrear_list' ).find("tr:gt(0)").remove();
				
				var pschool_id          = {{$pschool_id}};
				var select_invoice_type = $("select[name='parent_invoice_type']").val();
				
				getArrearList(pschool_id, select_invoice_type);
			} else {
				$( '#arrear_list' ).find("tr:gt(0)").remove();
				$('#arrear_list').hide();
			}
		});
		
		$("select[name='parent_invoice_type']").change(function() {

			if( $("#arrear_check").prop('checked') ){

				var pschool_id          = {{$pschool_id}};
				var select_invoice_type = $(this).val();

				$( '#arrear_list' ).find("tr:gt(0)").remove();
			
				getArrearList(pschool_id, select_invoice_type);
			}
		});
		
		
	});

</script>

