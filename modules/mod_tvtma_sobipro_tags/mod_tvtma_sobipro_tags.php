<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );
$sectionId = $params->get('sectionId');
$fiedId = $params->get('fieldId');
$tags = modTvtSobiproTagHelper::getTag($fiedId);
$nid = modTvtSobiproTagHelper::getNid($fiedId);
require( JModuleHelper::getLayoutPath( 'mod_tvtma_sobipro_tags' ) );
?>