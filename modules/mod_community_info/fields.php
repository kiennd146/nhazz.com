<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
require_once dirname(__FILE__) . '/javascript.php';

// The class name must always be the same as the filename (in camel case)
class JFormFieldFields extends JFormFieldList {

	//The field class must know its own type through the variable $type.
	protected $type = 'Fields';

	public function getInput() {
		// code that returns HTML that will be shown as the form field
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('sl.sValue as text,sf.nid AS value,sf.section as class');
		$query->from('#__sobipro_field as sf,#__sobipro_language as sl');
		$query->where("sl.oType='field' AND sl.sKey='name' AND sl.fid=sf.fid");
		$db->setQuery((string) $query);
		$messages = $db->loadObjectList();
		$options = array();
		$options = "";
		if ($messages) {
			foreach ($messages as $message) {
				//$options[] = JHtml::_('select.option', $message->fid, $this->getNameOfField($message->fid));
				$selected = ($message->value == $this->value) ? 'selected="selected"' : '';
				$options .= "<option value='{$message->value}' {$selected} class='mod_tvtma_slider_option mod_tvtma_slider_sec_{$message->class}' >" . $message->text . "</option>";
			}
		}
		return '<select id="' . $this->id . '" name="' . $this->name . '">' .
						$options .
						'</select>';
		// add a first option to the list without looking at the database result
		//$options[] = JHTML::_('select.option','',JText::_('please choose a filter'));
		//now fill the array with your database result
		//foreach($messages as $key=>$value) :
		//$options[] = JHTML::_('select.option',$value->value,JText::_($value->text));
		//endforeach;
		//return JHTML::_('select.genericlist',  $messages, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id);
	}

	/**
	 * Get name of field
	 * @param type $field_id 
	 */
	static function getNameOfField($field_key) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('sValue ');
		$query->from('#__sobipro_language');
		$query->where("oType='field' AND fid='" . $field_key . "' AND sKey='name'");
		$db->setQuery((string) $query);
		$field = $db->loadObject();
		return $field->sValue;
	}

}