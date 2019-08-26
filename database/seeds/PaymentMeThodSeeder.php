<?php

use Illuminate\Database\Seeder;
use App\PaymentAgency;
use \App\Common\Constants;

class PaymentMeThodSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        try {
            DB::table ('payment_method')->truncate();
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method')->insert ([
                    "name" => "cash_method_title",
                    "code" => Constants::CASH,
                    "payment_agency_id" => "0",
                    "is_using_bank" => "0",
                    "description" => "",
                    "sort_no" => "1",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method')->insert ([
                    "name" => "bank_method_title",
                    "code" => Constants::TRAN_RICOH,
                    "payment_agency_id" => PaymentAgency::where('agency_code', 'RICO0001')->first()->id,
                    "is_using_bank" => "1",
                    "description" => "",
                    "sort_no" => "3",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method')->insert ([
                    "name" => "store_method_title",
                    "code" => Constants::CONV_RICOH,
                    "payment_agency_id" => PaymentAgency::where('agency_code', 'RICO0001')->first()->id,
                    "is_using_bank" => "1",
                    "description" => "",
                    "sort_no" => "4",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method')->insert ([
                "name" => "yucho_method_title",
                "code" => Constants::POST_RICOH,
                "payment_agency_id" => PaymentAgency::where('agency_code', 'RICO0001')->first()->id,
                "is_using_bank" => "1",
                "description" => "",
                "sort_no" => "5",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method')->insert ([
                "name" => "credit_card_method_title",
                "code" => Constants::CRED_ZEUS,
                "payment_agency_id" => PaymentAgency::where('agency_code', 'ZEUS0001')->first()->id,
                "is_using_bank" => "0",
                "description" => "",
                "sort_no" => "6",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method')->insert ([
                    "name" => "bank_transfer_method_title",
                    "code" => Constants::TRAN_BANK,
                    "payment_agency_id" => 0,
                    "is_using_bank" => "1",
                    "description" => "",
                    "sort_no" => "2",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method')->insert ([
                "name" => "credit_card_connect_method_title",
                "code" => Constants::CRED_ZEUS_CONNECT,
                "payment_agency_id" => PaymentAgency::where('agency_code', 'ZEUS0001')->first()->id,
                "is_using_bank" => "0",
                "description" => "",
                "sort_no" => "7",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
    }
}
