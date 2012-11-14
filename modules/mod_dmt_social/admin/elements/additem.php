<?php
/*
* Created by		: Demente Design
* Email				: contact@demente-design.com
* Created on		: september 2010
* Last Modified 	: june 2011
* URL				: http://demente-design.com
* License			: GPLv2
* Copyright			: 2011 demente-design.com
* License 			: GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
* 
* The icons included by default in the package are from www.komodomedia.com
*/

defined('JPATH_BASE') or die;
  
class JFormFieldAddItem extends JFormField {

	protected $type = 'AddItem';

	protected function  getInput()
	{
		// Add CSS and JS
		
		// This file's parent directory
		$modulePath = str_replace("elements", "", str_replace(DS,"/", str_replace( JPATH_SITE, JURI::root(1), dirname(__FILE__) )));
		$doc =& JFactory::getDocument();
		
		$doc->addStyleSheet( $modulePath . 'style.css');
		$doc->addScript( $modulePath . 'scripts.js');
		
		$name = $this->name;
		$values = $this->value;
		
		// Make sure it's an array
		if( !is_array($values) && empty($values) ){
			$values = array();
		}
		$values = !is_array($values) ? array($values):$values;

		// Template to copy when adding items
		$items = '
				<div id="dmt-item-template" class="dmt-item">
					<input type="text" class="alt" value="'.$values[0]->alt.'" name="'.$name.'[0][alt]">
					<input type="text" class="url" value="'.$values[0]->url.'" name="'.$name.'[0][url]"><br />'.
					JFormFieldAddItem::iconList(0, $values).'<span class="icon icon-remove"></span>
				</div>
			';
		
		//$values = array_slice($values,1,0,true);
		foreach( $values as $key => $value ){
			if($key !== 0){ // The first item was already displayed
				$items .= '
					<div class="dmt-item">
						<input type="text" class="alt" value="'.$values[$key]->alt.'" name="'.$name.'['.$key.'][alt]">
						<input type="text" class="url" value="'.$values[$key]->url.'" name="'.$name.'['.$key.'][url]"><br />'.
						JFormFieldAddItem::iconList($key, $values).'<span class="icon icon-remove"></span>
					</div>
				';
			}
		}
		
		$noscript = '<noscript>You must have JavaScript enabled to add/remove links.</noscript>';
		return '<div class="dmt-add-item" id="'.$this->fieldname.'" name="jform[params]['.$this->fieldname.'][]">'.$noscript.$items.'<a class="button-add"><span class="icon icon-add"></span>Add Item</a></div>';
	}

  	private function iconList($key, $values)
	{
		// This file's parent directory
		$modulePath = str_replace("admin/elements", "", str_replace(DS,"/", str_replace( JPATH_SITE, JURI::root(1), dirname(__FILE__) )));
		
		// The icon stored
		$SelectedIcon = $values[$key]->icon;
		
		// Add styles for the icons
		$doc = JFactory::getDocument();
		$css = '.icon-preview { display: inline-block; height: 16px; width: 16px; float: left; margin: 5px 5px 5px 0;}';
		$doc->addStyleDeclaration( $css );

		// Scan the icons directory
		$imagesDir = '../modules/mod_dmt_social/icons/';
		$scan = scandir($imagesDir);
		
		// Return only images ending with _16.png
		if(!function_exists('icons'))
		{
			function icons($item){
				$pos = strpos($item,'_16.png');
				if($pos !== false) return true;
			}
		}

		$icons = array_filter($scan, 'icons');

		// Add the span to preview the selected icon
		$html = '<span class="icon-preview" style="background-image: url('.$imagesDir.$SelectedIcon.'_16.png);"></span>';
		$html .= '<select name="'.$this->name.'['.$key.'][icon]" class="icon-select">';
		
		// Show available icons
		foreach( $icons as $i => $icon){
			$name = preg_replace('/_16.png/','',$icon);
			($SelectedIcon == $name)? $selected = ' selected="selected"' : $selected = '';
			$html .= '<option value="'.$name.'"'.$selected.'>'.$name.'</option>';
		}
		
		$html .= '</select>';
		
		// Add script to update image on selection
		$iconUpdate = "
				var updateIcons = function(){
				window.addEvent('domready', function() {
					$$('.icon-preview').each(function(el, i){
						var select = el.getNext('select');
						select.addEvents({
							'change':function(){
								el.setStyle( 'background-image','url(".$imagesDir."'+select.getSelected()[0].get('value')+'_16.png)' );
							}
						});
					});
				});
				}
				updateIcons();";
		$doc->AddScriptDeclaration($iconUpdate);

		return $html;
	}
}
?>
