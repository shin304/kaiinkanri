<?php

namespace Tests\Feature;

use App\ConstantsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\StudentTable;
use App\Model\PschoolTable;
use App\Http\Controllers\School\StudentController;

class StudentTableTest extends TestCase {
    private $validData = [
        'm_student_type_id' => 1,
        'student_name' => '江錦進',
        'student_name_kana' => 'ジャンカムティエン',
        'student_romaji' => 'Giang Cam Tien',
        'mailaddress' => 'giangcamtien_test@gmail.com',
        'birthday' => '1992/02/06',
        'sex' => 1,
        'parent_name' => '江錦進のお父さん',
        'name_kana' => 'オトウサン',
        'parent_mailaddress1' => 'giangcamtien_father_test@gmail.com',
        'parent_pass' => 123456789
    ];

    public function setUp() {
        parent::setUp();
        $loginId = 'test@asto-system.com';
        $password = '12345678';
        $this->admin = $school = PschoolTable::getInstance()->getLoginInfo($loginId, $password);
        $this->withSession([
            'school' => [
                'login' => [
                    'language' => 1,
                    'business_divisions' => 3,
                    'lang_code' => 1,
                    'id' => 188
                ],
            ]
        ]);

    }

    public function testValidBasicStudentInfo() {
        $request = new Request($this->validData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertFalse($validator->fails());
    }

    public function testStudentNameRequired() {
        $studentData = $this->validData;
        unset($studentData['student_name']);

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testStudentMailAddressRequired() {
        $studentData = $this->validData;
        unset($studentData['mailaddress']);

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testStudentMailAddressFormat() {
        $studentData = $this->validData;
        $studentData['mailaddress'] = "abc@com";

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testStudentBirthdayFormat() {
        $studentData = $this->validData;
        $studentData['birthday'] = '1992/02/30';

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testStudentTypeRequired() {
        $studentData = $this->validData;
        unset($studentData['m_student_type_id']);

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testParentNameRequired() {
        $studentData = $this->validData;
        unset($studentData['parent_name']);

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testParentMailAddressRequired() {
        $studentData = $this->validData;
        unset($studentData['parent_mailaddress1']);

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testParentMailAddressFormat() {
        $studentData = $this->validData;
        $studentData['parent_mailaddress1'] = "abc.com";

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testParentPasswordRequired() {
        $studentData = $this->validData;
        unset($studentData['parent_pass']);

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testStudentPhoneRequired() {
        $studentData = $this->validData;
        $studentData['have_student_address_info'] = 1;

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testParentAddressRequired() {
        $studentData = $this->validData;
        $studentData['have_parent_address_info'] = 1;

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testInvoiceTypeCash() {
        $studentData = $this->validData;
        $studentData['have_payment_info'] = 1;
        $studentData['invoice_type'] = ConstantsModel::$INVOICE_CASH_PAYMENT;
        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertFalse($validator->fails());
    }

    public function testInvoiceTypeTransfer() {
        $studentData = $this->validData;
        $studentData['have_payment_info'] = 1;
        $studentData['invoice_type'] = ConstantsModel::$INVOICE_TRANSFER_PAYMENT;
        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertFalse($validator->fails());
    }

    public function testInvoiceTypeBankRequired() {
        $studentData = $this->validData;
        $studentData['have_payment_info'] = 1;
        $studentData['invoice_type'] = ConstantsModel::$INVOICE_BANK_PAYMENT;
        $studentData['bank_type'] = ConstantsModel::$FINANCIAL_TYPE_BANK;
        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testStudentWithoutParent() {
        $studentData = $this->validData;
        unset($studentData['parent_name']);
        unset($studentData['parent_mailaddress1']);
        unset($studentData['parent_pass']);

        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertTrue($validator->fails());
    }

    public function testStudentHasParentId() {
        $studentData = $this->validData;
        $studentData['parent_id'] = 1;
        $request = new Request($studentData);
        $studentController = new StudentController($request);
        $validator = $studentController->doValidate($request);
        $this->assertFalse($validator->fails());
    }

    public function testCanDeleteStudent1() {
        $classes = [
            0 => ['delete_date' => '2017/06/18', 'close_date' => '2017/05/15'],
            1 => ['delete_date' => null, 'close_date' => '2017/06/17'],
            2 => ['delete_date' => null, 'close_date' => null],
        ];
        $courses = [
            0 => ['delete_date' => '2017/04/04', 'close_date' => '2017/05/30'],
            1 => ['delete_date' => '2017/02/14', 'close_date' => '2017/02/14'],
            2 => ['delete_date' => '2017/03/08', 'close_date' => '2017/03/05'],
        ];
        $programs = [
            0 => ['delete_date' => '2017/01/08', 'close_date' => '2017/01/18'],
            1 => ['delete_date' => null, 'close_date' => '2017/06/17'],
            2 => ['delete_date' => '2017/02/12', 'close_date' => '2017/02/16'],
        ];

        $studentController = new StudentController(new Request());
        $this->assertFalse($studentController->isCanDelete($classes, $courses, $programs, '2017/06/19'));
    }

    public function testCanDeleteStudent2() {
        $classes = [
            0 => ['delete_date' => '2017/06/18', 'close_date' => '2017/05/15'],
            1 => ['delete_date' => null, 'close_date' => '2017/06/17'],
            2 => ['delete_date' => '2017/06/12', 'close_date' => null],
        ];
        $courses = [
            0 => ['delete_date' => '2017/04/04', 'close_date' => '2017/05/30'],
            1 => ['delete_date' => '2017/02/14', 'close_date' => '2017/02/14'],
            2 => ['delete_date' => '2017/03/08', 'close_date' => '2017/03/05'],
        ];
        $programs = [
            0 => ['delete_date' => '2017/01/08', 'close_date' => '2017/01/18'],
            1 => ['delete_date' => null, 'close_date' => '2017/06/17'],
            2 => ['delete_date' => '2017/02/12', 'close_date' => '2017/02/16'],
        ];

        $studentController = new StudentController(new Request());
        $this->assertTrue($studentController->isCanDelete($classes, $courses, $programs, '2017/06/19'));
    }

    public function testCanDeleteStudent3() {
        $classes = [
            0 => ['delete_date' => '2017/06/18', 'close_date' => '2017/05/15'],
            1 => ['delete_date' => null, 'close_date' => '2017/06/17'],
            2 => ['delete_date' => '2017/06/12', 'close_date' => null],
        ];
        $courses = [
            0 => ['delete_date' => null, 'close_date' => '2017/07/20'],
        ];
        $programs = [
            0 => ['delete_date' => '2017/01/08', 'close_date' => '2017/01/18'],
            1 => ['delete_date' => null, 'close_date' => '2017/06/17'],
            2 => ['delete_date' => '2017/02/12', 'close_date' => '2017/02/16'],
        ];

        $studentController = new StudentController(new Request());
        $this->assertFalse($studentController->isCanDelete($classes, $courses, $programs, '2017/06/19'));
    }

    public function testCanDeleteStudent4() {
        $classes = [];
        $courses = [];
        $programs = [];

        $studentController = new StudentController(new Request());
        $this->assertTrue($studentController->isCanDelete($classes, $courses, $programs, '2017/06/19'));
    }
}
