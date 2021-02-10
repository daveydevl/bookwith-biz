<?php
    if (isset($_POST['txn_type'])) {
        ?>
        
        <div class="mui-panel">
            <div class="mui-form">
                <h4><?php echo $txt['Account'][4]; ?></h4>
                <hr />
                <button type="button" onclick="window.location='/account'" class="mui-btn mui-btn--raised mui-btn--primary"><?php echo $txt['Account'][5]; ?></button>
            </div>
        </div>
        
        <?php
        
        exit;
    }
?>

<div class="mui-panel">

<div class="mui-form">

<h4><?php echo sprintf($txt['Account'][1], '<b>' . $business['email'] . '</b>'); ?></h4>

<?php

if ($business['sub_active']) {
            
    ?>
    
    <h4><?php echo $txt['Account'][2]; ?><br>
    
    <?php echo sprintf($txt['Account'][3], 
    
        '<b>' . date('F j, Y', $business['sub_resub']) . '</b>',
        '<b>' . $business['sub_amt'] . ' CAD</b>'); 
    
    ?></h4>
    <hr />
    
    <button type="button" onclick="window.location='https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=FSMNTJ85586JA'" class="mui-btn mui-btn--raised mui-btn--accent"><?php echo $txt['Account'][9]; ?></button>

    <?php

} else {
    
    
    if (time() > $business['sub_expire']) {
        ?>
        
        <h4><?php echo $txt['Account'][6]; ?><br><br>    
        <?php echo $txt['Account'][7]; ?>
        </h4>
        
        <?php
        
    } else {
        ?>
        
        <h4><?php echo sprintf($txt['Account'][8], '<b>' . date('F j, Y', $business['sub_expire']) . '</b>'); ?><br><br>
        <?php echo $txt['Account'][7]; ?>
        </h4>
        
        <?php
    }
    ?>
    <hr />
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="T8G87B4ZNVHXN">
        <input type="hidden" name="on0" value="">
        
        <div class="mui-select">
            <select name="os0" required>
                <option value="30 days"><?php echo $txt['Plans'][1]; ?></option>
                <option value="365 days"><?php echo $txt['Plans'][2]; ?></option>
            </select>
            <label><?php echo $txt['Plans'][3]; ?></label>
        </div>
        
        <input type="hidden" name="custom" value="<?php echo $business['email']; ?>">
        <input type="hidden" name="currency_code" value="CAD">
        <button type="submit" class="mui-btn mui-btn--raised mui-btn--accent"><?php echo $txt['Plans'][4]; ?></button>

    </form>

    <?php

}

?>
</div>

</div>

<div class="mui-panel">
    <form class="mui-form" method="post" action="">
        <input type="hidden" name="handler" value="post_set_language">
        <div class="mui-select">
            <select name="language" required>
                <?php
                foreach ($languages as $lang => $code)
                    if ($business['language'] == $code)
                        echo '<option value="' . $code . '" selected>' . $lang . '</option>';
                    else
                        echo '<option value="'. $code . '">' . $lang . '</option>';
                ?>
            </select>
            <label><?php echo $txt['Language'][1]; ?></label>
        </div>
        
        <button type="submit" name="save_btn" class="mui-btn mui-btn--raised mui-btn--primary"><?php echo $txt['Language'][2]; ?></button>
    </div>
</div>