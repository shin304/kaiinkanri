<?php

use Illuminate\Database\Seeder;
use App\PaymentAgency;
use App\Model\PaymentMethodTable;
use App\Common\Constants;

class PaymentMethodSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table ('payment_method_setting')->truncate();
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_setting')->insert ([
                    "payment_method_id" => PaymentMethodTable::where('code', Constants::TRAN_RICOH)->first()->id,
                    "payment_agency_id" => PaymentAgency::where('agency_code', 'RICO0001')->first()->id,
                    "item_name" => "consignor_code",
                    "item_display_name" => "consignor_code_title",
                    "item_type" => "1",
                    "unit" => "",
                    "description" => "",
                    "note" => "10_digit_no_title",
                    "place_holder" => "",
                    "default_value" => "",
                    "sort_no" => "1",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_setting')->insert ([
                    "payment_method_id" => PaymentMethodTable::where('code', Constants::TRAN_RICOH)->first()->id,
                    "payment_agency_id" => PaymentAgency::where('agency_code', 'RICO0001')->first()->id,
                    "item_name" => "consignor_name",
                    "item_display_name" => "consignor_name_title",
                    "item_type" => "1",
                    "unit" => "",
                    "description" => "",
                    "note" => "40_single_kana_upper_title",
                    "place_holder" => "",
                    "default_value" => "",
                    "sort_no" => "1",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_setting')->insert ([
                    "payment_method_id" => PaymentMethodTable::where('code', Constants::TRAN_RICOH)->first()->id,
                    "payment_agency_id" => PaymentAgency::where('agency_code', 'RICO0001')->first()->id,
                    "item_name" => "withdrawal_date",
                    "item_display_name" => "debit_day_title",
                    "item_type" => "2",
                    "unit" => "day_title",
                    "description" => "",
                    "note" => "",
                    "place_holder" => "",
                    "default_value" => "1:4;2:20;3:27",
                    "sort_no" => "1",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        //
        try {
            DB::table ('payment_method_setting')->insert ([
                    "payment_method_id" => PaymentMethodTable::where('code', Constants::CONV_RICOH)->first()->id,
                    "payment_agency_id" => PaymentAgency::where('agency_code', 'ZEUS0001')->first()->id,
                    "item_name" => "consignor_code",
                    "item_display_name" => "consignor_code_title",
                    "item_type" => "1",
                    "unit" => "",
                    "description" => "",
                    "note" => "10_digit_no_title",
                    "place_holder" => "",
                    "default_value" => "",
                    "sort_no" => "1",
                    "register_admin" => 1,
                    "delete_date" => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_setting')->insert ([
                    "payment_method_id" => PaymentMethodTable::where('code', Constants::CONV_RICOH)->first()->id,
                    "payment_agency_id" => PaymentAgency::where('agency_code', 'ZEUS0001')->first()->id,
                    "item_name" => "consignor_name",
                    "item_display_name" => "consignor_name_title",
                    "item_type" => "1",
                    "unit" => "",
                    "description" => "",
                    "note" => "40_single_kana_upper_title",
                    "place_holder" => "",
                    "default_value" => "",
                    "sort_no" => "1",
                    "register_admin" => 1,
                    "delete_date" => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_setting')->insert ([
                    "payment_method_id" => PaymentMethodTable::where('code', Constants::CONV_RICOH)->first()->id,
                    "payment_agency_id" => PaymentAgency::where('agency_code', 'ZEUS0001')->first()->id,
                    "item_name" => "withdrawal_date",
                    "item_display_name" => "debit_day_title",
                    "item_type" => "2",
                    "unit" => "day_title",
                    "description" => "",
                    "note" => "",
                    "place_holder" => "",
                    "default_value" => "1:5;2:15;3:25",
                    "sort_no" => "1",
                    "register_admin" => 1,
                    "delete_date" => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method_setting')->insert ([
                "payment_method_id" => PaymentMethodTable::where('code', Constants::CONV_RICOH)->first()->id,
                "payment_agency_id" => PaymentAgency::where('agency_code', 'RICO0001')->first()->id,
                "item_name" => "send_form",
                "item_display_name" => "send_form_title",
                "item_type" => "4",
                "unit" => "",
                "description" => "",
                "note" => "",
                "place_holder" => "",
                "default_value" => json_encode([
                    '1' => 'fusho_title',
                    '2' => 'hagaki_title',
                    '3' => 'itaki_nashi_title',
                ]),
                "sort_no" => "1",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method_setting')->insert ([
                "payment_method_id" => PaymentMethodTable::where('code', Constants::CONV_RICOH)->first()->id,
                "payment_agency_id" => PaymentAgency::where('agency_code', 'RICO0001')->first()->id,
                "item_name" => "pay_easy",
                "item_display_name" => "pay_easy_title",
                "item_type" => "4",
                "unit" => "",
                "description" => "",
                "note" => "",
                "place_holder" => "",
                "default_value" => json_encode([
                    '1' => 'unused_title',
                    '2' => 'used_title',
                ]),
                "sort_no" => "1",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method_setting')->insert ([
                "payment_method_id" => PaymentMethodTable::where('code', Constants::POST_RICOH)->first()->id,
                "payment_agency_id" => PaymentAgency::where('agency_code', 'RICO0001')->first()->id,
                "item_name" => "send_form",
                "item_display_name" => "send_form_title",
                "item_type" => "4",
                "unit" => "",
                "description" => "",
                "note" => "",
                "place_holder" => "",
                "default_value" => json_encode([
                    '1' => 'fusho_title',
                    '2' => 'hagaki_title',
                    '3' => 'itaki_nashi_title',
                ]),
                "sort_no" => "1",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method_setting')->insert ([
                "payment_method_id" => PaymentMethodTable::where('code', Constants::POST_RICOH)->first()->id,
                "payment_agency_id" => PaymentAgency::where('agency_code', 'RICO0001')->first()->id,
                "item_name" => "pay_easy",
                "item_display_name" => "pay_easy_title",
                "item_type" => "4",
                "unit" => "",
                "description" => "",
                "note" => "",
                "place_holder" => "",
                "default_value" => json_encode([
                    '1' => 'unused_title',
                    '2' => 'used_title',
                ]),
                "sort_no" => "1",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method_setting')->insert ([
                "payment_method_id" => PaymentMethodTable::where('code', Constants::CRED_ZEUS)->first()->id,
                "payment_agency_id" => PaymentAgency::where('agency_code', 'ZEUS0001')->first()->id,
                "item_name" => "ip_code",
                "item_display_name" => "ip_code_half_width_number_title",
                "item_type" => "1",
                "unit" => "",
                "description" => "",
                "note" => "10_digit_no_title",
                "place_holder" => "",
                "default_value" => "",
                "sort_no" => "1",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method_setting')->insert ([
                "payment_method_id" => PaymentMethodTable::where('code', Constants::CRED_ZEUS_CONNECT)->first()->id,
                "payment_agency_id" => PaymentAgency::where('agency_code', 'ZEUS0001')->first()->id,
                "item_name" => "ip_code",
                "item_display_name" => "ip_code_half_width_number_title",
                "item_type" => "1",
                "unit" => "",
                "description" => "",
                "note" => "10_digit_no_title",
                "place_holder" => "",
                "default_value" => "",
                "sort_no" => "1",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
    }
}
