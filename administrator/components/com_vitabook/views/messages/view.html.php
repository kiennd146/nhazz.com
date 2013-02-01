<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

//-- No direct access
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
jimport('joomla.application.component.view');
if (!defined('DISCUSS_PHOTOS_PATH')) define('DISCUSS_PHOTOS_PATH', "images/discuss");
if (!defined('DISCUSS_PHOTOS_THUMB')) define('DISCUSS_PHOTOS_THUMB', "thumbs");
class VitabookViewMessages extends JViewLegacy
{
	protected $messages;
	protected $pagination;
	protected $state;

	public function getPhotos(&$item) {
		// get photos
		$item->photos = array();
		//$item->images_data = array();
		
		$images = json_decode($item->images);
		
		foreach($images as $image) {
			if (file_exists(JPATH_BASE. DS . '../' . DS . $image->origin) && file_exists(JPATH_BASE. DS . '../' . DS . $image->thumb)) {
				$image_photo = (object)array(
					'origin'=>JURI::base(). '../'  . DS . $image->origin,
					'thumb'=>JURI::base(). '../'  . DS . $image->thumb,
					'json'=> $image->origin.','.$image->thumb
				);
				
			}
			else {
				$image_photo = (object)array(
					'origin'=>JURI::base(). '../' . DISCUSS_PHOTOS_PATH . DS . 'no_photo.jpg',
					'thumb'=>JURI::base(). '../'  . DISCUSS_PHOTOS_PATH . DS . 'no_photo.jpg',
					'json'=> ''
				);
			}
			
			$item->photos[] = $image_photo;
		}
	}
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->messages		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		//-- Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("<br />", $errors));
			return false;
		}

		//-- Set toolbar
        $this->addToolbar();

		//-- Set the document
		$this->setDocument();

        // load legacy templates if joomla version < 3.0.0
        $jversion = new JVersion();
        if(version_compare($jversion->getShortVersion(),'3.0.0','lt'))
        {
            //-- Prepare table
            $this->prepareTableLegacy();
            $tpl .= "legacy";
        }
        else 
        {
            // Prepare table
            $this->prepareTable();
            $this->addSidebar();
            $this->sidebar = JHtmlSidebar::render();
        }
        
		parent::display($tpl);
	}


	/**
	 * Add table with JGrid
     * for Joomla version 2.5
	 */
	protected function prepareTableLegacy()
	{
		//-- Implement ordering
		$this->listOrder 	= $this->escape($this->state->get('list.ordering'));
		$this->listDirn 	= $this->escape($this->state->get('list.direction'));

		jimport('joomla.html.grid');

		$table = new JGrid(array('class' => 'adminlist'));

		$table	->addColumn('checkbox')
				->addColumn('title')
				->addColumn('photo')
				->addColumn('topic')
				->addColumn('name')
				->addColumn('status')
				->addColumn('date')
				->addColumn('featured')
				->addColumn('populared')
				->addColumn('id');

		$table	->addRow(array(), 1)
				->setRowCell('checkbox', '<input type="checkbox" name="checkall-toggle" value="" title="'.JText::_('JGLOBAL_CHECK_ALL').'" onclick="Joomla.checkAll(this)" />', array('width' => '1%'))
				->setRowCell('name', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_MESSAGES_THEAD_NAME', 'name', $this->listDirn, $this->listOrder), array())
				->setRowCell('photo', 'Photo', array())
				->setRowCell('title', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_MESSAGES_THEAD_TITLE', 'title', $this->listDirn, $this->listOrder), array())
				->setRowCell('topic', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_CATEGORIES_TITLE', 'topic', $this->listDirn, $this->listOrder), array())
				->setRowCell('status', JHtml::_('jhtml.grid.sort', 'JSTATUS', 'published', $this->listDirn, $this->listOrder), array('width' => '5%'))
				->setRowCell('date', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_MESSAGES_THEAD_DATE', 'date', $this->listDirn, $this->listOrder), array())
				->setRowCell('featured', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_MESSAGES_THEAD_FEATURED', 'featured', $this->listDirn, $this->listOrder), array('width' => '5%'))
				->setRowCell('populared', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_MESSAGES_THEAD_POPULARED', 'populared', $this->listDirn, $this->listOrder), array('width' => '5%'))
				->setRowCell('id', JText::_('COM_VITABOOK_MESSAGES_THEAD_ID'), array('width' => '1%', 'class' => 'nowrap'));

		//-- Add pagination
		$table	->addRow(array(), 2)
				->setRowCell('checkbox', $this->pagination->getListFooter(), array('colspan' => 10))
		;

		//-- Fill table
		foreach ($this->messages as $i => $message) {
			$this->getPhotos($message);
			$photo_str = '';
			foreach($message->photos as $photo) {
				$photo_str .= '<img src="'.$photo->thumb.'" style="width:50px;height:50px" />';
			}
			
			$table	->addRow(array('class' => 'row'.($i % 2)));
			$table	->setRowCell('checkbox', JHtml::_('grid.id', $i, $message->id), array('class' => 'center'));
			$table	->setRowCell('title', '<a href="'.JRoute::_($message->url).'" title="'.JText::_('COM_VITABOOK_MESSAGES_TBODY_EDIT').'">'.$message->title.'</a>');
			
			$table	->setRowCell('photo', $photo_str);
			$table	->setRowCell('topic', $message->topic);
			$table	->setRowCell('name', $message->name);
			$table	->setRowCell('status', JHtml::_('jgrid.published', $message->published, $i, 'messages.'), array('class' => 'center'));
			$table	->setRowCell('featured', JHtml::_('messages.featured', $message->featured, $i, true), array('class' => 'center'));
			$table	->setRowCell('populared', JHtml::_('messages.populared', $message->populared, $i, 'messages.'), array('class' => 'center'));
			$table	->setRowCell('date', $message->date);
			$table	->setRowCell('id', (int) $message->id, array('class' => 'center'));
		}

		$this->table = $table;
	}
    
	/**
	 * Add table with JGrid
     * for Joomla version > 3.0
	 */
	protected function prepareTable()
	{
		//-- Implement ordering
		$this->listOrder 	= $this->escape($this->state->get('list.ordering'));
		$this->listDirn 	= $this->escape($this->state->get('list.direction'));

		jimport('joomla.html.grid');

		$table = new JGrid(array('class' => 'table table-striped'));

		$table	->addColumn('checkbox')
				->addColumn('title')
				->addColumn('message')
				->addColumn('topic')
				->addColumn('status')
				->addColumn('date')
				->addColumn('featured')
				->addColumn('populared')
				->addColumn('id');

		$table	->addRow(array(), 1)
				->setRowCell('checkbox', '<input type="checkbox" name="checkall-toggle" value="" title="'.JText::_('JGLOBAL_CHECK_ALL').'" onclick="Joomla.checkAll(this)" />', array('width' => '1%', 'class' => 'hidden-phone'))
				->setRowCell('name', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_MESSAGES_THEAD_NAME', 'name', $this->listDirn, $this->listOrder), array())
				->setRowCell('message', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_MESSAGES_THEAD_MESSAGE', 'message', $this->listDirn, $this->listOrder), array())
				->setRowCell('status', JHtml::_('jhtml.grid.sort', 'JSTATUS', 'published', $this->listDirn, $this->listOrder), array('width' => '5%'))
				->setRowCell('date', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_MESSAGES_THEAD_DATE', 'date', $this->listDirn, $this->listOrder), array())
				->setRowCell('email', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_MESSAGES_THEAD_EMAIL', 'email', $this->listDirn, $this->listOrder), array())
				->setRowCell('site', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_MESSAGES_THEAD_SITE', 'site', $this->listDirn, $this->listOrder), array())
                ->setRowCell('location', JHtml::_('jhtml.grid.sort', 'COM_VITABOOK_MESSAGES_THEAD_LOCATION', 'location', $this->listDirn, $this->listOrder), array())
				->setRowCell('ip', JText::_('COM_VITABOOK_MESSAGES_THEAD_IP'), array())
				->setRowCell('id', JText::_('COM_VITABOOK_MESSAGES_THEAD_ID'), array('width' => '1%', 'class' => 'nowrap'))
		;

		//-- Add pagination
		$table	->addRow(array(), 2)
				->setRowCell('checkbox', $this->pagination->getListFooter(), array('colspan' => 10))
		;
        
		//-- Fill table
		foreach ($this->messages as $i => $message) {
			$table	->addRow(array('class' => 'row'.($i % 2)));
			$table	->setRowCell('checkbox', JHtml::_('grid.id', $i, $message->id), array('class' => 'center hidden-phone'));
			$table	->setRowCell('name', str_repeat('<span class="gi">&mdash;</span>', $message->level-1) . $this->escape($message->name));
			$table	->setRowCell('message', str_repeat('<span class="gi">|&mdash;</span>', $message->level-1) . '<a href="'.JRoute::_($message->url).'" title="'.JText::_('COM_VITABOOK_MESSAGES_TBODY_EDIT').'">'.strip_tags($message->message).'</a>');
			$table	->setRowCell('status', JHtml::_('jgrid.published', $message->published, $i, 'messages.'), array('class' => 'center'));
			$table	->setRowCell('date', $message->date);
			$table	->setRowCell('email', $this->escape($message->email));
			$table	->setRowCell('site', $this->escape($message->site));
            $table	->setRowCell('location', $this->escape($message->location));
			$table	->setRowCell('ip', $message->ip);
			$table	->setRowCell('id', '<span title="'. sprintf('%d-%d', $message->lft, $message->rgt).'">'.(int) $message->id .'</span>', array('class' => 'center'));
		}

		$this->table = $table;
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		$canDo	= VitabookHelper::getActions();

		JToolBarHelper::title(JText::_('COM_VITABOOK_MESSAGES_TITLE'), 'items.png');

 		if ($canDo->get('vitabook.create.new') OR $canDo->get('vitabook.create.reply'))
		{
			JToolBarHelper::addNew('message.add', 'JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit') OR $canDo->get('core.edit.own'))
		{
			JToolBarHelper::editList('message.edit', 'JTOOLBAR_EDIT');
			JToolBarHelper::divider();
		}
		if ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::publishList('messages.publish');
			JToolBarHelper::unpublishList('messages.unpublish');
			JToolBarHelper::divider();
		}
		if ($canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', 'messages.delete', 'JTOOLBAR_DELETE');
		}
		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_vitabook');
		}
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addSidebar()
	{
        JHtmlSidebar::setAction('index.php?option=com_vitabook&view=messages');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
		);
    }

	/**
	 * Method to set up the document properties
	 */
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_VITABOOK_MESSAGES_TITLE'));
	}
}
