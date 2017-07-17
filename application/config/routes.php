<?php
/**
* URI ROUTING
*/

/** Examples **/

#$route['default_controller'] = "home";

// Pages
#$route['pages/(.*)'] = 'pages/index/$1';
#$route['(\w{2})/pages/(.*)'] = 'pages/index/$2';
#$route['404_override'] = 'my404';

// Language
#$route['(\w{2})/(.*)'] = '$2';
#$route['(\w{2})'] = $route['default_controller'];
#$route['admin/(\w{2})/(.*)'] = "admin/$2";

// Others
#$route['abc/(:any)/(:num)/dub'] = 'home/abc';
#$route['xyz/(:any)'] = 'home/xyz/$1';
#$route['(\w{2})/logout'] = '$1/admin/logout';
#$route['logout'] = 'admin/logout';
#$route['r/(.*)'] = 'r/index/$1';
#$route['(\w{2,3})'] = 'home/index/$1';