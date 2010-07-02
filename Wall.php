<?php
/*
 * Copyright (c) 2004-2005, Kaspars Foigts
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 *    * Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *
 *    * Redistributions in binary form must reproduce the above
 *      copyright notice, this list of conditions and the following
 *      disclaimer in the documentation and/or other materials provided
 *      with the distribution.
 *
 *    * Neither the name of the WALL4PHP nor the names of its
 *      contributors may be used to endorse or promote products derived
 *      from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

 * Authors: Kaspars Foigts (wall4php@laacz.lv)
 *
*/

# PEAR is not required
#require_once('PEAR.php');

require_once('Wall/wallXmlParser.php');
require_once('Wall/TagUtil.php');

define('WALL_DEVELOPER_DEBUG', false);

define('WALL_SKIP_BODY', 1);

function wall_shutdown_callback() {
    while (ob_get_level()) ob_end_flush();
}

function wall_debug($str) {
    if (WALL_DEVELOPER_DEBUG)
        error_log($str);
}

wall_debug('');

register_shutdown_function('wall_shutdown_callback');

class Wall {
    
    var $_content; // Where Wall template is stored
    
    var $_stack = Array(); // Element stack
    
    var $_xmlparser = null;
    
    var $_output = '';
    
    var $_capaCache = Array();
    var $wurfl;
    
    var $use_xhtml_extensions = false;
    var $use_wml_extensions = false;
    
    var $title = false;
    var $enforce_title = false;
    
    var $skipper = false;
    
    var $_has_cdata;
    
    var $menu_css_tag = false;

    var $ua = false;
    
    function Wall($ua = false) {

        global $_GET, $_SERVER;
        if (!$ua) {
            $this->ua = isset($_GET['UA']) ? $_GET['UA'] : getenv('HTTP_USER_AGENT');
        }else{
        	$this->ua = $ua;
        }
        if (defined('WALL_USE_TERA_WURFL') && WALL_USE_TERA_WURFL) {
        	if(TERA_WURFL_VERSION == 1){
        		require_once(WURFL_CLASS_FILE);
	            $this->wurfl = new tera_wurfl();
        	}else{
        		// The class file was loaded in wall_prepend.php
        		$this->wurfl = new TeraWurfl();
        		if(!$ua){
        			$this->ua = WurflSupport::getUserAgent();
        		}
        	}
        } else {
            $this->wurfl = new wurfl_class();
        }
        $this->wurfl->GetDeviceCapabilitiesFromAgent($this->ua);
        ob_start(Array($this, '_obCallBack'));
        register_shutdown_function(Array($this, '_obEndFlush'));
    }
    
    function _obCallBack($content) {
        # Quote from PHP manual: http://lv.php.net/ob_start
        # Some web servers (e.g. Apache) change the working directory
        # of a script when calling the callback function. You can change
        # it back by e.g. chdir(dirname($_SERVER['PHP_SELF'])) in the
        # callback function.
        $olddir = getcwd();
        chdir(dirname(__FILE__));
        $this->setContent($content);
	$this->parse();

	chdir($olddir);
	$tmp = $this->getOutput();

#	$fh = fopen('/tmp/' . md5($this->ua), 'w');
#	fwrite($fh, $this->_output);
#	fclose($fh);
#	error_log($this->ua . ' => /tmp/' . md5($this->ua));
#	error_log(getenv('HTTP_USER_AGENT') . ' gets this: ' . $this->_output);
	return $tmp;
    }
    
    function _obEndFlush() {
	    while (ob_get_level()) {
		    @ob_end_flush();
	    }
    }
    
    function getCapa($capa) {
        if (!isset($this->_capaCache[$capa])) {
            $this->_capaCache[$capa] = $this->wurfl->getDeviceCapability($capa);
        }
        return $this->_capaCache[$capa];
    }
    
    function setContent($content) {
        $this->_content = $content;
        $this->_prefix = 'wall';
    }
    
    function _decode_entities($str) {
        $ret = $str;
        $table = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        foreach ($table as $k=>$v) {
            $ret = str_replace($v, '&#' . ord($k) . ';', $ret);
        }
        return $ret;
    }
    
    function parse() {
        $this->_xmlparser = new wallXmlParser($this->_content);
        $this->_xmlparser->setObject($this);
        $this->_xmlparser->setHandlers('_start_element', '_end_element', '_cdata');
        $this->_xmlparser->parse();
    }
    
    function getOutput() {
        #$this->_output = preg_replace('/<([a-z0-9\:_]+)([^>]*)><\/\1>/ism', '<\1\2/>', $this->_output);
        return trim($this->_output);
    }
    
    function _start_element($parser, $name, $attributes, $issingle) {
        
        $tagname = strtolower($name);
        $element = false;
        
        $this->skipper = NULL;
        $this->_has_cdata = false;
        
        if (substr($tagname, 0, strlen($this->_prefix)) == $this->_prefix) {
            wall_debug('STARTS ' . $name . ($issingle ? ' single' : ' full') . ' (wall element)');
        
            $tagname = substr($tagname, strlen($this->_prefix) + 1);
            
            $classname = 'WallElement' . ucfirst($tagname);
            
            $incname = 'Wall/Element/' . ucfirst($tagname) . '.php';
            $exists = $readable = false;
            if ($exists = @include_once($incname)) {

                if (class_exists($classname)) {
                    $element = new $classname($this, $attributes);
                    wall_debug($classname . 'doStartTag()');
                    $this->skipper = $element->doStartTag();
                    $this->_output .= $element->getOutput();
                } else {
                    $str = 'Could not find class ' . $classname . ' (for element ' . $tagname . ' in file ' . $incname . ')!';
                    trigger_error($str, E_USER_ERROR);
                }
            } else {
                $str = 'Could not include file ' . $incname . '!';
                $str .= ' (File' . ($exists ? ' exists' : ' does not exist') . '';
                if (isset($readable)) {
                    $str .= ' and is' . ($readable ? '' : ' not') . ' readable';
                }
                $str .= ' in ' . __FILE__ . ')';
                #trigger_error($str, E_USER_ERROR);
                #die();
                $element = false;
                #echo ' die' ;
                #die('Could not include file ' . $incname . '!');
            }

        } else {
            wall_debug('STARTS ' . $name . ($issingle ? ' single' : ' full') . ' (NOT wall element)');
        }
        
        # If we receive an unknown element (even with prefix "wall:"), we should
        # return it back as it was.
        if (!$element) {
            $element = Array('name' => strtolower($name));
            $str = '<' . strtolower($name);
            foreach($attributes as $name=>$value) {
                $str .= ' ' . strtolower($name) . '="' . htmlspecialchars($value) . '"';
            }
            if ($issingle) $str .= '/';
            $str .= '>';
    
            # Added 2005-11-29 (bad XML parsing)
            if (!$issingle) {
                #wall_debug('PUSH ' . $element['name']);
                #array_push($this->_stack, $element);
            }

            $this->_output .= $str;
        } else {
            if (!$issingle) {
                #wall_debug('PUSH ' . $element->tag);
                array_push($this->_stack, $element);
            }
            
            if ($issingle) {
                $element->doEndTag();
                $this->_output .= $element->getOutput();
            }
        }
        #echo $this->_stack2str() . ' START' . "\n";
        
    }
    
    function _end_element($parser, $name) {
        $this->skipper = NULL;
        #echo $this->_stack2str() . ' END' . "\n";
#        wall_debug('ENDS ' . $name);
        end($this->_stack);
        $element = current($this->_stack);
        if (substr($name, 0, strlen($this->_prefix)) == $this->_prefix && is_object($element)) { // && is_subclass_of($element, 'WallElement')) {
            wall_debug('ENDS ' . $name . ' (wall element)');
            array_pop($this->_stack);
            wall_debug(get_class($element) . 'doEndTag()');
            $element->doEndTag();
            #error_log($name . '-' . get_class($element));
            $this->_output .= $element->getOutput();
        } else {
            #array_push($this->_stack, $element);
            $this->_output .= '</' . strtolower($name) . '>';
            wall_debug('ENDS ' . $name . ' (NOT wall element)');
        } 
        $this->_has_cdata = false;
    }
    
    function _cdata($parser, $data) {
        
        #echo $this->_stack2str() . ' CDATA' . "\n";
        $element = array_pop($this->_stack);
        if (is_array($element)) {
            if (!isset($element['cdata'])) {
                $element['cdata'] = '';
            }
            $element['cdata'] .= $data;
        } else {
            if (!isset($element->cdata)) {
                $element->cdata = '';
            }
            $element->cdata .= $data;
        }
        array_push($this->_stack, $element);
        if ($this->skipper !== WALL_SKIP_BODY) {
            $this->_output .= $data;
        }
    }
    
    function _stack2str() {
        $tmp = Array();
        foreach ($this->_stack as $k=>$v) {
            if (is_object($v) && is_subclass_of($v, 'WallElement')) {
                $tmp[] = get_class($v);
            } else {
                $tmp[] = $v['name'];
            }
        }
        #echo (int)$this->skip_body . ' ';
        return join($tmp, '=>');
    }
    
}

# Workaround lack of POSIX extension on win32 
if (!function_exists('posix_uname')) {
    function posix_uname() {
        return Array(
            'sysname'  => php_uname('s'),
            'nodename' => php_uname('n'),
            'release'  => php_uname('r'),
            'version'  => php_uname('v'),
            'machine'  => php_uname('m'),
        );
    }
}

# Workaround lack of POSIX extension on win32 
if (!function_exists('posix_getpid')) {
    function posix_getpid() {
        return getmypid();
    }
}


?>
