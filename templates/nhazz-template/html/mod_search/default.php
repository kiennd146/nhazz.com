<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_search
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php');?>" method="post" name="frmsearch" id="frmsearch">
	<div class="search<?php echo $moduleclass_sfx ?>">
		<?php
			$output = '<input name="searchword" id="mod-search-searchword" maxlength="100"  class="inputbox'.$moduleclass_sfx.'" type="text" size="'.$width.'" value="'.$text.'"  onblur="if (this.value==\'\') this.value=\''.$text.'\';" onfocus="if (this.value==\''.$text.'\') this.value=\'\';" />';
                        require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_search', 'models', 'search.php' ) ) );
                        $searchModel = new SearchModelSearch();
                        $areaLists = $searchModel->getAreas();
                        //echo "<pre>";
                        //var_dump($areaLists['search']);
                        //echo "</pre>";
                        //die();
                        $output .= '<select id="searchSelect" onchange="document.forms[\'frmsearch\'].areas.value=this.value">';
                        $output .= '<option value="">Chọn</option>';
                        foreach ($areaLists['search'] as $key => $txt) {
                            $output .= '<option value="'. $key .'">' . JText::_($txt) . '</option>';
                        }
                        $output .= '</select>';
			if ($button) :
				if ($imagebutton) :
					$button = '<input type="image" value="'.$button_text.'" class="button'.$moduleclass_sfx.'" src="'.$img.'" onclick="this.form.searchword.focus();"/>';
				else :
					$button = '<input id="searchButton" type="submit" value="'.$button_text.'" class="button'.$moduleclass_sfx.'" onclick="this.form.searchword.focus();"/>';
				endif;
			endif;

			switch ($button_pos) :
				case 'top' :
					$button = $button.'<br />';
					$output = $button.$output;
					break;

				case 'bottom' :
					$button = '<br />'.$button;
					$output = $output.$button;
					break;

				case 'right' :
					$output = $output.$button;
					break;

				case 'left' :
				default :
					$output = $button.$output;
					break;
			endswitch;

			echo $output;
		?>
	<input type="hidden" name="task" value="search" />
	<input type="hidden" name="option" value="com_search" />
        <input type="hidden" name="areas[0]" value="" id="areas" />
        <?php
        $app = JFactory::getApplication();
        $menu = $app->getMenu();
        if ($menu->getActive() == $menu->getDefault()) {
            $mitemid = 0;
        } 
        ?>
	<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
	</div>
</form>
