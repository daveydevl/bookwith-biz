<?php

if (empty($_POST['event_id']))
    return;

$event_id = $_POST['event_id'];
$datastore->deleteEvent($business['email'], $event_id);

exit;