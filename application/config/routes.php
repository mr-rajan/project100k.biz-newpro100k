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
/*
if(strstr($_SERVER['REQUEST_URI'],'/admincp') || strstr($_SERVER['REQUEST_URI'],'/admincp/')){
	$route['default_controller'] = '/admincp/home';
}
elseif(strstr($_SERVER['REQUEST_URI'],'/admincp/$1')){
	$route['default_controller'] = '/admincp/$1';
}
else{
	$route['default_controller'] = 'home';
}*/
$route['default_controller'] 					= 	'home';
$route['register/ref/(:any)'] 					= 	'register/index/$1';
$route['404_override'] 							= 	'';
$route['admincp']								=	'admincp/home';
$route['admincp/account(:any)']					=	'admincp/account/$1';
$route['admincp/user/addnew/(:any)']			=	'admincp/user/addnew/$1';
$route['admincp/user/edit/(:any)']				=	'admincp/user/edit/$1';
$route['admincp/user/downline/(:any)']			=	'admincp/user/downline/$1';
$route['admincp/user/edituser/(:any)']			=	'admincp/user/edituser/$1';
//Routes handling bannerurl related request
$route['admincp/bannerurl/edit/(:any)']			=	'admincp/bannerurl/edit/$1';
$route['admincp/bannerurl/addnewfield/(:any)']	=	'admincp/bannerurl/addnewfield/$1';
$route['admincp/bannerurl/editfield/(:any)']	=	'admincp/bannerurl/editfield/$1';
$route['admincp/bannercategory/save']			=	'admincp/bannercategory/save';
$route['admincp/bannercategory/addnew/(:any)']	=	'admincp/bannercategory/addnew/$1';
$route['admincp/bannercategory/edit/(:any)/(:any)']	=	'admincp/bannercategory/edit/$1/$2';
$route['admincp/bannercategory/(:any)']			=	'admincp/bannercategory/index/$1';
//end

// Routes handling promotion releated request
$route['admincp/promotion/edit/(:any)']			=	'admincp/promotion/edit/$1';
$route['admincp/promotion/addnewfield/(:any)']	=	'admincp/promotion/addnewfield/$1';
$route['admincp/promotion/editfield/(:any)']	=	'admincp/promotion/editfield/$1';
$route['admincp/promotioncategory/save']			=	'admincp/promotioncategory/save';
$route['admincp/promotioncategory/addnew/(:any)']	=	'admincp/promotioncategory/addnew/$1';
$route['admincp/promotioncategory/edit/(:any)/(:any)']	=	'admincp/promotioncategory/edit/$1/$2';
$route['admincp/promotioncategory/(:any)']			=	'admincp/promotioncategory/index/$1';

//end
$route['translate_uri_dashes'] = FALSE;
