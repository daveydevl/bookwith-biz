<?php

$start_time = time();
$end_time   = $start_time + 2592000;
$timezone   = $business['timezone'];
$slot_size  = $business['slot_size'];

$events = $datastore->getEvents($business['email'], $start_time, $end_time);
$busy_slots = [];

foreach ($events as $event)
    $busy_slots[] = [$event['start'], $event['end']];

$free_slots = inverse($busy_slots, $start_time, $end_time);
$free_intervals = gen_free($business, $timezone);
$free = overlap($free_slots, $free_intervals);

$times = [];
$times_index = [];

foreach ($free as $interval) {
    
    foreach (cut_interval($interval, $slot_size * 60) as $block) {
        $date       = date('Y-m-d', $block[0]);
        $time_start = date('g:ia', $block[0]);
        $time_end   = date('g:ia', $block[1]);
        
        if (!isset($times[$date]))
            $times[$date] = [];
        
        $times[$date][] = $time_start . ' - ' . $time_end;
        $times_index[$date . ' ' . $time_start . ' - ' . $time_end] = [
            'start' => date('H:i', $block[0]) . ':00',
            'end'   => date('H:i', $block[1]) . ':00',
            'date'  => date('l, F j, Y', $block[0])
        ];
        
    }
}

$disabled = [];

$ids = get_intervals($timezone);
foreach ($ids as $id => $interval)
    if (can_fit($free, $interval))
        $disabled[] = $id;
    
$submitting = true;
$taken      = false;

foreach ([
    'client_name',
    'client_tel',
    'client_date_submit',
    'client_time'
] as $field)
    if (empty($_POST[$field])) {
        $submitting = false;
        break;
    }
    
if ($submitting) {
    $date_sub = $_POST['client_date_submit'];
    $time_sub = $_POST['client_time'];
    
    $lookup   = $date_sub . ' ' . $time_sub;
    
    if (empty($times_index[$lookup]))
        $taken = true;
    else {   
        $info = $times_index[$lookup];
        
        $book_start = dateToVal($timezone, $date_sub, $info['start']);
        $book_end   = dateToVal($timezone, $date_sub, $info['end']);
        
        $datastore->makeEvent(
            $business['email'],
            $book_start,
            $book_end,
            $_POST['client_name'],
            $_POST['client_tel']
        );
        
        //get_headers('https://us-central1-bookwith-biz-realtime.cloudfunctions.net/ping?user=' . md5($business['email']));
        
?><!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <title>bookwith.biz</title>
    
    <link href="//cdnjs.cloudflare.com/ajax/libs/muicss/0.9.39/css/mui.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/styles/panel.css" rel="stylesheet" type="text/css" />
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/muicss/0.9.39/js/mui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="/assets/scripts/ics.js"></script>

    <style>
        a {
            color: black;
            text-decoration: none;
        }
        
        a:hover, li a:focus {
            text-decoration: none;
        }
        
        #content-wrapper {
            margin-left: 0px !important;
        }
    </style>
  </head>
  <body>
    <div id="content-wrapper">
        <br>
        <div class="mui-container" style="max-width:400px">
            <div class="mui-panel">
                <form class="mui-form" method="post" action="">
                    <legend><?php echo $txt['Book Appointment'][1]; ?></legend>
                    <hr />
                    <p><?php echo $txt['Appointment Booked'][1]; ?><br><br>
                    
                    <b><?php echo $txt['Appointment Booked'][2]; ?></b> <?php echo $info['date']; ?><br>
                    <b><?php echo $txt['Appointment Booked'][3]; ?></b> <?php echo $time_sub; ?><br>
                    <b><?php echo $txt['Appointment Booked'][4]; ?></b> <?php echo $business['map_title']; ?><br>
                    <b><?php echo $txt['Appointment Booked'][5]; ?></b> <?php echo $business['tel']; ?>
                    
                    
                    </p>
                    <hr />
                    <button type="button" class="mui-btn mui-btn--raised mui-btn--primary" onclick="cal.download()"><?php echo $txt['Appointment Booked'][6]; ?></button>
                    <button type="button" class="mui-btn mui-btn--raised" onclick="window.location='/'"><?php echo $txt['Appointment Booked'][7]; ?></button>
                </form>
            </div>
        </div>
    </div>
    <script>
        var data = <?php
            $data = [
                sprintf($txt['Appointment Booked'][7], $business['name']),
                $business['tel'],
                $business['map_title'],
                date('Y/m/d H:i', $book_start),
                date('Y/m/d H:i', $book_end)
            ];
            
            echo json_encode($data);
        ?>;
        
        var cal = ics();
        cal.addEvent.apply(null, data); 
    </script>
  </body>
</html>

<?php
        exit;
    }
}

?><!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <title>BookWith.biz</title>
    
    <link href="//cdn.muicss.com/mui-0.9.39/css/mui.min.css" rel="stylesheet" type="text/css" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/compressed/themes/default.css" rel="stylesheet" type="text/css" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/compressed/themes/default.date.css" rel="stylesheet" type="text/css" />
    <link href="/assets/styles/panel.css" rel="stylesheet" type="text/css" />
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/muicss/0.9.39/js/mui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/caret/1.0.0/jquery.caret.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.mobilephonenumber/1.0.7/jquery.mobilePhoneNumber.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/compressed/picker.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/compressed/picker.date.js"></script>
    
    <script>
    var times = <?php echo json_encode($times); ?>;
    
    $(document).ready(function() {
        var now = Date.now();
        
        $('#client_tel').mobilePhoneNumber({allowPhoneWithoutPrefix: '+1'});
        
        $('#client_date').pickadate({
            min: new Date(now),
            max: new Date(now + 2592000000),
            
            format: 'dddd, mmmm d, yyyy',
            formatSubmit: 'yyyy-mm-dd',
            
            disable: [<?php echo implode(',', $disabled); ?>],
            
            onSet: function(context) {
                if (!context.select) {
                    $('#client_time').html('<option disabled>No date selected</option>');
                    
                    return;
                }
                
                var date = this.get('select', 'yyyy-mm-dd');
                var opts = times[date];
                
                var new_html = '';
                
                for (var i = 0; i < opts.length; i++)
                    new_html += '<option>' + opts[i] + '</option>';
                
                $('#client_time').html(new_html);
            }
        });
    });
    
    function cancel_form() {
        if (confirm('<?php echo $txt['Book Appointment'][9]; ?>'))
            window.location = '/';
    }
    </script>
    
    <style>
        a {
            color: black;
            text-decoration: none;
        }
        
        a:hover, li a:focus {
            text-decoration: none;
        }
        
        #content-wrapper {
            margin-left: 0px !important;
        }
    </style>
  </head>
  <body>
    <div id="content-wrapper">
      <br>
      <div class="mui-container" style="max-width:400px">
        <?php
            if ($taken)
                echo '<div class="mui-panel">' . $txt['Appointment Booked'][8] . '</div>';
            
            $prefill_name = '';
            if (isset($_POST['client_name']))
                $prefill_name = htmlentities($_POST['client_name']);
            
            $prefill_tel = '';
            if (isset($_POST['client_tel']))
                $prefill_tel = htmlentities($_POST['client_tel']);
            
        ?>
        <div class="mui-panel">
            <form class="mui-form" method="post" action="">
                <legend><?php echo $txt['Book Appointment'][1]; ?></legend>
                <hr />
                <div class="mui-textfield">
                    <input type="text" name="client_name" value="<?php echo $prefill_name; ?>" autofocus required>
                    <label><?php echo $txt['Book Appointment'][2]; ?></label>
                </div>
                <div class="mui-textfield">
                    <input type="tel" name="client_tel" id="client_tel" value="<?php echo $prefill_tel; ?>" x-autocompletetype="tel" required>
                    <label><?php echo $txt['Book Appointment'][3]; ?></label>
                </div>
                <div class="mui-textfield">
                    <input type="text" name="client_date" id="client_date" required>
                    <label><?php echo $txt['Book Appointment'][4]; ?></label>
                </div>
                <div class="mui-textfield">
                    <select style="font-size:150%;margin-top:0.3em;width:100%;height:27px" name="client_time" id="client_time" required>
                        <option disabled><?php echo $txt['Book Appointment'][6]; ?></option>
                    </select>
                    <label><?php echo $txt['Book Appointment'][5]; ?></label>
                </div>
                <button type="submit" class="mui-btn mui-btn--raised mui-btn--primary"><?php echo $txt['Book Appointment'][7]; ?></button>
                <button type="button" class="mui-btn mui-btn--raised" onclick="cancel_form()"><?php echo $txt['Book Appointment'][8]; ?></button>
            </form>
        </div>
      </div>
    </div>
  </body>
</html>
