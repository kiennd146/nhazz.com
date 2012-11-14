<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<dl class="search-results<?php echo $this->pageclass_sfx; ?>">
<?php foreach($this->results as $result) : ?>
    <div class="item-search">
        
        <div class="inner">
            <div class="result-title">
		<?php //echo $this->pagination->limitstart + $result->count.'. ';?>
		<?php if ($result->href) :?>
			<a href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) :?> target="_blank"<?php endif;?>>
				<?php echo $result->title;?>
			</a>
		<?php else:?>
			<?php echo $this->escape($result->title);?>
		<?php endif; ?>
            </div>
            <?php if ($result->section) : ?>
                    <div class="result-category">
                            <span class="small<?php echo $this->pageclass_sfx; ?>">
                                    (<?php echo $this->escape($result->section); ?>)
                            </span>
                    </div>
            <?php endif; ?>
            <div class="result-text">
                    <?php echo $result->text; ?>
            </div>
        </div>
	
        
    </div>
<?php endforeach; ?>
</dl>

<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
