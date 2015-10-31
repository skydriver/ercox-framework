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
 * Main Database class
 *
 * @author Damjan
 * @since 1.0.0
 * @package ercox-mvc
 * @copyright (c) 2015, Damjan Krstevski
 */
class Database extends Config
{
    /**
     * PDO reference
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var PDO object
     */
    private static $instance = null;
    
    
    
    
    
    /**
     * Create new database connection using PDO object
     * 
     * @since 1.0.0
     * @access private
     * 
     * @return void
     */
    private function __construct()
    {
        try
        {
            $dns = $this->dns();

            $this->PDO = new PDO(
                $dns,
                Config::database_username,
                Config::database_password
                );
            
            $this->set_error_mode();
            
            if ($this->PDO)
            {
                $this->set_timeout();
                $this->set_charset();
            }
        }
        catch ( PDOException $ex )
        {
            var_dump( $ex->getMessage() );
            return;
        }
    } // End of function __construct();
    
    
    
    
    
    /**
     * Object destructor, clear the PDO object memory
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->PDO = null;
    } // End of function __destruct();
    
    
    
    
    
    /**
     * Get the PDO dns
     * 
     * @since 1.0.0
     * @access private
     * 
     * @return string PDO dns
     */
    private function dns()
    {
        $format = ('mysql' === Config::database_driver) ?
            '%s:host=%s;dbport=%d;dbname=%s' :
            '%s:host=%s;port=%d;dbname=%s';
        
        $dns = sprintf(
            $format,
            Config::database_driver,
            Config::database_hostname,
            Config::database_port,
            Config::database_name
        );
        
        return $dns;
    } // End of function dns();
    
    
    
    
    
    /**
     * Set the database execution timeout
     * 
     * @since 1.0.0
     * @access private
     * 
     * @return void
     */
    private function set_timeout()
    {
        $this->PDO->setAttribute(
            PDO::ATTR_TIMEOUT,
            Config::database_timeout
            );
    } // End of function set_timeout();
    
    
    
    
    
    /**
     * Set the database charset
     * 
     * @since 1.0.0
     * @access private
     * 
     * @return void
     */
    private function set_charset()
    {
        $format = ('mysql' === Config::database_driver) ?
            'SET NAMES %s' :
            'SET CLIENT_ENCODING TO %s';
        
        $pdo_charset = sprintf(
            $format,
            Config::database_charset
            );
        
        $this->PDO->exec($pdo_charset);
    } // End of function set_charset();
    
    
    
    
    
    /**
     * Set the PDO error mode
     * 
     * @since 1.0.0
     * @access private
     * 
     * @return void
     */
    private function set_error_mode()
    {
        $this->PDO->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
            );
    } // End of function set_error_mode();
    
    
    
    
    
    /**
     * Create class reference
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return Database Reference to the PDO object
     */
    final public static function connect()
    {     
        if ( self::$instance == null )
        {
            self::$instance = new Database();
        }
        
        return self::$instance;
    } // End of function connect();
    
    
    
    
    
    /**
     * Access to PDO class functions
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $method PDO method name
     * @param type $args PDO method's args
     * 
     * @return PDO The results from the PDO object
     */
    public function __call( $method, $args )
    {
        if ( !empty($this->PDO) && is_callable(array($this->PDO, $method)) )
        {
            return call_user_func_array(array($this->PDO, $method), $args);
        }
    } // End of function __call();
    
    
    
    
    
    /**
     * Stopping Clonning of Object
     * 
     * @since 1.0.0
     * 
     * @return boolean This function will always return false
     */
    public function __clone()
    {
        return false;
    } // End of function __clone();
    
    
    
    
    
    /**
     * Stopping unserialize of object
     * 
     * @since 1.0.0
     * 
     * @return boolean This functions will always return false
     */
    public function __wakeup()
    {
        return false;
    } // End of function __wakeup();
    
} // End of class Database;