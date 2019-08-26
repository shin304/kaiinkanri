<?php

use Illuminate\Database\Seeder;

class PaymentMethodValidationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table ('payment_method_validation')->truncate();
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_validation')->insert ([
                    "payment_method_setting_id" => "1",
                    "rule" => "required",
                    "message" => "required_consignor_code_error_title",
                    "description" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_validation')->insert ([
                    "payment_method_setting_id" => "1",
                    "rule" => "digits:10",
                    "message" => "length_consignor_code_error_title",
                    "description" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_validation')->insert ([
                    "payment_method_setting_id" => "2",
                    "rule" => "required",
                    "message" => "required_consignor_name_error_title",
                    "description" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_validation')->insert ([
                    "payment_method_setting_id" => "2",
                    "rule" => "max:40",
                    "message" => "length_consignor_name_error_title",
                    "description" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_validation')->insert ([
                    "payment_method_setting_id" => "2",
                    "rule" => "regex:/^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$/u",
                    "message" => "format_consignor_name_error_title",
                    "description" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        //
        try {
            DB::table ('payment_method_validation')->insert ([
                    "payment_method_setting_id" => "4",
                    "rule" => "required",
                    "message" => "required_consignor_code_error_title",
                    "description" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_validation')->insert ([
                    "payment_method_setting_id" => "4",
                    "rule" => "digits:10",
                    "message" => "length_consignor_code_error_title",
                    "description" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_validation')->insert ([
                    "payment_method_setting_id" => "5",
                    "rule" => "required",
                    "message" => "required_consignor_name_error_title",
                    "description" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_validation')->insert ([
                    "payment_method_setting_id" => "5",
                    "rule" => "max:40",
                    "message" => "length_consignor_name_error_title",
                    "description" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_validation')->insert ([
                    "payment_method_setting_id" => "5",
                    "rule" => "regex:/^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$/u",
                    "message" => "format_consignor_name_error_title",
                    "description" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }

        try {
            DB::table ('payment_method_validation')->insert ([
                "payment_method_setting_id" => "11",
                "rule" => "required",
                "message" => "required_ip_code_error_title",
                "description" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            DB::table ('payment_method_validation')->insert ([
                "payment_method_setting_id" => "11",
                "rule" => "digits:10",
                "message" => "length_ip_code_error_title",
                "description" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e) {

        }
    }
}
