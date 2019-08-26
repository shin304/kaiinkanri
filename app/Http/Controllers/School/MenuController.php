<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PschoolTable;
use App\Model\SchoolMenuTable;
use App\Model\MasterMenuTable;
use App\Model\SchoolDefaultMenuTable;
use App\ConstantsModel;
use App\Lang;
use DB;

class MenuController extends _BaseSchoolController
{
    protected static $LANG_URL = 'menu_manage';
	private $lan = array(); //multi language message content

	public function __construct()
    {
    	parent::__construct();

        // 多国語対応
    	$message_content = parent::getMessageLocale();
        $this->lan = new Lang($message_content);
    }

    public function index() {
    	return view('School.Menu.index');
    }

    public function input() {
    	$default_menu_select = array();

    	//TODO get parent menu list
        //$default_menu_select: INSERT:template menu; EDIT:existed menu
    	$menu_list = SchoolMenuTable::getInstance()->getActiveMenuListNew(session('school.login.id'));
        // handle sub menu
        $sub_menu_list = array();
        foreach ($menu_list as $key => $value) {
            $menu_path = explode("/", $value['menu_path']);
            array_pop($menu_path);
            if (count($menu_path) > 1) {
                
                $sub_menu_list[$menu_path[0]][$key] = $value;
                unset($menu_list[$key]);
            }
            
        }

        if (isset($_REQUEST['pschool_id']) && isset($_REQUEST['edit'])) {
            // $menu_select : school menu list
            $menu_select = SchoolMenuTable::getInstance()->getActiveMenuListNew ($_REQUEST['pschool_id']);
            $default_menu_select = array_intersect_key($menu_select, $menu_list);
            
        } else if (isset($_REQUEST['pschool_id'])) {
    		
    		$default_menu_list = SchoolDefaultMenuTable::getInstance()->getListDefaultMenu($_REQUEST['pschool_id']);
    		// TODO validate with parent menu
    		if(!empty($default_menu_list)) {
    			$default_menu_select = array_intersect_key($default_menu_list, $menu_list);
    		}
    	}
    	return view('School.Menu.input')->with('lan', $this->lan)->with('parentMenuList' , $menu_list)
    									->with('defaultMenuList',$default_menu_select)->with('pschool_id', $_REQUEST['pschool_id'])
                                        ->with('parentSubMenuList', $sub_menu_list);
    }

    public function doInput() {
    	$school_menu_table = SchoolMenuTable::getInstance();
    	//TODO add group menu
    	if (isset($_REQUEST['pschool_id']) && isset($_REQUEST['menu_list'])) {
    		// get list mapping master menu
    		// $master_list = MasterMenuTable::getInstance()->getListMenu($_REQUEST['menu_list']);
    		
    		try {
    			// $school_menu_table->beginTransaction ();
                // $menu_select: ['1'=>[...], '2'=>[...],...] #key: master_menu_id
                $menu_select = $school_menu_table->getActiveMenuListNew($_REQUEST['pschool_id']);
	    		$index = 1;
	    		foreach ($_REQUEST['menu_list'] as $key => $value) {
	    			$menu = array(
	    				"pschool_id" 		=> $_REQUEST['pschool_id'],
	    				"master_menu_id" 	=> $value,
	    				"viewable" 			=> isset($_REQUEST['viewable_list'][$key])? 1: 0,
	    				"editable" 			=> isset($_REQUEST['editable_list'][$key])? 1: 0,
	    				"seq_no"			=> $index,
	    				// "icon_url"			=> ""
                        "active_flag"       => 1,
	    			);
                    // case update 
                    if (isset($menu_select[$value])) {
                        $menu['id'] = $menu_select[$value]['id'];
                    }
	    			$index++;
	    			$school_menu_table->save($menu);
	    		} 
	    		// $school_menu_table->commit ();
    		} catch (Exception $e) {
    			// $school_menu_table->rollBack ();
    			var_dump($e);
    		}
    		
    	}
    	
    	return redirect()->to('/school/menu');
    }
}
