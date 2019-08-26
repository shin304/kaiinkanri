<?php

namespace App\Model;

use App\ConstantsModel;
use DaveJamesMiller\Breadcrumbs\Exception;

class LoginAccountTable extends DbModel {
    /**
     *
     * @var LoginAccountTable
     */
    private static $_instance = null;

    /**
     *
     * @return LoginAccountTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new LoginAccountTable ();
        }
        return self::$_instance;
    }
    protected $table = 'login_account';
    public $timestamps = false;

    public function createListStudentAccount(){
        $studentTable = StudentTable::getInstance();
        $studentTable->beginTransaction();
        try{
            $sql = "SELECT student.id as student_id , pschool_id , student.mailaddress as login_id, MD5('12345678') as login_pw, 9 as auth_type, 1 as active_flag,
                '".date('Y-m-d H:i:s')."' as register_date , pschool.language as lang_code
                FROM student 
                LEFT JOIN pschool ON pschool.id = student.pschool_id
                ";

            $students = $this->fetchAll($sql);

            foreach ($students as $k => $student){
                if(empty($student['lang_code'])){
                    $student['lang_code'] = 2;
                }
                $login_account_id = $this->save($student,true);
                if(!empty($login_account_id)){
                    $student['id'] = $student['student_id'];
                    $student['login_account_id'] = $login_account_id;
                    $studentTable->save($student,true);
                }
            }
        }catch(Exception $e){
            dd("Fail");
            $studentTable->rollBack();
        }
        $studentTable->commit();
        dd("Success");

    }

    public function checkLoginAcountByLoginID($email, $pschool_id)
    {
        $log_acc = LoginAccountTable::where ( 'login_id', $email )->where ('pschool_id', $pschool_id)->where ( 'delete_date', null )->first ();
        return $log_acc;
    }

    public function checkLoginAcountByID($id)
    {
        $log_acc = LoginAccountTable::where ( 'id', $id )->where ( 'delete_date', null )->first ();
        return $log_acc;
    }

    public function checkEmailLogin($email)
    {
        $log_acc = LoginAccountTable::where ( 'login_id', $email )->where ( 'delete_date', null )->first ();
        return $log_acc;
    }

    public function getSchoolByLoginID($email)
    {
        $login_acc = 
        LoginAccountTable::where('login_account.login_id', $email)
        ->join('pschool', 'pschool.id', '=', 'login_account.pschool_id')->get ();
        //$login_acc = LoginAccountTable::where ( 'login_id', $email )->where ( 'delete_date', null )->get ();
        return $login_acc;
    }

    public function insertNewPasswordTemp($email, $pschool_id, $code, $time_code, $password_temp)
    {
        $login_account_table = LoginAccountTable::getInstance ();
        $sql = LoginAccountTable::where ('login_id', $email)->where ('pschool_id', $pschool_id)->where ('delete_date', null)->first ();
        $login_account_table->beginTransaction();
        try{
            $log_acc = LoginAccountTable::find ($sql->id)->toArray();
            
            $log_acc ['code'] = $code ;
            $log_acc ['time_of_code'] = $time_code;
            $log_acc ['password_tmp'] = $password_temp;
            LoginAccountTable::getInstance ()->save ($log_acc, true);
        }
        catch(Exception $e){
            $login_account_table->rollBack();
        }
        $login_account_table->commit();
    }

    public function changePassword($id, $password)
    {
        $login_account_table = LoginAccountTable::getInstance ();
        // $login_acc_sql = LoginAccountTable::where ('login_id', $email)->where ('delete_date', null)->first ();
        $login_account_table->beginTransaction ();
        try{
            // $log_acc = LoginAccountTable::find ($login_acc_sql->id)->toArray();
            $log_acc = LoginAccountTable::find ($id)->toArray();
            $log_acc ['login_pw'] = $password ;
            $log_acc ['code'] = null ;
            $log_acc ['time_of_code'] = null;
            $log_acc ['password_tmp'] = null;
            $login_account_table->save ($log_acc, true);
        }
        catch(Exception $e){
            $login_account_table->rollBack();
        }
        $login_account_table->commit();
    }

    public function changeStudentPassword($id, $password)
    {
        $student_table = StudentTable::getInstance ();
        $student_table->beginTransaction ();
        try{

                $student_acc_tmp = StudentTable::where ('login_account_id', $id)->where ('delete_date', null)->first ();
                $student_acc = StudentTable::find ($student_acc_tmp->id)->toArray();
                $student_acc ['login_pw'] = $password;
                $student_table->save ($student_acc, true);
        }
        catch(Exception $e){
            $student_table->rollBack ();
        }
        $student_table->commit ();
    }

    public function changeParentPassword($id, $password)
    {
        $parent_table = ParentTable::getInstance ();
        $parent_table->beginTransaction ();
        try {
            $parent_acc_tmp = ParentTable::where('login_account_id', $id)->where('delete_date', null)->first();
            $parent_acc = ParentTable::find($parent_acc_tmp->id)->toArray();
            $parent_acc ['login_pw'] = $password;
            $parent_table->save($parent_acc, true);
        } catch (Exception $e) {
            $parent_table->rollBack();
        }
        $parent_table->commit();
    }

    public function getName($id)
    {
        $name = null;
        $login_acc_sql = LoginAccountTable::where ('id', $id)->where ('delete_date', null)->first ();
        if ($login_acc_sql->auth_type == 2) {
            $school_acc = PschoolTable::where ('login_account_id', $login_acc_sql->id)->where ('delete_date', null)->first ();
            $name = $school_acc->name;
        }
        elseif ($login_acc_sql->auth_type == 3) { // Staff
            $staff_acc = StaffTable::where ('login_account_id', $login_acc_sql->id)->where ('delete_date', null)->first ();
            $name = $staff_acc->staff_name;
        }
        elseif ($login_acc_sql->auth_type == 5) { // Coach
            $coach_acc = CoachTable::where ('login_account_id', $login_acc_sql->id)->where ('delete_date', null)->first ();
            $name = $coach_acc->coach_name;
        }
        elseif ($login_acc_sql->auth_type == 9) { // Student
            $student_acc = StudentTable::where ('login_account_id', $login_acc_sql->id)->where ('delete_date', null)->first ();
            $name = $student_acc->student_name;
        }
        elseif ($login_acc_sql->auth_type == 10) { // Parent
            $parent_acc = ParentTable::where ('login_account_id', $login_acc_sql->id)->where ('delete_date', null)->first ();
            $name = $parent_acc->parent_name;
        }
        return $name;
    }

    public function getPschoolForLogin($email, $password)
    {
        $list_pschool_id = array();
        $login_account_not_type_school = LoginAccountTable::where('login_account.login_id', $email)
            ->join('pschool', 'pschool.id', '=', 'login_account.pschool_id')
            ->where('login_account.login_pw', md5($password))
            ->where('login_account.delete_date', NULL)
            ->where('pschool.delete_date', NULL)
            ->get();

        $login_account_type_school = LoginAccountTable::where('login_account.login_id', $email)
            ->where('login_account.login_pw', md5($password))
            ->where('login_account.delete_date', NULL)
            ->where('login_account.auth_type', ConstantsModel::$LOGIN_AUTH_SCHOOL)
            ->get();

        foreach ($login_account_not_type_school as $v) {
            $list_pschool_id[$v->id] = $v->name;
        }

        foreach ($login_account_type_school as $v) {
            $pschool = PschoolTable::where('login_account_id', $v['id'])
                                    ->first();
            if ($pschool) {
                $list_pschool_id[$pschool->id] = $pschool->name;
            }
        }

        return $list_pschool_id;
    }
}
