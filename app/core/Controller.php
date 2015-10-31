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
 * Main controller class
 *
 * @author Damjan
 * @since 1.0.0
 * @package ercox-mvc
 * @copyright (c) 2015, Damjan Krstevski
 */
abstract class Controller
{
    
    /**
     * Force Extending class to define index method
     * 
     * @since 1.0.0
     * @access protected
     * 
     * @return View
     */
    abstract protected function index( $params );
    
    
    
    
    /**
     * Method to call the model
     * 
     * @since 1.0.0
     * @access protected
     * 
     * @param string $model The name of the model to call
     * 
     * @return \model Model instance
     */
	protected static function model( $model )
	{
		// require_once '../app/models/' . $model . '.php';
		new Model( $model );
		return new $model;
	} // End of protected function model();
    
    
    
    /**
     * Method to call the view
     * 
     * @since 1.0.0
     * @access protected
     * 
     * @param string $view Name of the view to call
     * @param array $data Data values to pass
     * 
     * @return void
     */
	protected static function view( $view, $data = [] )
	{
		View::load( $view, $data );
	} // End of protected function view();

} // End of class Controller
