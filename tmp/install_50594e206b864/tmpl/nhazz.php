<?php
/**
 * @package 	mod_bt_contentslider - BT ContentSlider Module
 * @version		1.4
 * @created		August 2012

 * @author		Luan vu
 * @email		luan.vu@tvtmarine.com
 * @website		http://ma.tvtmarine.com
 * @support		Forum - http://ma.tvtmarine.com
 * @copyright	Copyright (C) 2011 http://ma.tvtmarine.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<?php if(count($list)>0) :?>
<div style="width:<?php echo $moduleWidthWrapper;?>">

	<div id="btcontentslider<?php echo $module->id; ?>" style="display:none;overflow: hidden" class="bt-cs<?php echo $moduleclass_sfx? ' bt-cs'.$params->get('moduleclass_sfx'):'';?>">
		<?php if( $next_back && $totalPages  > 1  ) : ?>
		<a class="next" href="#">Next</a> <a class="prev" href="#">Prev</a>
		<?php endif; ?>
		<?php 
			$add_style = "";
			if( trim($params->get('content_title')) ){
			$add_style= "border: 1px solid #CFCFCF;padding:10px 0px;";
		?>
		<h3>
			<span><?php echo $params->get('content_title') ?> </span>
		</h3>
		<?php } ?>
		<div class="slides_container" style="width:<?php echo $moduleWidth.";".$add_style;?>;height:<?php echo $moduleHeight;?>">

		<?php foreach( $pages as $key => $list ): ?>

			<div class="slide" style="width:<?php echo $moduleWidth;?>;height:<?php echo $moduleHeight;?>">

			<?php foreach( $list as $i => $row ): ?>

				<div class="bt-row <?php if($i==0) echo 'bt-row-first'; else if($i==count($list)-1) echo 'bt-row-last'; else echo 'bt-row-middle'; ?>"  style="width:<?php echo ($i==0)? $left : $right;?>%;" >

                                    <div class="bt-inner" style="float:left;">
					<?php if( $show_category_name ): ?>
                                                <?php if($show_category_name_as_link) : ?>
                                                    <a class="bt-category" target="<?php echo $openTarget; ?>"
                                                            title="<?php echo $row->category_title; ?>"
                                                            href="<?php echo $row->categoryLink;?>"> <?php echo $row->category_title; ?>
                                                    </a>
						<?php else :?>
                                                    <span class="bt-category"> <?php echo $row->category_title; ?> </span>
						<?php endif; ?>
                                        <?php endif;// End show category name ?>
                                                
                                        <?php if( $row->thumbnail ): ?>
                                            <?php if($i == 0) echo "<div class='bt-image-box'>";?>
                                            
                                                <a target="<?php echo $openTarget; ?>"
                                                        class="bt-image-link"
                                                        title="<?php echo $row->title;?>" href="<?php echo $row->link;?>"
                                                        style="<?php echo ($i==0) ? 'display: block;max-height: '. $thumbnail_max_height .'px;overflow: hidden;': '';?>"
                                                        >
                                                    <div class="bt-image-box">
                                                        <img class="bt-image-thumb" <?php echo $imgClass; ?> src="<?php echo $row->thumbnail; ?>" alt="<?php echo $row->title;?>"  style="<?php echo($i == 0) ? "width:100%" :  '';?>;margin-right: 10px;<?php if($i == 0) echo 'margin-bottom:10px;';?>float:left;" title="<?php echo $row->title;?>" />
                                                    </div>
                                                </a>
                                            
                                            <?php if($i == 0) echo "</div>";?>        
                                        <?php endif ; // End thumbnail ?>
                                            
                                            <?php if($i == 0) echo "<div>";?>

                                            <?php if( $showTitle ): ?>
                                                <a class="bt-title" target="<?php echo $openTarget; ?>"
                                                        title="<?php echo $row->title; ?>"
                                                        href="<?php echo $row->link;?>"
                                                        style="<?php if($i!=0) echo 'clear: none';?>"
                                                        > <?php echo $row->title_cut; ?> </a>
                                            <?php endif; // End show title ?>
                                            <?php if( $showAuthor || $showDate ): ?>
                                                <div class="">
                                                <?php if( $showAuthor ): ?>
                                                    <span class="bt-author"><?php 
                                                    $user = CFactory::getUser($row->created_by);
                                                    $avatar = $user->getAvatar();
                                                    unset($user);
                                                    $image = '<img class="bt-avatar" src="'. $avatar .'" alt="" border="0"/>';
                                                    echo JHtml::_('link',CRoute::_($row->authorLink),$image . $row->author); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if( $showDate ): ?>
                                                    <span class="bt-date"><?php echo JText::sprintf('BT_CREATEDON', $row->date); ?>
                                                    </span>
                                                <?php endif; ?>
                                                </div>
                                            <?php endif; // End show author and show date?>

                                            <?php if( $show_intro ): ?>
                                                    <div class="bt-introtext">
                                                    <?php echo $row->description; ?>
                                                    <?php if( $showReadmore ) : ?>
                                                            <a class="more-link" target="<?php echo $openTarget; ?>"
                                                                    title="<?php echo $row->title;?>"
                                                                    href="<?php echo $row->link;?>"> <?php echo JText::_('READ_MORE');?>
                                                            </a>
                                                    <?php endif; // End show readmore ?>

                                                </div>
                                            <?php endif; // End show intro ?>
                                            
                                            <?php if($i == 0) echo "</div>";?>
                                             <!-- bt-intro -->

					</div>
					<!-- bt-inner -->

				</div>
				<!-- bt-row -->
				<?php
				if($itemsPerCol > 1 && $i < count($list)-1){
					if(($i+1)%$itemsPerRow ==0){
						echo '<div class="bt-row-separate"></div>';
					}
				}
				?>

				<?php 
                                unset($row);
                                unset($i);
                                endforeach;
                                ?>
				<div style="clear: both;"></div>

			</div>
			<!-- bt-main-item page	-->
			<?php 
                        unset($list);
                        endforeach;
                        unset($pages);
                        ?>

		</div>


	</div>
	<!-- bt-container -->


</div>
			<?php else : ?>
<div>No result...</div>
			<?php endif; ?>
<div style="clear: both;"></div>
