<?PHP

namespace App\Model;

use GuzzleHttp\Psr7\Request;

class BroadcastMailTable extends DbModel {
    
    /**
     *
     * @var BroadcastMailTable
     */
    private static $_instance = null;
    protected $table = 'broadcast_mail';
    public $timestamps = false;
    private $target_type = array(
            'parent'=>1,
            'student'=>2,
            'teacher'=>3,
            'staff'=>4,
            );
    const BROADCAST_CATEGORY_CODE    = '2';
    /**
     *
     * @return BroadcastMailTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new BroadcastMailTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getQueryList($arryQuery = null, $request ) {
        $arraySearch = null;
        $arraySearch = $request->_c;
        $bind = array ();
        // dd ( $arraySearch );
        $sql = " (SELECT a.* FROM {$this->getTableName(true)} a";
        if (isset ( $arraySearch ['bc_option'] ) && $arraySearch ['bc_option'] != "") {
            //mail_message type = 6 => broadcast mail
            $bc_option = implode(",",$arraySearch ['bc_option']);
            $sql .= " INNER JOIN (SELECT relative_ID FROM mail_message WHERE type = 6 
                    AND pschool_id = ".$arryQuery ['pschool_id']." 
                    AND target in (".$bc_option.") GROUP BY relative_ID) B
                    ON a.id = B.relative_ID ";
        }
        $sql .= " WHERE a.pschool_id = ? AND a.delete_date is null ";
        $bind [] = $arryQuery ['pschool_id'];
        $res = array ();
        if (! empty ( $arraySearch ['input_search'] )) {
            $sql .= " AND (title like ?)";
            $bind [] = "%" . $arraySearch ['input_search'] . "%";
        }
        if (isset ( $arraySearch ['send_flag'])){
            $sql .= " AND a.send_flag = ? ";
            $bind [] = $arraySearch ['send_flag'];
        }

        $sql .= ' ORDER BY CASE WHEN a.update_date is null THEN a.register_date ELSE a.update_date END DESC )';
        if(isset($request['bc_option'])){
            $sql .= " UNION (SELECT a.* FROM {$this->getTableName(true)} a";

            $sql .= " WHERE a.pschool_id = ? AND a.delete_date is null ";
            $bind [] = $arryQuery ['pschool_id'];

            if (! empty ( $arraySearch ['input_search'] )) {
                $sql .= " AND (title like ?)";
                $bind [] = "%" . $arraySearch ['input_search'] . "%";
            }
            if (isset ( $arraySearch ['send_flag'])){
                $sql .= " AND a.send_flag = ? ";
                $bind [] = $arraySearch ['send_flag'];
            }
            $sql.= "AND a.bc_option = ?";
            $bind [] = $request['bc_option'];

            $sql .= ' ORDER BY CASE WHEN a.update_date is null THEN a.register_date ELSE a.update_date END DESC )';
        }

        $res = $this->fetchAll ( $sql, $bind );
        return $res;
    }
    private function isEmpty($param_value = null) {
        $return_cd = false; // 入力されている
        if (! strlen ( $param_value )) {
            // 空文字
            $return_cd = true; // 未入力
        }
        
        return $return_cd;
    }

    public function getBroadCastList($pschool_id, $period = null) {
        $bind = array ();
        $sql = " SELECT a.*, DATE(a.time_send) as date, 'broadcast' as mail_type
                 FROM {$this->getTableName(true)} a
                 WHERE a.delete_date is NULL AND a.send_flag = 1 AND a.pschool_id = ? ";
        $bind [] = $pschool_id;
        if (!is_null($period)) {
            $sql .= " AND If (a.update_date is NULL, a.register_date, a.update_date) >= DATE_ADD(NOW(),INTERVAL -? DAY) ";
            $bind [] = $period;
        }
        $sql .= ' ORDER BY CASE WHEN a.update_date is null THEN a.register_date ELSE a.update_date END DESC';
        $res = $this->fetchAll ( $sql, $bind );
        return $res;
    }
    public function getBroadCastInfo($id,$target,$target_id){
        $sql = "SELECT bm.send_flag, bm.id as broadcast_mail_id,bm.pschool_id,bm.title,bm.content,bm.footer,bm.memo, ps.mailaddress as school_mailaddress, A.* ";
        $sql.= " FROM broadcast_mail bm ";
        $sql.= " INNER JOIN pschool ps ON ps.id = bm.pschool_id , ";
        switch ($target ){
            case $this->target_type['parent']:
//                $sql.=" (SELECT la.login_id ,parent.parent_mailaddress1, parent.parent_name as name
                $sql.=" (SELECT parent.parent_mailaddress1 as login_id, parent.parent_name as name
                        FROM login_account la 
                        INNER JOIN parent ON parent.login_account_id = la.id 
                        WHERE parent.id =".$target_id." 
                        AND la.delete_date IS NULL AND parent.delete_date IS NULL";
                break;

            case $this->target_type['student']:
                $sql.=" (SELECT student.mailaddress as login_id ,student.student_name as name
                        FROM student 
                        WHERE student.id =".$target_id." 
                        AND student.delete_date IS NULL";
                break;

            case $this->target_type['teacher']:
                $sql.=" (SELECT la.login_id ,coach.coach_name as name
                        FROM login_account la 
                        INNER JOIN coach ON coach.login_account_id = la.id 
                        WHERE coach.id =".$target_id." 
                        AND la.delete_date IS NULL AND coach.delete_date IS NULL";
                break;

            case $this->target_type['staff']:
                $sql.=" (SELECT la.login_id ,staff.staff_name as name
                        FROM login_account la 
                        INNER JOIN staff ON staff.login_account_id = la.id 
                        WHERE staff.id =".$target_id." 
                        AND la.delete_date IS NULL AND staff.delete_date IS NULL";
                break;
            default: break;
        }
        $sql.=") A ";
        $sql.=" WHERE bm.id = ".$id." AND bm.delete_date IS NULL ";
        $res = $this->fetch($sql);
        $sql_upload = "SELECT  file_path, disp_file_name FROM upload_files WHERE target_id = ".$id." AND category_code = ".self::BROADCAST_CATEGORY_CODE." ";
        $files = $this->fetchAll($sql_upload);
        $res['files'] = $files;
        return $res;
    }
}