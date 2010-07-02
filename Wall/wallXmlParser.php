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


class wallXmlParser {
    var $data = NULL;
    
    var $encoding = 'utf-8';
    
    var $onElementStart = NULL;
    var $onElementEnd = NULL;
    var $onCdata = NULL;
    
    var $obj = NULL;
    
    var $pos = 0;
    
    function wallXmlParser($data, $encoding = 'utf-8') {
        $this->encoding = $encoding;
        $this->data = $data;
    }
    
    function setHandlers($start, $end, $cdata) {
        $this->onElementStart = $start ? $start : NULL;
        $this->onElementEnd = $end ? $end : NULL;
        $this->onCdata = $cdata ? $cdata : NULL;
    }
    
    function setObject(&$obj) {
        $this->obj = &$obj;
    }
    
    function parse() {
        $tmp = '';
        $len = strlen($this->data);
        for ($i = 0; $i < $len; $i++) {
            $char = substr($this->data, $i, 1);
            $tmp .= $char;
            switch ($char) {
                case '>' :
                    if (preg_match('/
                    <
                    (\/)?          # Does it start with flash?
                    ([a-z\_\-\:123456]+) # Tag name
                    ([^>]+)?         # All trailing shit
                    >          # Does it end with slash?
                    /xmsi', $tmp, $matches)) {
                        
                        if (isset($matches[3]) && substr($matches[3], strlen($matches[3])-1, 1) == '/') {
                            $matches[3] = substr($matches[3], 0, -1);
                            $matches[4] = '/';
                        }

                        $single = false;
                        $attributes = Array();

                        if (isset($matches[3]) && strlen(trim($matches[3]))) {
                            #$a = trim($matches[3]);
                            $re = '/([a-z0-9\_\-]+)(="([^"]+)")?/is';
                            $re = '/([a-z0-9\_\-]+)=(["\'])(.*)\2/Uis'; # The new, improved version (attributes values now with ')
                            if (preg_match_all($re, $matches[3], $m)) {
                                foreach ($m[0] as $k=>$v) {
                                    $attributes[$m[1][$k]] = $m[3][$k];
                                }
                            }
                        }
                        if (isset($matches[1]) && $matches[1]) {
                            if ($this->obj) {
                                $func = Array(&$this->obj, $this->onElementEnd);
                            } else {
                                $func = $this->onElementEnd;
                            }

                            call_user_func($func, $this, $matches[2]);
                            
                        } else if (isset($matches[4]) && $matches[4]) {
                            if ($this->obj) {
                                $func = Array(&$this->obj, $this->onElementStart);
                            } else {
                                $func = $this->onElementStart;
                            }

                            call_user_func($func, $this, $matches[2], $attributes, true);
                            $single = true;
                        } else {
                            if ($this->obj) {
                                $func = Array(&$this->obj, $this->onElementStart);
                            } else {
                                $func = $this->onElementStart;
                            }

                            call_user_func($func, $this, $matches[2], $attributes, false);
                        }
                    } else {
                        if ($this->obj) {
                            $func = Array(&$this->obj, $this->onCdata);
                        } else {
                            $func = $this->onCdata;
                        }
                        call_user_func($func, $this, $tmp);
                    }
                    $tmp = '';
                    $insideTag = false;
                    break;
                case '<' :
                    $tmp = substr($tmp, 0, -1);
                    if (strlen($tmp)) {
                        if ($this->obj) {
                            $func = Array(&$this->obj, $this->onCdata);
                        } else {
                            $func = $this->onCdata;
                        }
                        call_user_func($func, $this, $tmp);
                    }
                    $tmp = '<';
                    $insideTag = true;
                    break;
            }
        }
    }
    
}

?>