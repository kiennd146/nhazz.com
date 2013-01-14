<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div id="featuredcs">
	<h2><?php echo JText::_('VITABOOK_MOD_VITAFEATURE_TITLE') ?></h2>
	<ul id="featuredcs_list">
		<?php foreach ($list as $message): ?>
		<?php
		$count = JComments::getCommentsCount($message->id, 'com_vitabook');
        
		?>
		<li>
			<div class="dcs_img_wrapper">
				<a href="<?php echo JRoute::_(VitabookHelperRoute::getVitabookRoute($message->id)) ?>">
				<img src="<?php echo $message->photo->thumb ?>" <?php echo JHtml::setImageDimension($message->photo, 126, 136); ?> />
				</a>
			</div>
			<div class="dcs_content_wrapper">
				<a style="color:#000;font-weight:bold;" href="<?php echo JRoute::_(VitabookHelperRoute::getVitabookRoute($message->id)) ?>"><?php echo $message->title?></a>-<span> "<?php echo JHtml::cutText($message->message, 40) //echo $shortmsg ?>"</span>
				<div class="dcs_wrapper">
					<?php if ($message->user_link): ?>
					<div class="dcs_avatar">
						<a href="<?php echo $message->user_link ?>">
						<img style="width:30px;height:30px" src="<?php echo $message->user_avatar ?>" />
						</a>
					</div>
					<div class="dcs_name">
						<?php echo JText::_('VITABOOK_MOD_VITAFEATURE_BY') ?> &nbsp; <a href="<?php echo $message->user_link  ?>"><?php echo $message->user_name; ?></a>
					</div>
					<?php endif; ?>
				</div>
				<div class="dcs_wrapper">
					<a class="dcs_comment" href="#"><?php echo $count ?></a>
				</div>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
