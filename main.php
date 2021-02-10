<?php

require_once 'vendor/autoload.php';
require_once 'includes/intervals.php';
require_once 'includes/languages.php';
require_once 'includes/colors.php';
require_once 'includes/handlers.php';
require_once 'includes/datastore.php';

session_start();

function redirect($url) {
    header('Location: https://' . $url);
    exit;
}

//handle URL request
$domain    = 'bookwith.biz';
$clothes   = 'app';
$request   = $_SERVER["REQUEST_URI"];
$req       = substr($request, 1);
$host_size = sizeof($host = explode('.', strtolower($_SERVER['HTTP_HOST'])));
$real      = array_slice($host, max($host_size - 3, 0));
$sub       = strtolower($real[0]);

//if logging out
if ($req == 'logout') {
    unset($_SESSION['email']);
    
    foreach ($_COOKIE as $key => $value)
        setcookie ($key, '', 1, '/');
        
    redirect('www.' . $domain);
}

//use http accept language (will be overwritten if business has a language set)
$header_lang = 'en';
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
    $header_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$language = matchLang($header_lang);

//if domain doesn't match
if (implode('.', array_slice($real, sizeof($real) - 2)) !== $domain)
    redirect($clothes . '.' . $domain);

//if domain is naked
if ($host_size < 3)
    redirect($clothes . '.' . $domain . $request);

//if domain is bloated or request was sent without HTTPS
if ($host_size > 3 || $_SERVER['HTTPS'] == 'off')
    redirect($sub . '.' . $domain . $request);

//init datastore
$datastore = new Datastore();

//if subdomain is not app
if ($sub != $clothes) {
    $business = $datastore->fetchBusinessByDomain($sub);
    if (!empty($business))
        $language = $business['language'];
    
    $txt = loadLang($language);
    
    if (isset($business) && $business['sub_expire'] > time())
        include $req == 'calendar' ?
                        'pages/make_appointment.php' :
                        'pages/view_business.php';               
    else
        include 'pages/missing.php';
    
    exit;
}

if (empty($_SESSION['email'])) {
    $txt = loadLang($language);
    
    include 'pages/login.php';
    exit;
}

$user_email = $_SESSION['email'];
$business = $datastore->fetchBusinessByEmail($user_email);
if (!empty($business['new_account']))
    $req = 'edit_business';

$language = $business['language'];
$txt = loadLang($language);

$output = '';

//process a _POST request
if (isset($_POST['handler'])) {
    $handler = $_POST['handler'];
    
    if (in_array($handler, $handlers)) {
        ob_start();
        include 'handlers/' . $handler . '.php';
        $output = ob_get_clean();
    }
}

include 'pages/panel.php';
