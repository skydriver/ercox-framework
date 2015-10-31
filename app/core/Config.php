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
 * Main Config class
 *
 * @author Damjan
 * @since 1.0.0
 * @package ercox.mvc
 * @copyright (c) 2015, Damjan Krstevski
 */
abstract class Config
{
    // Global website configuration
    
    /**
     * Website charset
     */
	const charset = 'UTF-8';
    
    /**
     * Website language
     */
    const lang = 'en-US';
    
    /**
     * Website title
     */
	const site_title = 'ErCox - High Quality Solutions';
	
    /**
     * Website base URL
     */
    const base_url = 'http://mvc.dev/';
    // End global website configuration
    
    
    
    
    
    // Database configuration
    /**
     * Database driver [mysql or pgsql]
     */
    const database_driver = 'mysql';
    
    /**
     * Database hostname
     */
	const database_hostname = 'localhost';
    
    /**
     * Database name
     */
    const database_name = '';
    
    /**
     * Database password
     */
	const database_username = 'root';
    
    /**
     * Database password
     */
	const database_password = 'toor';
    
    /**
     * Database table prefix
     */
	const database_prefix = 'mvc_';
    
    /**
     * Database charset
     */
	const database_charset = 'UTF8';
    
    /**
     * Database port
     */
    const database_port = 3306;
    
    /**
     * Database execution time
     */
	const database_timeout = 30;
    // End Database configuration

} // End of class Config;
