<?php
/**
 * @package		JomSocial
 * @subpackage	Library
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

Class CFilesAccess implements CAccessInterface
{

	/**
	 * Method to check if a user is authorised to perform an action in this class
	 *
	 * @param	integer	$userId	Id of the user for which to check authorisation.
	 * @param	string	$action	The name of the action to authorise.
	 * @param	mixed	$asset	Name of the asset as a string.
	 *
	 * @return	boolean	True if authorised.
	 * @since	Jomsocial 2.4
	 */
	static public function authorise()
	{
		$args      = func_get_args();          
		$assetName = array_shift ( $args );
                
        if (method_exists(__CLASS__,$assetName)) {
			return call_user_func_array(array(__CLASS__, $assetName), $args);
		} else {
			return null;
		}
	}

	static public function filesDiscussionAdd($userId,$discussionId)
	{
		$config                 = CFactory::getConfig();
		CFactory::load('helpers' , 'limits' );
		CFactory::load( 'libraries' , 'limits' );

		$discussionTable =& JTable::getInstance('Discussion' , 'CTable' );
		$discussionTable->load($discussionId);

		$groupModel		= CFactory::getModel( 'groups' );
		
		$discusionParams = $discussionTable->getParams();

		if($userId == 0)
		{
			CAccess::setError('blockUnregister');
			return false;
		}

		if(!CLimitsHelper::exceededGroupFileUpload($discussionTable->groupid))
		{
			return false;
		}

		if( CLimitsLibrary::exceedDaily( 'files',$userId ) )
		{
			return false;
		}
		
		if( COwnerHelper::isCommunityAdmin() || $groupModel->isAdmin($userId, $discussionTable->groupid) || ($groupModel->isMember($userId, $discussionTable->groupid) && $discusionParams->get('filepermission-member')) )
		{
			return true;
		}

		return false;
	}

	static public function filesDiscussionDelete($userId,$obj)
	{
		$discussionTable =& JTable::getInstance('Discussion' , 'CTable' );
		$discussionTable->load($obj->discussionid);


		$groupModel		= CFactory::getModel( 'groups' );

		if( COwnerHelper::isCommunityAdmin() || $groupModel->isAdmin($userId, $discussionTable->groupid) || ($discussionTable->creator == $userId) || ($obj->creator == $userId))
		{
			return true;
		}

		return false;

	}
	
	static public function filesBulletinAdd($userId,$bulletinId)
	{

		if($userId == 0)
		{
			CAccess::setError('blockUnregister');
			return false;
		}

		$table =& JTable::getInstance('Bulletin' , 'CTable' );
		$table->load($bulletinId);

		CFactory::load('helpers' , 'limits' );
		CFactory::load( 'libraries' , 'limits' );

		$groupModel		= CFactory::getModel( 'groups' );
		
		$bulletinParams = $table->getParams();

		if(!CLimitsHelper::exceededGroupFileUpload($table->groupid))
		{
			return false;
		}

		if( CLimitsLibrary::exceedDaily( 'files',$userId ) )
		{
			return false;
		}

		if( COwnerHelper::isCommunityAdmin()|| $groupModel->isAdmin($userId, $table->groupid) || ($groupModel->isMember($userId, $table->groupid) && $bulletinParams->get('filepermission-member')))
		{
			return true;
		}

		return false;
	}

	static public function filesBulletinDelete($userId,$obj)
	{
		$table =& JTable::getInstance('Bulletin' , 'CTable' );
		$table->load($obj->discussionid);


		$groupModel		= CFactory::getModel( 'groups' );

		if( COwnerHelper::isCommunityAdmin() || $groupModel->isAdmin($userId, $table->groupid) || ($table->creator == $userId) || ($obj->creator == $userId))
		{
			return true;
		}

		return false;

	}

	static public function filesDiscussionDownload($userId,$discussionId)
	{
		$discussionTable =& JTable::getInstance('Discussion' , 'CTable' );
		$discussionTable->load($discussionId);

		$groupModel		= CFactory::getModel( 'groups' );

		if( COwnerHelper::isCommunityAdmin() || $groupModel->isAdmin($userId, $discussionTable->groupid) || $groupModel->isMember($userId, $discussionTable->groupid) )
		{
			return true;
		}

		return false;
	}

	static public function filesBulletinDownload($userId,$bulletinId)
	{

		$table =& JTable::getInstance('Bulletin' , 'CTable' );
		$table->load($discussionId);

		$groupModel		= CFactory::getModel( 'groups' );

		if( COwnerHelper::isCommunityAdmin() || $groupModel->isAdmin($userId, $table->groupid) || $groupModel->isMember($userId, $table->groupid) )
		{
			return true;
		}

		return false;
	}

	static public function filesGroupDownload($userId, $groupId)
	{
		$groupModel		= CFactory::getModel( 'groups' );

		if( COwnerHelper::isCommunityAdmin() || $groupModel->isAdmin($userId, $groupId) || $groupModel->isMember($userId, $groupId) )
		{
			return true;
		}

		return false;
	}
}