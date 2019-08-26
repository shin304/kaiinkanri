<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\BulletinBoardTable;
use App\Model\UploadFilesTable;
use App\Model\PschoolTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BulletinBoardTableTest extends TestCase
{
    private $admin;
    private static $is_home = 1;
    private static $saved_id = '';
    public function setUp()
    {
        parent::setUp();

        $loginid = 'test@asto-system.com';
        $password = '12345678';
        $this->admin = $school = PschoolTable::getInstance ()->getLoginInfo ( $loginid, $password );
        
    }

    public function testSaveBulletin()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: SAVE BULLETIN INFO (SCHOOL/BULLETIN BOARD)";
        echo "\n\t ====================";
        $this->session(['login_account_id' => 0]);

    // test add new
        $staff      = 1;
        $teacher    = 0;
        $other      = 1;
        $target     = $staff . "," . $teacher . "," . $other;
        $bulletin_board = array(
            'title'             => "Unit Test_タイトル",
            'message'           => "Unit Test_メッセージ",
            'start_date'        => date('Y-m-d'),
            'finish_date'       => date('Y-m-d'),
            'calendar_flag'     => 1,
            'calendar_color'    => '808080',
            'target'            => $target
        );
        $bulletin_board['pschool_id'] = $this->admin['id'];
        self::$saved_id = BulletinBoardTable::getInstance()->save($bulletin_board);
        // check save_success
        $this->assertNotNull(self::$saved_id, "Add Process failed");

        // check save exactly info
        $saved_item = BulletinBoardTable::getInstance()->load(self::$saved_id);
        $this->assertEquals( $bulletin_board['title'], $saved_item['title'] );
        $this->assertEquals( $bulletin_board['message'], $saved_item['message'] );
        $this->assertEquals( $bulletin_board['start_date'], $saved_item['start_date'] );
        $this->assertEquals( $bulletin_board['finish_date'], $saved_item['finish_date'] );
        $this->assertEquals( $bulletin_board['calendar_flag'], $saved_item['calendar_flag'] );
        $this->assertEquals( $bulletin_board['calendar_color'], $saved_item['calendar_color'] );
        $this->assertEquals( $bulletin_board['target'], $saved_item['target'] );
    // end - test add new

    // test update
        $staff      = 0;
        $teacher    = 1;
        $other      = 0;
        $target     = $staff . "," . $teacher . "," . $other;
        $bulletin_board_update = array(
            'title'             => "Unit Test_タイトル_update",
            'message'           => "Unit Test_メッセージ_update",
            'start_date'        => date('Y-m-d'),
            'finish_date'       => date('Y-m-d'),
            'calendar_flag'     => 0,
            'calendar_color'    => 'FFFFFF',
            'target'            => $target
        );
        $bulletin_board_update['id'] = self::$saved_id;
        $updated_id = BulletinBoardTable::getInstance()->save($bulletin_board_update);
        // check save_success
        $this->assertNotNull($updated_id, "Update Process failed");

        // check save exactly info
        $updated_item = BulletinBoardTable::getInstance()->load($updated_id);
        $this->assertEquals( $bulletin_board_update['title'], $updated_item['title'] );
        $this->assertEquals( $bulletin_board_update['message'], $updated_item['message'] );
        $this->assertEquals( $bulletin_board_update['start_date'], $updated_item['start_date'] );
        $this->assertEquals( $bulletin_board_update['finish_date'], $updated_item['finish_date'] );
        $this->assertEquals( $bulletin_board_update['calendar_flag'], $updated_item['calendar_flag'] );
        $this->assertEquals( $bulletin_board_update['calendar_color'], $updated_item['calendar_color'] );
        $this->assertEquals( $bulletin_board_update['target'], $updated_item['target'] );
    // end test update

    }

// test delete bulletin - Thang 2017/06/02
    public function testDeleteBulletin()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: DELETE BULLETIN INFO (SCHOOL/BULLETIN BOARD)";
        echo "\n\t ====================";
        $pschool_id = $this->admin['id'];
        $delete_id = self::$saved_id; // run testSaveBulletin() first to get delete_id

        $bulletinBoardTable = BulletinBoardTable::getInstance();
        $bulletin_board = $bulletinBoardTable->load($delete_id);
        if ($bulletin_board) {
            $uploadFilesTable = UploadFilesTable::getInstance();
            try {
                // delete file(s) in DB
                $deleted_id = $bulletinBoardTable->deleteRow(array( 'id' => $delete_id ));
                $this->assertNotNull($deleted_id);
                $files = $uploadFilesTable->getActiveList( array(
                            'category_code' => 1,
                            'target_id'     => $delete_id
                        ) );
                // delete file(s) in storage
                foreach ($files as $key => $file) {
                    $is_deleted = $this->deleteUploadFile( $file['id'] );
                    $this->assertTrue( $is_deleted );
                }
                // set message
            } catch (Exception $e) {
                $this->_logger->error($ex->getMessage());
            }
        }
    }
// end - test delete bulletin - Thang 2017/06/02

// test school/bulletin list - Thang 2017/06/02
    public function testGetListInBulletinPage()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET LIST BULLETIN INFO (SCHOOL/BULLETIN BOARD)";
        echo "\n\t ====================";
        $pschool_id = $this->admin['id'];

// ログインアカウント種別　1=システム2=塾 3=スタッフ 5=講師 10=保護者
    // Load list bulletin with account_type = 2 : 塾
        // search_title = ""
        $condition = array( 'pschool_id'    => $pschool_id,
                            'account_type'  => 2 );
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (admin) hasn't any bulletin");
        $this->assertEquals("7", count($bulletin_list), "{$this->admin['login_id']}'s school (admin) hasn't exactly 7 bulletins");

        // search_title = 1
        $condition['search_title'] = '1';
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (admin; search = '1' ) hasn't any bulletin");
        $this->assertEquals("3", count($bulletin_list), "{$this->admin['login_id']}'s school (admin; search = '1') hasn't exactly 3 bulletins");

        // search_title = テス
        $condition['search_title'] = 'テス';
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (admin; search = 'テス' ) hasn't any bulletin");
        $this->assertEquals("1", count($bulletin_list), "{$this->admin['login_id']}'s school (admin; search = 'テス') hasn't exactly 4 bulletins");

    // Load list bulletin with account_type = 3 : スタッフ
        // search_title = ""
        $condition = array( 'pschool_id'    => $pschool_id,
                            'account_type'  => 3 );
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (staff) hasn't any bulletin");
        $this->assertEquals("4", count($bulletin_list), "{$this->admin['login_id']}'s school (staff) hasn't exactly 4 bulletins");

        // search_title = 1
        $condition['search_title'] = '1';
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (staff; search = '1' ) hasn't any bulletin");
        $this->assertEquals("1", count($bulletin_list), "{$this->admin['login_id']}'s school (staff; search = '1') hasn't exactly 1 bulletins");

        // search_title = テス
        $condition['search_title'] = 'テス';
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (staff; search = 'テス' ) hasn't any bulletin");
        $this->assertEquals("1", count($bulletin_list), "{$this->admin['login_id']}'s school (staff; search = 'テス') hasn't exactly 1 bulletins");

    // Load list bulletin with account_type = 5 : 講師
        // search_title = ""
        $condition = array( 'pschool_id'    => $pschool_id,
                            'account_type'  => 5 );
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (teacher) hasn't any bulletin");
        $this->assertEquals("5", count($bulletin_list), "{$this->admin['login_id']}'s school (teacher) hasn't exactly 5 bulletins");

        // search_title = 1
        $condition['search_title'] = '1';
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (teacher; search = '1' ) hasn't any bulletin");
        $this->assertEquals("3", count($bulletin_list), "{$this->admin['login_id']}'s school (teacher; search = '1') hasn't exactly 3 bulletins");

        // search_title = テス
        $condition['search_title'] = 'テス';
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (teacher; search = 'テス' ) hasn't any bulletin");
        $this->assertEquals("1", count($bulletin_list), "{$this->admin['login_id']}'s school ( teacher; search = 'テス') hasn't exactly 1 bulletins");

    // Load list bulletin with account_type = 10 : 保護者
        // search_title = ""
        $condition = array( 'pschool_id'    => $pschool_id,
                            'account_type'  => 10 );
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (parent) hasn't any bulletin");
        $this->assertEquals("4", count($bulletin_list), "{$this->admin['login_id']}'s school (parent) hasn't exactly 4 bulletins");

        // search_title = 1
        $condition['search_title'] = '1';
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (parent; search = '1' ) hasn't any bulletin");
        $this->assertEquals("2", count($bulletin_list), "{$this->admin['login_id']}'s school (parent; search = '1' ) hasn't exactly 2 bulletins");

        // search_title = テス
        $condition['search_title'] = 'テス';
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (parent; search = 'テス' ) hasn't any bulletin");
        $this->assertEquals("1", count($bulletin_list), "{$this->admin['login_id']}'s school ( parent; search = 'テス') hasn't exactly 1 bulletins");
    }
// end --- test school/bulletin list - Thang 2017/06/02

// test school/home - Thang 2017/06/02
    public function testBulletinListHomePage()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET LIST BULLETIN INFO (SCHOOL/HOME)";
        echo "\n\t ====================";
        $pschool_id = $this->admin['id'];

    // ログインアカウント種別　1=システム2=塾 3=スタッフ 5=講師 10=保護者
        // Load list bulletin with account_type = 2 : 塾
        $condition = array( 'pschool_id'    => $pschool_id,
                            'account_type'  => 2,
                            'is_home'       => 1, );
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (auth_type = 2) hasn't any bulletin");
        $this->assertEquals("6", count($bulletin_list), "{$this->admin['login_id']}'s school (auth_type = 2: admin) hasn't exactly 6 bulletins");

        // Load list bulletin with account_type = 3 : スタッフ
        $condition['account_type'] = 3;
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (auth_type = 3) hasn't any bulletin");
        $this->assertEquals("3", count($bulletin_list), "{$this->admin['login_id']}'s school (auth_type = 3: staff) hasn't exactly 3 bulletins");

        // Load list bulletin with account_type = 5 : 講師
        $condition['account_type'] = 5;
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (auth_type = 5: teacher) hasn't any bulletin");
        $this->assertEquals("4", count($bulletin_list), "{$this->admin['login_id']}'s school (auth_type = 5: teacher) hasn't exactly 4 bulletins");

        // Load list bulletin with account_type = 10 : 保護者
        $condition['account_type'] = 10;
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        $this->assertNotNull($bulletin_list, "{$this->admin['login_id']}'s school (auth_type = 10: parent) hasn't any bulletin");
        $this->assertEquals("3", count($bulletin_list), "{$this->admin['login_id']}'s school (auth_type = 10: parent) hasn't exactly 3 bulletins");
    }
// end ---- test school/home - Thang 2017/06/02

    private function deleteUploadFile($file_id)
    {
        $uploadFilesTable = UploadFilesTable::getInstance();
        $file = $uploadFilesTable->load($file_id);
        if ($file) {
            $file_deleted = '';
            $file_row_deleted = '';
            try {
                // delete file in folder
                if ( Storage::disk('uploads')->exists($file['file_path'])) {
                    $file_deleted = Storage::disk('uploads')->delete($file['file_path']);
                } else {
                    echo " File does not exist ! ";
                }
                // delete row in upload_files table
                $file_row_deleted = $uploadFilesTable->deleteRow(array('id'=>$file_id));
            } catch (Exception $e) {
                $this->_logger->error($ex->getMessage());
                return false;
            }
            if ( $file_deleted && $file_row_deleted ) {
                return true;
            }
        }
        return false;
    }
}