<?php

namespace Yalms\Models\Users;

use Eloquent;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserTrait;


/**
 * Class User
 *
 * @property integer     $id
 * @property string      $first_name
 * @property string      $middle_name
 * @property string      $last_name
 * @property string      $email
 * @property string      $phone
 * @property string      $password
 * @property string      $remember_token
 * @property boolean     $enabled
 *
 * @property UserStudent $student
 * @property UserTeacher $teacher
 * @property UserAdmin   $admin
 *
 * @method static User whereEnabled($boolean)
 * @method static User wherePhone($phone)
 * @method static User findOrFail($phone)
 * @method static User first()
 *
 */
class User extends Eloquent implements UserInterface, RemindableInterface
{
	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token', 'enabled');


	public function student()
	{
		return $this->hasOne('UserStudent');
	}

	public function teacher()
	{
		return $this->hasOne('UserTeacher');
	}

	public function admin()
	{
		return $this->hasOne('UserAdmin');
	}

    public static function login( $data )
    {
        /*
         * логиним юзера*/
    }

    public static function register( $data )
    {
        /*
         * Запись юзера в базу*/
    }


}
