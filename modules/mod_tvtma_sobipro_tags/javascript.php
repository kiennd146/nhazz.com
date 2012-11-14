<?php
$document =& JFactory::getDocument();
JHTML::_("behavior.mootools");
JHTML::_( 'behavior.modal' );
JHTML::_('behavior.formvalidation');
$content = 'window.addEvent( "domready", function() {
    var sectionId = $("jform_params_sectionId").value;
    if(sectionId) {
        $$(".mod_tvtma_sobipro_option").each(function(item){
            if(!item.hasClass( "mod_tvtma_sobipro_sec_" +  sectionId)) {
                item.hide();
            } else {
                item.show();
            }
        });
    }
    $("jform_params_sectionId").addEvent("change", function(e){
        var sectionId = $("jform_params_sectionId").value;
        $$(".mod_tvtma_sobipro_option").each(function(item){
            if(!item.hasClass( "mod_tvtma_sobipro_sec_" +  sectionId)) {
                item.hide();
            } else {
                item.show();
            }
        });
    });
    
    
})';
$document->addScriptDeclaration( $content );
?>
