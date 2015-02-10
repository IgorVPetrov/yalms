<?php
namespace app\controllers\Api\User;

use Input;
use Response;
use app\controllers\Api\BaseApiController;
use Yalms\Component\User\UserComponent;
use Yalms\Component\User\UserTeacherComponent;
use Yalms\Models\Users\UserTeacher;
use Yalms\Models\Users\User;

class UserTeacherController extends BaseApiController
{

	/**
	 * Display a listing of the resource.
	 *
	 * Параметры:
	 *      page — N страницы,
	 *      per_page — количество на странице.
	 *      sort = created|updated   Сортировка по полю  "created_at" или "updated_at", по умолчанию "created"
	 *      direction = asc|desc     Направление сортировки, по умолчанию "desc"
	 *
	 * @return Response
	 */
	public function index()
	{
		$userComponent = new UserComponent(Input::only(
			array('page', 'per_page', 'sort', 'direction')
		));
		$params = $userComponent->getParameters();

		$teacher = UserTeacher::whereEnabled(1)->with(array(
				'user' => function ($query) {
					/** @var User $query */
					$query->whereEnabled(true);
				}
			)
		)->orderBy($params->sort, $params->direction)->paginate($params->per_page);

		return Response::json($teacher);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return $this->clientError(405);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$utc = new UserTeacherComponent(Input::all());

		if (!$utc->store()) {
			return $this->responseError($utc->getMessage(), $utc->getErrors());
		}

		return $this->responseSuccess($utc->getMessage());

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		User::whereEnabled(true)->findOrFail($id);
		$teacher = UserTeacher::with('user')->find($id, array('user_id', 'enabled'));

		return Response::json(['teacher' => $teacher]);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::whereEnabled(true)->findOrFail($id, array('id', 'first_name', 'middle_name', 'last_name'));

		return Response::json(array(
			'teacher'         => array(
				'id'      => $id,
				'enabled' => UserTeacher::find($id)->enabled,
				'user'    => $user
			),
			'edit_fields'     => array('enabled' => 'Назначить учителем'),
			'required_fields' => array('enabled')
		));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function update($id)
	{
		$userComponent = new UserComponent(Input::all());

		if ($userComponent->updateTeacher($id) == UserComponent::FAILED_VALIDATION) {
			return $this->responseError($userComponent->getMessage(), $userComponent->getErrors());
		}

		return $this->show($id);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 *
	 * @return Response
	 */
	public function destroy()
	{
		return $this->clientError(405);
	}


}
