<?php
/**
 * @version: $Id: mod_sobipro_entries.php 1759 2011-08-02 14:54:17Z Radek Suski $
 * @package: SobiPro Entries Module Application
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2011 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/gpl.html GNU/GPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU General Public License version 3
 * ===================================================
 * $Date: 2011-08-02 16:54:17 +0200 (Di, 02 Aug 2011) $
 * $Revision: 1759 $
 * $Author: Radek Suski $
 */

 defined('_JEXEC') || die( 'Direct Access to this location is not allowed.' );
 JHTML::_('behavior.mootools');
require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) ) );
Sobi::Init( JPATH_ROOT, JFactory::getConfig()->getValue( 'config.language' ), $params->get( 'sid' ) );
require_once dirname( __FILE__ ).'/helper.php';
require_once dirname( __FILE__ ).'/view.php';
if ($params->get( 'sid' )==72){
$sId =  JRequest::getVar('sid');
$pId =  JRequest::getVar('pid');
$task = JRequest::getVar('task');}
if(isset($sId)) {
   $params->set('sidT', $sId);
   $params->set('pidT', $pId);
   $params->set('typeT', 'Category');
   $params->set('taskT', $task);
} else {
    $params->set('typeT', 'Section');
}
$document	= &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'modules/mod_tvtma_sobipro_entries/includes/css/entries.css');
$document->addStyleSheet(JURI::root().'modules/mod_tvtma_sobipro_entries/includes/css/ddmegamenu.css');
if( $params->get( 'filter' )){
$document->addScript(JURI::root().'modules/mod_tvtma_sobipro_entries/includes/ddmegamenu.js');
}
SPEntriesMod::ListEntries( $params );

