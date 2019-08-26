<?php

namespace App\Model;

class AxisLogAdjustTable extends DbModel {
    
    /**
     *
     * @var AxisLogAdjustTable
     */
    private static $_instance = null;
    protected $table = 'axis_log_adjust';
    public $timestamps = false;
    /**
     *
     * @return AxisLogAdjustTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new AxisLogAdjustTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getAxisInvoiceList($school, $str_date, $student_id, $old_date = null) {
        $students ['student_invoice_amount'] = 0;
        $students ['student_surcharge_amount'] = 0;
        $students ['student_discharge_amount'] = 0;
        $students ['invoice_list'] = array ();
        $students ['adjust_list'] = array ();
        
        // 後払いはデータなし
        if ($school ['payment_style'] == 2 && empty ( $old_date )) {
            return $students;
        }
        
        if (empty ( $str_date ) && ! empty ( $old_date )) {
            $str_date = $old_date;
        }
        
        // invoice_list
        $sql = ' SELECT A.student_id, A.unit_price as adjust_fee, A.class_id, A.course_id ';
        $sql .= ' FROM invoice_item as A ';
        $sql .= ' INNER JOIN invoice_header as C ON (A.invoice_id=C.id) ';
        $sql .= ' WHERE A.pschool_id = ? ';
        $sql .= ' and A.student_id = ? ';
        $sql .= ' and A.active_flag = 1 ';
        $sql .= ' and A.delete_date is NULL ';
        $sql .= ' and A.invoice_adjust_name_id is NULL ';
        $sql .= ' and A.active_flag = 1 ';
        $sql .= ' and C.active_flag = 1 ';
        $sql .= ' and C.delete_date is NULL ';
        $sql .= ' and C.invoice_year_month = ? ';
        $bind = array (
                $school ['pschool_id'],
                $student_id,
                date ( 'Y-m', $str_date ) 
        );
        $invoices = $this->fetchAll ( $sql, $bind );
        if (! empty ( $invoices )) {
            foreach ( $invoices as $key => $invoice ) {
                // 請求
                $students ['student_invoice_amount'] += $invoice ['adjust_fee'];
            }
            $students ['invoice_list'] = $invoices;
        }
        
        // adjust_list
        $sql = ' SELECT A.unit_price as adjust_fee, A.student_id as log_student_id ';
        $sql .= ' , B.id as adjust_id, B.name as adjust_name, B.type ';
        $sql .= ' FROM invoice_item as A ';
        $sql .= ' LEFT JOIN invoice_adjust_name as B ON (A.invoice_adjust_name_id=B.id) ';
        $sql .= ' INNER JOIN invoice_header as C ON (A.invoice_id=C.id) ';
        $sql .= ' WHERE A.pschool_id = ? ';
        $sql .= ' and A.student_id = ? ';
        $sql .= ' and A.active_flag = 1 ';
        $sql .= ' and A.delete_date is NULL ';
        $sql .= ' and A.invoice_adjust_name_id is not NULL ';
        $sql .= ' and C.active_flag = 1 ';
        $sql .= ' and C.delete_date is NULL ';
        $sql .= ' and C.invoice_year_month = ? ';
        $bind = array (
                $school ['pschool_id'],
                $student_id,
                date ( 'Y-m', $str_date ) 
        );
        $adjusts = $this->fetchAll ( $sql, $bind );
        if (! empty ( $adjusts )) {
            foreach ( $adjusts as $key => $adjust ) {
                // 請求
                $students ['student_invoice_amount'] += $adjust ['adjust_fee'];
                
                if ($adjust ['type'] == 0) {
                    // 割増
                    $students ['student_surcharge_amount'] += $adjust ['adjust_fee'];
                } else {
                    // 割引
                    $students ['student_discharge_amount'] += $adjust ['adjust_fee'];
                }
                
                $adjusts [$key] ['id'] = null;
                $adjusts [$key] ['group_id'] = $school ['group_id'];
                $adjusts [$key] ['group_name'] = null; // TODO
                $adjusts [$key] ['layer'] = $school ['layer'];
                $adjusts [$key] ['pschool_id'] = $school ['pschool_id'];
                $adjusts [$key] ['pschool_name'] = $school ['pschool_name'];
                $adjusts [$key] ['log_year'] = date ( 'Y', $str_date );
                $adjusts [$key] ['log_month'] = date ( 'n', $str_date );
                $adjusts [$key] ['register_date'] = date ( 'Y-m-d H:i:s' );
                
                unset ( $adjusts [$key] ['type'] );
            }
            $students ['adjust_list'] = $adjusts;
        }
        
        return $students;
    }
}