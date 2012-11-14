<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// Include the syndicate functions only once
$sectionId  = $params->get('sectionId');
require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) ) );
Sobi::Init( JPATH_ROOT, JFactory::getConfig()->getValue( 'config.language' ), $sectionId);
require_once( dirname(__FILE__).DS.'helper.php' );
$menus = modTVTMASliderHelper::getMenu($params);
require( JModuleHelper::getLayoutPath( 'mod_tvtma_slider' ) );
?>