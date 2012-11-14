<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$baseurl = JURI::base();
$document = &JFactory::getDocument();
$document->addScript( $baseurl.'components/com_tvtma1080/js/jquery.js' );
$document->addScript( $baseurl.'components/com_tvtma1080/js/jquery.bpopup-0.7.0.min.js' );
$document->addCustomTag( '<script type="text/javascript">jQuery.noConflict();</script>' );
?>

