<?php

$required = [
    'name', 'phone', 'start', 'end'
];

foreach ($required as $field)
    if (!isset($_POST[$field]))
        return;

date_default_timezone_set($business['timezone']);

$datastore->makeEvent(
    $business['email'],
    strtotime($_POST['start'] . date('P')),
    strtotime($_POST['end'] . date('P')),
    $_POST['name'],
    $_POST['phone']
);

exit;