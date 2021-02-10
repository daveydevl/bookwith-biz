<?php

$slot_size = 30;
if (!empty($business['slot_size']))
    $slot_size = $business['slot_size'];

$days = explode(',', $txt['Calendar Settings'][3]);

$short_days = [
    'sun_times',
    'mon_times',
    'tue_times',
    'wed_times',
    'thu_times',
    'fri_times',
    'sat_times'
];

?><div class="mui-panel">
    <form class="mui-form" method="post" action="">
        <div class="mui-select">
            <?php
                $timezones = array();

                echo '<select id="timezone" name="timezone" required>';
                echo '<option value="" selected>Choose a timezone</option>';

                foreach (timezone_identifiers_list() as $timezone) {
                    $time = new DateTime(NULL, new DateTimeZone($timezone));
                    
                    if ($business['timezone'] == $timezone)
                        echo '<option value="' . $timezone . '" selected>' . $timezone . ' (' . $time->format('g:i a') . ')</option>';
                    else
                        echo '<option value="' . $timezone . '">' . $timezone . ' (' . $time->format('g:i a') . ')</option>';

                }

                echo '</select>';

            ?>
            <label><?php echo $txt['Calendar Settings'][1]; ?></label>
        </div>
        <h5><?php echo $txt['Calendar Settings'][2]; ?></h5>
        <?php
        
        foreach ($days as $day_num => $day) {
            
            $short = $short_days[$day_num];
            
            $c_times = [
                1, '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00'
            ];
        
            if (!empty($business[$short]))
                $c_times = $business[$short];
            
            $c_max = $c_times[0];
        ?>
            <input type="hidden" name="<?php echo $short; ?>[]" id="hidden_<?php echo $day_num; ?>" value="<?php echo $c_max; ?>">
            
            <?php
            
                for ($i = 0; $i < 5; $i++) {
                    
                    $c_time_start = $c_times[$i * 2 + 1];
                    $c_time_end   = $c_times[$i * 2 + 2];
                    
                    $display = $i >= $c_max ? ' style="display:none"' : '';
                    
            ?>
            <div class="mui-row" id="row_<?php echo $day_num . '_' . $i . '"'; ?>"<?php echo $display; ?>>
                <div class="mui-col-md-2" style="white-space:nowrap"><b><?php if ($i == 0) echo $day; ?></b></div>
                <div class="mui-col-md-4" style="text-align:center">
                    <?php
                        echo '<input name="' . $short . '[]" id="time_' . $day_num . '_' . $i . '_1" type="time" style="text-align:center;width:100%" value="' . $c_time_start . '" required>';
                    ?>
                </div>
                <div class="mui-col-md-1" style="text-align:center">➔</div>
                <div class="mui-col-md-4" style="text-align:center">
                    <?php
                        echo '<input name="' . $short . '[]" id="time_' . $day_num . '_' . $i . '_2" type="time" style="text-align:center;width:100%" value="' . $c_time_end . '" required>';
                    ?>
                </div>
                
            </div>
            <?php
                }
            ?>
            <div class="mui-row" id="row_<?php echo $day_num; ?>_5">
                <div class="mui-col-md-2" style="white-space:nowrap<?php if ($c_max > 0) echo ';visibility:hidden'; ?>"><b><?php echo $day; ?></b></div>
                <div class="mui-col-md-9">
                    <input type="button" value="<?php echo $txt['Calendar Settings'][5]; ?>" id="add_<?php echo $day_num; ?>"<?php if ($c_max == 5) echo ' disabled'; ?>>
                    <input type="button" value="<?php echo $txt['Calendar Settings'][6]; ?>" id="del_<?php echo $day_num; ?>"<?php if ($c_max == 0) echo ' disabled'; ?>>
                    <select id="select_<?php echo $day_num; ?>">
                        <option value="-1"><?php echo $txt['Calendar Settings'][7]; ?></option>
                        <?php
                            foreach ($days as $index => $_day)
                                if ($_day != $day)
                                    echo '<option value="' . $index . '">' . $_day . '</option>';
                        ?>
                    </select>
                </div>
            </div>
            <hr />
        <?php
        }
        ?>
        
        <div class="mui-select">
            <select name="slot_size" required>
            <?php
                for ($i = 5; $i <= 120; $i += 5)
                    if ($i == $slot_size)
                        echo '<option selected>' . $i . '</option>';
                    else
                        echo '<option>' . $i . '</option>';
            ?>
            </select>
            <label><?php echo $txt['Calendar Settings'][4]; ?></label>
        </div>
        
        <input type="hidden" name="handler" value="post_calendar_settings">
        <button type="submit" name="save_btn" class="mui-btn mui-btn--raised mui-btn--primary"><?php echo $txt['Calendar Settings'][8]; ?></button>
    </form>
        
    <script>
    
    function enableTimes(state, day) {
        state = !state;
        
        document.getElementById("start_" + day).disabled = state;
        document.getElementById("end_" + day).disabled = state;
    }
    
    var tz = document.getElementById('timezone');
    if (!tz.value)
        tz.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    
    $(document).ready(function() {
       for (let i = 0; i < 7; i++) {
            
            $('#add_' + i).click(function() {
                var amount = parseInt($('#hidden_' + i).val()) + 1;
                $('#hidden_' + i).val(amount);
                
                $('#del_' + i).prop('disabled', false);
                $('#row_' + i + '_5 .mui-col-md-2').css('visibility', 'hidden');
                
                if (amount == 5)
                    $(this).prop('disabled', true);
                
                for (var x = 0; x < 5; x++)
                    $('#row_' + i + '_' + x).hide();
                
                for (var x = 0; x < amount; x++)
                    $('#row_' + i + '_' + x).show();
            });
            
            $('#del_' + i).click(function() {
                var amount = parseInt($('#hidden_' + i).val()) - 1;
                $('#hidden_' + i).val(amount);
                
                $('#add_' + i).prop('disabled', false);
                
                if (amount == 0) {
                    $(this).prop('disabled', true);
                    $('#row_' + i + '_5 .mui-col-md-2').css('visibility', 'visible');
                }
                
                for (var x = 0; x < 5; x++)
                    $('#row_' + i + '_' + x).hide();
                
                for (var x = 0; x < amount; x++)
                    $('#row_' + i + '_' + x).show();
            });
            
            $('#select_' + i).change(function() {
                var copy = $(this).val();
                var amount = parseInt($('#hidden_' + copy).val());
                
                $('#hidden_' + i).val(amount);
                $('#add_' + i + ',#del_' + i).prop('disabled', false);
                $('#row_' + i + '_5 .mui-col-md-2').css('visibility', 'hidden');
                
                if (amount == 5)
                    $('#add_' + i).prop('disabled', true);
                if (amount == 0) {
                    $('#del_' + i).prop('disabled', true);
                    $('#row_' + i + '_5 .mui-col-md-2').css('visibility', 'visible');
                }
                
                for (var x = 0; x < 5; x++) {
                    $('#row_' + i + '_' + x).hide();
                    
                    $('#time_' + i + '_' + x + '_1').val($('#time_' + copy + '_' + x + '_1').val());
                    $('#time_' + i + '_' + x + '_2').val($('#time_' + copy + '_' + x + '_2').val());
                }
                
                for (var x = 0; x < amount; x++)
                    $('#row_' + i + '_' + x).show();
                
                
                $(this).val("-1");
            });
       }
        
        
    });
    
    </script>
    
</div>
