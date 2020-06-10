<?php



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
$active_multilang = defined('CNF_MULTILANG') ? CNF_LANG : 'en'; 
 \App::setLocale($active_multilang);
 if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {

    $lang = (\Session::get('lang') != "" ? \Session::get('lang') : CNF_LANG);
    \App::setLocale($lang);
}   

Route::get('/', 'HomeController@index');
Route::post('user/signin', 'UserController@signin');

Route::resource('home', 'HomeController');
Route::resource('blog', 'PostController');
Route::resource('post', 'PostController');
Route::get('/user/login', [ 'as' => 'login', 'uses' => 'UserController@index']);
Route::resource('/user', 'UserController');

include('pageroutes.php');
include('moduleroutes.php');

Route::get('/restric',function(){

	return view('errors.blocked');

});

Route::resource('mmbapi', 'MmbapiController'); 
Route::group(['middleware' => 'auth'], function()
{

	Route::get('core/elfinder', 'Core\ElfinderController@getIndex');
	Route::post('core/elfinder', 'Core\ElfinderController@getIndex'); 
	Route::resource('/dashboard', 'DashboardController');	
    
    
	Route::resource('core/users', 'Core\UsersController');
	Route::resource('notification', 'NotificationController');
	Route::resource('core/logs', 'Core\LogsController');
	Route::resource('core/pages', 'Core\PagesController');
	Route::resource('core/groups', 'Core\GroupsController');
	Route::resource('core/template', 'Core\TemplateController');
	Route::resource('core/posts', 'Core\PostsController');	
	Route::resource('core/forms', 'Core\FormsController');


});	

Route::group(['middleware' => 'auth' , 'middleware'=>'mmbauth'], function()
{

    Route::resource('core/menu', 'Mmb\MenuController');
    Route::resource('core/config', 'Mmb\ConfigController');
    Route::resource('mmb/module', 'Mmb\ModuleController');
    Route::resource('core/tables', 'Mmb\TablesController');
    Route::resource('core/code', 'Mmb\CodeController');
    Route::resource('core/rac', 'Mmb\RacController');

});





