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
 * Description of Http
 *
 * @author Damjan
 * @since 1.0.0
 * @package ercox-mvc
 * @copyright (c) 2015, Damjan Krstevski
 */
abstract class Http
{
    
    /**
     * Method to get the value from GET request
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $key The GET key name
     * @param mixed $default The default value [Default is string]
     * @param int $filter The ID of the filter to apply [Default is null]
     * 
     * @return mixed Value from the GET or default value
     */
    public static function get($key, $default = '', $filter = null)
    {
        $value = filter_input(INPUT_GET, $key);
        $value = empty($value) ? $default : $value;
        
        if ($filter)
        {
            $value = filter_var($value, $filter);
        }
        
        return $value;
    } // End of function get();
    
    
    
    
    
    /**
     * Method to get the value from POST request
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $key The POST key name
     * @param mixed $default The default value [Default is string]
     * @param int $filter The ID of the filter to apply [Default is null]
     * 
     * @return mixed Value from the POST or default value
     */
    public static function post($key, $default = '', $filter = null)
    {
        $value = filter_input(INPUT_POST, $key);
        $value = empty($value) ? $default : $value;
        
        if ($filter)
        {
            $value = filter_var($value, $filter);
        }
        
        return $value;
    } // End of function post();
    
    
} // End of class Http;
