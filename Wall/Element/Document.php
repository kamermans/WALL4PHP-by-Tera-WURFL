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

require_once('Wall/Element.php');

class WallElementDocument extends WallElement {
    
    var $tag = 'document';
    
    var $disable_wml_extensions = false;
    var $disable_xhtml_extensions = false;
    
    var $uplink = false;
    
    var $title = false;
    
    function WallElementDocument (&$wall, $attributes = Array()) {
        $this->WallElement($wall, $attributes);
    }
    
    function doStartTag() {
        parent::doStartTag();

        if (defined('WALL_USE_TERA_WURFL') && WALL_USE_TERA_WURFL && TERA_WURFL_VERSION >= 2){
			$this->uplink = TagUtil::isUpLink($this->_wall->wurfl->userAgent);
        }else{
        	$this->uplink = TagUtil::isUpLink($this->_wall->wurfl->user_agent);
        }
        
        #header('X-test', $this->_wall->getCapa('xhtmlmp_preferred_mime_type'));
        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            if (!defined('WALL_SUPRESS_HEADERS') || !WALL_SUPRESS_HEADERS) {
                
                $hdr = $this->_wall->getCapa('xhtmlmp_preferred_mime_type');
                $chr = $this->_wall->getCapa('xhtml_preferred_charset');
                
#                error_log($chr);
                
                if ($chr && ($chr != 'utf8')) $chr = false;
                if (!$hdr) $hdr = 'text/html';
                
#		error_log($this->_wall->ua . ' gets Content-Type: ' . $hdr . ($chr ? '; charset=' . $chr : ''));

                #header('Content-Type: ' . $hdr . ($chr ? '; charset=' . $chr : ''));
                header('Content-Type: ' . $hdr);
                
            }
            
            $this->_wall->use_xhtml_extensions = !$this->disable_xhtml_extensions &&
                                                 $this->uplink &&
                                                 $this->_wall->getCapa('opwv_xhtml_extensions_support');
            
        } else if (strpos($this->preferred_markup, 'wml') !== false) {
            
            if (!defined('WALL_SUPRESS_HEADERS') || !WALL_SUPRESS_HEADERS) {
                error_log($this->_wall->ua . ' gets Content-Type: text/vnd.wap.wml; charset=utf-8');
                header('Content-Type: text/vnd.wap.wml; charset=utf-8');
            }

            $this->_wall->use_wml_extensions = !$this->disable_wml_extensions &&
                                               $this->uplink &&
                                               $this->_wall->getCapa('opwv_wml_extensions_support');

        } else if (strpos($this->preferred_markup, 'chtml') !== false) {
            if (!defined('WALL_SUPRESS_HEADERS') || !WALL_SUPRESS_HEADERS) {
                error_log($this->_wall->ua . ' gets Content-Type: text/html; charset=utf-8');
                header('Content-Type: text/html; charset=utf-8');
            }
        } else {
            trigger_error('No valid markup found: ' . $this->preferred_markup, E_USER_ERROR);
        }
        
    }
    
    function doEndTag() {
        parent::doEndTag();

    }
    
}

?>
