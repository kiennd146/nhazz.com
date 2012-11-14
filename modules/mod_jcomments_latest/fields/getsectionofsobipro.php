<?php
defined('JPATH_PLATFORM') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldgetsectionofsobipro extends JFormFieldList
{
	protected function getInput()
	{
		$attr = '';
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		$options = (array) $this->getOptions();

		return JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
	}

	protected function getOptions()
	{
		$options = array();

		$db = JFactory::getDBO();
		$db->setQuery('SELECT * FROM `#__sobipro_object` WHERE `oType` = "section";');
		$sections = $db->loadObjectList();
                
			foreach($sections as $section) {
						$o = new StdClass;
						$o->value = $section->id;
						$o->text = $section->name;
						$options[] = $o;
			}
		return $options;
	}
}
