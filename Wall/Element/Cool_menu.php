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

class WallElementCool_menu extends WallElement {
    
    var $tag = 'cool_menu';
    
    var $colnum = 2;
    var $colnum_int = 2;
    var $tabularize = true;
    
    var $imode_table_support = false;
    var $xhtml_table_support = false;
    
    var $perform_tabularization = false;
    var $cellnumber = 1;
    
    var $counter = 0;

    function isFirstRowCell() {
#        error_log('Cellno: ' . $this->cellnumber . ', colnumInt: ' . $this->colnum_int);
        $ret = $this->cellnumber % $this->colnum_int == 1;
        $ret = $ret || ($this->colnum_int == 1);
#        error_log('Step2: ' . $ret);
        return $ret;
    }

    function isLastRowCell() {
        if (($this->cellnumber % $this->colnum_int) == 0) {
            $this->cellnumber = 1;
            return true;
        } else {
            $this->cellnumber++;
            return false;
        }
    }

    function WallElementCool_menu (&$wall, $attributes = Array()) {
        $this->WallElement($wall, $attributes);
        if (!($this->colnum_int = (int)$this->colnum)) {
            trigger_error("'colnum' property for 'cool_menu' element should be numeric and greater than zero.", E_USER_ERROR);
        }
    }
    
    function doStartTag() {
        parent::doStartTag();

        $this->cellnumber = 1;
        
        if ($this->getAncestorByClassName('wallelementblock')) {
            trigger_error("'cool_menu' tag cannot be nested inside a 'block' tag (breaks XHTML validity and will produce an error on some browsers).\n Close or remove the containing 'block' tag.", E_USER_ERROR);
        }
        
        if ($this->tabularize) {
            if (strpos($this->preferred_markup, 'chtml') !== false) {
                $this->imode_table_support = $this->perform_tabularization = $this->_wall->getCapa('chtml_table_support');
            } else if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
                $this->xhtml_table_support = $this->perform_tabularization = $this->_wall->getCapa('xhtml_table_support');
            }
        }
        if ($this->perform_tabularization) {
            if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
                $this->write('<table class="coolmenu">');
            } else if (strpos($this->preferred_markup, 'chtml') !== false) {
                $this->write('<table>');
            }
        } else {
            if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
                $this->write('<p>');
            }
        }
        
        if (strpos($this->preferred_markup, 'wml') !== false) {
            $this->write('<p align="left" mode="nowrap">');
        }
      
    }
    
    function doEndTag() {
        parent::doEndTag();
        if (strpos($this->preferred_markup, 'wml') !== false) {
            $this->write('</p>');
        } else {
            if ($this->perform_tabularization) {
                $css_for_xhtml = (strpos($this->preferred_markup, 'xhtmlmp') !== false) ? ' class="coolmenu"' : '';
                $start = $this->cellnumber % $this->colnum_int;
                if ($start != 1 && $this->colnum_int != 1) {
                    if ($start == 0) {
                        $this->write('<td' . $css_for_xhtml . '></td>');
                    } else {
                        for ($i = $start; $i <= $this->colnum_int; $i++) {
                            $this->write('<td' . $css_for_xhtml . '></td>');
                        }
                    }
                    $this->write('</tr>');
                }
                $this->write('</table>');
            } else {
                if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
                    $this->write('</p>');
                }
            }
        }
    }
}

?>