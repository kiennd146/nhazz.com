<?php
/*
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
	
$document =& JFactory::getDocument();
$modulePath = JURI::base() . 'modules/mod_dmt_social/';

// add the stylesheet
if($params->get('addCSS')){
	$document->addStylesheet($modulePath.'css/dmt_social.css');
}

// Parameters
$websites = $params->get('websites');
$iconSize = $params->get('iconSize');
$addRelMe = $params->get('addRelMe');
$addRelNofollow = $params->get('addRelNofollow');
$RSS = $params->get('RSS');
$targetBlank = $params->get('targetBlank');

// Check for rel attributes
$rel = '';
if($addRelMe || $addRelNofollow){
	$rel .= ' rel="';
	if($addRelMe && $addRelNofollow){ $rel .= "me "; }
	else if($addRelMe){ $rel .= "me"; }
	if($addRelNofollow){ $rel .= "nofollow"; }
	$rel .= '"';
}
?>

<ul class="dmt-social-links<?php echo $params->get('moduleclass_sfx'); ?> dmt-icons-<?php echo $iconSize; ?>">
	<?php foreach( $websites as $key => $website ): ?>
	<li class="<?php echo $website->icon; ?>">
		<a title="<?php echo $website->alt; ?>"<?php echo $rel; ?> href="<?php echo $website->url; ?>"<?php if($targetBlank) echo ' target="_blank"'; ?>>
			<img src="<?php echo $modulePath.'icons/'.$website->icon.'_'.$iconSize.'.png'; ?>" alt="" />
		</a>
	</li>
	<?php endforeach; ?>	

	<?php if ($RSS && !is_null($RSSlink)): ?>
	<li class="rss">
		<a href="<?php echo $RSSlink; ?>">
			<img src="<?php echo $modulePath.'icons/rss_'.$iconSize.'.png'; ?>" alt="" />
		</a>
	</li>
	<?php endif; ?>
</ul>
	