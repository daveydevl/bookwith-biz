<?php

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

require_once 'includes/pages.php';

$c_page = $default_page;

if (is_string($req) && array_key_exists($req, $pages))
    $c_page = $req;

$page_name = $txt['Pages'][$pages[$c_page]];

?><!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <title><?php echo $page_name; ?> - bookwith.biz</title>
    
    <link href="//cdnjs.cloudflare.com/ajax/libs/muicss/0.9.39/css/mui.min.css" rel="stylesheet" type="text/css" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/styles/panel.css" rel="stylesheet" type="text/css" />
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/muicss/0.9.39/js/mui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/caret/1.0.0/jquery.caret.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.mobilephonenumber/1.0.7/jquery.mobilePhoneNumber.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/locale-all.js"></script>
    <script src="/assets/scripts/panel.js"></script>
    
    <style>
        a {
            color: black;
            text-decoration: none;
        }
        
        a:hover, a:focus {
            text-decoration: none;
        }
    </style>
  </head>
  <body>
    <div id="sidedrawer" class="mui--no-user-select" style="overflow-x:hidden">
      <div id="sidedrawer-brand" class="mui--appbar-line-height">
        <a href="/"><span class="mui--text-title" style="vertical-align:middle">bookwith.biz</span></a>
      </div>
      <div class="mui-divider"></div>
      <ul>
        <?php
        
        foreach ($pages as $href => $page_index) {
            if ($href == 'divider') {
                echo '</ul><div class="mui-divider"></div><ul>';
                continue;
            }
            
            $active = '';
            if ($href == $c_page)
                $active = ' style="background-color:#E0E0E0"';
            
            echo '<li><a href="/' . $href . '"><strong' . $active . '>' . $txt['Pages'][$page_index] . '</strong></a></li>';
        }
        
        ?>
        <li><a href="/logout"><strong><?php echo $txt['Pages'][6]; ?></strong></a></li>
      </ul>
      
      <?php
      if (!empty($business['subdomain'])) {
        $dom = htmlentities($business['subdomain']);
      
      ?>
          <div class="mui-divider"></div>
          <ul>
              <img src="https://chart.apis.google.com/chart?cht=qr&chs=200x200&chl=https%3A//<?php echo $dom; ?>.bookwith.biz">
              <a href="https://<?php echo $dom; ?>.bookwith.biz" target="_blank"><p style="text-align:center"><?php echo $dom; ?>.bookwith.biz</p></a>
          </ul>
      <?php
      }
      ?>
    </div>
    <header id="header">
      <div class="mui-appbar mui--appbar-line-height" style="background-color:#6ec27e">
        <div class="mui-container-fluid">
          <a class="sidedrawer-toggle mui--visible-xs-inline-block mui--visible-sm-inline-block js-show-sidedrawer" style="vertical-align:middle">☰</a>
          <a class="sidedrawer-toggle mui--hidden-xs mui--hidden-sm js-hide-sidedrawer" style="vertical-align:middle">☰</a>
          <span class="mui--text-title" style="vertical-align:middle"><?php echo $page_name; ?></span>
        </div>
      </div>
    </header>
    <div id="content-wrapper">
      <div class="mui--appbar-height"></div>
      <br>
      <div class="mui-container">
      <?php
      
      echo $output;
      
      include 'subpages/' . $c_page . '.php';
      
      ?>
      </div>
    </div>
  </body>
</html>
