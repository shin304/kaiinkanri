<?PHP

namespace App\Model;

use App\Common\Constants;
use Illuminate\Support\Facades\Log;
use DB;

class SystemLogTable extends DbModel {
    
    /**
     *
     * @var SystemLogTable
     */
    private static $_instance = null;
    protected $table = 'system_log';
    public $timestamps = false;
    /**
     *
     * @return SystemLogTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new SystemLogTable ();
        }
        return self::$_instance;
    }

    public function getListSystemLog($pschool_id, $period = null) {
        $bind = array();
        $sql = "SELECT A.*, (CASE WHEN A.update_date is NULL THEN A.register_date ELSE A.update_date END) as date 
                FROM system_log A join  system_log_pschool B on A.id=B.notify_id
                WHERE B.pschool_id = {$pschool_id} 
                AND A.delete_date IS NULL 
                AND B.active_flag IS NOT NULL";
        if (!is_null($period)) {
            $sql .= "AND If (A.update_date is NULL, A.register_date, A.update_date) >= DATE_ADD(NOW(),INTERVAL -? DAY) ";
            $bind [] = $period;
        }
        $sql .= " ORDER BY date DESC";
        $res = $this->fetchAll ( $sql, $bind );
        return $res;
    }

    /**
     * @param $pschool_id
     * @param null $status: is type of log. 1:通知 2:エラー
     * @param null $process: is name of process. ex: 請求処理
     * @param null $message: content of log
     * @return
     */
    public function writeSystemLog($pschool_id, $status = 1, $process = NULL, $message = NULL, $is_batch = false){
        try {
            $log = array(
                'pschool_id'    => $pschool_id,
                'status'        => $status,
            );
            if (!is_null($process)) {
                $log['process'] = $process;
            }
            if (!is_null($message)) {
                $log['message'] = $message;
            }
            $log_id = $this->save($log, $is_batch);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
        return $log_id;
    }
    public  function FilterSystemLog ($systemlogs) {
        $systemlog_display=array();
        foreach ($systemlogs as $systemlog) {
            if(!empty($systemlog['start_date']&&!empty($systemlog['end_date']))) {
                if($systemlog['start_date']<=date('Y-m-d')&&$systemlog['end_date']>=date('Y-m-d'))
                    $systemlog_display[]=$systemlog;
            }
            elseif (!empty($systemlog['start_date'])) {
                if($systemlog['start_date']<=date('Y-m-d')) {
                    $systemlog_display[]=$systemlog;
                }
            }
            else {
                if($systemlog['end_date']>=date('Y-m-d')) {
                    $systemlog_display[]=$systemlog;
                }
            }
        }
        return $systemlog_display;
    }
    public  function updateSystemLogViewdate ($id) {
            $data=['view_date'=> date('Y-m-d H:i:s')];
            DB::table($this->table)->where('id', $id)->update($data);
    }
}