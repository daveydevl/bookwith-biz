<?php

$required = [
    'biz_subdomain',
    'biz_name',
    'biz_logo',
    'biz_desc',
    'biz_map_title',
    'biz_map_link',
    'biz_tel',
    'biz_color'
];

foreach ($required as $field)
    if (empty($_POST[$field]))
        return;
    else
        ${$field} = trim($_POST[$field]);

$len = strlen($biz_subdomain);
if ($len < 4 || $len > 63)
    return;

if (!ctype_alnum($biz_subdomain))
    return;

if (strlen($biz_name) > 64)
    return;

if (strlen($biz_logo) > 64)
    return;

if (strlen($biz_desc) > 128)
    return;

if (strlen($biz_map_title) > 256)
    return;

if (strlen($biz_map_link) > 256)
    return;

if (strlen($biz_tel) > 32)
    return;

if (!isset($colors[$biz_color]))
    return;
    
$biz_subdomain = strtolower($biz_subdomain);

$fetched = $datastore->fetchBusinessByDomain($biz_subdomain);

if (isset($fetched) && $fetched['email'] != $business['email']) {
    $user_email_part = explode('@', $business['email'])[0];
    $biz_subdomain = preg_replace("/[^a-z0-9]/", '', $user_email_part);
    
    echo '<div class="mui-panel">' . $txt['Edit Business Save'][2] . '</div>';
}

$changes = [
    'subdomain' => $biz_subdomain,
    'name'      => $biz_name,
    'logo'      => $biz_logo,
    'desc'      => $biz_desc,
    'map_title' => $biz_map_title,
    'map_link'  => $biz_map_link,
    'tel'       => $biz_tel,
    'color'     => $biz_color
];

foreach ($changes as $index => $change)
    $business[$index] = $change;
    
$datastore->update($business);

?>

<div class="mui-panel"><?php echo sprintf($txt['Edit Business Save'][1], 

    '<a style="color:#2196F3" href="https://' .
    $biz_subdomain .
    '.bookwith.biz" target="_blank">https://' .
    $biz_subdomain .
    '.bookwith.biz</a>'

); ?></div>




















