<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$baseurl = JURI::base();
$document = &JFactory::getDocument();
$document->addStyleSheet($baseurl.'modules/mod_tvtma_sobipro_tags/includes/mod_tvtma_sobipro_tags.css');
?>
<div class="tvtmasobiprotags">
<?php
foreach ($tags as $value) {
    //echo JHTML::link(JRoute::_('index.php?option=com_tvtma1080&task=getTags&view=tvtmasobiprotags&fieldId='.$fiedId.'&tag='. trim($value)), $value) . "  ";
    echo JHTML::link(JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for='. trim($value) .'&'. $nid .'&sid=' . $sectionId . '&spsearchphrase=exact'), $value);
}
?>
</div>