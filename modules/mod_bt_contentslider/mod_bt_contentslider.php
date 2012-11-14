<?php
/**
 * @package 	mod_bt_contentslider - BT ContentSlider Module
 * @version		1.4
 * @created		Oct 2011

 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2011 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
//echo "Start : " . time() . "<br/>" ;
require_once JPATH_SITE.DS.'modules'.DS.'mod_bt_contentslider'.DS.'helpers'.DS.'helper.php';
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
//Get list content
$list = modBtContentSliderHelper::getList( $params, $module );
// ROW * COL
$itemsPerRow = (int)$params->get( 'items_per_rows', 3 );
$itemsPerCol = (int)$params->get( 'items_per_cols', 1 );
$moduleclass_sfx = $params->get('moduleclass_sfx');
$left = $params->get('left',50);
$right = $params->get('right', 100 - $left);
$imgClass = $params->get('hovereffect',1)? 'class= "hovereffect"':'';
//Num of item display
$maxPages = $itemsPerRow*$itemsPerCol;
//Get pages list array
$pages = array_chunk( $list, $maxPages  );
//Get total pages
$totalPages = count($pages);
// calculate width of each row. (percent)
$itemWidth = floor(100/$itemsPerRow -0.1);
$tmp = $params->get( 'module_height', 'auto' );
$moduleHeight   =  ( $tmp=='auto' ) ? 'auto' : ((int)$tmp).'px';
$tmp = $params->get( 'module_width', 'auto' );
$moduleWidth    =  ( $tmp=='auto') ? 'auto': ((int)$tmp-2).'px';
$moduleWidthWrapper = ( $tmp=='auto') ? 'auto': (int)$tmp.'px';
//Get Open target
$openTarget 	= $params->get( 'open_target', '_parent' );
//auto_start
$auto_start 	= $params->get('auto_start',1);
//butlet and next back
$next_back 		= $params->get( 'next_back', 0 );
$butlet 		= $params->get( 'butlet', 1 );
//Option for content
$showReadmore = $params->get( 'show_readmore', '1' );
$showTitle = $params->get( 'show_title', '1' );

$show_category_name = $params->get( 'show_category_name', 0 );
$show_category_name_as_link = $params->get( 'show_category_name_as_link', 0 );

$showDate = $params->get( 'show_date', '0' );
$showAuthor = $params->get( 'show_author', '0' );
$show_intro = $params->get( 'show_intro', '0' );

//Option for image
$thumbWidth    = (int)$params->get( 'thumbnail_width', 200 );
$thumbHeight   = (int)$params->get( 'thumbnail_height', 150 );
$thumbnail_max_height = (int)$params->get( 'thumbnail_max_height', 400 );
$image_crop = $params->get( 'image_crop', '0' );
$show_image = $params->get( 'show_image', '0' );
modBtContentSliderHelper::fetchHead( $params );
//Get tmpl
$tmp = $params->get( 'template', "nhazz" );
//echo "Next : " . time() . "<br/>" ;
require( JModuleHelper::getLayoutPath($module->module, $tmp) );
require( JModuleHelper::getLayoutPath($module->module,"slider") );
unset($list);
unset($pages);
//echo "End : " . time() . "<br/>" ;
?>

