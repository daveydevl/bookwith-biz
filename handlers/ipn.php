<?php

if (empty($_POST))
    exit;

include 'vendor/autoload.php';
include 'includes/datastore.php';
include 'includes/paypal_ipn.php';

$datastore = new Datastore();

$ipn = new PaypalIPN();
//$ipn->useSandbox();

$verified = $ipn->verifyIPN();
if (!$verified || empty($_POST['custom']))
    exit;

if (empty($_POST['custom']))
    exit;

$business = $datastore->fetchBusinessByEmail($_POST['custom']);
if (empty($business))
    exit;

switch ($_POST['txn_type']) {
    case 'subscr_payment':
    
        $business['sub_active'] = true;
        $business['sub_amt'] = $_POST['mc_gross'];
        
        $payment_date = strtotime($_POST['payment_date']);
        
        $next = 2592000;
        if ($business['sub_amt'] == '50.00')
            $next = 31536000;
        
        //resub date 30 or 365 days after payment
        $business['sub_resub'] = $payment_date + $next;
        
        if ($business['sub_expire'] > time())
            $business['sub_expire'] += $next;
        else
            $business['sub_expire'] = $business['sub_resub'] + 259200;
    
        break;
    
    case 'subscr_failed':
    case 'subscr_eot':
    case 'subscr_cancel':
        $business['sub_active'] = false;
}

$datastore->update($business);
