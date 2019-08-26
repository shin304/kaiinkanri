<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Model\HierarchyTable;

class HierarchyTableTest extends TestCase {
    /**
     * author Phuc Nguyen
     * Test get Hierarchy by pschool_id
     *
     * @var $pschool_id
     *
     */
    public function testGetHierarchy() {
        $pschool_id = "1";
        $Hierarchy = HierarchyTable::getInstance ();
        $result = $Hierarchy->getHierarchy ( $pschool_id );
        $this->assertContains ( "0", $result );
    }
}
