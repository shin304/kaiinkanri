<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MasterMenuTable extends DbModel
{
    /**
	 * @var MasterMenuTable
	 */
	private static $_instance = null;
    protected $table = 'master_menu';

	/**
	 * @return MasterMenuTable
	 */
	public static function getInstance(){
		if( is_null( self::$_instance ) ){
			self::$_instance = new MasterMenuTable();
		}
		return self::$_instance;
	}

	public function getListMenu($ids=null) {
		$sql = "SELECT * FROM `master_menu` WHERE delete_date IS NULL ";
		if (isset($ids)) {
			$sql .= " AND id IN(" . implode(',', $ids). ") ";
		}
		$sql .= "ORDER BY sort_no ASC ";
		return $this->fetchAll($sql);
	}

	/**
	* @return List Parent Menu, Ex: 1/, 2/,...
	**/
	public function getParentMenuList($exclude_id=null) {
		$sql = "SELECT * FROM `master_menu` WHERE menu_path REGEXP '^[0-9]+\/$' ";
		if (!is_null($exclude_id)) {
			$sql .= " AND id !=" .$exclude_id;
		}

		$sql .= " AND delete_date IS NULL";
		return $this->fetchAll($sql);
	}

	public function getChildList($id=null) {
		$bind = array();
		$sql = "SELECT * FROM `master_menu` WHERE menu_path";
		if (!is_null($id)) {
			$sql .= " LIKE ? ";
			$bind[] = $id.'/%';
		} else { // get all sub menu
			$sql .= " REGEXP '^([0-9]+\/){2}$'";
		}
		$sql .= " AND delete_date IS NULL";
		// $sql .=' ORDER BY id DESC';
		$res = $this->fetchAll($sql, $bind);
		
		$lst = array();
		if(!is_null($id)) {
			// $lst: ['1'=>[..], [2]=>[...],..] *key: sub_seq_no
			foreach ($res as $key => $value) {
            $seq_no = (int)$value['sub_seq_no'];

            //duplicate sequence number
            if (isset($lst[$seq_no])) {
            	//duplicate has number sequence
            	$this->assignChildListSequence($lst, $seq_no, $value);
            	
            } else {
            	$lst[$seq_no] = $value;
            }
            
			}
			return $lst;
		}

		foreach ($res as $key => $value) {
			// $lst: ['1'=>[['1'=>[]]], [2]=>[...],..] *key: parent_id
			$menu_path = explode("/", $value['menu_path']);
            array_pop($menu_path);

            $seq_no = (int)$value['sub_seq_no'];
            if (isset($lst[$menu_path[0]][$seq_no])) {
            	// $lst[$menu_path[0]][++$seq_no] = $value;
            	$this->assignChildListSequence($lst[$menu_path[0]], $seq_no, $value);
            } else {
            	$lst[$menu_path[0]][$seq_no] = $value;
            }
            
		}
		return $lst;
	}

	private function assignChildListSequence(&$lst, $seq_no, $value) {
		if ($seq_no!=0) {
            while ( isset($lst[$seq_no]) ) { 
            	$tmp = $lst[$seq_no];
            	$lst[$seq_no] = $value;
            	$value = $tmp;
            	++$seq_no;
	        }
	        $lst[$seq_no] = $value;
            //duplicate has null sequence
        } else {
            $last_key = key( array_slice( $lst, -1, 1, TRUE ) );
            while ( isset($lst[$last_key]) ) { 
            	$tmp = $lst[$last_key];
            	++$last_key;
	        }
	        $lst[$last_key--] = $value;
	        $lst[$last_key] = $tmp;
            // $lst[++$last_key] = $value;
        }
	}

	/**
	 * update sub_seq_no
	 */
	public function updateSubSequenceNo ($seq_lst) {
		$sql = " UPDATE master_menu SET  sub_seq_no = CASE ";
		foreach ($seq_lst as $idx=>$row) {
			$sql .= " WHEN id = " . $idx . " THEN " . $row;
		}
		
		$sql .= " END WHERE id IN (". implode(', ', array_keys($seq_lst)). ")";
		$row_count = $this->execute($sql);
		return $row_count;
	}


}
