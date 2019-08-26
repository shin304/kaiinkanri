<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Translatable;
use DB;
class Pschool extends Model
{
    use Translatable;

    protected $translatable = ['name'];

    protected $table = 'pschool';

    protected $fillable = ['slug', 'name'];

    public static function getPschoolList() {

    	// $res = $this->whereNull('delete_date');
    	// return DB::table('pschool')->whereNull('delete_date')->where('C.layer', 2)
     //             ->select('pschool.*', 'B.login_id', 'B.active_flag AS login_active_flag', 'C.layer')
     //             ->leftJoin('login_account as B', 'pschool.login_account_id', '=', 'B.id')
     //             ->leftJoin('hierarchy as C', 'pschool.id', '=', 'C.pschool_id')
     //             ;
    			// ->select('pschool.*', 'B.login_id', 'B.active_flag AS login_active_flag', 'C.layer')
       //          ->leftJoin('login_account as B', 'pschool.login_account_id', '=', 'B.id')
       //          ->leftJoin('hierarchy as C', 'pschool.id', '=', 'C.pschool_id')
                
    	return DB::select('SELECT A.*, B.login_id, B.active_flag AS login_active_flag, C.layer FROM pschool A LEFT JOIN login_account B ON (B.id = A.login_account_id)
			LEFT JOIN hierarchy C ON (A.id = C.pschool_id) WHERE C.layer = 2 ');
    }

    // public function pref_id($value='')
    // {
    //     return $this->belongsTo(m_pref::class);
    // }
}
