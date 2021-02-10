<?php

if (empty($_POST['language']))
    return;

$lang = matchLang($_POST['language']);
$business['language'] = $lang;
$datastore->update($business);

$txt = loadLang($lang);

?><div class="mui-panel"><?php echo $txt['Language'][3]; ?></div>