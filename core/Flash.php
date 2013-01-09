<?php
    /*
    *   FLASH version 1.0
    *
    *   Imagina - Plugin.
    *
    *
    *   Copyright (c) 2012 Dolem Labs
    *
    *   Authors:    Paul Marclay (paul.eduardo.marclay@gmail.com)
    *
    */
    
    class Flash extends Ancestor {
        private $_prefix    = null;
        
        public function __construct() {
            $this->_prefix  = "flash_";
        }
        
        private function getSessionKey($key) {
            return $this->_prefix.$key;
        }
        
        public function __call($method, $args) {
	    	switch (substr($method, 0, 3)) {
            case 'get' :
                $key    = Conversor::underscore(substr($method,3));
                $result = $this->_getData($key, isset($args[0]) ? $args[0] : null);
                return $result;

            case 'set' :
                $key    = Conversor::underscore(substr($method,3));
                $result = $this->_setData($key, isset($args[0]) ? $args[0] : null);
                return $result;
	    	
            case 'has' :
                $key = Conversor::underscore(substr($method,3));
                return $this->_hasData($key);
                
            case 'uns' :
                $key = Conversor::underscore(substr($method,3));
                return $this->_unsData($key);
	    	}
	    }
        
        public static function __callStatic($method, $args) {
            $class = 'Flash';
            $tmpObject = new $class;
            return $tmpObject->__call($method, $args);
        }
        
        private function _setData($key, $value = null) {
            Api::getSession()->setDataUsingMethod($this->getSessionKey($key), $value);
	    }
        
        private function _hasData($key) {
            return Api::getSession()->is_set($this->getSessionKey($key));
        }
        
        private function _getData($key = '') {
	    	if ($this->_hasData($key)) {
                $tmpValue = Api::getSession()->getDataUsingMethod($this->getSessionKey($key));
                Api::getSession()->unsDataUsingMethod($this->getSessionKey($key));
                return $tmpValue;
            }
            
            return null;
	    }
        
        private function unsData($key) {
            if ($this->_hasData($key)) {
                Api::getSession()->unsDataUsingMethod($this->getSessionKey($key));
            }
            return true;
        }
        
        public function __set($name, $value) {
            $key    = Conversor::underscore($name);
            $this->_setData($key, $value) ? $value : null;
        }

        public function __get($name) {
            $key    = Conversor::underscore($name);
            $result = $this->_getData($key, isset($name) ? $name : null);

            return $result;
        }
        
    }