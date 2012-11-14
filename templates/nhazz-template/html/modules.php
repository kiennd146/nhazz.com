<?php  
/*------------------------------------------------------------------------
# author    your name or company
# copyright Copyright (C) 2011 example.com. All rights reserved.
# @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website   http://www.example.com
-------------------------------------------------------------------------*/

defined('_JEXEC') or die;

function modChrome_slider($module, &$params, &$attribs) {
	echo JHtml::_('sliders.panel',JText::_($module->title),'module'.$module->id);
	echo $module->content;
}

function modChrome_mystyle($module, &$params, &$attribs) { ?>
	<div class="moduletable <?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
                        <?php if ($params->get('linktitlemodule')) : ?>      
                        <h3><a href="<?php echo $params->get('linktitlemodule'); ?>"><?php echo JText::_( $module->title ); ?></a></h3>
                        <?php else : ?>
                        <h3><a href="#"><?php echo JText::_( $module->title ); ?></a></h3>
                        <?php endif; ?>
			<div class="modulcontent"><?php echo $module->content; ?></div>
	</div><?php
}
?>
