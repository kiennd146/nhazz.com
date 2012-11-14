<?php
$baseurl = JURI::base();
$document = &JFactory::getDocument();
$document->addStyleSheet($baseurl . 'modules/mod_user_presenter/includes/mod_user_presenter.css');
$value = JRequest::getVar('value');
$html = "";
if($type == 'list'):
    $html .= "<ul>";
    foreach ($lists as $list) {
	$class = ($list == $value) ? "active" : "";
	$html .= "<li class='{$class}'>";
	$html .= JHTML::link(JRoute::_('index.php?option=com_tvtma_user_presenter&view=tvtmauserpresenter&fieldSeach=' . $fieldId . '&value=' . urlencode($list)), $list);
	$html .=  "</li>";
    }
    $html .= "</ul>";
endif;

if($type == 'dropbox'):
    $html .= "<form>";
    $html .= "<select name='url' onChange='JumpToIt(this.form)' class='dropdownlist'>";
    $html .= "<option value='#'>Chọn</option>";
    foreach ($lists as $list) {
	$class = ($list == $value) ? "selected='selected'" : "";
	$url = JRoute::_('index.php?option=com_tvtma_user_presenter&view=tvtmauserpresenter&fieldSeach=' . $fieldId . '&value=' . urlencode($list));
	$html .= "<option {$class} value={$url}>";
	$html .= $list;
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