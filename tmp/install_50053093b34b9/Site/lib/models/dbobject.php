<?php
/**
 * @version: $Id: dbobject.php 2192 2012-01-27 13:30:05Z Radek Suski $
 * @package: SobiPro Library
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2012 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/lgpl.html GNU/LGPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU Lesser General Public License version 3
 * ===================================================
 * $Date: 2012-01-27 14:30:05 +0100 (Fri, 27 Jan 2012) $
 * $Revision: 2192 $
 * $Author: Radek Suski $
 * File location: components/com_sobipro/lib/models/dbobject.php $
 */

defined( 'SOBIPRO' ) || exit( 'Restricted access' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 10-Jan-2009 5:24:28 PM
 */
abstract class SPDBObject extends SPObject
{
	/**
	 * @var bool
	 */
	protected $approved = false;
	/**
	 * @var bool
	 */
	protected $confirmed = false;
	/**
	 * @var int
	 */
	protected $counter = 0;
	/**
	 * @var int
	 */
	protected $section = 0;
	/**
	 * @var bool
	 */
	protected $cout = false;
	/**
	 * @var string
	 */
	protected $coutTime = null;
	/**
	 * @var string
	 */
	protected $createdTime = null;
	/**
	 * @var string
	 */
	protected $defURL = null;
	/**
	 * @var int database object id
	 */
	protected $id = 0;
	/**
	 * @var string
	 */
	protected $nid = null;
	/**
	 * @var string
	 */
	protected $metaDesc = null;
	/**
	 * @var string
	 */
	protected $metaKeys = null;
	/**
	 * @var string
	 */
	protected $metaAuthor = null;
	/**
	 * @var string
	 */
	protected $metaRobots = null;
	/**
	 * @var string
	 */
	protected $name = null;
	/**
	 * @var array
	 */
	protected $options = array();
	/**
	 * @var string
	 */
	protected $oType = null;
	/**
	 * @var int
	 */
	protected $owner = 0;
	/**
	 * @var string
	 */
	protected $ownerIP = null;
	/**
	 * @var array
	 */
	protected $params = array();
	/**
	 * @var int
	 */
	protected $parent = 0;
	/**
	 * @var string
	 */
	protected $query = null;
	/**
	 * @var int
	 */
	protected $state = 0;
	/**
	 * @var string
	 */
	protected $stateExpl = null;
	/**
	 * @var string
	 */
	protected $template = null;
	/**
	 * @var string
	 */
	protected $updatedTime = null;
	/**
	 * @var int
	 */
	protected $updater = 0;
	/**
	 * @var string
	 */
	protected $updaterIP = null;
	/**
	 * @var string
	 */
	protected $validSince = null;
	/**
	 * @var string
	 */
	protected $validUntil = null;
	/**
	 * @var int
	 */
	protected $version = 0;

	/**
	 * list of adjustable proporties and the corresponding request method for each property.
	 * If a property isn't declared here, it will be ignored in the getRequest method
	 * @var array
	 */
	private static $types = array(
		'approved' => 'bool',
		'confirmed' => 'bool',
		'counter' => 'int',
		'createdTime' => 'datetime',
		'defURL' => 'string',
		'metaAuthor' => 'string',
		'metaDesc' => 'string',
		'metaKeys' => 'string',
		'metaRobots' => 'string',
		'name' => 'string',
		'nid' => 'cmd',
	/**
	 * the id is not needed (and it's dengerous) because if we updating an object it's beeing created through the id anyway
	 * so at that point we have to already have it. If not, we don't need it because then we are creating a new object
	 */
//		'id' => 'int',
		'owner' => 'int',
		'ownerIP' => 'ip',
		'parent' => 'int',
		'state' => 'int',
		'stateExpl' => 'string',
		'validSince' => 'datetime',
		'validUntil' => 'datetime',
	);
	/**
	 * @var array
	 */
	private static $translatable = array( 'name', 'metaKeys', 'metaDesc' );

	/**
	 * @return bool
	 */
	public function __construct()
	{
		$this->validUntil 	= SPFactory::config()->date( SPFactory::db()->getNullDate(), 'date.db_format' );
		$this->createdTime	= SPFactory::config()->date( time(), 'date.db_format' );
		$this->validSince 	= SPFactory::config()->date( time(), 'date.db_format' );
		Sobi::Trigger( 'CreateModell', $this->name(), array( &$this ) );
	}

	public function formatDatesToEdit()
	{
		if( $this->validUntil ) {
			$this->validUntil = SPFactory::config()->date( $this->validUntil, 'date.publishing_format' );
		}
		$this->createdTime	= SPFactory::config()->date( $this->createdTime, 'date.publishing_format' );
		$this->validSince 	= SPFactory::config()->date( $this->validSince, 'date.publishing_format' );
	}

	/**
	 * @param int $state
	 * @param string $reason
	 */
	public function changeState( $state, $reason = null )
	{
		try {
			SPFactory::db()->update( 'spdb_object', array( 'state' => ( int ) $state, 'stateExpl' => $reason ), array( 'id' => $this->id ) );
		}
		catch ( SPException $x ) {
			Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::ERROR, 500, __LINE__, __FILE__ );
		}
		SPFactory::cache()->purgeSectionVars();
		SPFactory::cache()->deleteObj( $this->type(), $this->id );
	}

	/**
	 * Checking in current object
	 */
	public function checkIn()
	{
		if( $this->id ) {
			$this->cout = 0;
			$this->coutTime = null;
			try {
				SPFactory::db()->update( 'spdb_object', array( 'coutTime' => $this->coutTime, 'cout' => $this->cout ), array( 'id' => $this->id ) );
			}
			catch ( SPException $x ) {
				Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
			}
		}
	}

	/**
	 * checking out current object
	 */
	public function checkOut()
	{
		if( $this->id ) {
			/* @var SPdb $db */
			$config =& SPFactory::config();
			$this->cout = Sobi::My( 'id' );
			$this->coutTime = $config->date( ( time() + $config->key( 'editing.def_cout_time', 3600 ) ), 'date.db_format' );
			try {
				SPFactory::db()->update( 'spdb_object', array( 'coutTime' => $this->coutTime, 'cout' => $this->cout ), array( 'id' => $this->id ) );
			}
			catch ( SPException $x ) {
				Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
			}
		}
	}

	/**
	 */
	public function delete()
	{
		Sobi::Trigger( $this->name(), ucfirst( __FUNCTION__ ), array( $this->id ) );
		try {
			SPFactory::db()->delete( 'spdb_object', array( 'id' => $this->id ) );
		}
		catch ( SPException $x ) {
			Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
		}
		try {
			SPFactory::db()->delete( 'spdb_relations', array( 'id' => $this->id, 'oType' => $this->type() ) );
		}
		catch ( SPException $x ) {
			Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
		}
		try {
			SPFactory::db()->delete( 'spdb_language', array( 'id' => $this->id, 'oType' => $this->type() ) );
		}
		catch ( SPException $x ) {
			Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
		}
	}

	/**
	 * @param string $type
	 * @param bool $recursive
	 * @return array
	 */
	public function getChilds( $type = 'entry', $recursive = false, $state = 0, $name = false )
	{
		$childs = SPFactory::cache()->getVar( 'childs_'.$type.( $recursive ? '_recursive' : '' ).( $name ? '_full' : '' ).$state, $this->id );
		if( $childs ) {
			return $childs == SPC::NO_VALUE ? array() : $childs;
		}
		$db	=& SPFactory::db();
		$childs = array();
		try {
			$cond = array( 'pid' => $this->id );
			if( $state ) {
				$cond[ 'so.state' ] = $state ;
				$tables = $db->join(
					array(
						array( 'table' => 'spdb_object', 'as' => 'so', 'key' => 'id' ),
						array( 'table' => 'spdb_relations', 'as' => 'sr', 'key' => 'id' )
					)
				);
				$db->select( array( 'sr.id', 'sr.oType' ), $tables, $cond );
			}
			else {
				$db->select( array( 'id', 'oType' ), 'spdb_relations', $cond );
			}
			$results = $db->loadAssocList( 'id' );
		}
		catch ( SPException $x ) {
			Sobi::Error( $this->name(), SPLang::e( 'CANNOT_GET_CHILDS_DB_ERR', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
		}
		if( $recursive && count( $results ) ) {
			foreach ( $results as $cid ) {
				$this->rGetChilds( $results, $cid, $state );
			}
		}
		if( count( $results ) ) {
			if( $type == 'all' ) {
				foreach ( $results as $id => $r ) {
					$childs[ $id ] = $r[ 'id' ];
				}
			}
			else {
				foreach ( $results as $id => $r ) {
					if( $r[ 'oType' ] == $type ) {
						$childs[ $id ] = $id;
					}
				}
			}
		}
		if( $name && count( $childs ) ) {
			$names = SPLang::translateObject( $childs, 'name', $type );
			if( is_array( $names ) && !empty( $names ) ) {
				foreach ( $childs as $i => $id ) {
					$childs[ $i ] = $names[ $id ][ 'value' ];
				}
			}
		}
		if( !$state ) {
			SPFactory::cache()->addVar( $childs, 'childs_'.$type.( $recursive ? '_recursive' : '' ).( $name ? '_full' : '' ).$state, $this->id );
		}
		return $childs;
	}

	/**
	 * @param array $results
	 * @param string $type
	 * @param int $id
	 */
	private function rGetChilds( &$results, $id )
	{
		if( is_array( $id ) ) {
			$id = $id[ 'id' ];
		}
		/* @var SPdb $db */
		$db	=& SPFactory::db();
		try {
			$cond = array( 'pid' => $id );
			$db->select( array( 'id', 'oType' ), 'spdb_relations', $cond );
			$r = $db->loadAssocList( 'id' );
		}
		catch ( SPException $x ) {
			Sobi::Error( $this->name(), SPLang::e( 'CANNOT_GET_CHILDS_DB_ERR', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
		}
		if( count( $r ) ) {
			foreach ( $r as $id => $rs ) {
				$results[ $id ] = $rs;
				$this->rGetChilds( $results, $id );
			}
		}
	}

	/**
	 */
	protected function getFullPath()
	{
	}

	/**
	 * Gettin data from request for this object
	 * @param string $prefix
	 * @param string $request
	 */
	public function getRequest( $prefix = null, $request = 'POST' )
	{
		$prefix = $prefix ? $prefix.'_' : null;
		/* get data types of my  properties */
		$properties = get_object_vars( $this );
		Sobi::Trigger( $this->name(), ucfirst( __FUNCTION__ ).'Start', array( &$properties ) );
		/* and of the parent proporties */
		$types = array_merge( $this->types(), self::$types );
		foreach ( $properties as $property => $values ) {
			/* if this is an internal variable */
			if( substr( $property, 0, 1 ) == '_' ) {
				continue;
			}
			/* if no data type has been declared */
			if( !isset( $types[ $property ] ) ) {
				continue;
			}
			/* if there was no data for this property ( not if it was just empty ) */
			if( !( SPRequest::exists( $prefix.$property, $request ) ) ) {
				continue;
			}
			/* if the declared data type has not handler in request class */
			if( !method_exists( 'SPRequest', $types[ $property ] ) ) {
				Sobi::Error( $this->name(), SPLang::e( 'Method %s does not exists', $types[ $property ] ), SPC::WARNING, 0, __LINE__, __FILE__ );
				continue;
			}
			/* now we get it ;) */
			$this->$property = SPRequest::$types[ $property ]( $prefix.$property, null, $request );
		}
		/* trigger plugins */
		Sobi::Trigger( 'getRequest', $this->name(), array( &$this ) );
	}

	public function countChilds( $type = null, $state = 0 )
	{
		return count( $this->getChilds( $type, true, $state ) );
	}

	/**
	 * @return string
	 */
	public function type()
	{
		return $this->oType;
	}

	public function countVisit( $reset = false )
	{
		$count = true;
		Sobi::Trigger( 'CountVisit', ucfirst( $this->type() ), array( &$count, $this->id ) );
		if( $this->id && $count ) {
			try {
				SPFactory::db()->update( 'spdb_object', array( 'counter' => ( $reset ? '0' : '++' ) ), array( 'id' => $this->id ), 1 );
			}
			catch ( SPException $x ) {
				Sobi::Error( $this->name(), SPLang::e( 'CANNOT_INC_COUNTER_DB', $x->getMessage() ), SPC::ERROR, 0, __LINE__, __FILE__ );
			}
		}
	}

	/**
	 */
	public function save( $request = 'post' )
	{
		$this->version++;
		/* get current data */
		$this->updatedTime 	= SPRequest::now();
		$this->updaterIP = SPRequest::ip( 'REMOTE_ADDR', 0, 'SERVER' );
		$this->updater = Sobi::My( 'id' );
		$this->nid = SPLang::nid( $this->nid );
		/* get THIS class properties */
		$properties = get_class_vars( __CLASS__ );

		/* if new object */
		if( !$this->id ) {
			$this->createdTime 	= $this->createdTime ? $this->createdTime : $this->updatedTime;
			$this->owner		= $this->owner ? $this->owner : $this->updater;
			$this->ownerIP 		= $this->updaterIP;
		}
		/* just a security check to avoid mistakes */
		else {
			$obj = SPFactory::object( $this->id );
			if ( $obj->oType != $this->oType ) {
				Sobi::Error( 'Object Save',  sprintf( 'Serious security violation. Trying to save an object which claims to be an %s but it is a %s. Task was %s',  $this->oType, $obj->oType, SPRequest::task() ), SPC::ERROR, 403, __LINE__, __FILE__ );
				exit;
			}
		}

		/* @var SPdb $db */
		$db	=& SPFactory::db();
		$db->transaction();

		/* get database colums and their ordering */
		$cols	= $db->getColumns( 'spdb_object' );
		$values = array();

		/*
		 * @todo: manage own is not implemented yet
		 */
		//$this->approved = Sobi::Can( $this->type(), 'manage', 'own' );
		$this->approved = Sobi::Can( $this->type(), 'publish', 'own' );
		/* if not published, check if user can manage own and if yes, publish it */
		if( !( $this->state ) && !( defined( 'SOBIPRO_ADM' ) ) ) {
			$this->state = Sobi::Can( $this->type(), 'publish', 'own' );
		}

		/* and sort the properties in the same order */
		foreach ( $cols as $col ) {
			$values[ $col ] = key_exists( $col, $properties ) ? $this->$col : '';
		}
		/* trigger plugins */
		Sobi::Trigger( 'save', $this->name(), array( &$this ) );
		/* try to save */
		try {
			/* if new object */
			if( !$this->id ) {
				$db->insert( 'spdb_object', $values );
				$this->id = $db->insertid();
			}
			/* if update */
			else {
				$db->update( 'spdb_object', $values, array( 'id' => $this->id ) );
			}
		}
		catch ( SPException $x ) {
			$db->rollback();
			Sobi::Error( $this->name(), SPLang::e( 'CANNOT_SAVE_OBJECT_DB_ERR', $x->getMessage() ), SPC::ERROR, 500, __LINE__, __FILE__ );
		}

		/* get translatable properties */
		$attributes = array_merge( $this->translatable(), self::$translatable );
		$labels = array();
		$defLabels = array();
		foreach ( $attributes as $attr ) {
			if( $this->has( $attr ) ) {
				$labels[] = array( 'sKey' => $attr, 'sValue' => $this->$attr, 'language' => Sobi::Lang(), 'id' => $this->id, 'oType' => $this->type(), 'fid' => 0 );
				if( Sobi::Lang() != Sobi::DefLang() ) {
					$defLabels[] = array( 'sKey' => $attr, 'sValue' => $this->$attr, 'language' => Sobi::DefLang(), 'id' => $this->id, 'oType' => $this->type(), 'fid' => 0 );
				}
			}
		}

		/* save translatable properties */
		if( count( $labels ) ) {
			try {
				if( Sobi::Lang() != Sobi::DefLang() ) {
					$db->insertArray( 'spdb_language', $defLabels, false, true );
				}
				$db->insertArray( 'spdb_language', $labels, true );
			}
			catch ( SPException $x ) {
				Sobi::Error( $this->name(), SPLang::e( 'CANNOT_SAVE_OBJECT_DB_ERR', $x->getMessage() ), SPC::ERROR, 500, __LINE__, __FILE__ );
			}
		}
		$db->commit();
		$this->checkIn();
	}

	/**
	 * Dummy function
	 */
	public function update()
	{
		return $this->save();
	}

	/**
	 * @param stdClass $obj
	 */
	public function extend( $obj )
	{
		if( !empty( $obj ) ) {
			foreach ( $obj as $k => $v ) {
				$this->_set( $k, $v );
			}
		}
		Sobi::Trigger( $this->name(), ucfirst( __FUNCTION__ ), array( &$obj ) );
		$this->loadTable();
		// causing some date formatting issues
//		$this->createdTime	= SPFactory::config()->date( $this->createdTime, 'Y-m-d H:i:s' );
//		$this->updatedTime	= SPFactory::config()->date( $this->updatedTime, 'Y-m-d H:i:s' );
//		$this->validSince 	= SPFactory::config()->date( $this->validSince, 'Y-m-d H:i:s' );
		$this->validUntil 	= SPFactory::config()->date( $this->validUntil, 'Y-m-d H:i:s' );
	}

	/**
	 * @param stdClass $obj
	 */
	public function & init( $id = 0 )
	{
		Sobi::Trigger( $this->name(), ucfirst( __FUNCTION__ ).'Start', array( &$id ) );
		$this->id = $id ? $id : $this->id;
		if( $this->id ) {
			try {
				$obj = SPFactory::object( $this->id );
				if( !empty( $obj ) ) {
					/* ensure that the id was right */
					if( $obj->oType == $this->oType ) {
						$this->extend( $obj );
					}
					else {
						$this->id = 0;
					}
				}
				$this->createdTime = SPFactory::config()->date( $this->createdTime );
				$this->validSince = SPFactory::config()->date( $this->validSince );
				if( $this->validUntil ) {
					$this->validUntil = SPFactory::config()->date( $this->validUntil );
				}
			}
			catch ( SPException $x ) {
				Sobi::Error( $this->name(), SPLang::e( 'CANNOT_GET_OBJECT_DB_ERR', $x->getMessage() ), SPC::ERROR, 500, __LINE__, __FILE__ );
			}
			$this->loadTable();
		}
		return $this;
	}

	/**
	 */
	public function load( $id )
	{
		return $this->init( $id );
	}

	/**
	 */
	public function loadTable()
	{
		if( $this->has( '_dbTable' ) && $this->_dbTable ) {
			try {
				$db =& SPFactory::db();
				$db->select( '*', $this->_dbTable, array( 'id' => $this->id )  );
				$obj = $db->loadObject();
				Sobi::Trigger( $this->name(), ucfirst( __FUNCTION__ ), array( &$obj ) );
			}
			catch ( SPException $x ) {
				Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
			}
			if( !empty( $obj ) ) {
				foreach ( $obj as $k => $v ) {
					$this->_set( $k, $v );
				}
			}
			else {
				Sobi::Error( $this->name(), SPLang::e( 'NO_ENTRIES' ), SPC::WARNING, 0, __LINE__, __FILE__ );
			}
		}
		$this->translate();
	}

	/**
	 */
	protected function translate()
	{
		$attributes = array_merge( $this->translatable(), self::$translatable );
		Sobi::Trigger( $this->name(), ucfirst( __FUNCTION__ ).'Start', array( &$attributes ) );
		$db =& SPFactory::db();
		try {
			$db->select( 'sValue, sKey', 'spdb_language', array( 'id' => $this->id, 'sKey' => $attributes, 'language' => Sobi::Lang(), 'oType' => $this->type() ) );
			$labels = $db->loadAssocList( 'sKey' );
			/* get labels in the default lang first */
			if( Sobi::Lang( false ) != Sobi::DefLang() ) {
				$db->select( 'sValue, sKey', 'spdb_language', array( 'id' => $this->id, 'sKey' => $attributes, 'language' => Sobi::DefLang(), 'oType' => $this->type() ) );
				$dlabels = $db->loadAssocList( 'sKey' );
				if( count( $dlabels ) ) {
					foreach ( $dlabels as $k => $v ) {
						if( !( isset( $labels[ $k ] ) ) || !( $labels[ $k ] ) ) {
							$labels[ $k ] = $v;
						}
					}
				}
			}
		}
		catch ( SPException $x ) {
			Sobi::Error( $this->name(), SPLang::e( 'DB_REPORTS_ERR', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
		}
		if( count( $labels ) ) {
			foreach ( $labels as $k => $v ) {
				$this->_set( $k, $v[ 'sValue' ] );
			}
		}
		Sobi::Trigger( $this->name(), ucfirst( __FUNCTION__ ), array( &$labels ) );
	}

	/**
	 * @param string $var
	 * @param mixed $val
	 */
	protected function _set( $var, $val )
	{
		if( $this->has( $var ) ) {
			if( is_array( $this->$var ) && is_string( $val )  && strlen( $val ) > 2 ) {
				try {
					$val = SPConfig::unserialize( $val, $var );
				}
				catch ( SPException $x ) {
					Sobi::Error( $this->name(), SPLang::e( '%s.', $x->getMessage() ), SPC::NOTICE, 0, __LINE__, __FILE__ );
				}
			}
			$this->$var = $val;
		}
	}

	/**
	 *  @return bool
	 */
	public function isCheckedOut()
	{
		if(
		$this->cout &&
		$this->cout != Sobi::My( 'id' ) &&
		strtotime( $this->coutTime ) > time()
		) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * @param string $var
	 * @param mixed $val
	 */
	public function set( $var, $val )
	{
		static $types = array();
		if( !count( $types ) ) {
			$types = array_merge( $this->types(), self::$types );
		}
		if( $this->has( $var ) && isset( $types[ $var ] ) ) {
			if( is_array( $this->$var ) && is_string( $val ) && strlen( $val ) > 2 ) {
				try {
					$val = SPConfig::unserialize( $val, $var );
				}
				catch ( SPException $x ) {
					Sobi::Error( $this->name(), SPLang::e( '%s.', $x->getMessage() ), SPC::NOTICE, 0, __LINE__, __FILE__ );
				}
			}
			$this->$var = $val;
		}
	}

	/**
	 * @return array
	 */
	protected function translatable()
	{
		return array();
	}

}
?>
