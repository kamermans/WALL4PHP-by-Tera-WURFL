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

class WallElementA extends WallElement {
    
    var $tag = 'a';
    
    var $href = false;
    var $accesskey = false;
    var $title = false;
    var $opvw_icon = false;
    var $xhtmlId = false;
    var $xhtmlClass = false;
    
    var $wml_menu_with_select = false;
    var $fancy_ok = false;
    var $region = false;
    
    # New in beta2
    var $current_bgcolor = 'bgcolor1';
    
    function WallElementA (&$wall, $attributes = Array()) {
        $this->WallElement($wall, $attributes);

        $this->menu = &$this->getAncestorByClassName('wallelementmenu');
        
        if (strpos($this->preferred_markup, 'chtml') !== false) {
            $this->region = $this->_wall->getCapa('imode_region');
        } else if (strpos($this->preferred_markup, 'wml') !== false) {
            $this->wml_menu_with_select = $this->_wall->getCapa('menu_with_select_element_recommended');
        }
        
    }
    
    function doStartTag() {
        parent::doStartTag();
        
        $this->href = TagUtil::normalizeHref($this->href, $this->preferred_markup);

        if ($this->menu) {  // INSIDE A MENU
            $counter = ++$this->menu->counter;
            if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
                
                $this->fancy_ok = $this->menu->colorize && $this->_wall->menu_css_tag && $this->menu->table_and_css_background;
                
                if ($this->fancy_ok) {
                    $this->write('<tr>');
                    $this->write('<td class="bgcolor' . (($counter + 1) % 2 + 1) . '">');
                    
                    if ($this->menu->autonumber) {
                        $this->write(' ' . $counter);
                        
                        if ($this->opvw_icon) {
                            $this->write(' <img localsrc="' . $this->opvw_icon . '" src="" alt=""/>');
                        }
                        
                        $this->write(' <a accesskey="' . $counter . '"');
                        
                    } else {
                        
                        if ($this->accesskey) {
                            $this->write(' ' . $this->accesskey . ' <a accesskey="' . $this->accesskey . '"');
                        } else {
                            $this->write('<a');
                        }
                        
                    }
                    
                    $this->write(' href="' . $this->href . '"');
                    
                    if ($this->title) {
                        $this->write(' title="' . $this->title . '"');
                    }
                    
                    if ($this->xhtmlClass) {
                        $this->write(' class="' . $this->xhtmlClass . '"');
                    }
                    if ($this->xhtmlId) {
                        $this->write(' id="' . $this->xhtmlId . '"');
                    }
    
                    $this->write('>');
                    
                } else {
                    
                    $this->write('<li>');
                    
                    if ($this->opvw_icon && $this->_wall->use_xhtml_extensions) {
                        $this->write('<img localsrc="' . $this->opvw_icon . '" src="" alt=""/> ');
                    }
                    
                    if ($this->menu->autonumber) {
                        $this->write('<a accesskey="' . $counter . '"');
                    } else {
                        if ($this->accesskey) {
                            $this->write('<a accesskey="' . $this->accesskey . '"');
                        } else {
                            $this->write('<a');
                        }
                    }
                    
                    $this->write(' href="' . $this->href . '"');
                    
                    if ($this->title) {
                        $this->write(' title="' . $this->title . '"');
                    }
                    
                    if ($this->xhtmlClass) {
                        $this->write(' class="' . $this->xhtmlClass . '"');
                    }
                    if ($this->xhtmlId) {
                        $this->write(' id="' . $this->xhtmlId . '"');
                    }
                    $this->write('>');
                }
                
            } else if (strpos($this->preferred_markup, 'chtml') !== false) {
                
                if ($this->menu->autonumber) {
                    $emoji = TagUtil::getEmoji($counter, $this->region);
                    $this->write($emoji . '&nbsp;<a accesskey="' . $counter . '"');
                } else {
                    if ($this->accesskey) {
                        $emoji = TagUtil::getEmoji($this->accesskey, $this->region);
                        $this->write($emoji . '&nbsp;<a accesskey="' . $this->accesskey . '"');
                    } else {
                        $this->write('<a');
                    }
                }
                
                $this->write(' href="' . $this->href . '"');
                $this->write('>');
                
            } else if (strpos($this->preferred_markup, 'wml') !== false) {
    
                if ($this->wml_menu_with_select) {
                    $this->write('<option onpick="' . $this->href . '"');
                    if ($this->title) {
                        $this->write(' title="' . $this->title . '"');
                    }
                    $this->write('>');
                    
                    if ($this->opvw_icon && $this->_wall->use_wml_extensions) {
                        $this->write('<img localsrc="' . $this->opvw_icon . '" src="" alt=""/>');
                    }
                    
                } else {
                    //accesskey for WML is tricky: extra check accesskey support by WML browser
                    //SECOND THOUGHT: I get problems with GATEWAY. removing accesskey 
                    //                for WML for the time being
                    
                    $this->write('<a href="' . $this->href . '"');
                    
                    if ($this->title) {
                        $this->write(' title="'. $this->title . '"');
                    }
                    $this->write('>');
                    
                }
    
            }
        
        } else { // NOT INSIDE A MENU
        
            if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
                
                if ($this->accesskey) {
                    $this->write('<a accesskey="' . $this->accesskey . '"');
                } else {
                    $this->write('<a');
                }
                
                $this->write(' href="' . $this->href . '"');
                
                if ($this->title) {
                    $this->write(' title="' . $this->title . '"');
                }
                
                $this->write('>');
    
            } else if (strpos($this->preferred_markup, 'chtml') !== false) {
    
                
                if ($this->accesskey) {
                    $this->write('<a accesskey="' . $this->accesskey . '"');
                } else {
                    $this->write('<a');
                }
                
                $this->write(' href="' . $this->href . '"');
                
                $this->write('>');
                
            } else if (strpos($this->preferred_markup, 'wml') !== false) {

                $this->write('<a');

                $this->write(' href="' . $this->href . '"');
                
                if ($this->title) {
                    $this->write(' title="' . $this->title . '"');
                }
                
                $this->write('>');
    
            }

        }
        
    }
    
    function doEndTag() {
        parent::doEndTag();

        if ($this->menu) {
            if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
                
                if ($this->fancy_ok) {
                    $this->write('</a></td></tr>');
                } else {
                    $this->write('</a></li>');
                }
                
            } else if (strpos($this->preferred_markup, 'wml') !== false) {
    
                if ($this->wml_menu_with_select) {
                    $this->write('</option>');
                } else {
                    $this->write('</a><br/>');
                }
    
                
            } else if (strpos($this->preferred_markup, 'chtml') !== false) {
    
                $this->write('</a><br>');
    
            }
        } else {
            $this->write('</a>');
        }
        
    }
    
}

?>