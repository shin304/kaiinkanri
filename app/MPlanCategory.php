<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Traits\Translatable;

class MPlanCategory extends Model
{

    use Translatable;
    protected $translatable = [
            'name'
    ];
    protected $table = 'm_plan_category';
    protected $fillable = [
            'slug',
            'name'
    ];

    public static function canEditDeleteCategory($categoryId){

        $category = DB::table('m_plan_category')->where('id', '=', $categoryId)->get()->toArray();


        if(!empty($category)){

            $category = $category[0];

            switch ($category->category_type){
                case 1 :
                    $data = DB::table('m_plan')->where('number_register_id','=', $categoryId)->get()->toArray();

                    if(!empty($data)){

                        return false;

                    }

                    break;

                case 2 :
                    $data = DB::table('m_plan')->where('number_active_id','=', $categoryId)->get()->toArray();

                    if(!empty($data)){

                        return false;

                    }

                    break;

                case 3 :
                    $data = DB::table('m_plan')->where('number_institution_id','=', $categoryId)->get()->toArray();

                    if(!empty($data)){

                        return false;

                    }

                    break;

                default:


                    break;
            }

        }

        return true;

    }
}
