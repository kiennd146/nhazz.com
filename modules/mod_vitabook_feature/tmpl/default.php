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
	<h2>Featured Discussion</h2>
	<ul id="featuredcs_list">
		<?php foreach ($list as $message): ?>
		<?php
		//$comment = JComments::getLastComment($message->id, 'com_vitabook');
		$count = JComments::getCommentsCount($message->id, 'com_vitabook');
		$shortmsg = strip_tags($message->message);
		$limited_char = 40;
		if (strlen($shortmsg) > $limited_char) {
			// truncate string
			$stringCut = substr($shortmsg, 0, $limited_char);
			$shortmsg = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
		}
		?>
		<li>
			<div class="dcs_img_wrapper">
				<a href="<?php echo JRoute::_(VitabookHelperRoute::getVitabookRoute($message->id)) ?>">
				<img style="width:126px; height:136px;"  src="<?php echo $message->photo ?>" />
				</a>
			</div>
			<div class="dcs_content_wrapper">
				<a style="color:#000;font-weight:bold;" href="<?php echo JRoute::_(VitabookHelperRoute::getVitabookRoute($message->id)) ?>"><?php echo $message->title?></a>-<span> "<?php echo $shortmsg?>..."</span>
				<div class="dcs_wrapper">
					<?php if ($message->user_link): ?>
					<div class="dcs_avatar">
						<a href="<?php echo $message->user_link ?>">
						<img style="width:30px;height:30px" src="<?php echo $message->user_avatar ?>" />
						</a>
					</div>
					<div class="dcs_name">
						by &nbsp; <a href="<?php echo $message->user_link  ?>"><?php echo $message->user_name; ?></a>
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
