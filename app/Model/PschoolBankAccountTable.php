<?PHP

namespace App\Model;

use Illuminate\Support\Facades\DB;

class PschoolBankAccountTable extends DbModel {
    
    /**
     *
     * @var PschoolBankAccountTable
     */
    private static $_instance = null;
    protected $table = 'pschool_bank_account';
    public $timestamps = false;
    /**
     *
     * @return PschoolBankAccountTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new PschoolBankAccountTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    
    // public function getActiveListPsBank( $where=array(), $order=null, $limit=null ){
    // $where[] = $this->getActiveWhere();
    // return $this->getListPsBank( $where, $order, $limit );
    // }
    final public function getListPsBank($pschool_id) {
        $sql = " SELECT * FROM pschool_bank_account WHERE pschool_id = ? AND delete_date IS NULL";
        $arr = $this->fetchAll ( $sql, array (
                $pschool_id 
        ) );
        foreach ($arr as $k => $v){
            if(isset($v['post_account_number'])){
                $arr[$k]['post_account_number_1'] = substr($v['post_account_number'],0,1);
                $arr[$k]['post_account_number_2'] = substr($v['post_account_number'],1,strlen($v['post_account_number']));
            }
        }
        return json_decode ( json_encode ( $arr ), true );
    }
    public function setDefaultBankAccount($bank_id,$pschool_id){
        DB::table('pschool_bank_account')->where('pschool_id',$pschool_id)->where('is_default_account',1)
                ->update(['is_default_account' =>0]);
        $rs = DB::table('pschool_bank_account')->where('id',$bank_id)
                ->update(['is_default_account' =>1]);
        return $rs;
    }

    public function getListPSchoolBank($pschool_id, $payment_method_ids = array(), $single_result = false) {
        $sql = "SELECT pba.*, pmbr.payment_method_id
                FROM pschool_bank_account pba
                INNER JOIN payment_method_bank_rel pmbr ON pmbr.bank_account_id = pba.id
                WHERE pmbr.delete_date IS NULL AND pba.delete_date IS NULL
                AND pba.pschool_id = ?";
        if ($payment_method_ids) {
            $sql .= " AND pmbr.payment_method_id IN (". implode(", ", $payment_method_ids) .")";
        }
        if ($single_result) {
            return $this->fetch($sql, [$pschool_id]);
        }
        return $this->fetchAll($sql, [$pschool_id]);
    }
}