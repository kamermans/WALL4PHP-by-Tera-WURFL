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

class WallElementBody extends WallElement {
    
    var $tag = 'body';
    
    var $bgcolor = false;
    var $text = false;
    var $wml_back_button_label = 'Back';
    var $disable_wml_template = false;
    
    var $xhtml_extra_title = true;
    var $back_button_support = true;
    
    var $document = false;
    
    function WallElementBody (&$wall, $attributes = Array()) {
        $this->WallElement($wall, $attributes);
        $this->document =& $this->getAncestorByClassName('wallelementdocument');
        if (!$this->document) {
            trigger_error("tag 'body' must be nested inside a 'document' tag", E_USER_ERROR);
        }
        $this->xhtml_extra_title = $this->_wall->getCapa('xhtml_document_title_support');
        $this->back_button_support = $this->_wall->getCapa('built_in_back_button_support');
        $this->wml_extra_title = $this->_wall->getCapa('card_title_support');
    }
    
    function doStartTag() {
        parent::doStartTag();

        if (strpos($this->preferred_markup, 'xhtmlmp') !== false ||
            strpos($this->preferred_markup, 'chtml') !== false) {
            
            $this->write('<body');

            if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
                $style = Array();
                if ($this->text) {
                    $style[] = 'color: ' . $this->text;
                }
                if ($this->bgcolor) {
                    $style[] = 'background-color: ' . $this->bgcolor;
                }
                
                if (count($style)) {
                    $this->write(' style="' . join($style, '; ') . '"');
                }
                
            } else {
                if ($this->text) {
                    $this->write(' text="' . $this->text . '"');
                }
                if ($this->bgcolor) {
                    $this->write(' bgcolor="' . $this->bgcolor . '"');
                }
            }

            $this->write('>');
            
            //echo $this->_wall->enforce_title;
            
            if ($this->_wall->enforce_title) {
                if (!$this->xhtml_extra_title) {
                    $this->write('<p>' . $this->document->title . '</p>');
                }
            }
                
        } else if (strpos($this->preferred_markup, 'wml') !== false) {

            if (!$this->back_button_support && !$this->disable_wml_template) {
                $this->writeln('<template><do type="prev" label="' . $this->wml_back_button_label . '"><prev/></do></template>');
            }
            
            $this->write('<card id="w" title="' . $this->document->title . '">');
            
            if ($this->_wall->enforce_title) {
                if (!$this->wml_extra_title) {
                    $this->write('<p>' . $this->document->title . '</p>');
                }
            }

        }
        
    }
    
    function doEndTag() {
        parent::doEndTag();

        if (strpos($this->preferred_markup, 'xhtmlmp') !== false ||
            strpos($this->preferred_markup, 'chtml') !== false) {
            
            $this->write('</body>');
            $this->write('</html>');
            
        } else if (strpos($this->preferred_markup, 'wml') !== false) {

            $this->write('</card>');
            $this->write('</wml>');
        
        }
        
    }
    
}

?>