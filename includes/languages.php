<?php

//'简体中文' => 'zh-cn'
//'日本語'   => 'ja'

$languages = [
    'English' => 'en',
    '简体中文' => 'zh-cn'
];

$same_lang = [
    '简体中文' => 'zh-cn'
];

function loadLang($lang) {
    global $languages;
    
    if (!in_array($lang, $languages))
        $lang = 'en';
    
    return parse_ini_file('languages/' . $lang . '.ini', true);
}

function startsWith($test, $sub) {
    return strtolower(substr($test, 0, strlen($sub))) == strtolower($sub);
}

function matchLang($header) {
    global $languages;
    global $same_lang;
    
    foreach ($languages as $language)
        if (startsWith($header, $language))
            return $language;
    foreach ($same_lang as $match => $language)
        if (startsWith($header, $match))
            return $language;
    
    return 'en';
}
