<?php namespace Modules\User\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class UserController extends Controller {

	public function index()
	{
		return View::make('user::index');
	}
	
}