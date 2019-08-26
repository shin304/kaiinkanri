<?php

namespace App\Model;

use App\Common\Constants;
use App\ConstantsModel;
use DaveJamesMiller\Breadcrumbs\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SchoolMenuTable extends DbModel {
    /**
     *
     * @var SchoolMenuTable
     */
    private static $_instance = null;
    protected $table = 'school_menu';
    /**
     *
     * @return SchoolMenuTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new SchoolMenuTable ();
        }
        return self::$_instance;
    }
    public function getActiveMenuList($arryQuery = null) {
        $bind = array ();
        
        $sql = " SELECT a.*, b.id as default_id, b.action_url, b.menu_name as default_menu_name";
        $sql .= " , b.student_detail_menu_id as default_student_detail_menu_id";
        $sql .= " , b.student_detail_menu_name as default_student_detail_menu_name ";
        $sql .= " FROM school_menu a";
        $sql .= " LEFT JOIN default_menu b ON a.default_menu_id = b.id";
        $sql .= " WHERE a.pschool_id = ? AND a.active_flag=1 AND a.delete_date is null";
        $sql .= " ORDER BY a.seq_no ASC";
        
        $bind [] = $arryQuery ['pschool_id'];
        
        $res = array ();
        $res = $this->fetchAll ( $sql, $bind );
        
        return $res;
    }
    public function getActiveMenuListNew($id, $member_type = null, $m_student_type_id = null) { // Default member_type=1: DANTAI
        $bind = array ();
        
        $sql = " SELECT a.*, b.action_url, b.menu_name_key, b.editable as master_editable, b.menu_path, b.default_flag ";
        $sql .= " FROM school_menu a";
        $sql .= " LEFT JOIN master_menu b ON a.master_menu_id = b.id";
        $sql .= " WHERE a.pschool_id = ? AND a.active_flag=1 AND a.delete_date is null ";
        $bind [] = $id;

        if(!empty($member_type)){
            $sql .= "AND a.member_type = ?";
            $bind [] = $member_type;
        }

        if(!empty($m_student_type_id)){
            $sql .= " AND a.m_student_type_id = ? ";
            $bind [] = $m_student_type_id;
        }

        $sql .= " ORDER BY a.seq_no ASC";

        $res = $this->fetchAll ( $sql, $bind );
        $lst = array ();
        foreach ( $res as $key => $value ) {
            $lst [$value ['master_menu_id']] = $value;
        }
        
        return $lst;
    }
    
    public function getActiveMenuListNew2($id, $member_type = null ,$m_student_type_id = null) { // Default member_type=1: DANTAI
        $bind = array ();
        
        $sql = " SELECT a.*, b.action_url, b.menu_name_key, b.editable as master_editable, b.menu_path, b.default_flag, b.icon_url as master_icon ";
        $sql .= " FROM school_menu a";
        $sql .= " LEFT JOIN master_menu b ON a.master_menu_id = b.id";
        $sql .= " WHERE a.pschool_id = ? AND a.active_flag=1 AND a.delete_date is null ";
        $bind [] = $id;
        if(!empty($member_type)){
            $sql .= " AND a.member_type = ?";
            $bind [] = $member_type;
        }
        if(!empty($m_student_type_id)){
            $sql .= " AND a.m_student_type_id = ?";
            $bind [] = $m_student_type_id;
        }
        $sql .= " ORDER BY a.seq_no ASC";

        $res = $this->fetchAll ( $sql, $bind );
        return $res;
    }

    /*
     * list menu from request
     * $request must be instance of Illuminate\Http\Request
     * $target = array(
     *      target_id,
     *      member_type ("DANTAI","STAFF","PLAN",...)
     * )
     */
    public function saveMenuSelection(Request $request, $target){

        $menu = array();
        $member_type = array_flip(ConstantsModel::$member_type);

        try{

            $this->deleteRow(array(
                "pschool_id"        => $target['target_id'],
                "member_type"       => $member_type[$target['member_type']]
            ));

            $menu_list      = $request->request->get('menu_list', array());
            $viewable_list  = $request->request->get('viewable_list', array());
            $editable_list  = $request->request->get('editable_list', array());
            $menu_ids       = array(); // Use for add message on screen
            $index = 1;

            foreach ($menu_list as $key => $value) {
                $menu = array(
                    "pschool_id"        => $target['target_id'],
                    "master_menu_id"    => $value,
                    "viewable"          => isset($viewable_list[$key])? 1: 0,
                    "editable"          => isset($editable_list[$key])? 1: 0,
                    "seq_no"            => $index,
                    "active_flag"       => 1,
                    "member_type"       => $member_type[$target['member_type']],
                );

                $index++;

                $this->save($menu);

                $menu_ids[] = $value;
            }

        }catch(Exception $e){

            return false;

        }

        return $menu_ids;
    }
}
