<?php

$fields = [
    'email',
    'subdomain',
    'name',
    'logo',
    'desc',
    'map_title',
    'map_link',
    'tel',
    'tokens',
    'color'
];

$biz = [];
foreach ($fields as $field)
    $biz[$field] = htmlentities($business[$field]);
    
$color_name = $biz['color'];
$color = $colors[$color_name];

?><!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo $biz['name']; ?> - bookwith.biz</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1" />
        <link rel="stylesheet" href="/assets/styles/client.css" />
        <style>
        .contact main section {
            background-color: #<?php echo $color[0]; ?>;
        }
        
        .contact main .title {
            background-color: #<?php echo $color[1]; ?>;
        }
        </style>
	</head>
	<body>
        <div class="contact-area">
          <div class="contact">
            <main>
              <section>
                <div class="content">
                  <img src="<?php echo $biz['logo']; ?>" style="width:96px;height:96px" alt="Profile Image">
                  <aside>
                    <h1><?php echo $biz['name']; ?></h1>
                    <p><?php echo $biz['desc']; ?></p>
                  </aside>
                </div>
                <div class="title active">
                    <p><a href="https://<?php echo $biz['subdomain']; ?>.bookwith.biz"><?php echo $biz['subdomain']; ?>.bookwith.biz</a></p>
                    <a target="_blank" href="https://chart.apis.google.com/chart?cht=qr&amp;chs=500x500&amp;chl=https%3A//<?php echo $biz['subdomain']; ?>.bookwith.biz"><img src="https://chart.apis.google.com/chart?cht=qr&amp;chs=100x100&amp;chl=https%3A//<?php echo $biz['subdomain']; ?>.bookwith.biz" style="width:48px;float:right"></a>
                </div>
              </section>
            </main>
            <nav class="active">
              <a href="/calendar" class="appointment">
                <div class="icon"></div>
                <div class="content">
                  <h1><?php echo $txt['View Business'][1]; ?></h1>
                  <span><?php echo $txt['View Business'][2]; ?></span>
                </div>
                <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"><g class="nc-icon-wrapper" fill="#444444"><path d="M17.17 32.92l9.17-9.17-9.17-9.17L20 11.75l12 12-12 12z"></path></g></svg>
              </a>
              <a href="<?php echo $biz['map_link']; ?>" target="_blank" class="maps">
                <div class="icon"></div>
                <div class="content">
                  <h1><?php echo $txt['View Business'][3]; ?></h1>
                  <span><?php echo $biz['map_title']; ?></span>
                </div>
                <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"><g class="nc-icon-wrapper" fill="#444444"><path d="M17.17 32.92l9.17-9.17-9.17-9.17L20 11.75l12 12-12 12z"></path></g></svg>
              </a>
              <a href="tel:<?php echo $biz['tel']; ?>" class="phone">
                <div class="icon"></div>
                <div class="content">
                  <h1><?php echo $txt['View Business'][4]; ?></h1>
                  <span><?php echo $biz['tel']; ?></span>
                </div>
                <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"><g class="nc-icon-wrapper" fill="#444444"><path d="M17.17 32.92l9.17-9.17-9.17-9.17L20 11.75l12 12-12 12z"></path></g></svg>
              </a>
            </nav>
          </div>
        </div>
	</body>
</html>
