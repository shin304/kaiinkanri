<?php

namespace App\Model;

use App\ConstantsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdditionalCategoryTable extends DbModel {
    /**
     *
     * @var AdditionalCategoryTable
     */
    private static $_instance = null;
    protected $table = 'additional_category';

    /**
     *
     * @return AdditionalCategoryTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new AdditionalCategoryTable();
        }
        return self::$_instance;
    }

    public function getListCateOfPschool($pschool_id){
        $bind = array();
        $sql = "SELECT * FROM {$this->getTableName(true)} WHERE pschool_id = ? ";
        $sql.= " AND delete_date is null ";
        $sql.= " ORDER BY sort_no ";
        $bind[]= $pschool_id;
        return $this->fetchAll($sql,$bind);
    }
    public function getMaxSortNo($pschool_id){
        $bind = array();
        $sql = "SELECT MAX(sort_no) as max FROM {$this->getTableName(true)} WHERE pschool_id = ? ";
        $bind[]= $pschool_id;
        $res = $this->fetch($sql,$bind);
        return $res['max'];
    }

    public function getListData($filters, $returnList = false) {
        $columns = [
            'ac.*',
            'acr.id AS additional_category_rel_id',
            'acr.value',
        ];
        $query = DB::table('additional_category AS ac')
            ->select(DB::raw(implode(',', $columns)))
            ->leftJoin('additional_category_rel AS acr', function($join) use($filters) {
                $join->on('acr.additional_category_id', '=', 'ac.id')
                    ->where('acr.related_id', $filters['related_id']);
            });

        if (isset($filters['type']) && $filters['type'] == ConstantsModel::$ADDITIONAL_CATEGORY_STUDENT) {
            $query->leftJoin('student AS s', 's.id', '=', 'acr.related_id');
        }
        if (isset($filters['type']) && $filters['type'] == ConstantsModel::$ADDITIONAL_CATEGORY_CLASS) {
            $query->leftJoin('class AS c', 'c.id', '=', 'acr.related_id');
        }
        if (isset($filters['type']) && $filters['type'] == ConstantsModel::$ADDITIONAL_CATEGORY_COURSE) {
            $query->leftJoin('course AS co', 'co.id', '=', 'acr.related_id');
        }
        if (isset($filters['type']) && $filters['type'] == ConstantsModel::$ADDITIONAL_CATEGORY_PROGRAM) {
            $query->leftJoin('program AS p', 'p.id', '=', 'acr.related_id');
        }
        if (isset($filters['type']) && $filters['type'] == ConstantsModel::$ADDITIONAL_CATEGORY_PARENT) {
            $query->leftJoin('parent AS pr', 'pr.id', '=', 'acr.related_id');
        }
        if (isset($filters['type']) && $filters['type'] == ConstantsModel::$ADDITIONAL_CATEGORY_TEACHER) {
            $query->leftJoin('teacher AS t', 't.id', '=', 'acr.related_id');
        }
        if(isset($filters['type'])){
            $query->where('ac.type',$filters['type']);
        }

        if (isset($filters['active_flag'])) {
            $query->where('ac.active_flag', $filters['active_flag']);
        } else {
            $query->where('ac.active_flag', 1);
        }

        $query->where('ac.pschool_id', session()->get('school.login.id'))
            ->whereNull('ac.delete_date')
            ->whereNull('acr.delete_date')
            ->groupBy('ac.id')
            ->orderBy('ac.sort_no', 'ASC');

        if ($returnList) {
            return $this->convertToArray($query->get());
        }
        return $this->convertToArray($query->first());
    }
}
