<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 03.03.15
 * Time: 19:41
 */

namespace Yalms\Tests\Api;

use Yalms\Component\User\UserComponent;
use Yalms\Models\Users\User;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;
use TestCase;
use DB;





class UserProfileSwitchingTest extends TestCase{




	/**
	 *  Начальные установки
	 *
	 * @throws \ErrorException
	 */


	public function setUp()
    {
	    parent::setUp();

	    /**
	     *  Подготовка таблицы
	     */


	    UserAdmin::truncate();
	    UserStudent::truncate();
	    UserTeacher::truncate();
	    DB::statement('SET foreign_key_checks = 0');
	    User::truncate();
	    DB::statement('SET foreign_key_checks = 1');

	    /**
	     *  Массив данных для создания нового пользователя
	     *
	     * @var array
	     */
	    $input = array(
	    'phone'    => '000',
	    'email'    => 'inf@igoruha.org',
	    'password' =>'11111111',
	    'password_confirmation' =>'11111111',
    );
	    /**
	     *  создание нового пользователя
	     */

	    $this->call('POST','/api/v1/user',$input);



    }




	public function testStudentProfileEnable()
	{
		$input= [
			'id'      => 1,
			'profile' => 'student',
			'enabled' => 1
		];

		$this->call('POST','/api/v1/user/profile',$input);

		$this->assertResponseOk();

	}

	public function testStudentProfileDisable()
	{
		$this->assertTrue(true);

	}
	public function testTeacherProfileEnable()
	{
		$this->assertTrue(true);
	}

	public function testTeacherProfileDisable()
	{
		$this->assertTrue(true);
	}

}