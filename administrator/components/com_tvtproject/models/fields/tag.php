<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
require_once dirname(__FILE__).'/javascript.php';
JFormHelper::loadFieldClass('list');
// The class name must always be the same as the filename (in camel case)
class JFormFieldTag extends JFormFieldList {
 
	//The field class must know its own type through the variable $type.
	protected $type = 'Tag';
	public function getInput() {
		// code that returns HTML that will be shown as the form field
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('fid,nid,section');
        $query->from('#__sobipro_field');
        //$query->where("fieldType='textarea'");
        $db->setQuery((string)$query);
        $messages = $db->loadObjectList();
        $options = "";
        if ($messages)
        {
                foreach($messages as $message) 
                {
                    //$options[] = JHtml::_('select.option', $message->fid, $this->getNameOfField($message->fid));
                    $options .= "<option value='{$message->nid}' class='mod_tvtma_sobipro_option mod_tvtma_sobipro_sec_{$message->section}' >" . $this->getNameOfField('sValue',$message->fid, 'name', 'field') . "</option>";
                }
        }

        //$options = array_merge(parent::getOptions(), $options);
        //return $options;
        return '<select id="'.$this->id.'" name="'.$this->name.'" size="5" multiple="true">'.
            $options.
        '</select>';
        
	}
        
        /**
         * Get name of field
         * @param type $field_id 
         */
        static function getNameOfField($select,$field_key, $target, $oType)
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select($select);
            $query->from('#__sobipro_language');
            $query->where("oType='$oType' AND fid='".$field_key."' AND sKey='$target'");
            $db->setQuery((string)$query);
            $field = $db->loadObject();
            return $field->sValue;
        }
}