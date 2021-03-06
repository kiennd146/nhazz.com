<?php
/**
 * @version: $Id: database.php 551 2011-01-11 14:34:26Z Radek Suski $
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
 * $Date: 2011-01-11 15:34:26 +0100 (Tue, 11 Jan 2011) $
 * $Revision: 551 $
 * $Author: Radek Suski $
 * File location: components/com_sobipro/lib/base/database.php $
 */

defined( 'SOBIPRO' ) || exit( 'Restricted access' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 08-Jul-2008 9:43:25
 * @updated 14-Jul-2008 9:57:07
 */
interface SPDatabase
{
	public function __construct();

	/**
	 * @return SPDatabase
	 */
	public static function & getInstance();

	/**
	 * Returns the error number
	 *
	 * @return int
	 */
	public function getErrorNum();

	/**
	 * Returns the error message
	 *
	 * @return string
	 */
	public function getErrorMsg();

	/**
	 * Returns a database escaped string
	 *
	 * @param string $text string to be escaped
	 * @param bool $esc extra escaping
	 * @return string
	 */
	public function escape( $text, $esc = false );

	/**
	 * Returns database null date format
	 *
	 * @return string Quoted null date string
	 */
	public function getNullDate();

	/* (non-PHPdoc);
	 * @see Site/lib/base/SPDatabase#loadFile($file);
	 */
	public function loadFile( $file );

	/**
	 * Alias for select where $distinct is true
	 *
	 * @param string $toSelect
	 * @param string $tables
	 * @param string $where
	 * @param int $limit
	 * @param int $limitStart
	 * @param string $groupBy - column to group by
	 */
	public function dselect( $toSelect, $tables, $where = null, $order = null, $limit = 0, $limitStart = 0, $group = null );

	/**
	 * Creates a "select" SQL query.
	 *
	 * @param string $toSelect - table rows to select
	 * @param string $tables - from which table(s);
	 * @param string $where - SQL select condition
	 * @param int $limit - maximal number of rows
	 * @param int $limitStart - start position
	 * @param bool $distinct - clear??
	 * @param string $groupBy - column to group by
	 * @return SPDb
	 */
	public function & select( $toSelect, $tables, $where = null, $order = null, $limit = 0, $limitStart = 0, $distinct = false, $groupBy = null );

	/**
	 * Creates a "delete" SQL query
	 *
	 * @param string $table - in which table
	 * @param string $where - SQL delete condition
	 * @param int $limit - maximal number of rows to delete
	 */
	public function delete( $table, $where, $limit = 0 );

	/**
	 * Creates a "drop table" SQL query
	 *
	 * @param string $table - in which table
	 * @param string $ifExists
	 */
	public function drop( $table, $ifExists = true );

	/**
	 * Creates a "drop table" SQL query
	 *
	 * @param string $table - in which table
	 * @param string $ifExists
	 */
	public function truncate( $table );
	public function argsOr( $val );

	/**
	 * Creates a "update" SQL query
	 *
	 * @param string $table - table to update
	 * @param array $set  - two-dimensional array with table row name to update => new value
	 * @param string $where  - SQL update condition
	 */
	public function update( $table, $set, $where, $limit = 0 );

	/**
	 * Creates a "insert" SQL query
	 *
	 * @param string $table - table name
	 * @param array $values - two-dimensional array with table row name => value
	 * @param bool $ignore - adds "IGNORE" after "INSERT" command
	 */
	public function insert( $table, $values, $ignore = false );

	/**
	 * Creates a "insert" SQL query with multiple values
	 *
	 * @param string $table - table name
	 * @param array $values - one-dimensional array with two-dimensional array with table row name => value
	 * @param bool $update - update existing row if cannot insert it because of duplicate primary key
	 * @param bool $ignore - adds "IGNORE" after "INSERT" command
	 */
	public function insertArray( $table, $values, $update = false, $ignore = false );

	/**
	 * Creates a "insert" SQL query with update if cannot insert it because of duplicate primary key
	 *
	 * @param string $table - table name
	 * @param array $values - two-dimensional array with table row name => value
	 */
	public function insertUpdate( $table, $values );

	/**
	 * Returns current query
	 *
	 * @return string
	 */
	public function getQuery();

	/**
	 * Returns queries counter
	 *
	 * @return int
	 */
	public function getCount();

	/**
	 * Execute the query
	 *
	 * @return mixed database resource or <var>false</var>.
	 */
	public function query();

	/**
	 * Loads the first field of the first row returned by the query.
	 *
	 * @return string
	 */
	public function loadResult();

	/**
	 * Load an array of single field results into an array
	 *
	 * @return array
	 */
	public function loadResultArray();

	/**
	 * Load a assoc list of database rows
	 *
	 * @param string $key field name of a primary key
	 * @return array If key is empty as sequential list of returned records.
	 */
	public function loadAssocList( $key = null );

	/**
	 * Loads the first row of a query into an object
	 *
	 * @return stdObject
	 */
	public function loadObject();

	/**
	 * Load a list of database objects
	 *
	 * @param string $key
	 * @return array If key is empty as sequential list of returned records.
	 */
	public function loadObjectList( $key = null );

	/**
	 * Load the first row of the query.
	 *
	 * @return array
	 */
	public function loadRow();

	/**
	 * Load a list of database rows (numeric column indexing);
	 *
	 * @param string $key field name of a primary key
	 * @return array If <var>key</var> is empty as sequential list of returned records.
	 */
	public function loadRowList( $key = null );

	/**
	 * Returns an error statement
	 *
	 * @return string
	 */
	public function stderr();

	/**
	 * Returns the ID generated from the previous insert operation
	 *
	 * @return int
	 */
	public function insertid();

	/**
	 * executing query (update/insert etc);
	 *
	 * @param string $query - query to execute
	 * @return mixed
	 */
	public function exec( $query );

	/**
	 * Returns all rows of given table
	 * @param string $table
	 * @return array
	 */
	public function getColumns( $table );

	/**
	 * rolls back the current transaction, canceling its changes
	 *
	 * @return bool
	 */
	public function rollback();

	/**
	 * begin a new transaction
	 *
	 * @return bool
	 */
	public function transaction();

	/**
	 * ommits the current transaction, making its changes permanent
	 *
	 * @return bool
	 */
	public function commit();

	/**
	 * Returns current datetime in database acceptable format
	 * @return string
	 */
	public function now();

	/**
	 * Creates yntax for joins two tables
	 *
	 * @param array $params - two cells array with table name <var>table</var>, alias name <var>as</var> and common key <var>key</var>
	 * @param string $through - join direction (left/right);
	 * @return string
	 */
	public function join( $params, $through = 'left' );

	/**
	 * Creates syntax to check the expiration date, state, and start publishing date off an row
	 * @param string $until - row name where the expiration date is stored
	 * @param string $since - row name where the start date is stored
	 * @param string $pub - row name where the state is stored (e.g. 'published');
	 * @return string
	 */
	public function valid( $until, $since = null, $pub = null );
}
?>