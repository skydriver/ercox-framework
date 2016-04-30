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

$class_map = [
    'core/Config.php',
    'core/Exception.php',
    'core/Route.php',
    'core/Http.php',
    'core/Cache.php',
    'core/Template.php',
    'core/View.php',
    'core/App.php',
    'core/Database.php',
    'core/ORM.php',
    'core/Model.php',
    'core/Auth.php',
    'core/Controller.php'
];

foreach ($class_map as $value)
{
    $value = __DIR__ . DIRECTORY_SEPARATOR . $value;
    if (!file_exists($value))
    {
        exit();
    }
    
    require_once $value;
}


function debug($val)
{
    echo '<pre>' . print_r($val, true) . '</pre>';
}


/* -------------------------------------------------- */
$error_reporting_mode   = Config::debug ? E_ALL : 0;
$display_errors_mode    = Config::debug ? 1 : 0;

error_reporting($error_reporting_mode);
ini_set('display_errors', $display_errors_mode);
/* -------------------------------------------------- */