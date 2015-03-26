<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 25.03.15
 * Time: 18:58
 */


/**
 *  регистрация обработчика событий пользователя
 */
Event::subscribe(new \Yalms\Component\User\UserEventHandler());