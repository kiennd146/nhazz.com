<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_TVTPROJECT_TVTPROJECT_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JText::_('COM_TVTPROJECT_TVTPROJECT_HEADING_NAME'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_TVTPROJECT_TVTPROJECT_HEADING_OWNER'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_TVTPROJECT_TVTPROJECT_HEADING_PUBLISH'); ?>
	</th>
</tr>
