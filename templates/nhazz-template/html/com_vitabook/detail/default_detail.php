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


?>
					
<?php
$comments = JPATH_SITE . DS .'components' . DS . 'com_jcomments' . DS . 'jcomments.php';
if (file_exists($comments)) {
    require_once($comments);
    //
    
    //echo $count ? ('Comments('. $count . ')') : 'Add comment';
}
?>
<script>	
(function($){
	$(document).ready(function() {
		$(".dcsdt_comment_add").click(function(){
			$('html,body').animate({
			scrollTop: $("#comments-form").offset().top - 200},
			'slow');
		});
		$(".dcsdt_small a").click(function(e){
			e.preventDefault();
			var src = $("img", this).attr("src");
			$("ul.dcsdt_small li").removeClass("active");
			$(".dcsdt_large img").attr("src", src);
			$(this).parent().addClass("active");
		});
	});
})(jQuery);

</script>
<?php
function renderMessage($message,$params,$parentState){ ?>
<?php
	$count = JComments::getCommentsCount($message->id, 'com_vitabook');
    if(is_object($message))
    { ?>
		
	<div id="dcsdetail">
		<div class="dcsdt_avatar">
			<a href="<?php echo $message->user_link ?>"><img src="<?php echo $message->user_avatar ?>" style="width:34px; height:34px;"></a>
		</div>
		<div class="dcsdt_content">
			<p class="author">by <a href="<?php echo $message->user_link ?>"><?php echo $message->user_name ?></a></p>
			<p class="author"><?php echo $message->date ?> in <a href="<?php echo JRoute::_(VitabookHelperRoute::getCategoryRoute($message->category->id)) ?>"><?php echo $message->category->title ?></a></p>
			
			<h2><?php echo $message->title; ?></h2>
			<p><?php var_dump($message->message); ?></p>
			<div id="dcsdt_images">
				<?php if (count($message->photos) > 0):?>
				<div class="dcsdt_large">
					<img style="width:478px;" src="<?php echo $message->photos[0]?>">
				</div>
				<ul class="dcsdt_small">
					<?php $first = true;?>
					<?php foreach($message->photos as $photo):?>
					<li  <?php if ($first == true):?>class="active"<?php endif;?>><a href="#"><img style="width:75px;height:55px" src="<?php echo $photo; ?>"></a></li>
					<?php $first = false;?>
					<?php endforeach;?>
					
				</ul>
				<?php endif;?>
				
			</div>
		</div>
		<div class="dcsdt_footer">
			<a class="dcs_comment" href="#"><?php echo $count ?></a>&nbsp;&nbsp;<a class="dcsdt_comment_add" href="#">Add Comment</a>
		</div>
	</div>
    <div id="dcsdetail_comment">     
    <?php
	echo JComments::showComments($message->id, 'com_vitabook', 'test');
    } 
	?>
	</div>
<?php
} 

if(!empty($this->messages))
{
    foreach ($this->messages as $message):
        renderMessage($message,$this->params,1);
    endforeach;
}
?>
