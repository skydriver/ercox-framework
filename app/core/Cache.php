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
 * Class for caches
 *
 * @author Damjan
 * @since 1.0.0
 * @package ercox-mvc
 * @copyright (c) 2015, Damjan Krstevski
 */
class Cache
{
    private $cache_ext = '.txt'; // file extension
    private $cache_time = 3600;  // Cache file expires afere these seconds (1 hour = 3600 sec)
    private $cache_folder = 'cache/'; // folder to store Cache files   
    private $ignore_pages = array('', '');

    public static function start()
    {
        // requested dynamic page (full url)
        $dynamic_url = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
        
        // construct a cache file
        $cache_file = $cache_folder . md5($dynamic_url) . $cache_ext;
        
        // check if url is in ignore list
        $ignore = (in_array($dynamic_url , $ignore_pages)) ? true : false;
        
        // check Cache exist and it's not expired.
        if (!$ignore && file_exists($cache_file) && time() - $cache_time < filemtime($cache_file))
        {
            // Turn on output buffering, "ob_gzhandler" for the compressed page with gzip.
            ob_start('ob_gzhandler');
            
            // read Cache file
            readfile($cache_file);
            
            echo '<!-- cached page - '.date('l jS \of F Y h:i:s A', filemtime($cache_file)).', Page : '.$dynamic_url.' -->';
            
            // Flush and turn off output buffering
            ob_end_flush();
            
            // no need to proceed further, exit the flow.
            exit();
        }

        // Turn on output buffering with gzip compression.
        ob_start('ob_gzhandler'); 
    }
    
    
    
    public static function end()
    {
        // create a new folder if we need to
        if (!is_dir($cache_folder))
        {
            mkdir($cache_folder);
        }
        
        if (!$ignore)
        {
            // open file for writing
            $fp = fopen($cache_file, 'w');
            
            // write contents of the output buffer in Cache file
            fwrite($fp, ob_get_contents());
            
            // Close file pointer
            fclose($fp);
        }
        
        // Flush and turn off output buffering
        ob_end_flush();
    }
    
    
    
    public static function clean() {}


} // End of class Cache
