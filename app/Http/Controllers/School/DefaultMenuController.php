<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\SchoolDefaultMenuTable;
use App\Model\SchoolMenuTable;
use App\ConstantsModel;

class DefaultMenuController extends _BaseSchoolController
{
	protected static $LANG_URL = 'default_menu';
	private $lan = array(); //multi language message content
    
    public function __construct()
    {
    	parent::__construct();

    	$this->lan = parent::getMessageLocale();
    }

    public function index() {
    	return view('School.DefaultMenu.index');
    }

    public function input() {
    	
    	// TODO get roles data for selectbox
    	#Compare constants role list With role in default menu table => Role not set default yet
    	// $school_role : ['1' => 'parent', '2' => 'teacher',...]
    	// $roles : [0 => '1', 1=>'2',...]
    	$school_role = ConstantsModel::$school_role;
    	$school_role_keys = array_keys($school_role);
    	
    	$roles = SchoolDefaultMenuTable::getInstance()->getRoles(session('school.login')['id']);
    	// $abc = array_diff($roles, $school_role_keys);
    	

    	//TODO get school menu list
    	$menu_list = SchoolMenuTable::getInstance ()->getActiveMenuList (array('pschool_id' => session('school.login')['id']));
    	// echo "<pre>";
    	// var_dump($this->lan);
    	return view('School.DefaultMenu.input')->with('lan', $this->lan)->with('menuList' , $menu_list)->with('roleList',$school_role);
    }

    public function doInput() {
    	//TODO add group default menu

    	return redirect()->to('/school/menu');
    }
}
