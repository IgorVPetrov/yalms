<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 03.03.15
 * Time: 19:41
 */

namespace Yalms\Tests\Api;


use Yalms\Models\Users\User;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;
use TestCase;
use DB;


class UserProfileSwitchingTest extends TestCase
{


	/**
	 * @var
	 */
	private $user_id;

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
			'phone'                 => '000',
			'email'                 => 'info@igoruha.org',
			'password'              => '11111111',
			'password_confirmation' => '11111111',
		);
		/**
		 *  создание нового пользователя
		 */
		$this->call('POST', '/api/v1/user/', $input);

		/**
		 *  получение id созданного пользователя
		 */
		$this->user_id = DB::table('users')->where('phone', '000')->pluck('id');


	}

	/**
	 *   проверка влючения студента
	 */
	public function testStudentProfileEnable()
	{

		$this->setProfile($this->user_id, 'student', 1);

		$student = UserStudent::find($this->user_id);

		$this->assertEquals($student->enabled , 1);
	}

	/**
	 *  проверка выключения студента
	 */
	public function testStudentProfileDisable()
	{

		$this->setProfile($this->user_id, 'student', 1);

		$this->setProfile($this->user_id, 'student', 0);

		$student = UserStudent::find($this->user_id);

		$this->assertEquals($student->enabled, 0);

	}

	/**
	 * проверка включения преподавателя
	 */
	public function testTeacherProfileEnable()
	{

		$this->setProfile($this->user_id, 'teacher', 1);

		$teacher = UserTeacher::find($this->user_id);

		$this->assertEquals($teacher->enabled, 1);

	}

	/**
	 *  проверка выключения преподавателя
	 */
	public function testTeacherProfileDisable()
	{

		$this->setProfile($this->user_id, 'teacher', 1);

		$this->setProfile($this->user_id, 'teacher', 0);

		$teacher = UserTeacher::find($this->user_id);

		$this->assertEquals($teacher->enabled, 0);
	}

	/**
	 *
	 *  вспомогательная функция, устанавливающая состояние профиля
	 *
	 * @param $id      int       идентификатор пользователя
	 * @param $profile string   профиль пользователя teacher student
	 * @param $enabled int      1 - включить 0 - выключить
	 */
	private function setProfile($id, $profile, $enabled)
	{
		$input = [
			'id'      => $id,
			'profile' => $profile,
			'enabled' => $enabled
		];

		$this->call('POST', '/api/v1/user/profile', $input);
	}

}