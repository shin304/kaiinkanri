<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Traits\Translatable;

class MPlan extends Model
{
    use Translatable;
    protected $translatable = [
            'name'
    ];
    protected $table = 'm_plan';
    protected $fillable = [
            'slug',
            'name'
    ];

    public function numberRegisterId(){
        return $this->belongsTo(MPlanCategory::class);
    }

    public function numberRegisterIdList(){
//        return MPlanCategory::select('id',DB::raw('concat_ws(" : ",category_name,category_value) as category_value' ))->where('category_type', 1)->whereNull('delete_date')->get();
        return MPlanCategory::where('category_type', 1)->whereNull('delete_date')->get();
    }

    public function numberActiveId(){
        return $this->belongsTo(MPlanCategory::class);
    }

    public function numberActiveIdList(){
        return MPlanCategory::where('category_type', 2)->whereNull('delete_date')->get();
    }

    public function numberInstitutionId(){
        return $this->belongsTo(MPlanCategory::class);
    }

    public function numberInstitutionIdList(){
        return MPlanCategory::where('category_type', 3)->whereNull('delete_date')->get();
    }

    public static function getPlanDetail($planId){

        $planDetail = DB::table('m_plan')->select('m_plan.id','plan_name','number_register.category_value as number_register',
                'number_active.category_value as number_active', 'number_institution.category_value as number_institution',
                'm_plan.plan_amount', 'm_plan.validation_date')
                ->leftJoin('m_plan_category as number_register','number_register.id','=','m_plan.number_register_id')
                ->leftJoin('m_plan_category as number_active','number_active.id','=','m_plan.number_active_id')
                ->leftJoin('m_plan_category as number_institution','number_institution.id','=','m_plan.number_institution_id')
                ->whereNull('m_plan.delete_date')
                ->where('m_plan.id','=', $planId)
                ->get()->toArray();

        if(!empty($planDetail)){

            return $planDetail[0];

        }

        return array();
    }

    public static function canUpdatePlan($pschoolId, $planId){

        $currentPlanId = DB::table('pschool')->select('m_plan_id')->where('id','=',$pschoolId)->get() ;

        $currentPlanId = $currentPlanId->get(0)->m_plan_id;

        $currentPlan = MPlan::getPlanDetail($currentPlanId);

        $newPlan = MPlan::getPlanDetail($planId);

        if(!empty($newPlan) && !empty($currentPlan)){

            //compare new plan and old plan
            if(($newPlan->number_register >= $currentPlan->number_register || $newPlan->number_register == null ) &&
                ($newPlan->number_active >= $currentPlan->number_active || $newPlan->number_active == null) &&
                ($newPlan->number_institution >= $currentPlan->number_institution || $newPlan->number_institution == null)
                ){

                // all of new plan is bigger -> can update
                return true;

            }else{

                return false;
            }


        }elseif (!empty($newPlan)){

            // error but accept new plan
            return true;
        }

        return false;
    }
}
