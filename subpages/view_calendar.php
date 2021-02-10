<?php

$start_time = time();
$end_time   = $start_time + 2592000;
$events = $datastore->getEvents($business['email'], $start_time, $end_time);

$output = [];

if (!empty($business['timezone']))
    date_default_timezone_set($business['timezone']);

foreach ($events as $event) {
    
    $start = $event['start'];
    $end   = $event['end'];
    $name  = $event['name'];
    $phone = $event['phone'];
    
    $tmp = [
        'title' => $name,
        'start' => date('c', $start),
        'end'   => date('c', $end),
        'phone' => $phone,
        'id'    => $start
    ];
    
    if (empty($name))
        $tmp['color'] = 'red';
    
    $output[] = $tmp;
}

$minMax = getMinMax($business);

?><div class="mui-panel" style="min-height:100px">

<div id='calendar'></div>

</div> 
    
<style>
    .sright {
        float: right;
    }
</style>
<!--<script src="https://www.gstatic.com/firebasejs/5.2.0/firebase.js"></script>-->
<script>
    /*firebase.initializeApp({
        apiKey: "AIzaSyAhR22TBGd1NLxLOrtG9Ap-3iWjlyj1hnQ",
        authDomain: "bookwith-biz-realtime.firebaseapp.com",
        databaseURL: "https://bookwith-biz-realtime.firebaseio.com",
        projectId: "bookwith-biz-realtime",
        storageBucket: "bookwith-biz-realtime.appspot.com",
        messagingSenderId: "24186837986"
    });*/
    
    var once = false;
    var events = <?php echo json_encode($output); ?>;

    /*firebase.database().ref('users/<?php echo md5($business['email']); ?>').on('value', function(stamp) {
        if (once == true)
            location.reload();
        once = true;
    });*/
    
    function delEvt(cal_id, start) {
        var dialog = confirm('<?php echo $txt['Appointment Details'][8]; ?>');
        
        if (!dialog)
            return;
        
        mui.overlay('off');
        showLoading();
        
        $.post('/', {
            handler: 'post_delete_event',
            event_id: start  
        }, function(data) {
             $('#calendar').fullCalendar('removeEvents', cal_id);
             mui.overlay('off');
        });
    }
    
    function showLoading() {
        var modalEl = document.createElement('div');
        modalEl.innerHTML = '<img src="https://i.imgur.com/AQM9upK.gif">';
        modalEl.style.margin   = '100px auto';
        modalEl.style.maxWidth = '100px';
        
        mui.overlay('on', {static: true}, modalEl); 
    }
    
    function toggleDetails(that) {
        $('#client_name_row,#client_tel_row').toggle(!that.checked);
        $('#client_name,#client_tel').prop('disabled', that.checked);
    }
    
    function addEvent() {
        var is_break       = $('#is_break').prop('checked');
        var client_name    = $('#client_name').val();
        var client_tel     = $('#client_tel').val();
        var client_start   = $('#client_start').val();
        var client_end     = $('#client_end').val();
        
        var event = {
            title : client_name,
            start : client_start,
            end   : client_end,
            id    : moment(client_start).valueOf() / 1000,
            phone : client_tel
        };
        
        if (is_break) {
            client_name = '';
            client_tel  = '';
            
            event.color = 'red';
        }
        
        mui.overlay('off');
        showLoading();
        
        $.post('/', {
            handler : 'post_add_event',
            name    : client_name,
            phone   : client_tel,
            start   : client_start,
            end     : client_end
        }, function(data) {
            $('#calendar').fullCalendar('renderEvent', event);
            mui.overlay('off');
        });
        
        return false;
    }
    
    function showPrint(evt_id) {
        var w = 800;
        var h = 600;
        
        var x = window.top.outerWidth / 2 + window.top.screenX - ( w / 2);
        var y = window.top.outerHeight / 2 + window.top.screenY - ( h / 2);
        
        var evt = $("#calendar").fullCalendar('clientEvents', evt_id)[0];
        
        var myWindow = window.open('', '', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=' + w + ',height=' + h + ',top=' + y + ',left=' + x);
        
        myWindow.document.write('<p><?php echo $txt['Appointment Booked'][1]; ?><br><br>\
                    <b><?php echo $txt['Appointment Booked'][2]; ?></b> ' + evt.start.format('dddd, MMMM Mo, YYYY') + '<br>\
                    <b><?php echo $txt['Appointment Booked'][3]; ?></b> ' + evt.start.format('h:mma') + ' - ' + evt.end.format('h:mma') + '<br>\
                    <b><?php echo $txt['Appointment Booked'][4]; ?></b> <?php echo $business['map_title']; ?><br>\
                    <b><?php echo $txt['Appointment Booked'][5]; ?></b> <?php echo $business['tel']; ?>');

        myWindow.document.close();
        myWindow.focus();
        myWindow.print();
        myWindow.close();
    }
    
    function showEvent(event) {
        var modalEl = document.createElement('div');
        var msg1 = '<?php echo $txt['Appointment Details'][1]; ?>';
        var msg2 = '<?php echo $txt['Break Details'][1]; ?>';
        var msg3 = msg1;
        var details = '<hr />\
                <h4><b><?php echo $txt['Appointment Details'][4]; ?> </b>' + event.title + '</h4>\
                <h4><b><?php echo $txt['Appointment Details'][5]; ?> </b><a href="tel:' + event.phone + '">' + event.phone + '</a></h4>';
        var print_btn = '<button type="button" class="mui-btn mui-btn--raised" style="float:right" onclick="showPrint(\'' + event._id + '\')"><?php echo $txt['Appointment Details'][9]; ?></button>';
        
        if (event.title == '' && event.phone == '') {
            msg3 = msg2;
            details = '';
            print_btn = '';
        }
        
        modalEl.innerHTML = '<div class="mui-panel">\
            <div class="mui-form">\
                <legend>' + msg3 + '</legend>\
                <hr />\
                <h4><b><?php echo $txt['Appointment Details'][2]; ?> </b>' + event.start.format('MMMM Do, YYYY') + '</h4>\
                <h4><b><?php echo $txt['Appointment Details'][3]; ?> </b>' + event.start.format('h:mm A') + ' - ' + event.end.format('h:mm A') + '</h4>' + details +
                '<hr />\
                <button type="button" class="mui-btn mui-btn--raised mui-btn--danger" onclick="delEvt(\'' + event._id + '\',' + event.id + ')"><?php echo $txt['Appointment Details'][6]; ?></button>\
                <button type="button" class="mui-btn mui-btn--raised" onclick="mui.overlay(\'off\')"><?php echo $txt['Appointment Details'][7]; ?></button>' + print_btn +
            '</div></div>';
        
        modalEl.style.margin   = '100px auto';
        modalEl.style.maxWidth = '400px';

        mui.overlay('on', {static: false}, modalEl);
    }
    
    function showModal(start, end) {
        var s_time = start.format('YYYY-MM-DD') + 'T' + start.format('HH:mm:ss');
        var e_time = end.format('YYYY-MM-DD') + 'T' + end.format('HH:mm:ss');
        
        var modalEl = document.createElement('div');
        modalEl.innerHTML = '<div class="mui-panel">\
            <form class="mui-form" onsubmit="addEvent()" autocomplete="off">\
                <legend><?php echo $txt['Add Appointment'][1]; ?></legend>\
                <hr />\
                <div class="mui-checkbox">\
                    <label>\
                        <input type="checkbox" onchange="toggleDetails(this)" id="is_break"><?php echo $txt['Add Appointment'][2]; ?>\
                    </label>\
                </div>\
                <div class="mui-textfield" id="client_name_row">\
                    <input type="text" name="client_name" id="client_name" required>\
                    <label><?php echo $txt['Add Appointment'][3]; ?></label>\
                </div>\
                <div class="mui-textfield" id="client_tel_row">\
                    <input type="tel" name="client_tel" id="client_tel" required>\
                    <label><?php echo $txt['Add Appointment'][4]; ?></label>\
                </div>\
                <div class="mui-textfield">\
                    <input type="datetime-local" id="client_start" value="' + s_time + '">\
                    <label><?php echo $txt['Add Appointment'][5]; ?></label>\
                </div>\
                <div class="mui-textfield">\
                    <input type="datetime-local" id="client_end" value="' + e_time + '">\
                    <label><?php echo $txt['Add Appointment'][6]; ?></label>\
                </div>\
                <button type="submit" class="mui-btn mui-btn--raised mui-btn--primary"><?php echo $txt['Add Appointment'][7]; ?></button>\
                <button type="button" class="mui-btn mui-btn--raised" onclick="mui.overlay(\'off\')"><?php echo $txt['Add Appointment'][8]; ?></button>\
            </div>\
        </div>';
        
        modalEl.style.margin   = '100px auto';
        modalEl.style.maxWidth = '400px';

        mui.overlay('on', {static: true}, modalEl);
        
        $('#client_name').focus();
        $('#client_tel').mobilePhoneNumber({allowPhoneWithoutPrefix: '+1'});
        
    }
    
    
    $(document).ready(function() {

    $('#calendar').fullCalendar({
      timezone: '<?php echo $business['timezone']; ?>',
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'agendaWeek,month,listMonth'
      },
      defaultView: 'month',
      locale: '<?php echo $business['language']; ?>',
      views: {
          agendaWeek: { buttonText: '<?php echo $txt['View Calendar'][1]; ?>' },
          month:      { buttonText: '<?php echo $txt['View Calendar'][2]; ?>' },
          listMonth:  { buttonText: '<?php echo $txt['View Calendar'][3]; ?>' }
      },
      eventRender: function( event, element, view ) {
        element.click(function() {
            showEvent(event);
        });
      },
      
      minTime: '<?php echo $minMax[0]; ?>:00',
      maxTime: '<?php echo $minMax[1]; ?>:00',
      
      allDaySlot: false,
      selectable: true,
      selectHelper: true,
      selectAllow: function(info) {
        if (info.start.add(1, 'days').isBefore(moment()))
            return false;
        
        return true;          
      },
      select: function(start, end) {
        showModal(start, end);
      },
      
      selectOverlap: false,
      longPressDelay: 0,
      
      navLinks: false,
      editable: false,
      height: 'auto',
      eventLimit: true,
      events: events
    });

  });
</script>




