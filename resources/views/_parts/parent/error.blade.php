 @if( isset($request->errors['parent_name']['notEmpty']))
<li class="error_message">{{$claimant_name_required}}</li>
@endif @if( isset($request->errors['parent_name']['overLength']))
<li class="error_message">{{$claimant_email_address_1_255}}</li>
@endif @if( isset($request->errors['parent_mailaddress1']['notEmpty']))
<li class="error_message">{{$require_claimant_email_address_1}}</li>
@endif @if(
isset($request->errors['parent_mailaddress1']['mailAddress']))
<li class="error_message">{{$invalid_format_claimant_email_address_1}}</li>
@endif @if(
isset($request->errors['parent_mailaddress1']['overLength']))
<li class="error_message">{{$claimant_email_address_1_64}}</li>
@endif @if( isset($request->errors['name_kana']['notEmpty']))
<li class="error_message">{{$require_claimant_name_kana}}</li>
@endif @if( isset($request->errors['name_kana']['isZenkakukana']))
<li class="error_message">{{$character_valid_claimant_name_kana}}</li>
@endif @if( isset($request->errors['name_kana']['overLength']))
<li class="error_message">{{$claimant_name_kana_255}}</li>
@endif @if( isset($request->errors['zip_code1']['isDigit']))
<li class="error_message">{{$zip_code_1_single_byte_number}}</li>
@endif @if( isset($request->errors['zip_code1']['equalsLength']))
<li class="error_message">{{$zip_code_1_3_digit}}</li>
@endif @if( isset($request->errors['zip_code2']['isDigit']))
<li class="error_message">{{$zip_code_2_single_byte_number}}</li>
@endif @if( isset($request->errors['zip_code2']['equalsLength']))
<li class="error_message">{{$zip_code_2_4_digit}}</li>
@endif @if( isset($request->errors['pref_id']['notEmpty']))
<li class="error_message">{{$require_state_name}}</li>
@endif @if( isset($request->errors['city_id']['notEmpty']))
<li class="error_message">{{$require_city_name}}</li>
@endif @if( isset($request->errors['address']['notEmpty']))
<li class="error_message">{{$mandatory_address}}</li>
@endif @if( isset($request->errors['phone_no']['notEmpty']))
<li class="error_message">{{$require_home_telephone}}</li>
@endif @if( isset($request->errors['phone_no']['telNo']))
<li class="error_message">{{$invalid_home_phone_number}}</li>
@endif @if( isset($request->errors['bank_code']['notEmpty']))
<li class="error_message">{{$require_financial_institutions_code}}</li>
@endif @if( isset($request->errors['bank_code']['isDigit']))
<li class="error_message">{{$financial_institutions_code_alphanumeric_character}}</li>
@endif @if( isset($request->errors['bank_code']['overLength']))
<li class="error_message">{{$please_enter_more_than_four_character_financial_institution_code}}</li>
@endif @if( isset($request->errors['bank_name']['notEmpty']))
<li class="error_message">{{$mandatory_financial_institution_name}}</li>
@endif @if( isset($request->errors['bank_name']['isHankaku']))
<li class="error_message">{{$financial_institution_name_alphanumeric_kana}}</li>
@endif @if( isset($request->errors['bank_name']['overLength']))
<li class="error_message">{{$financial_institution_name_255}}</li>
@endif @if( isset($request->errors['branch_code']['notEmpty']))
<li class="error_message">{{$require_branch_code}}</li>
@endif @if( isset($request->errors['branch_code']['isDigit']))
<li class="error_message">{{$branch_code_alphanumeric}}</li>
@endif @if( isset($request->errors['branch_code']['overLength']))
<li class="error_message">{{$within_three_character_branch_code}}</li>
@endif @if( isset($request->errors['branch_name']['notEmpty']))
<li class="error_message">{{$require_branch_name}}</li>
@endif @if( isset($request->errors['branch_name']['isHankaku']))
<li class="error_message">{{$branch_name_alphanumeric_kana}}</li>
@endif @if( isset($request->errors['branch_name']['overLength']))
<li class="error_message">{{$branch_name_255}}</li>
@endif @if( isset($request->errors['bank_account_type']['notEmpty']))
<li class="error_message">{{$require_financial_institution_type}}</li>
@endif @if( isset($request->errors['bank_account_number']['notEmpty']
<li class="error_message">{{$require_account_number}}</li>
@endif @if( isset($request->errors['bank_account_number']['isDigit']))
<li class="error_message">{{$account_number_entered_number}}</li>
@endif @if(
isset($request->errors['bank_account_number']['overLength']))
<li class="error_message">{{$account_number_up_7_character}}</li>
@endif @if( isset($request->errors['post_account_kigou']['notEmpty']))
<li class="error_message">{{$mandatory_passbook_symbol}}</li>
@endif @if( isset($request->errors['post_account_kigou']['isDigit']))
<li class="error_message">{{$number_passbook_symbol}}</li>
@endif @if( isset($request->errors['post_account_kigou']['overLength']))
<li class="error_message">{{$within_5_character_passbook_symbol}}</li>
@endif @if( isset($request->errors['post_account_numbe']['notEmpty']))
<li class="error_message">{{$require_passbook_number}}</li>
@endif @if( isset($request->errors['post_account_number']['isDigit']))
<li class="error_message">{{$passbook_number_entered_number}}</li>
@endif @if(
isset($request->errors['post_account_number']['overLength']))
<li class="error_message">{{$passbook_number_up_8_character}}</li>
@endif @if( isset($request->errors['bank_account_name']['notEmpty']))
<li class="error_message">{{$account_holder_require}}</li>
@endif @if( isset($request->errors['bank_account_name']['isHankaku']))
<li class="error_message">{{$account_holder_entered_alphanumeric_kana}}</li>
@endif @if( isset($request->errors['bank_account_name']['overLength']))
<li class="error_message">{{$account_holder_up_30_character}}</li>
@endif @if(
isset($request->errors['bank_account_name_kana']['notEmpty']))
<li class="error_message">{{$require_account_name_kana}}</li>
@endif @if(
isset($request->errors['bank_account_name_kana['overLength']))
<li class="error_message">{{$account_name_kana_up_255_character}}</li>
@endif @if( isset($request->errors['post_account_name']['notEmpty']))
<li class="error_message">{{$require_passbook_name}}</li>
@endif @if( isset($request->errors['post_account_name']['isHankaku']))
<li class="error_message">{{$passbook_name_entered_alphanumeric_kana}}</li>
@endif @if( isset($request->errors['post_account_name']['overLength']))
<li class="error_message">{{$passbook_name_up_30_character}}</li>
@endif @foreach($request->errors as $idx => $error) @if(
$error['payment_month']['notEmpty'])
<li class="error_message">{{$error['name']}}{{$require_target_month}}</li>
@endif @if( $error['payment_adjust']['notEmpty'])
<li class="error_message">{{$error['name']}}{{$require_abstract}}</li>
@endif @if( $error['payment_fee']['notEmpty'])
<li class="error_message">{{$error['name']}}{{$require_amount_of_money}}</li>
@endif @if( $error['payment_fee']['notNumeric'])
<li class="error_message">{{$error['name']}}{{$amount_money_numeric}}</li>
@endif @if( $error['payment_fee']['Mean'])
<li class="error_message">{{$error['name']}}{{$target_month_there_same_thing}}</li>
@endif @endforeach


