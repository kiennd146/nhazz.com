<?php
/**
 * @category	Tables
 * @package		JomSocial
 * @subpackage	Activities
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_community'.DS.'tables');

class CTableFile extends CTableCache
{
	var $id					= null;
	var $name               = null;
	var $groupid            = null;
	var $discussionid       = null;
	var $bulletinid         = null;
	var $eventid            = null;
	var $profileid			= null;
	var $filepath           = null;
	var $filesize           = null;
	var $type				= 'miscellaneous';
	var $storage            = 'file';
	var $hits				= 0;
	var $creator            = null;
	var $created			= null;


	public function __construct( &$db )
	{
		parent::__construct( '#__community_files', 'id', $db );
	}


	public function store()
	{
		$this->filepath	= CString::str_ireplace( '\\' , '/' , $this->filepath );
		return parent::store();
	}

	public function delete()
	{
		CFactory::load('libraries', 'storage');
		$storage = CStorage::getStorage($this->storage);

		$storage->delete($this->filepath);
		return parent::delete();
	}
}
?>