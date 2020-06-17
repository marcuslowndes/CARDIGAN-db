<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/


$route['login'] = 'users/login';
$route['register'] = 'users/register';
$route['view_all_users'] = 'users/users_list';
$route['users/edit/(:any)/(:any)'] = 'users/users_list/$1/$2';
$route['change_password'] = 'users/change_password';
$route['delete_account/(:any)'] = 'users/delete_account/$1';
$route['logout'] = 'users/logout';

$route['database'] = 'database/index';
$route['database/reset'] = 'database/reset_form';
$route['database/add'] = 'database/add_to_selected';
$route['database_remove/(:any)'] = 'database/remove_from_selected/$1';
$route['database_result'] = 'database/result';
$route['database/(:any)'] = 'database/index/$1';
$route['database/(:any)/(:any)'] = 'database/index/$1/$2';
$route['database/(:any)/(:any)/(:any)'] = 'database/index/$1/$2/$3';
$route['database/(:any)/(:any)/(:any)/(:any)'] = 'database/index/$1/$2/$3/$4';

$route['about'] = 'welcome/about';
$route['contact'] = 'welcome/contact';
$route['default_controller'] = 'welcome/index';
$route['(:any)'] = 'welcome/index';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
