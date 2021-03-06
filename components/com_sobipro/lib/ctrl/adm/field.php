<?php
/**
 * @version: $Id: field.php 1979 2011-11-08 18:25:45Z Radek Suski $
 * @package: SobiPro Library
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2011 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/lgpl.html GNU/LGPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU Lesser General Public License version 3
 * ===================================================
 * $Date: 2011-11-08 19:25:45 +0100 (Tue, 08 Nov 2011) $
 * $Revision: 1979 $
 * $Author: Radek Suski $
 * File location: components/com_sobipro/lib/ctrl/adm/field.php $
 */
defined( 'SOBIPRO' ) || exit( 'Restricted access' );

SPLoader::loadController( 'controller' );
SPLoader::loadController( 'field' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 10-Jan-2009 4:38:46 PM
 */
final class SPFieldAdmCtrl extends SPFieldCtrl
{
	/**
	 * @var string
	 */
	protected $_type = 'field';
	/**
	 * @var string
	 */
	private $attr = array();

	/**
	 * While editing an field
	 * When adding new field - second step
	 */
	private function edit()
	{
		SPLoader::loadClass( 'html.input' );
		$fid = SPRequest::int( 'fid' );

		/* if adding new field - call #add */
		if( !$fid ) {
			return $this->add();
		}

		if ( $this->isCheckedOut() ) {
			SPMainFrame::msg( Sobi::Txt( 'FM.IS_CHECKED_OUT' ), SPC::ERROR_MSG );
		}
		else {
			/* check it out */
			$this->checkOut( $fid );
		}

		/* load base data */
		$db =& SPFactory::db();
		$f = $this->loadField( $fid );
		$groups = array();

		$fc = SPLoader::loadModel( 'field', true );
		$field = new $fc();
		$field->extend( $f );

		/* get cognate field types */
		if( $f->tGroup != 'special' ) {
			try {
				$db->select( '*', 'spdb_field_types', array( 'tGroup' => $f->tGroup ), 'fPos' );
				$fTypes = $db->loadObjectList();
			}
			catch ( SPException $x ) {
				Sobi::Error( $this->name(), SPLang::e( 'CANNOT_GET_FIELD_TYPES_DB_ERR', $x->getMessage() ), SPC::WARNING, 500, __LINE__, __FILE__ );
			}

			if( count( $fTypes ) ) {
				$pre = 'FIELD.TYPE_OPTG_';
				foreach ( $fTypes as $type ) {
					$groups[ str_replace( $pre, null, Sobi::Txt( $pre.$type->tGroup ) ) ][ $type->tid ] = $type->fType;
				}
			}
		}
		else {
			$groups[ Sobi::Txt( 'FIELD.TYPE_OPTG_SPECIAL' ) ][ $field->get( 'fieldType' ) ] = $f->fType;
		}

		/* get input filters */
		$registry =& SPFactory::registry();
		$registry->loadDBSection( 'fields_filter' );
		$helpTask = 'field.'.$field->get( 'fieldType' );
		$registry->set( 'help_task', $helpTask );
		$filters = $registry->get( 'fields_filter' );
		$f = array( 0 => Sobi::Txt( 'FM.NO_FILTER' ) );
		if( count( $filters ) ) {
			foreach ( $filters as $filter => $data ) {
				$f[ $filter ] = Sobi::Txt( $data[ 'value' ] );
			}
		}

		/* get view class */
		$class = SPLoader::loadView( 'field', true );
		$view = new $class();
		$view->addHidden( SPRequest::int( 'fid' ), 'fid' );
		$view->addHidden( SPRequest::sid(), 'sid' );
		$view->assign( $groups, 'types' );
		$view->assign( $f, 'filters' );
		$view->assign( $field, 'field' );
		$view->assign( $this->_task, 'task' );
		$field->onFieldEdit( $view );
		if( SPLoader::translatePath( 'field.edit.'.$field->get( 'fieldType' ), 'adm', true, 'ini' ) ) {
			$view->loadConfig( 'field.edit.'.$field->get( 'fieldType' ) );
		}
		else {
			$view->loadConfig( 'field.edit' );
		}
		$view->setTemplate( 'field.edit' );
		if( SPLoader::translatePath( 'field.edit.'.$field->get( 'fieldType' ), 'adm' ) ) {
			$view->setTemplate( 'field.edit.'.$field->get( 'fieldType' ) );
		}
		$view->display();
	}

	/**
	 * @param int $fid
	 * @return stdClass
	 */
	private function loadField( $fid )
	{
		/* @var SPdb $db */
		$db =& SPFactory::db();
		try {
			$db->select( '*', $db->join( array( array( 'table' => 'spdb_field', 'as' => 'sField', 'key' => 'fieldType' ), array( 'table' => 'spdb_field_types', 'as' => 'sType', 'key' => 'tid' ) ) ), array( 'fid' => $fid ) );
			$f = $db->loadObject();
		}
		catch ( SPException $x ) {
			Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 500, __LINE__, __FILE__ );
		}
		return $f;
	}

	/**
	 * Just when adding new field - first step
	 */
	private function add()
	{
		SPLoader::loadClass( 'html.input' );
		/* @var SPdb $db */
		$db =& SPFactory::db();

		/* get all existing field types */
		try {
			$db->select( '*', 'spdb_field_types', null, 'fPos' );
			$fTypes = $db->loadObjectList();
		}
		catch ( SPException $x ) {
			Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 500, __LINE__, __FILE__ );
		}

		$groups = array();
		if( count( $fTypes ) ) {
			$pre = 'FIELD.TYPE_OPTG_';
			foreach ( $fTypes as $type ) {
				$groups[ str_replace( $pre, null, Sobi::Txt( $pre.$type->tGroup ) ) ][ $type->tid ] = $type->fType;
			}
		}
		/* create dummy field with initial values */
		$field = array(
            'name' 			=> '',
            'nid' 			=> '',
            'notice' 		=> '',
            'description' 	=> '',
            'adminField' 	=> 0,
            'enabled' 		=> 1,
            'fee' 			=> 0,
            'isFree' 		=> 1,
			'withLabel'		=> 1,
            'version' 		=> 1,
            'editable' 		=> 1,
            'required' 		=> 0,
            'showIn' 		=> 'details',
            'editLimit' 	=> -1,
            'version' 		=> 1,
            'inSearch' 		=> 0,
			'cssClass'		=> '',
            'fieldType' 	=> ''
         );

         /* get view class */
         $class  = SPLoader::loadView( 'field', true );
         $view   = new $class();
         $view->addHidden( SPRequest::sid(), 'sid' );
         $view->assign( $groups, 'types' );
         $view->assign( $field, 'field' );
         $view->assign( $this->_task, 'task' );
         $view->loadConfig( 'field.edit' );
         $view->setTemplate( 'field.edit' );
         $view->display();
	}

	/**
	 * @TODO should be moved to the model ????
	 * Adding new field
	 * Save base data and redirect to the edit function when the field type has been chosed
	 * @todo it should be moved to the model
	 * @return integer
	 */
	public function saveNew()
	{
		$fc= SPLoader::loadModel( 'field', true );
		$field = new $fc();
		$this->getRequest();
		return $field->saveNew( $this->attr );
	}

	/**
	 * Get data from request
	 */
	private function getRequest()
	{
		foreach ( $_REQUEST as $k => $v ) {
			if( strstr( $k, 'field_' ) ) {
				$value = SPRequest::raw( $k );
				$this->attr[ str_replace( 'field_', null, $k ) ] = $value;
			}
		}
	}

	/**
	 */
	public function delete( $id = 0 )
	{
		$fids = array();
		$m = array();
		if( $id ) {
			$fids[] = $id;
		}
		else {
			if( SPRequest::int( 'fid', 0 ) ) {
				$fids[] = SPRequest::int( 'fid', 0 );
			}
			else {
				$fids = SPRequest::arr( 'p_fid', array() );
			}
		}
		foreach ( $fids as $id ) {
			$fc= SPLoader::loadModel( 'field', true );
			$field = new $fc();
			$field->extend( $this->loadField( $id ) );
			$m[] = $field->delete();
		}
		SPMainFrame::msg( $m );
		return $m;
	}

	public function checkOut()
	{
	}

	public function isCheckedOut()
	{
	}

	public function checkIn()
	{
	}

	/**
	 * Save existing field
	 */
	protected function save( $clone = false )
	{
		if( !( SPFactory::mainframe()->checkToken() ) ) {
			Sobi::Error( 'Token', SPLang::e( 'UNAUTHORIZED_ACCESS_TASK', SPRequest::task() ), SPC::ERROR, 403, __LINE__, __FILE__ );
		}
		$fid = SPRequest::int( 'fid' );
		$f = $this->loadField( $fid );
		$fClass = SPLoader::loadModel( 'field', true );
		$field = new $fClass();
		$field->extend( $f );
		$this->getRequest();

		/* in case we are changing the sort by field */
		$onid = $field->get( 'nid' );

		if( $clone ) {
			SPRequest::set( 'fid', 0, 'post' );
			$fid = $field->saveNew( $this->attr );
			$field->save( $this->attr );
		}
		else {
			$field->save( $this->attr );
		}

		/* in case we are changing the sort by field */
		if( Sobi::Cfg( 'list.entries_ordering' ) == $onid && $field->get( 'nid' ) != $onid ) {
			SPFactory::config()->saveCfg( 'list.entries_ordering', $field->get( 'nid' ) );
		}

		SPFactory::cache()->cleanSection();
		if( $this->_task == 'apply' || $clone ) {
			if( $clone ) {
				$msg = Sobi::Txt( 'FM.FIELD_CLONED' );
			}
			else {
				$msg = Sobi::Txt( 'MSG.ALL_CHANGES_SAVED' );
			}
			Sobi::Redirect( Sobi::Url( array( 'task' => 'field.edit', 'fid' => $fid, 'sid' => SPRequest::sid() ) ), $msg );
		}
		else {
			Sobi::Redirect( Sobi::Back(), Sobi::Txt( 'MSG.ALL_CHANGES_SAVED' ) );
		}

	}

	/**
	 * List all fields in this section
	 */
	private function listFields()
	{
		/* @var SPdb $db */
		$db =& SPFactory::db();
		$ord = $this->parseOrdering( 'forder', 'position.asc' );
		SPLoader::loadClass( 'html.input' );
		Sobi::ReturnPoint();

		/* create menu */
		$sid = Sobi::Reg( 'current_section' );
		$menuc = SPLoader::loadClass( 'helpers.adm.menu' );
		$menu = new $menuc( 'field.list', $sid );
		$cfg = SPLoader::loadIniFile( 'etc.adm.section_menu' );
		Sobi::Trigger( 'Create', 'AdmMenu', array( &$cfg ) );
		if( count( $cfg ) ) {
			foreach ( $cfg as $section => $keys ) {
				$menu->addSection( $section, $keys );
			}
		}
		Sobi::Trigger( 'AfterCreate', 'AdmMenu', array( &$menu ) );
		/* create new SigsiuTree */
		$tree = SPLoader::loadClass( 'mlo.tree' );
		$tree = new $tree( Sobi::GetUserState( 'categories.order', 'corder', 'position.asc' ) );
		/* set link */
		$tree->setHref( Sobi::Url( array( 'sid' => '{sid}') ) );
		$tree->setId( 'menuTree' );
		/* set the task to expand the tree */
		$tree->setTask( 'category.expand' );
		$tree->init( $sid );
		/* add the tree into the menu */
		$menu->addCustom( 'AMN.ENT_CAT', $tree->getTree() );

		try {
			$db->select( '*', 'spdb_field', array( 'section' => $sid ), $ord );
			$results = $db->loadObjectList();
		}
		catch ( SPException $x ) {
			Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
		}
		$fields = array();
		if( count( $results ) ) {
			foreach ($results as $result ) {
				$field = SPFactory::Model( 'field', true );
				$field->extend( $result );
				$fields[] = $field;
			}
		}
		/* get view class */
		$class  = SPLoader::loadView( 'field', true );
		$view   = new $class();
		$view->addHidden( $sid, 'sid' );
		$view->loadConfig( 'field.list' );
		$view->setTemplate( 'field.list' );
		$view->assign( $fields, 'fields' );
		$view->assign( $menu, 'menu' );
		$view->assign( $this->_task, 'task' );
		$view->display();
	}

	/**
	 * @param string $col
	 * @param string $def
	 * @return string
	 */
	protected function parseOrdering( $col, $def )
	{
		$order = Sobi::GetUserState( 'fields.order', $col, $def );
		$ord = $order;
		$dir = 'asc';

		if( strstr( $ord, '.' ) ) {
			$ord = explode( '.', $ord );
			$dir = $ord[ 1 ];
			$ord = $ord[ 0 ];
		}
		$ord = ( $ord == 'state' ) ? 'enabled' : $ord;
		$ord = ( $ord == 'order' ) ? 'position' : $ord;
		if( $ord == 'name' ) {
			/* @var SPdb $db */
			$db =& SPFactory::db();
			$db->select( 'fid', 'spdb_language', array( 'oType' => 'field', 'sKey' => 'name', 'language' => Sobi::Lang() ), 'sValue.'.$dir );
			$fields = $db->loadResultArray();
			if( !count( $fields ) && Sobi::Lang() != Sobi::DefLang() ) {
				$db->select( 'id', 'spdb_language', array( 'oType' => 'field', 'sKey' => 'name', 'language' => Sobi::DefLang() ), 'sValue.'.$dir );
				$fields = $db->loadResultArray();
			}
			if( count( $fields ) ) {
				$fields = implode( ',', $fields );
				$ord = "field( fid, {$fields} )";
			}
			else {
				$ord = 'fid.'.$dir;
			}
		}
		else {
			$ord = $ord.'.'.$dir;
		}
		Sobi::setUserState( 'fields.order', $order );
		return $ord;
	}

	/**
	 */
	private function reorder()
	{
		/* @var SPdb $db */
		$db =& SPFactory::db();
		$fids = SPRequest::arr( 'fid', array() );
		asort( $fids );
		$c = 0;
		foreach ( $fids as $fid => $pos ) {
			$c++;
			$pos++;
			try {
				$db->update( 'spdb_field', array( 'position' => $c ), array( 'fid' => $fid ) );
			}
			catch ( SPException $x ) {
				Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 500, __LINE__, __FILE__ );
			}
		}
	}

	/**
	 * @param bool $up
	 */
	private function singleReorder( $up )
	{
		$up = ( bool ) $up;
		/* @var SPdb $db */
		$db =& SPFactory::db();
		$fid = SPRequest::int( 'fid' );
		$fClass = SPLoader::loadModel( 'field', true );
		$fdata = $this->loadField( $fid );
		$field = new $fClass();
		$field->extend( $fdata );
		$eq = $up ? '<' : '>';
		$dir = $up ? 'position.desc' : 'position.asc';
		$current = $field->get( 'position' ) ;
		try {
			$db->select( 'position, fid', 'spdb_field', array( 'position'.$eq => $current, 'section' => SPRequest::int( 'sid' ) ), $dir, 1 );
			$interchange = $db->loadAssocList();
			if( $interchange && count( $interchange ) ) {
				$db->update( 'spdb_field', array( 'position' => $interchange[ 0 ][ 'position' ] ), array( 'section' => SPRequest::int( 'sid' ), 'fid' => $field->get( 'fid' ) ) , 1 );
				$db->update( 'spdb_field', array( 'position' => $current ), array( 'section' => SPRequest::int( 'sid' ), 'fid' => $interchange[ 0 ][ 'fid' ] ) , 1 );
			}
			else {
				$current = $up ? $current-- : $current++;
				$db->update( 'spdb_field', array( 'position' => $current ), array( 'section' => SPRequest::int( 'sid' ), 'fid' => $field->get( 'fid' ) ), 1 );
			}
		}
		catch ( SPException $x ) {
			Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 500, __LINE__, __FILE__ );
		}
	}

	/**
	 *
	 * @param task
	 * @return array
	 */
	protected function changeState( $task )
	{
		/* @var SPdb $db */
		$db =& SPFactory::db();
		$fids = SPRequest::arr( 'p_fid' );
		$col = 'enabled';
		$state = '0';
		switch ( $task ) {
			case 'hide':
			case 'publish':
				$col = 'enabled';
				$state = ( $task == 'publish' ) ? 1 : 0;
				break;
			case 'setRequired':
			case 'setNotRequired':
				$col = 'required';
				$state = ( $task == 'setRequired' ) ? 1 : 0;
				break;
			case 'setEditable':
			case 'setNotEditable':
				$col = 'editable';
				$state = ( $task == 'setEditable' ) ? 1 : 0;
				break;
			case 'setFee':
			case 'setFree':
				$col = 'isFree';
				$state = ( $task == 'setFree' ) ? 1 : 0;
				break;
		}
		if( !$fids ) {
			$fids = array( SPRequest::int( 'fid' ) );
		}
		$msg = null;
		if( count( $fids ) ) {
			foreach ( $fids as $fid ) {
				try {
					$db->update( 'spdb_field', array( $col => $state ), array( 'fid' => $fid ) , 1 );
					$msg .= Sobi::Txt( 'FM.STATE_CHANGED', array( 'fid' => $fid ) );
				}
				catch ( SPException $x ) {
					Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 500, __LINE__, __FILE__ );
					$msg .= Sobi::Txt( 'FM.STATE_NOT_CHANGED', array( 'fid' => $fid ) );
				}
			}
		}
		SPFactory::cache()->cleanSection( Sobi::Section() );
		return $msg;
	}

	/**
	 * Route task
	 */
	public function execute()
	{
		/* parent class executes the plugins */
		$r = false;
		switch ( $this->_task ) {
			case 'list':
				$r = true;
				$this->listFields();
				break;
			case 'add':
			case 'edit':
				$r = true;
				$this->edit();
				break;
			case 'cancel':
				$r = true;
				$this->checkIn();
				Sobi::Redirect( Sobi::Back() );
				break;
			case 'addNew':
				$r = true;
				Sobi::Redirect( Sobi::Url( array( 'task' => 'field.edit', 'fid' => $this->saveNew(), 'sid' => SPRequest::sid() ) ) );
				break;
			case 'apply':
			case 'save':
				$r = true;
				$this->save();
				break;
			case 'clone':
				$r = true;
				$this->save( true );
				break;
			case 'delete':
				$r = true;
				SPFactory::cache()->cleanSection();
				SPMainFrame::setRedirect( Sobi::Back(), $this->delete()  );
				break;
			case 'reorder':
				$r = true;
				$this->reorder();
				SPFactory::cache()->cleanSection();
				Sobi::Redirect( Sobi::Back(), "New ordering has been saved" );
				break;
			case 'up':
			case 'down':
				$r = true;
				$this->singleReorder( $this->_task == 'up' );
				SPFactory::cache()->cleanSection();
				Sobi::Redirect( Sobi::Back(), 'New ordering has been saved' );
				break;
			case 'hide':
			case 'publish':
			case 'setRequired':
			case 'setNotRequired':
			case 'setEditable':
			case 'setNotEditable':
			case 'setFee':
			case 'setFree':
				$r = true;
				$this->authorise( $this->_task );
				SPFactory::cache()->cleanSection();
				SPMainFrame::setRedirect( Sobi::Back(), $this->changeState( $this->_task ) );
				break;
			default:
				/* case plugin didn't registered this task, it was an error */
				if( !Sobi::Trigger( 'Execute', $this->name(), array( &$this ) ) ) {
					$fid = SPRequest::int( 'fid' );
					$method = $this->_task;
					if( $fid ) {
						SPLoader::loadModel( 'field', true );
						$fdata = $this->loadField( $fid );
						$field = new SPAdmField();
						$field->extend( $fdata );
						try {
							$field->$method();
						}
						catch ( SPException $x ) {
							Sobi::Error( $this->name(), SPLang::e( 'SUCH_TASK_NOT_FOUND', SPRequest::task() ), SPC::NOTICE, 404, __LINE__, __FILE__ );
						}
					}
					else {
						Sobi::Error( $this->name(), SPLang::e( 'SUCH_TASK_NOT_FOUND', SPRequest::task() ), SPC::NOTICE, 404, __LINE__, __FILE__ );
					}
				}
				break;
		}
		return $r;
	}
}
?>