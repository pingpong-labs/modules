<?php namespace Modules\Blog\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class BlogController extends Controller {

	public function index()
	{
		return View::make('blog::index');
	}
	
}