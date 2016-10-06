<?php
$document = JFactory::getDocument(); 
$document->addStyleSheet('modules/mod_instagram/css/mod_instagram.css');
$document->addScript('modules/mod_instagram/js/mod_instagram.js');
$module = JModuleHelper::getModule('mod_instagram');
$moduleParams = new JRegistry();
$moduleParams->loadString($module->params);
$token = $moduleParams->get('token');
$username = $moduleParams->get('username');
?>
<div id="instagram">
</div>
<div class="outstanding-footer">
        <a href="https://www.instagram.com/ufrb_edu" class="outstanding-link">
            <span class="text">Mais Fotos</span>
            <span class="icon-box">                                          
              <i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i>
            </span>
        </a>
    </div>
<script>
jQuery(function($){
    $('#instagram').recentInstagramUploads({
        access_token: '<?php echo $token ?>',
        ig_username:  '<?php echo $username ?>'
    });

});
</script>