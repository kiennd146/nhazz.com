<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
if (!empty($list)) :
?>
<ul>
<?php foreach($list as $actid=>$text): ?>
	<li <?php if (JRequest::getInt("actid")==$actid): ?>class="active"<?php endif ?>><a href="<?php echo JRoute::_(VitabookHelperRoute::getActivityRoute($actid)) ?>"><?php echo $text ?></a></li>
<?php endforeach; ?>
<?php endif; ?>
</ul>
