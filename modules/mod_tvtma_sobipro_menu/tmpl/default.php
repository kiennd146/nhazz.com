<?php
$baseurl = JURI::base();
$document = &JFactory::getDocument();
$document->addStyleSheet($baseurl . 'modules/mod_tvtma_sobipro_menu/includes/mod_tvtma_sobipro_menu.css');
$values = explode(",", JRequest::getVar('searchFor'));
$search_user_id = JRequest::getVar('search_user_id');
if(isset($search_user_id) && $search_user_id != "") return;
$html = "";
if($type == 'list'):
    $html .= "<ul>";
	$search_need_arr = array();
	
	foreach($values as $search_for) {
		if (in_array($search_for, $lists)) {
			$search_need_arr[] = $list->value;
		}
		else {
			$search_need_arr[] = 'NEED_REPLACE';
		}
	}
			
    foreach ($lists as $list) {
		$search_need = $list->value;
		$search_phrase = 'exact';
		if (count($values) > 0) {
			$search_need = implode(",", $search_need_arr);
			$search_phrase = 'multi';
			$search_need = str_replace('NEED_REPLACE', $list->value, $search_need);
		}
		
		$class = (in_array($list->value, $values)) ? "active" : "";
		$html .= "<li class='{$class}'>";
		$html .= JHTML::link(JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for=' . $search_need . '&' . $fieldId . '&sid=' . $sectionId . '&spsearchphrase='. $search_phrase), $list->text);
		$html .=  "</li>";
    }
    $html .= "</ul>";
endif;

if($type == 'dropbox'):
    $html .= "<form>";
    $html .= "<select name='url' onChange='JumpToIt(this.form)' class='dropdownlist'>";
    $html .= "<option value='#'>Chọn</option>";
    foreach ($lists as $list) {
	$class = (in_array($list->value, $values)) ? "selected='selected'" : "";
	$url = JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for=' . $list->value . '&' . $fieldId . '&sid=' . $sectionId . '&spsearchphrase=exact');
	$html .= "<option {$class} value={$url}>";
	$html .= $list->text;
	$html .=  "</option>";
    }
    $html .= "</select>";
    $html .= "</form>";
    $html .=<<<html
   <SCRIPT LANGUAGE="JavaScript">
    <!--
    function JumpToIt(frm) {
	var newPage = frm.url.options[frm.url.selectedIndex].value
	if (newPage != "None") {
	    location.href=newPage
	}
    }
    //-->
    </SCRIPT>
html;
endif;
echo $html;
?>