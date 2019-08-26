<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Model\ParentTable;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParentTableTest extends TestCase
{
    private $admin;

    public function setUp()
    {
        parent::setUp();
        $loginid = 'test@asto-system.com';
        $password = '12345678';
        $this->admin = ParentTable::getInstance ()->getLoginInfo ( $loginid, $password );
    }
    public function testGetParentList2(){
        $search_condition =array();
        //test get all
        $parentTable = ParentTable::getInstance();
        $list_parent = $parentTable->getParentList2($search_condition);
        $this->assertEquals("21",count($list_parent),"Does not have exact 21 records");

        //test get name like t
        $search_condition['search_name'] = 't';
        $list_parent = $parentTable->getParentList2($search_condition);
        $this->assertEquals("7",count($list_parent),"Does not have exact 7 records");

        //test get sutdent code like 0046
        $search_condition['search_code'] = '0046';
        $list_parent = $parentTable->getParentList2($search_condition);
        $this->assertEquals("2",count($list_parent),"Does not have exact 2 records");

        //test get invoice type = 0
        $search_condition= array();
        $search_condition['search_payment_method'] = 0;
        $list_parent = $parentTable->getParentList2($search_condition);
        $this->assertEquals("18",count($list_parent),"Does not have exact 2 records");

        //test get invoice type = 1
        $search_condition= array();
        $search_condition['search_payment_method'] = 1;
        $list_parent = $parentTable->getParentList2($search_condition);
        $this->assertEquals("0",count($list_parent),"Does not have exact 2 records");

        //test get invoice type = 2
        $search_condition= array();
        $search_condition['search_payment_method'] = 2;
        $list_parent = $parentTable->getParentList2($search_condition);
        $this->assertEquals("3",count($list_parent),"Does not have exact 2 records");
    }
}
