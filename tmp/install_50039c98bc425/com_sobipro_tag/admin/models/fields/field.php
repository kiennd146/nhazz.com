<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
// The class name must always be the same as the filename (in camel case)
class JFormFieldField extends JFormFieldList {
 
	//The field class must know its own type through the variable $type.
	protected $type = 'Field';
	public function getOptions() {
		// code that returns HTML that will be shown as the form field
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('fid,nid');
        $query->from('#__sobipro_field');
        $query->where("fieldType='textarea'");
        $db->setQuery((string)$query);
        $messages = $db->loadObjectList();
        $options = array();
        if ($messages)
        {
                foreach($messages as $message) 
                {
                        $options[] = JHtml::_('select.option', $message->fid, $message->nid);
                }
        }
        $options = array_merge(parent::getOptions(), $options);
        return $options;    
        
	}
}