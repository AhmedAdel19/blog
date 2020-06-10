<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));
define('CNF_APPNAME','');
define('CNF_APPDESC','');
define('CNF_COMNAME','City Travels');
define('CNF_ADDRESS','Eros fere nostrud
Facilisis luptatum occuro plaga verto');
define('CNF_TEL','00901234567');
define('CNF_EMAIL','info@managemybookings.net');
define('CNF_FACEBOOK','https://www.facebook.com/managemybookings/');
define('CNF_TWITTER','https://www.twitter.com/managemybookings/');
define('CNF_INSTAGRAM','https://www.instagram.com/managemybookings/');
define('CNF_TRIPADVISOR','https://www.tripadvisor.com/managemybookings/');
define('CNF_TAGLINE','Dont call it a dream, call it a plan');
define('CNF_TEMPCOLOR','red-dark');
define('CNF_METAKEY','vcvx
');
define('CNF_METADESC','test fin');
define('CNF_GROUP','6');
define('CNF_ACTIVATION','confirmation');
define('CNF_MAINTENANCE','');
define('CNF_SHOWHELP','ON');
define('CNF_MULTILANG','1');
define('CNF_LANG','en');
define('CNF_REGIST','true');
define('CNF_FRONT','true');
define('CNF_RECAPTCHA','false');
define('CNF_THEME','leo');
define('CNF_RECAPTCHAPUBLICKEY','');
define('CNF_RECAPTCHAPRIVATEKEY','');
define('CNF_MODE','production');
define('CNF_LOGO','logo.jpg');
define('CNF_HEADERIMAGE','header.jpg');
define('CNF_ALLOWIP','');
define('CNF_RESTRICIP','');
define('CNF_MAIL','phpmail');
define('CNF_DATE','M d Y');
define('CNF_APIKEY','');
define('CNF_CALENDARID','');
define('CNF_ANALYTICS','UA-XXXXX-X');

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
