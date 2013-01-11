<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Vitabook records.
 */
class VitabookModelMessages extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
				'title',
				'message',
				'published',
				'featured',
				'populared',
				'date'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to get an array of data items.
     *
     * @return  mixed  An array of data items on success, false on failure.
     *
     * @since   11.1
     */
	public function getItems()
	{
		$messages = parent::getItems();

		foreach ($messages as &$message) {
            $message->message = substr(strip_tags($message->message), 0,35); // Shorten messages to 35 characters
            $message->url = 'index.php?option=com_vitabook&task=message.edit&id=' . (int) $message->id; // Create edit link
            $message->date = VitabookHelper::formatDate($message); // format date according to settings
		}
		
		return $messages;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$catid = $this->getUserStateFromRequest($this->context.'.filter.catid', 'filter_catid');
		$this->setState('filter.catid', $catid);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published');
		$this->setState('filter.published', $published);

		$populared = $this->getUserStateFromRequest($this->context.'.filter.populared', 'filter_populared');
		$this->setState('filter.populared', $populared);
		
		$featured = $this->getUserStateFromRequest($this->context.'.filter.featured', 'filter_featured');
		$this->setState('filter.featured', $featured);
		
		// List state information.
		parent::populateState('a.rgt', 'DESC');
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		//-- Create a new query object.
		$query = parent::getListQuery();

		$query->select('a.*');
		$query->from('#__vitabook_messages as a');

		// Join over the categories.
		$query->select('c.title AS topic');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
	
		// Join over the categories.
		$query->select('u.name');
		$query->join('LEFT', '#__users AS u ON u.id = a.jid');
		
		//-- We don't want the root
		$query->where('(a.parent_id > 0)');

		//-- Filter state
		$published = $this->getState('filter.published');
		$featured = $this->getState('filter.featured');
		$populared = $this->getState('filter.populared');
		$catid = $this->getState('filter.catid');

		if ($published == '') {
			//$query->where('(published = 1 OR published = 0)');
		} else if ($published != '*') {
			$published = (int) $published;
			$query->where("published = '{$published}'");
		}

		if ($featured == '') {
			//$query->where('(published = 1 OR published = 0)');
		} else if ($featured != '*') {
			$featured = (int) $featured;
			$query->where("featured = '{$featured}'");
		}

		if ($populared == '') {
			//$query->where('(published = 1 OR published = 0)');
		} else if ($populared != '*') {
			$populared = (int) $populared;
			$query->where("populared = '{$populared}'");
		}
		//var_dump($catid);
		if ($catid > 0) {
			$catid = (int) $catid;
			//$query->where("catid = '{$catid}'");
			// Filter by a single or group of categories.
			if (is_numeric($catid)) {
				$query->where('a.catid = '.(int) $catid);
			}
			elseif (is_array($catid)) {
				JArrayHelper::toInteger($catid);
				$catid = implode(',', $catid);
				$query->where('a.catid IN ('.$catid.')');
			}
		}
		
		//-- Search
		$search = $this->getState('filter.search');

		$db = $this->getDbo();

		if (!empty($search)) {
			$search = '%' . $db->escape($search, true) . '%';

			$field_searches =
				"(message LIKE '{$search}' OR " .
				"title LIKE '{$search}' OR " .
				//"email LIKE '{$search}')";

			$query->where($field_searches);
		}

		//-- Column ordering
		$listOrdering = $this->getState('list.ordering');
		$listDirn = $db->escape($this->getState('list.direction'));

		//-- Standard order is 'rgt desc', as stated in the populateState method.
        $query->order($db->escape($listOrdering.' '.$listDirn));
        $query->order('a.rgt');

		return $query;
	}
}
