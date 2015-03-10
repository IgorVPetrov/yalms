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
			'email'                 => 'inf@igoruha.org',
			'password'              => '11111111',
			'password_confirmation' => '11111111',
		);
		/**
		 *  создание нового пользователя
		 */
		$this->call('POST', '/api/v1/user/', $input);


	}

	/**
	 *   проверка влючения студента
	 */
	public function testStudentProfileEnable()
	{
		$this->setProfile('student', 1);

		$this->assertTrue(UserStudent::find(1)->enabled == 1);
	}

	/**
	 *  проверка выключения студента
	 */
	public function testStudentProfileDisable()
	{
		$this->setProfile('student', 1);

		$this->setProfile('student', 0);

		$this->assertTrue(UserStudent::find(1)->enabled == 0);

	}

	/**
	 * проверка включения преподавателя
	 */
	public function testTeacherProfileEnable()
	{
		$this->setProfile('teacher', 1);

		$this->assertTrue(UserTeacher::find(1)->enabled == 1);

	}

	/**
	 *  проверка выключения преподавателя
	 */
	public function testTeacherProfileDisable()
	{
		$this->setProfile('teacher', 1);

		$this->setProfile('teacher', 0);

		$this->assertTrue(UserTeacher::find(1)->enabled == 0);
	}

	/**
	 *
	 *  вспомогательная функция, устанавливающая состояние профиля
	 *
	 * @param $profile string   профиль пользователя teacher student
	 * @param $enabled int      1 - включить 0 - выключить
	 */
	private function setProfile($profile, $enabled)
	{
		$input = [
			'id'      => 1,
			'profile' => $profile,
			'enabled' => $enabled
		];

		$this->call('POST', '/api/v1/user/profile', $input);
	}

}