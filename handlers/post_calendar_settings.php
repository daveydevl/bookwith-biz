<?php

if (!isset($_POST['save_btn']))
    return;

$required = [
    'timezone',
    'sun_times',
    'mon_times',
    'tue_times',
    'wed_times',
    'thu_times',
    'fri_times',
    'sat_times',
    'slot_size'
];

foreach ($required as $field)
    if (empty($_POST[$field]))
        return;
   
$business['timezone'] = $_POST['timezone'];
$business['slot_size'] = intval($_POST['slot_size']);

foreach ([
    'sun_times',
    'mon_times',
    'tue_times',
    'wed_times',
    'thu_times',
    'fri_times',
    'sat_times'
] as $time)
    $business[$time] = $_POST[$time];

$datastore->update($business);

?><div class="mui-panel"><?php echo $txt['Calendar Settings Save'][1]; ?></div>

