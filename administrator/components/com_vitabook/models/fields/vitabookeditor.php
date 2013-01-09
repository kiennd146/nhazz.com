<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

/**
 * Supports a custom TinyMCE editor on a textarea
 */
class JFormFieldVitabookEditor extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'vitabookEditor';
	protected $canDo;

	/**
	 * Method to get the textarea field input markup.
	 * Use the rows and columns attributes to specify the dimensions of the area.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		//-- Get user permissions
		$this->canDo = VitabookHelper::getActions();
		//-- Get component parameters
		$params = JComponentHelper::getParams('com_vitabook');
		
		//-- Initialize some field attributes.
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
        //-- Get editor width from component parameters
        $width = $params->get('editor_width');
		$height = $this->element['height'] ? (int) $this->element['height'] : '';
		
		//-- Configure VitabookEditor settings
		$document = JFactory::getDocument();
		$document->addScript( JURI::root().'media/editors/tinymce/jscripts/tiny_mce/tiny_mce.js');
		
		//-- Include external plugins
		$document->addScript( JURI::root().'components/com_vitabook/assets/vitabookvideo/editor_plugin.js');
		$document->addScript( JURI::root().'components/com_vitabook/assets/vitabookupload/editor_plugin.js');
		$document->addScript( JURI::root().'components/com_vitabook/assets/vitabookemoticons/editor_plugin.js');

		$document->addScriptDeclaration($this->VitabookEditor());		
		
		//-- Load external plugins, only if user is allowed to use them
		if($this->canDo->get('vitabook.insert.video'))
		{
			$document->addScriptDeclaration("tinymce.PluginManager.load(\"vitabookvideo\", \"".JURI::root()."components/com_vitabook/assets/vitabookvideo/\");");
		}
		if($this->canDo->get('vitabook.insert.image') OR $this->canDo->get('vitabook.upload.image'))
		{
			$document->addScriptDeclaration("tinymce.PluginManager.load(\"vitabookupload\", \"".JURI::root()."components/com_vitabook/assets/vitabookupload/\");");
		}
		$document->addScriptDeclaration("tinymce.PluginManager.load(\"vitabookemoticons\", \"".JURI::root()."components/com_vitabook/assets/vitabookemoticons/\");");
		
		//-- Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return '<textarea style="width:'.$width.'px; height:'.$height.'px;" name="' . $this->name . '" id="' . $this->id . '"' . $class . $disabled . $onchange . '>'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '</textarea>';
	}

	
	/**
	 * Method to make custom tinyMCY editor from textarea
	 * Only textareas with class="mceEditor" will be modified into an editor
	 *
	 */
	protected function VitabookEditor()
	{
		//-- Get component parameters
		$params = JComponentHelper::getParams('com_vitabook');

		//-- General settings for editor
		$entity_encoding = 'raw';
		$language	= JFactory::getLanguage();
		
		if ($language->isRTL()) {
			$text_direction = 'rtl';
		} else {
			$text_direction = 'ltr';
		}
		
		$forcenewline = "force_br_newlines : false, force_p_newlines : true, forced_root_block : 'p',";
		$relative_urls = 'false';
		$invalid_elements = 'script,applet';
		//-- Toolbar settings
		$toolbar = 'top';
		$toolbar_align = 'left';
		$resizing = 'true';
		$resize_horizontal = 'false';
		$element_path = "theme_advanced_statusbar_location : \"none\", theme_advanced_path : false";
			
		$buttons 	= array();
		$plugins 	= array();
		$elements 	= array();
		
		//-- Initial values for buttons
		array_push($buttons, 'bold', 'italic', 'underline', 'bullist', 'numlist', 'separator');

		if($params->get('editor_html') == 1) {
			//-- Add code button
			$buttons[]	= 'code';
		}
		
		//-- Add links
		$buttons[]	= 'link, unlink, separator';		

		//-- Add Vitabook emoticons
		$plugins[]	= '-vitabookemoticons';
		$buttons[]	= 'vitabookemoticons';

        //-- Check if uploading or inserting images is allowed
		if($this->canDo->get('vitabook.insert.image') OR $this->canDo->get('vitabook.upload.image'))
		{
			$plugins[]	= '-vitabookupload';
			$buttons[]	= 'vitabookupload';		
		}
		
		//-- Check if embedding videos is allowed
		if($this->canDo->get('vitabook.insert.video')) {
			$plugins[]	= '-vitabookvideo';
			$buttons[]	= 'vitabookvideo';
		}
		
		//-- Inline popups
		$plugins[]	= 'inlinepopups';
		$dialog_type = "dialog_type : \"modal\",";
		
		//-- Autolinks
		$plugins[]	= 'autolink';
		
		$buttons 	= implode(',', $buttons);
		$plugins 	= implode(',', $plugins);
		$elements 	= implode(',', $elements);		

        //-- set mode
        if(JFactory::getApplication()->isSite())
            $mode = "none";
        else
            $mode = "specific_textareas";

		//-- Build editor
		$editor = "tinyMCE.init({
					// General
					$dialog_type
					directionality: \"$text_direction\",
					editor_selector : \"mceEditor\",
					language : \"en\",
					mode : \"$mode\",
					plugins : \"$plugins\",
					skin : \"default\",
					theme : \"advanced\",
					// Cleanup/Output
					inline_styles : true,
					gecko_spellcheck : true,
					entity_encoding : \"$entity_encoding\",
					extended_valid_elements : \"$elements\",
					$forcenewline
					invalid_elements : \"$invalid_elements\",
					// URL
					relative_urls : $relative_urls,
					remove_script_host : true,
					document_base_url : \"". JURI::root() ."\",
					//Templates
					template_external_list_url :  \"". JURI::root() ."media/editors/tinymce/templates/template_list.js\",
					// Advanced theme
					theme_advanced_toolbar_location : \"$toolbar\",
					theme_advanced_toolbar_align : \"$toolbar_align\",
					theme_advanced_resizing : $resizing,
					theme_advanced_resize_horizontal : $resize_horizontal,
					$element_path,
					theme_advanced_buttons1 : \"$buttons\",
					theme_advanced_buttons2 : \"\",
					theme_advanced_buttons3 : \"\"
				  });";
	
		return $editor;	
	}
}
