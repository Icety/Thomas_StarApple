<?php

class ScriptsController extends VanillaController {
	
	function beforeAction () {

	}

        function index() {
        }
        
        //Returns the ScriptID that belongs to a certain Shortcut
        function getId($shortcut) {
          $this->Script->where('shortcut',$shortcut,'=');
          $script = $this->Script->search();
          return ($script['0']['Script']['ScriptID']);
        }

        //Returns a list of all scripts available for the 'rights' the person has
        function getList($rights = 0) {
          if ($rights >= 0)
            $this->Script->where('rights',$rights,'<=');

          $result = $this->Script->search();
          return $result;
        }

	function afterAction() {

	}
}