<?php

class VanillaController {
	
	protected $_controller;
	protected $_action;
	protected $_template;

	public $doNotRenderHeader;
	public $render;

	function __construct($controller, $action) {
		
		global $inflect;

		$this->_controller = ucfirst($controller);
		$this->_action = $action;
		
		$model = ucfirst($inflect->singularize($controller));
		$this->doNotRenderHeader = 0;
		$this->render = 1;
		$this->$model =& new $model;
		$this->_template =& new Template($controller,$action);

	}

	function set($name,$value) {
		$this->_template->set($name,$value);
	}

	function __destruct() {
		if ($this->render) {
			$this->_template->render($this->doNotRenderHeader);
		}
	} 
        function sql($set) {
                if (is_array($set)) {
                  foreach($set as &$value) {
                    $value = $this->sql($value);
                  }
                  return $set;
                }
                else {
                  return mysql_real_escape_string($set);
                }
        }
        
        function desql($set) {
                if (is_array($set)) {
                  foreach($set as &$value) {
                    $value = $this->desql($value);
                  }
                  return $set;
                }
                else {
                  return stripslashes($set);
                }
        }

}