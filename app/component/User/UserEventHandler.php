<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 26.03.15
 * Time: 2:19
 */

namespace Yalms\Component\User;

use Yalms\Component\Mailer\MailerComponent;

/**
 * Class UserEventHandler
 *
 *  класс слушающий и обрабатывающий события пользователя
 *
 * @package Yalms\Component\User
 */
class UserEventHandler
{


	/**
	 * @param $user
	 *
	 *  обработчик события "создан новый пользователь"
	 */
	public function newUserCreated($user)
	{

		MailerComponent::userConfirm($user->phone, $user->email);

	}


	public function subscribe($events)
	{
		$events->listen('user.created', '\Yalms\Component\User\UserEventHandler@newUserCreated');


	}


}