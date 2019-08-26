<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\BusinessTypeTable;

class BusinessTypeController extends _BaseSystemController {
    public function __construct() {
        parent::__construct ();
    }
    public function index() {
        $businessType = BusinessTypeTable::getInstance ();
        $types = $businessType->getAllType ();
        $count = $businessType->countTotal ();
        return view ( 'System.BusinessType.index' )->with ( 'types', $types )->with ( 'total', $count );
    }
    public function add() {
        return view ( 'System.BusinessType.add' );
    }
    public function edit() {
        $id = $_REQUEST ['id'];
        $obj = BusinessTypeTable::getInstance ()->getTypeByID ( $id );
        $type = ( array ) $obj [0];
        return view ( 'System.BusinessType.add' )->with ( 'type', $type );
    }
    public function doAdd() {
        $type = array ();
        /*
         * $file = request()->file('resource_file');
         * if(!empty($file) || isset($file)){
         * $rand_name = time().$file->getClientOriginalName();
         * $file->storeAs('BusinessType',$rand_name);
         * $type['resource_file'] = $rand_name;
         * }
         */
        $register_date = date ( "Y-m-d h:i:s" );
        $businessType = BusinessTypeTable::getInstance ();
        
        if (! isset ( $_REQUEST ['type_name'] ) || empty ( $_REQUEST ['type_name'] )) {
            $type ['error'] ['type_name'] = 'empty';
        } else {
            if ($businessType->checkExistType ( $_REQUEST ['type_name'] )) {
                $type ['error'] ['type_name'] = 'exist';
            }
        }
        $type ['type_name'] = $_REQUEST ['type_name'];
        if (! isset ( $_REQUEST ['resource_file'] ) || empty ( $_REQUEST ['resource_file'] )) {
            $type ['error'] ['resource_file'] = 'empty';
        }
        $type ['resource_file'] = $_REQUEST ['resource_file'];
        
        if (! empty ( $type ['error'] )) {
            
            return view ( 'System.BusinessType.add' )->with ( 'type', $type );
        }
        $type ['register_date'] = $register_date;
        $type ['update_date'] = $register_date;
        $type ['register_admin'] = 1;
        $type ['update_admin'] = 1;
        $res = $businessType->addSingleType ( $type );
        if ($res == 'Success') {
            return redirect ()->route ( 'index' );
        } else {
            return view ( 'System.BusinessType.add' )->with ( 'error', $res )->with ( 'type', $type );
        }
    }
    public function doEdit() {
        $type = array ();
        /*
         * $file = request()->file('resource_file');
         * if(!empty($file) || isset($file)){
         * $rand_name = time().$file->getClientOriginalName();
         * $file->storeAs('BusinessType',$rand_name);
         * $type['resource_file'] = $rand_name;
         * }
         */
        $update_date = date ( "Y-m-d h:i:s" );
        $businessType = BusinessTypeTable::getInstance ();
        $type ['id'] = $_REQUEST ['id'];
        $curr = $businessType->getTypeByID ( $_REQUEST ['id'] );
        
        if (! isset ( $_REQUEST ['type_name'] ) || empty ( $_REQUEST ['type_name'] )) {
            $type ['error'] ['type_name'] = 'empty';
        } else {
            if ($businessType->checkExistType ( $_REQUEST ['type_name'], $curr [0]->type_name )) {
                $type ['error'] ['type_name'] = 'exist';
            }
        }
        $type ['type_name'] = $_REQUEST ['type_name'];
        if (! isset ( $_REQUEST ['resource_file'] ) || empty ( $_REQUEST ['resource_file'] )) {
            $type ['error'] ['resource_file'] = 'empty';
        }
        $type ['resource_file'] = $_REQUEST ['resource_file'];
        
        if (! empty ( $type ['error'] )) {
            return view ( 'System.BusinessType.add' )->with ( 'type', $type );
        }
        $type ['update_date'] = $update_date;
        $type ['register_admin'] = 1;
        $type ['update_admin'] = 1;
        
        $res = $businessType->updateSingleType ( $type );
        if ($res == 'Success') {
            return redirect ()->route ( 'index' );
        } else {
            return view ( 'System.BusinessType.add' )->with ( 'error', $res )->with ( 'type', $type );
        }
    }
    public function delete() {
        $res = "";
        if (isset ( $_REQUEST ["chk"] ) && ! empty ( $_REQUEST ["chk"] )) {
            $ids = array_keys ( $_REQUEST ["chk"] );
            $businessType = BusinessTypeTable::getInstance ();
            $res = $businessType->deleteTypes ( $ids );
        }
        if ($res == 'Success') {
            return redirect ()->route ( 'index' );
        } else {
            return view ( 'System.BusinessType.index' )->with ( 'error', $res );
        }
    }
}
