<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ConsultationTable extends DbModel
{
    /**
     * @var ConsultationTable
     */
    private static $_instance = null;
    protected $table = 'consultation';

    /**
     * @return ConsultationTable
     */
    public static function getInstance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new ConsultationTable();
        }
        return self::$_instance;
    }

    // ここに実装して下さい

    public function getListOfEvents($pschool_id, $search = "", $detail = "")
    {
        $bind = array();
        $sql = "SELECT res.id, res.title, res.contents, res.consultation_unit_time, res.type, type_id, type2_id, res.reg, res.upd, res.con_info, cs.proc_date, cs.start_time, cs.end_time, CASE WHEN m1.mail1 is NULL THEN '未' ELSE '済' END AS mail0, CASE WHEN m1.mail1 is NULL THEN 0 ELSE m1.mail1 END AS mail1, CASE WHEN m2.mail2 is NULL THEN 0 ELSE m2.mail2 END AS mail2,  CASE WHEN e.mail3 is NULL THEN 0 ELSE e.mail3 END AS mail3 FROM ";
        $sql .= "(SELECT id, consultation_info as con_info, consultation_title as title, contents, consultation_unit_time, '面談' as type, 2 as type_id, 1 as type2_id, `register_date` as reg, `update_date` as upd, `delete_date` as del ";
        $sql .= "FROM consultation AS a WHERE a.pschool_id = ? ";
        $bind[] = $pschool_id;
        $sql .= "AND a.delete_date is null) res ";
        $sql .= "LEFT JOIN (select id,consultation_id,proc_date,start_time,end_time from consultation_schedule where delete_date is null) cs on (res.id=cs.consultation_id) ";
        $sql .= "LEFT JOIN (select count(*) as mail1 ,type, relative_ID from mail_message msg WHERE delete_date is null Group By type, relative_ID) m1 on (m1.type = res.type_id AND m1.relative_ID = res.id) ";
        $sql .= "LEFT JOIN (select count(*) as mail2 ,type, relative_ID, last_refer_date from mail_message WHERE delete_date is null and last_refer_date is NOT NULL Group by type, relative_ID) m2 on (m2.type = res.type_id AND m2.relative_ID = res.id AND last_refer_date is NOT NULL) ";
        $sql .= "LEFT JOIN (select count(*) as mail3, entry_type, relative_id, enter from entry WHERE delete_date is null group by entry_type, relative_id, enter) e on (e.entry_type = res.type2_id AND e.relative_id = res.id AND enter = 1) ";
        $sql .= " WHERE res.del is null";
//      if ($search != "")
        if (!empty($search['input_search']))
        {
            $sql .= " and res.title LIKE ? ";
            $bind[] =  "%".$search['input_search']."%" ;
        }
        if (!empty($detail)){
            $sql .= " and res.id = ? ";
            $bind[] =  $detail;
        }
        $sql .= " ORDER BY cs.proc_date DESC , IF (res.upd is null, res.reg, res.upd) DESC";
        $res = $this->fetchAll($sql, $bind);
        return $res;
    }

    public function getListOfFutureEvents($pschool_id, $search = "")
    {
        $bind = array();
        $sql = "SELECT res.id, res.title, res.type, type_id, type2_id, res.reg, res.upd, res.con_info, cs.proc_date, cs.start_time, cs.end_time, CASE WHEN m1.mail1 is NULL THEN '未' ELSE '済' END AS mail0, CASE WHEN m1.mail1 is NULL THEN 0 ELSE m1.mail1 END AS mail1, CASE WHEN m2.mail2 is NULL THEN 0 ELSE m2.mail2 END AS mail2,  CASE WHEN e.mail3 is NULL THEN 0 ELSE e.mail3 END AS mail3 FROM ";
        $sql .= "(SELECT id, consultation_info as con_info, consultation_title as title, '面談' as type, 2 as type_id, 1 as type2_id, `register_date` as reg, `update_date` as upd, `delete_date` as del ";
        $sql .= "FROM consultation AS a WHERE a.pschool_id = ? ";
        $bind[] = $pschool_id;
        $sql .= "AND a.delete_date is null) res ";
        $sql .= "LEFT JOIN (select id,consultation_id,proc_date,start_time,end_time from consultation_schedule where delete_date is null) cs on (res.id=cs.consultation_id) ";
        $sql .= "LEFT JOIN (select count(*) as mail1 ,type, relative_ID from mail_message msg WHERE delete_date is null Group By type, relative_ID) m1 on (m1.type = res.type_id AND m1.relative_ID = res.id) ";
        $sql .= "LEFT JOIN (select count(*) as mail2 ,type, relative_ID, last_refer_date from mail_message WHERE delete_date is null and last_refer_date is NOT NULL Group by type, relative_ID) m2 on (m2.type = res.type_id AND m2.relative_ID = res.id AND last_refer_date is NOT NULL) ";
        $sql .= "LEFT JOIN (select count(*) as mail3, entry_type, relative_id, enter from entry WHERE delete_date is null group by entry_type, relative_id, enter) e on (e.entry_type = res.type2_id AND e.relative_id = res.id AND enter = 1) ";
//      $sql .= " WHERE res.del is null and cs.proc_date > NOW() ";
        $sql .= " WHERE res.del is null and cs.proc_date >= CURDATE() ";
        if ($search != "")
        {
            $sql .= " and res.title LIKE ? ";
            $bind[] =  "%{$search}%" ;
        }
        $sql .= " ORDER BY cs.proc_date";
        $res = $this->fetchAll($sql, $bind);
        return $res;
    }
}
