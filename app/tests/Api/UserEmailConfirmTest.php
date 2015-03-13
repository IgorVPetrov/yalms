<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 13.03.15
 * Time: 15:20
 */

namespace Yalms\Tests\Api;

use Yalms\Models\Users\User;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;
use TestCase;
use DB;
use Log;
use DOMDocument;

class UserEmailConfirmTest extends TestCase
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
			'email'                 => 'info@igoruha.org',
			'password'              => '11111111',
			'password_confirmation' => '11111111',
		);
		/**
		 *  создание специального лог-файла для тестов
		 */
		$log_file_handle = fopen(storage_path() . '/logs/laravel_testing.log', 'a+');
		fclose($log_file_handle);
		/**
		 *  переключение вывода логов на этот файл
		 *
		 */
		Log::useFiles(storage_path() . '/logs/laravel_testing.log');


		/**
		 *  создание нового пользователя
		 */
		$this->call('POST', '/api/v1/user/', $input);

	}

	/**
	 *
	 *  проверка подтверждения пользователя по емейл
	 *
	 */
	public function testUserEmailConfirm()
	{
		$this->call('GET', $this->getConfirmURL());

		$user_enabled = DB::table('users')->where('phone', '000')->pluck('enabled');

		$this->assertEquals($user_enabled, 1);

	}

	/**
	 *
	 *  функция находит url в письме и возвращает его
	 *
	 * @return string
	 */
	private function getConfirmURL()
	{
		$string = file_get_contents(storage_path() . '/logs/laravel_testing.log');

		$string = substr($string, strpos($string, '<!DOCTYPE html>'));

		$document = new DOMDocument();

		$document->loadHTMl($string);

		$url = $document->getElementsByTagName("a")->item(0)
			->attributes->getNamedItem("href")->nodeValue;

		return $url;

	}


	/**
	 *
	 *  функция подключает обратно стандартный лог-файл и и удаляет тестовый
	 *
	 *
	 */
	public function tearDown()
	{
		parent::tearDown();

		Log::useFiles(storage_path() . '/logs/laravel.log');

		unlink(storage_path() . '/logs/laravel_testing.log');

	}


}
































































































