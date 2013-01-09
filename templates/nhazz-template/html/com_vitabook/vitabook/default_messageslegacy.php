<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license             GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

// no direct access
defined('_JEXEC') or die;
   /*
$comments = JPATH_SITE . DS .'components' . DS . 'com_jcomments' . DS . 'jcomments.php';
  if (file_exists($comments)) {
    require_once($comments);
    echo JComments::showComments($message->id, 'com_vitabook', 'Title test');
    $count = JComments::getCommentsCount($message->id, 'com_vitabook');
    echo $count ? ('Comments('. $count . ')') : 'Add comment';
  }
     */
// function to recursively render messages with their children
$comments = JPATH_SITE . DS .'components' . DS . 'com_jcomments' . DS . 'jcomments.php';
if (file_exists($comments)) {
    require_once($comments);
}
?>

<?php
function renderMessage($message,$params,$parentState){ ?>
    <li>
    <?php
    $comment = JComments::getLastComment($message->id, 'com_vitabook');
	
	$count = JComments::getCommentsCount($message->id, 'com_vitabook');
    if(is_object($message))
    { ?>
	
        <div class="dcs_img_wrapper">
			<a href="<?php echo JRoute::_(VitabookHelperRoute::getVitabookRoute($message->id)) ?>">
			<img style="width:136px; height:136px;"  src="<?php echo $message->photo ?>" />
			</a>
		</div>
		<div class="dcs_content_wrapper">
			<h3><a href="<?php echo JRoute::_(VitabookHelperRoute::getVitabookRoute($message->id)) ?>"><?php echo $message->title?></a></h3>
			<div class="dcs_avatar">
				<a href="<?php echo $message->user_link ?>">
				<img style="width:30px;height:30px" src="<?php echo $message->user_avatar ?>" />
				</a>
			</div>
			<div class="dcs_content">
				<?php if ($comment): ?>
				<p><a class="dsc_user" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $comment->userid ); ?>"><?php echo $comment->name; ?></a> commented:</p>
				<p><?php echo $comment->comment; ?></p>
				<p class="dsc_time">
					<?php echo $comment->date?> in <a href="<?php echo JRoute::_(VitabookHelperRoute::getCategoryRoute($message->category->id)) ?>"><?php echo $message->catname ?></a>
					<a class="dcs_comment" href="#"><?php echo $count ?></a>
				</p>
				<?php endif ?>
			</div>
		</div>
    <?php
    } // end if $message is object
    ?>
	</li>
<?php
} // end function render message

?>
<div id="discuss_list">
	<h2><?php echo $this->pagination->total ?> Discussions</h2>
	<ul>
<?php
// render available messages
if(!empty($this->messages))
{
    foreach ($this->messages as $message):
        renderMessage($message,$this->params,1);
    endforeach;
}

// if no messages are present, say so
else
{ 
}
?>

	</ul>
</div>