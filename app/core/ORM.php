<?php

/*
 * The MIT License
 *
 * Copyright 2015 damjan.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * ModelORM class
 *
 * @author Damjan
 * @since 1.0.0
 * @package ercox-mvc
 * @copyright (c) 2016, Damjan Krstevski
 */
class ModelORM extends Database
{

	/**
	 *	Name of the table
	 *
	 *	@since 1.0.0
	 *	@access protected
	 *
	 *	@var string $table
	 **/
	protected $table;

	/**
	 *	PDO Object
	 *
	 *	@since 1.0.0
	 *	@access protected
	 *
	 *	@var reference $pdo
	 *	@see http://php.net/manual/en/book.pdo.php
	 **/
	protected $pdo;

	/**
	 *	Default fetch mode
	 *
	 *	@since 1.0.0
	 *	@access private
	 *
	 *	@var int $fetchMode
	 *	@see http://php.net/manual/en/pdostatement.setfetchmode.php
	 **/
	private $fetchMode = PDO::FETCH_OBJ;

	/**
	 *	Where clause
	 *
	 *	@since 1.0.0
	 *	@access private
	 *
	 *	@var string $where
	 **/
	private $where 	= '';

	/**
	 *	Columns to select
	 *
	 *	@since 1.0.0
	 *	@access private
	 *
	 *	@var string $fields
	 **/
	private $fields = '*';

	/**
	 *	Order results
	 *
	 *	@since 1.0.0
	 *	@access private
	 *
	 *	@var string $orderBy
	 **/
	private $orderBy = '';

	/**
	 *	Limit the results
	 *
	 *	@since 1.0.0
	 *	@access private
	 *
	 *	@var string $limit
	 **/
	private $limit 	= '';

	/**
	 *	Group the results
	 *
	 *	@since 1.0.0
	 *	@access private
	 *
	 *	@var string $groupBy
	 **/
	private $groupBy = '';



	/**
	 *	Methot to Executes an SQL statement,
	 *	returning a result set as a PDOStatement object
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@return mixed PDOStatement object
	 **/
	public function get()
	{
		// Initialize PDO object
		$this->queryInit();

		// Build the SQL query
		$query = 'SELECT ' . $this->fields . ' FROM ' . $this->table
		. (($this->where) ? ' WHERE ' 		. $this->where : '')
		. (($this->groupBy) ? ' GROUP BY ' 	. $this->groupBy : '')
		. (($this->limit) ? ' LIMIT ' 		. $this->limit : '')
		. (($this->orderBy) ? ' ORDER BY ' 	. $this->orderBy : '');

		// Reset SQL query
		$this->resetQuery();

		return $this->query($query, $this->fetchMode)->fetchAll();
	} // End of function get();





	/**
	 *	Method to init the ORM
	 *
	 *	@since 1.0.0
	 *	@access private
	 *
	 *	@return void
	 **/
	private function queryInit()
	{
		// Create reference of Database class
		if ( !$this->pdo )
		{
			$this->pdo = Database::connect();
		}

		// Check the table or set the parent class name as table name
		if ( !$this->table )
		{
			$this->table = get_called_class();
		}
	} // End of function queryInit();





	/**
	 *	Method to insert data to the database
	 *
	 *	NOTE: This function may return Boolean FALSE,
	 *	but may also return a non-Boolean value which evaluates to FALSE.
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@param array $data Data to insert (should be [db_column => value])
	 *
	 *	@return mixed Insert ID on success or FALSE on failure
	 **/
	public function insert( $data )
	{
		// Initialize PDO object
		$this->queryInit();

		// Get the columns and values to insert
		$columns 	= array_keys($data);
		$values 	= array_values($data);

		// Build insert query
		$query = sprintf(
			"INSERT INTO %s (%s) VALUES (%s)",
			$this->table,
			implode(', ', $columns),
			rtrim(str_repeat('?,', count($values)), ',')
		);

		// Prepare and execute the insert query
		$insert = $this->pdo->prepare($query);
		$flag 	= $insert->execute($values);

		return $flag ? intval($this->pdo->lastInsertId()) : false;
	} // End of function insert();





	/**
	 *	Method to delete the record from the database
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@param string $column 	Database column
	 *	@param string $operator Comparation operator
	 *	@param string $value 	Value to compare
	 *
	 *	@return int Number of deleted rows
	 **/
	public function delete()
	{
		$this->queryInit();

		$query = sprintf(
			"DELETE FROM %s %s",
			$this->table,
			$this->where ? ('WHERE ' . $this->where) : ''
		);

		// Reset SQL query
		$this->resetQuery();

		// Delete the row form the database
		$delete = $this->pdo->prepare($query);
		$delete->execute();

		return $delete->rowCount();
	} // End of function delete();





	/**
	 *	Method to update the record
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@return int Number of updated rows
	 **/
	public function update( $data )
	{
		// Initialize PDO object
		$this->queryInit();

		// Extract the columns
		$columns = array_keys($data);
		$columns = implode('=?, ', $columns) . '=?';

		// Extract the values
		$values = array_values($data);

		// Build update query
		$query = sprintf(
			"UPDATE %s SET %s %s",
			$this->table,
			$columns,
			$this->where ? ('WHERE ' . $this->where) : ''
		);

		// Reset SQL query
		$this->resetQuery();

		// Prepare and execute update query
		$update = $this->pdo->prepare($query);
		$update->execute($values);

		return $update->rowCount();
	} // End of function update();





	/**
	 *	Method to reset the query
	 *
	 *	@since 1.0.0
	 *	@access private
	 *
	 *	@return void
	 **/
	private function resetQuery()
	{
		$this->table 	= '';
		$this->where 	= '';
		$this->fields 	= '*';
		$this->orderBy 	= '';
		$this->limit 	= '';
		$this->groupBy 	= '';
	} // End of function resetQuery();





	/**
	 *	Method to set the where clause
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@param string 	$column Table column name
	 *	@param string 	$operator Compare operator
	 *	@param string 	$value Value to compare
	 *	@param string 	$soft OR ot AND
	 *
	 *	@return ModelORM
	 **/
	public function where( $column = '', $operator = '', $value = '', $soft = 'OR' )
	{
		// Set quotes for strings
		$value = is_numeric($value) ? $value : sprintf("'%s'", $value);

		// Append the where clause
		$this->where .= ($this->where && $soft) ? " $soft " : '';
		$this->where .= sprintf('%s %s %s', $column, $operator, $value);

		return $this;
	} // End of function where();





	/**
	 *	Method to set the table
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@param string $table Name of the table
	 *
	 *	@return ModelORM
	 **/
	public function from( $table = '' )
	{
		return $this->setTable($table);
	} // End of function from();





	/**
	 *	Method to set the table
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@param string $table Name of the table
	 *
	 *	@return ModelORM
	 **/
	public function setTable( $table = '' )
	{
		$this->table = $table;
		return $this;
	} // End of function from();





	/**
	 *	Methot to set the select columns
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@param mixed $fields The columns to select
	 *
	 *	@return ModelORM
	 **/
	public function select( $fields = '*' )
	{
		$this->fields = is_array($fields) ? implode(', ', $fields) : $fields;
		return $this;
	} // End of function select();





	/**
	 *	Method to limit the results
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@param int 	$limit Offset or results count
	 *	@param int 	$length Results count
	 *
	 *	@return ModelORM
	 **/
	public function limit( $limit = 0, $length = 0 )
	{
		$limit 	= intval($limit);
		$length = intval($length);

		// If is set length than limit var will be offset
		$this->limit = $length ? ($limit . ', ' . $length) : $limit;
		return $this;
	} // End of function limit();





	/**
	 *	Method to order the results
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@param string 	$column Table column
	 *	@param string 	$order Type ASC or DESC
	 *
	 *	@return ModelORM
	 **/
	public function orderBy( $column = '', $order = 'ASC' )
	{
		$this->orderBy = sprintf('%s %s', $column, $order);
		return $this;
	} // End of function orderBy();





	/**
	 *	Method to group the results
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@param string 	$column Table column
	 *
	 *	@return ModelORM
	 **/
	public function groupBy( $column = '' )
	{
		$this->groupBy = sprintf('%s', $column);
		return $this;
	} // End of function groupBy();





	/**
	 *	Method to allow using PDO functions.
	 *
	 *	@since 1.0.0
	 *	@access public
	 *
	 *	@param string 	$method Name of the method to call
	 *	@param mixed 	$args Function arguments
	 *
	 *	@return mixed
	 **/
	public function __call( $method, $args )
	{
		if ( !empty($this->pdo) && is_callable(array($this->pdo, $method)) )
		{
			return call_user_func_array(array($this->pdo, $method), $args);
		}
	} // End function __call();

} // End class ModelORM();

?>