<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BulletinBoardTable extends DbModel
{
    /**
     *
     * @var BulletinBoardTable
     */
    private static $_instance = null;
    protected $table = 'bulletin_board';
    
    /**
     *
     * @return BulletinBoardTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new BulletinBoardTable ();
        }
        return self::$_instance;
    }

    public function getBulletinList($cond)
    {
        $bind = [];
        $sql = " SELECT *,
                 SUBSTRING_INDEX(SUBSTRING_INDEX(target, ',', 1), ',', -1) as staff,
                 SUBSTRING_INDEX(SUBSTRING_INDEX(target, ',', 2), ',', -1) as teacher,
                 SUBSTRING_INDEX(SUBSTRING_INDEX(target, ',', 3), ',', -1) as other
                 FROM bulletin_board  WHERE delete_date IS NULL ";
         if ( isset( $cond['pschool_id'] ) ) {
            $sql .= " AND pschool_id = ? ";
            $bind [] = $cond['pschool_id'];
        }
        // ログインアカウント種別　1=システム2=塾 3=スタッフ 5=講師 10=保護者
        if ( $cond['account_type'] != 2 && !empty($cond['account_type']) ) {
            if ( $cond['account_type'] == 3 ) {
                $sql .= " AND SUBSTRING_INDEX(SUBSTRING_INDEX(target, ',', 1), ',', -1) = 1 ";
            } elseif ( $cond['account_type'] == 5 ) {
                $sql .= " AND SUBSTRING_INDEX(SUBSTRING_INDEX(target, ',', 2), ',', -1) = 1 ";
            } elseif ( $cond['account_type'] != 3 && $cond['account_type'] != 5 ) {
                $sql .= " AND SUBSTRING_INDEX(SUBSTRING_INDEX(target, ',', 3), ',', -1) = 1 ";
            }
        }
        // search condition in bulletin list page
        if ( isset($cond['search_title']) ) {
            $sql .= " AND title LIKE ? ";
            $bind [] = "%" . $cond['search_title'] . "%";
        }

        if (!empty ($cond['search_start']) && !empty ($cond['search_finish'])) {
            $sql .= " AND   IF(finish_date IS NULL, 
                            !(DATE_FORMAT(start_date,'%Y-%m-%d') > ?),
                            !(DATE_FORMAT(start_date,'%Y-%m-%d') > ? OR DATE_FORMAT(finish_date,'%Y-%m-%d') < ?) ) ";
            $bind [] = $cond['search_finish'];
            $bind [] = $cond['search_finish'];
            $bind [] = $cond['search_start'];
        } else if (!empty ($cond['search_start'])) {
            $sql .= " AND   IF(finish_date IS NULL,
                            1,
                            !(DATE_FORMAT(finish_date,'%Y-%m-%d') < ?) ) ";
            $bind [] = $cond['search_start'];
        } else if (!empty ($cond['search_finish'])) {
            $sql .= " AND !(DATE_FORMAT(start_date,'%Y-%m-%d') > ?) ";
            $bind [] = $cond['search_finish'];
        }

        // list bulletin in HOME
        if ( isset( $cond['is_home'] ) ) {
            // $sql .= " AND calendar_flag　= 1 ";
            $sql .= " AND start_date <= DATE( NOW() ) ";
            $sql .= " AND ( finish_date IS NULL OR finish_date >= DATE( NOW()) ) ";
            $sql .= " ORDER BY start_date DESC ";
        } else {
            $sql .= " ORDER BY IFNULL(update_date, register_date) DESC ";
        }
        return $this->fetchAll($sql, $bind);
    }
}
