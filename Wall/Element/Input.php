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

class WallElementInput extends WallElement {
    
    var $tag = 'input';
    
    var $accesskey = false;
    var $checked = false;
    var $disabled = false;
    var $emptyok = true;
    var $format = false;
    var $maxlength = false;
    var $name = false;
    var $size = false;
    var $type = false;
    var $value = false;
    var $title = false;
    var $xhtmlId = false;
    var $xhtmlClass = false;
    
    function WallElementInput (&$wall, $attributes = Array()){
        $this->WallElement($wall, $attributes);

        $this->cssformat = $this->_wall->getCapa('xhtml_format_as_css_property');
        $this->attributeformat = $this->_wall->getCapa('xhtml_format_as_attribute');
        $this->softkey = $this->_wall->getCapa('softkey_support');
        $this->wml13 = $this->_wall->getCapa('wml_1_3');
        
        $this->form =& $this->getAncestorByClassName('wallelementform');
    }
    
    function doStartTag() {
        parent::doStartTag();
        
        if (!$this->getAncestorByClassName('wallelementdocument')) {
            trigger_error("tag 'input' must be nested inside a 'document' tag", E_USER_ERROR);
        }
        
        if ($this->emptyok && $this->emptyok != 'emptyok') {
            trigger_error("Only admitted value for emptyok attribute (<input> tag) is 'emptyok'", E_USER_ERROR);
        }

        if ($this->type == 'submit') {

            if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
                $this->write('<input type="submit" name="' . $this->name . '" value="' . $this->value . '"/>');
            } else if (strpos($this->preferred_markup, 'chtml') !== false) {
                $this->write('<input type="submit" name="' . $this->name . '" value="' . $this->value . '">');
            } else if (strpos($this->preferred_markup, 'wml') !== false) {
                
                $this->writeln('');
                
                if (!$this->wml13 && $this->softkey) {
                    $this->writeln('<do type="accept" label="' . $this->value . '">');
                } else {
                    $this->writeln('<anchor>' . $this->value);
                }
                
                $this->write('<go href="' . $this->form->action . '"');
                if ($this->form->method) {
                   $this->write(' method="' . $this->form->method . '"');
                }
                $this->writeln('>');
                
                foreach ($this->form->fields as $field) {
                    $this->write('<postfield name="' . $field['name'] . '" value="');
                    if ($field['type'] == 'hidden') {
                        $this->write($field['value']);
                    } else {
                        $this->write('$' . $field['name']);
                    }
                    $this->writeln('" />');
                }
                
                $this->writeln('</go>');
                if (!$this->wml13 && $this->softkey) {
                   $this->writeln('</do>');
                } else {
                    $this->writeln('</anchor>');
                }
                
            }

        } else {
        
            if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
    
                $this->write('<input type="' . $this->type . '" name="' . $this->name . '" value="' . $this->value . '"');
                
                $style = '';
                if ($this->format) {
                    if ($this->cssformat) {
                        $style .= '-wap-input-format: &apos;' . $this->format . '&apos;; ';
                    } else if ($this->attributeformat) {
                        $this->write(' format="' . $this->format . '"');
                    }
                }
                
                if (!$this->emptyok) {
                    if ($this->cssformat) {
                        $style .= '-wap-input-required; ';
                    } else if ($this->attributeformat) {
                        $this->write(' emptyok="emptyok"');
                    }
                }

                if (strlen($style)) $this->write(' style="' . trim($style) . '"');
                
                if ($this->title) $this->write(' title="' . $this->title . '"');
                if ($this->accesskey) $this->write(' accesskey="' . $this->accesskey . '"');
                if ($this->checked) $this->write(' checked="' . $this->checked . '"');
                if ($this->disabled) $this->write(' disabled="' . $this->disabled . '"');
                if ($this->maxlength) $this->write(' maxlength="' . $this->maxlength. '"');
                if ($this->size) $this->write(' size="' . $this->size . '"');
                
                if ($this->xhtmlClass) {
                    $this->write(' class="' . $this->xhtmlClass . '"');
                }
                if ($this->xhtmlId) {
                    $this->write(' id="' . $this->xhtmlId . '"');
                }

                $this->write('/>');
                
    
            } else if (strpos($this->preferred_markup, 'chtml') !== false) {

                $this->write('<input type="' . $this->type . '" name="' . $this->name . '" value="' . $this->value . '"');
                
                $style = '';
                if ($this->format && $this->type == 'text' && ($this->format == 'N*' || preg_match('/^N{1,}$/', $this->format))) {
                    $this->write(' istyle="4"');
                }
                
                if (strlen($style)) $this->write(' style="' . trim($style) . '"');
                
                if ($this->title) $this->write(' title="' . $this->title . '"');
                if ($this->accesskey) $this->write(' accesskey="' . $this->accesskey . '"');
                if ($this->checked) $this->write(' checked="' . $this->checked . '"');
                if ($this->disabled) $this->write(' disabled="' . $this->disabled . '"');
                if ($this->maxlength) $this->write(' maxlength="' . $this->maxlength. '"');
                if ($this->size) $this->write(' size="' . $this->size . '"');
                
                $this->write('>');
    
            } else if (strpos($this->preferred_markup, 'wml') !== false) {
                
                $this->form->addField($this);
    
                if ($this->type != 'hidden') {
                    
                    $this->write('<input type="' . $this->type . '" name="' . $this->name . '" value="' . $this->value . '"');
                    if ($this->title) $this->write(' title="' . $this->title . '"');
                    if ($this->format) $this->write(' format="' . $this->format . '"');
                    if (!$this->emptyok) $this->write(' emptyok="false"');
                    if ($this->accesskey) $this->write(' accesskey="' . $this->accesskey . '"');
                    if ($this->maxlength) $this->write(' maxlength="' . $this->maxlength. '"');
                    if ($this->size) $this->write(' size="' . $this->size . '"');
                    
                    $this->write('/>');

                }
    
            }

        }

    }
    
}

?>
