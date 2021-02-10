<?php

$fields = [
    'subdomain',
    'name',
    'logo',
    'desc',
    'map_title',
    'map_link',
    'tel',
    'color'
];

$biz = [];

if (isset($business))
    foreach ($fields as $field)
        $biz[$field] = htmlentities($business[$field]);
else
    foreach ($fields as $field)
        $biz[$field] = '';
        
if (empty($biz['subdomain'])) {
    $user_email_part = explode('@', $user_email)[0];
    $biz['subdomain'] = preg_replace("/[^a-z0-9]/", '', $user_email_part);
}

if (empty($biz['color']))
    $biz['color'] = 'Green';

if (empty($biz['logo']))
    $biz['logo'] = 'https://bookwith.biz/assets/images/default.png';

?><div class="mui-panel">
      
    <form class="mui-form" method="post" action="">
        <input type="hidden" name="handler" value="post_edit_business">
        
        <div class="mui-textfield">
            <input type="text" name="biz_subdomain" minlength="4" maxlength="63" pattern="[a-zA-Z0-9]+" value="<?php echo $biz['subdomain']; ?>" oninvalid="this.setCustomValidity('Use only letters and numbers. Minimum four characters.')" oninput="this.setCustomValidity('')" required>
            <label><?php echo $txt['Edit Business'][1]; ?></label>
        </div>
        
        <div class="mui-textfield">
            <input type="text" name="biz_name" maxlength="64" value="<?php echo $biz['name']; ?>" autofocus required>
            <label><?php echo $txt['Edit Business'][2]; ?></label>
        </div>
        
        <div class="mui-textfield">
            <input type="file" onchange="upload(this.files[0])" id="file_upload">
            <img id="biz_logo_disp" src="<?php echo $biz['logo']; ?>" style="width:100px;height:100px;border:1px lightGray solid" onclick="$('#file_upload').trigger('click')">
            <input type="hidden" name="biz_logo" id="biz_logo" value="<?php echo $biz['logo']; ?>">
            <label><?php echo $txt['Edit Business'][3]; ?></label>
        </div>
        
        <div class="mui-textfield">
            <input type="text" name="biz_desc" maxlength="128" value="<?php echo $biz['desc']; ?>" required>
            <label><?php echo $txt['Edit Business'][4]; ?></label>
        </div>
        
        <div class="mui-textfield">
            <input id="autocomplete" type="text" placeholder="" name="biz_map_title" value="<?php echo $biz['map_title']; ?>" required>
            <label><?php echo $txt['Edit Business'][5]; ?></label>
            <input type="hidden" name="biz_map_link" id="biz_map" onfocus="geolocate()" value="<?php echo $biz['map_link']; ?>">
        </div>
        
        <div class="mui-textfield">
            <input type="tel" name="biz_tel" id="biz_tel" maxlength="32" value="<?php echo $biz['tel']; ?>" required>
            <label><?php echo $txt['Edit Business'][6]; ?></label>
        </div>
        
        <div class="mui-select">
            <select name="biz_color" required>
                <?php
                $color_index = 1;
                
                foreach ($colors as $color_name => $color) {
                    if ($biz['color'] == $color_name)
                        echo '<option value="' . $color_name . '" selected>' . $txt['Colors'][$color_index] . '</option>';
                    else
                        echo '<option value="' . $color_name . '">' . $txt['Colors'][$color_index] . '</option>';
                    
                    $color_index++;
                }
                ?>
            </select>
            <label><?php echo $txt['Edit Business'][7]; ?></label>
        </div>
        
        <button id="submit_button" type="submit" class="mui-btn mui-btn--raised mui-btn--primary"><?php echo $txt['Edit Business'][8]; ?></button>
    </form>

</div>

<script>

var autocomplete;

function initAutocomplete() {
    
  autocomplete = new google.maps.places.Autocomplete(
      document.getElementById('autocomplete'),
      {types: ['geocode']}
  );
      
  autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
  var place = autocomplete.getPlace();

  document.getElementById("biz_map").value = place.url;
}

function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      autocomplete.setBounds(circle.getBounds());
    });
  }
}

function upload(file) {
    
    if (!file || !file.type.match(/image.*/)) {
        alert('The file you uploaded is not an image. Please upload an image.');
        
        return;
    }
    
    var logo_disp     = document.getElementById('biz_logo_disp');
    var logo_hidden   = document.getElementById('biz_logo');
    var submit_button = document.getElementById('submit_button');

    logo_disp.src = 'https://i.imgur.com/AQM9upK.gif';
    submit_button.disabled = true;
    
    var fd = new FormData();
    fd.append('image', file);
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'https://imgur-apiv3.p.mashape.com/3/image');
    xhr.onload = function() {
        var link = JSON.parse(xhr.responseText).data.link;
        
        logo_hidden.value = link;
        logo_disp.src = link;
        submit_button.disabled = false;
    }
    
    xhr.setRequestHeader('Authorization', 'Client-ID 63fc7d14e5e2525');
    xhr.setRequestHeader('X-Mashape-Key', 'lim7Afe3YNmshhsGOl3GFk4sGp5hp1wEcRtjsn5xIcAWYEnOI4');
    xhr.send(fd);
}

$(document).ready(function() {
    $('#biz_tel').mobilePhoneNumber(({allowPhoneWithoutPrefix: '+1'}));
});

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4UNTM30PUlYteXe6jHYiTPMwIlzZVdrc&libraries=places&callback=initAutocomplete"
        async defer></script>

