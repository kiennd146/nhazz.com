<?php
/*
* Version			: 1.1
* Created by		: Demente Design
* Email				: contact@demente-design.com
* Created on		: september 2010
* Last Modified 	: june 2011
* URL				: http://demente-design.com
* License			: GPLv2
* Copyright			: 2011 demente-design.com
* License 			: GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
* 
* The icons included by default in the package are from www.komodomedia.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once dirname(__FILE__).DS.'helper.php';

$RSSlink = modSyndicateHelper::getLink($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_dmt_social', $params->get('layout', 'default'));
?>