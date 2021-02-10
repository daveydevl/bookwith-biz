<div class="mui-panel"><div class="mui-form"><ul id="faq-list">
<?php
    $lang = strtolower($business['language']);

    if (!in_array($lang, $languages))
        $lang = 'en';

    include 'help/' . $lang . '.html';
?>
</ul></div></div>
<script>
$('#faq-list').faqGenerator({
	theme: 'material',
	limitOne: true,
	startOpen: false,
	icon: true
});
</script>






