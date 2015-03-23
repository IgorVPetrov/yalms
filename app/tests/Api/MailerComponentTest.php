<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 17.03.15
 * Time: 22:38
 */

namespace Yalms\Tests\Api;

use TestCase;
use Yalms\Component\Mailer\MailerComponent;
use Mockery;
use URL;
use Crypt;
use View;

/**
 * Class MailerComponentTest
 *
 *  класс даля тестирования MailerComponent
 *
 * @package Yalms\Tests\Api
 */
class MailerComponentTest extends TestCase
{


	/**
	 *  проверка отправки письма для подтверждения регистрации пользователя
	 */
	public function testUserConfirmEmailSend()
	{

		/**
		 *
		 *  поскольку функция Crypt::encrypt всегда возвращает разные строки
		 *  даже если шифруется одно и то же, я её использую один раз
		 *
		 */
		$key = Crypt::encrypt('000');

		$email = 'info@tra.org';

		$confirmURL = URL::route('user/confirm', array($key));

		$data = array('confirmURL' => $confirmURL);

		/**
		 *  здесь я получаю вид письма
		 */
		$view = View::make('emails.confirm.email', $data);

		/*
		 * здесь я подменяю фасад чтобы в тестируемом методе он
		 * вернул то, что мне нужно, а не высчитывал заново
		 */
		Crypt::shouldReceive('encrypt')->once()->andReturn($key);

		$mock_Swift_Mailer = Mockery::mock('Swift_Mailer');

		$this->app->make('mailer')->setSwiftMailer($mock_Swift_Mailer);

		$mock_Swift_Mailer->shouldReceive('send')->once()
			->andReturnUsing(function ($msg) use ($view, $email) {

				$this->assertEquals('Подтверждение регистрации', $msg->getSubject());
				$this->assertEquals(array($email => null), $msg->getTo());
				$this->assertEquals($view, $msg->getBody());

			});

		MailerComponent::userConfirm($key, $email);


	}

	public function tearDown()
	{
		Mockery::close();

	}

}
