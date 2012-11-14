<?php

class RightsController extends VanillaController {
	
	function beforeAction () {

	}

        function index() {

        }

        //Give back a list of rights that exist
        function getList() {
          $result = $this->Right->search();
          return $result;
        }

	function afterAction() {

	}
}