<?php

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

$google_login = UserService::createLoginURL($_SERVER['REQUEST_URI']);
$facebook_login = 'https://www.facebook.com/v3.1/dialog/oauth?client_id=288943405033117&redirect_uri=https%3A%2F%2Fapp.bookwith.biz%2F';

$google_user = UserService::getCurrentUser();

if (!empty($google_user)) {
    $_SESSION['email'] = trim(strtolower($google_user->getEmail()));
    
    header('Location: https://app.bookwith.biz/');
    exit;
}

if (!empty($_GET['code'])) {
    
    $code = urlencode($_GET['code']);
    
    $request = file_get_contents('https://graph.facebook.com/v3.1/oauth/access_token?client_id=288943405033117&redirect_uri=https%3A%2F%2Fapp.bookwith.biz%2F&client_secret=205c0e535617551d1e080a5d2ef04609&code=' . $code);
    $request = json_decode($request, true);
    
    if (!empty($request['access_token'])) {
        
        $user_data = file_get_contents('https://graph.facebook.com/v3.1/me?fields=id&access_token=' . $request['access_token']);
        $user_data = json_decode($user_data, true);
        
        if (!empty($user_data['id'])) {
            $_SESSION['email'] = 'fb:' . $user_data['id'];
            
            header('Location: https://app.bookwith.biz/');
            exit;
        }
    }
}
    
?><!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <title>bookwith.biz - Login</title>
    
    <link href="//cdnjs.cloudflare.com/ajax/libs/muicss/0.9.39/css/mui.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/styles/panel.css" rel="stylesheet" type="text/css" />
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/muicss/0.9.39/js/mui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <style>
        
#content-wrapper {
  margin-left: 0px !important;
}

.loginBtn {
  box-sizing: border-box;
  position: relative;
  width: 100%;
  margin: 0.2em;
  padding: 0 15px 0 46px;
  border: none;
  text-align: left;
  line-height: 34px;
  white-space: nowrap;
  border-radius: 0.2em;
  font-size: 16px;
  color: #FFF;
}
.loginBtn:before {
  content: "";
  box-sizing: border-box;
  position: absolute;
  top: 0;
  left: 0;
  width: 34px;
  height: 100%;
}
.loginBtn:focus {
  outline: none;
}
.loginBtn:active {
  box-shadow: inset 0 0 0 32px rgba(0,0,0,0.1);
}

.loginBtn--facebook {
  background-color: #4C69BA;
  background-image: linear-gradient(#4C69BA, #3B55A0);
  text-shadow: 0 -1px 0 #354C8C;
}
.loginBtn--facebook:before {
  border-right: #364e92 1px solid;
  background: url('https://i.imgur.com/hVunGIw.png') 6px 6px no-repeat;
}
.loginBtn--facebook:hover,
.loginBtn--facebook:focus {
  background-color: #5B7BD5;
  background-image: linear-gradient(#5B7BD5, #4864B1);
}

.loginBtn--google {
  background: #DD4B39;
}
.loginBtn--google:before {
  border-right: #BB3F30 1px solid;
  background: url('https://i.imgur.com/niDHSWS.png') 6px 6px no-repeat;
}
.loginBtn--google:hover,
.loginBtn--google:focus {
  background: #E74B37;
}

    </style>
  </head>
  <body>
    <div id="content-wrapper">
        <br>
        <div class="mui-container" style="max-width:400px">
            <div class="mui-panel">
                <div class="mui-form">
                    <legend>Welcome to bookwith.biz</legend>
                    <hr />
                    
<button class="loginBtn loginBtn--facebook" onclick="location.href='<?php echo $facebook_login; ?>'">
  Continue with Facebook
</button>
<br><br>
<button class="loginBtn loginBtn--google" onclick="location.href='<?php echo $google_login; ?>'">
  Continue with Google
</button>
                    
                    
                    <hr />
                </form>
            </div>
        </div>
    </div>
  </body>
</html>

