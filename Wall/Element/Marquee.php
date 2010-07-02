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

class WallElementMarquee extends WallElement {
    
    var $tag = 'marquee';
    
    var $behavior = false;
    var $direction = false;
    var $loop = false;
    var $bgcolor = false;
    
    var $nowrap_mode = false;
    var $marquee_as_css = false;
    
    function WallElementMarquee (&$wall, $attributes = Array()) {
        $this->WallElement($wall, $attributes);
    }
    
    function doStartTag() {
        parent::doStartTag();

        $this->nowrap_mode = $this->_wall->getCapa('xhtml_nowrap_mode');
        $this->marquee_as_css = $this->_wall->getCapa('xhtml_marquee_as_css_property');
        
        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            if ($this->marquee_as_css) {
                $style = 'display: -wap-marquee';
                
                if ($this->behavior) {
                    $style .= '; -wap-marquee-style: ' . $this->behavior;
                }
                
                if ($this->direction) {
                    $dir = $this->direction == 'left' ? 'rtl' : 'ltr';
                    $style .= '; -wap-marquee-dir: ' . $dir;
                }
                
                if ($this->loop) {
                    $style .= '; -wap-marquee-loop: ' . $this->loop;
                }
                
                if ($this->bgcolor) {
                    $style .= '; background-color: ' . $this->bgcolor;
                }
                
                $this->write('<div style="' . $style . '">');
                
                
            } else if ($this->nowrap_mode) {
                $this->write('<div mode="nowrap">');
            }
                
        } else if (strpos($this->preferred_markup, 'chtml') !== false) {
            $this->write('<marquee');

            if ($this->behavior) {
                $this->write(' behavior="' . $this->behavior . '"');
            }

            if ($this->direction) {
                $this->write(' direction="' . $this->direction . '"');
            }

            if ($this->loop) {
                $this->write(' loop="' . $this->loop . '"');
            }

            if ($this->bgcolor) {
                $this->write(' bgcolor="' . $this->bgcolor . '"');
            }

            $this->write('>');

        } else if (strpos($this->preferred_markup, 'wml') !== false) {
            //$this->write('');
        }
        
    }
    
    function doEndTag() {
        parent::doEndTag();
        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            if ($this->marquee_as_css) {
                $this->write('</div>');
            } else {
                if ($this->nowrap_mode) {
                    $this->write('</div>');
                }
            }
        } else if (strpos($this->preferred_markup, 'chtml') !== false) {
            $this->write('</marquee>');
        } else if (strpos($this->preferred_markup, 'wml') !== false) {
            //$this->write('');
        }
    }
}

?>