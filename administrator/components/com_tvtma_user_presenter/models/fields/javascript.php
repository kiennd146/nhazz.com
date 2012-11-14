<?php
$document =& JFactory::getDocument();
JHTML::_("behavior.mootools");
JHTML::_( 'behavior.modal' );
JHTML::_('behavior.formvalidation');
$content = 'window.addEvent( "domready", function() {
    var sectionId = $("jform_sectionId").value;
    if(sectionId) {
        $$(".mod_tvtma_slider_option").each(function(item){
            if(!item.hasClass( "mod_tvtma_slider_sec_" +  sectionId)) {
                item.hide();
            } else {
                item.show();
            }
        });
    }
    $("jform_sectionId").addEvent("change", function(e){
        var sectionId = $("jform_sectionId").value;
        $$(".mod_tvtma_slider_option").each(function(item){
            if(!item.hasClass( "mod_tvtma_slider_sec_" +  sectionId)) {
                item.hide();
            } else {
                item.show();
            }
        });
    });
    
    
})';
$document->addScriptDeclaration( $content );
?>
