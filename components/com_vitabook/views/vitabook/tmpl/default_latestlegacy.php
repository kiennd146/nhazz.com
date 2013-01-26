<ul class="jcomments-latest">
	<?php foreach ($this->list as $message): ?>	
	<?php
	$count = JComments::getCommentsCount($message->id, 'com_vitabook');
	$comment = JComments::getLastComment($message->id, 'com_vitabook');
	
	$message_link = JRoute::_(VitabookHelperRoute::getVitabookRoute($message->id));
	$comment_link = '';
	if ($comment) {
		$comment_link = $message_link . '#comment-' . $comment->id;
	}
	
	?>
	
	<li>
		<div style="overflow:hidden;width:82px;height:76px;float:left" class="imgcom">
			<a href="<?php echo $message_link ?>">
				<img src="<?php echo $message->photo->thumb ?>" <?php echo JHtml::setImageDimension($message->photo->thumb, 82, 76); ?> />
			</a>
		</div>
		<div style="overflow:hidden;width:77%;float:right;" class="contcom">
			<div class="comment rounded avatar-indent">
				<div><a href="<?php echo $message_link ?>"><?php echo $message->title?></a>
					<p class="jcomments-latest-readmore">
						<?php if ($comment): ?>
						<a class="comment" href="<?php echo $comment_link ?>"><?php echo $comment?JHtml::cutText($comment->comment, 100):'';?></a>
						<?php endif ?>
					</p>
				</div>
			</div>
			<span class="author"><?php echo $comment?JHtml::_('date.relative',$comment->date):'';?>
				<a href="<?php echo $message->user_link  ?>"><?php echo $message->user_name; ?></a>
			</span>                
		</div>
		<span class="comment-separator">&nbsp;
		<div class="dcs_wrapper">
			<a class="dcs_comment" href="#"><?php echo $count ?></a>
		</div>
		</span>
	</li>
	
	<?php endforeach; ?>
</ul>