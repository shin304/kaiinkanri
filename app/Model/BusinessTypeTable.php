<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BusinessTypeTable extends DbModel {
    private static $_instance = null;
    protected $table = 'business_type';
    public $timestamps = false;
    private static $itemPerPage = 5;
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new BusinessTypeTable ();
        }
        return self::$_instance;
    }
    public function getAllType() {
        $res = array ();
        $res = DB::table ( 'business_type' )->whereNull ( 'delete_date' )->orderBy ( 'update_date', 'desc' )->paginate ( self::$itemPerPage );
        return $res;
    }
    public function countTotal() {
        $count = DB::table ( 'business_type' )->whereNull ( 'delete_date' )->count ();
        return $count;
    }
    public function getTypeByID($id) {
        $res = array ();
        
        $res = DB::table ( 'business_type' )->where ( 'id', '=', $id )->get ();
        return $res;
    }
    public function addSingleType($type) {
        try {
            DB::beginTransaction ();
            $id = DB::table ( 'business_type' )->insertGetId ( $type );
            DB::commit ();
            return 'Success';
        } catch ( Exception $e ) {
            DB::rollBack ();
            return $e;
        }
    }
    public function updateSingleType($type) {
        try {
            DB::beginTransaction ();
            DB::table ( 'business_type' )->where ( 'id', $type ['id'] )->update ( $type );
            DB::commit ();
            return 'Success';
        } catch ( Exception $e ) {
            DB::rollBack ();
            return $e;
        }
    }
    public function deleteTypes($ids) {
        try {
            DB::beginTransaction ();
            foreach ( $ids as $id ) {
                DB::table ( 'business_type' )->where ( 'id', '=', $id )->update ( [ 
                        'delete_date' => date ( "Y-m-d h:i:s" ) 
                ] );
            }
            DB::commit ();
            return 'Success';
        } catch ( Exception $e ) {
            DB::rollBack ();
            return $e;
        }
    }
    public function checkExistType($type_name, $curr = null) {
        if (empty ( $curr )) {
            $id = DB::table ( 'business_type' )->where ( 'type_name', '=', $type_name )->get ();
        } else {
            $id = DB::table ( 'business_type' )->where ( [ 
                    [ 
                            'type_name',
                            '=',
                            $type_name 
                    ],
                    [ 
                            'type_name',
                            '!=',
                            $curr 
                    ] 
            ] )->get ();
        }
        
        if (count ( $id ) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
