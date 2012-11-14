<?php

class TextsController extends VanillaController {
	
	function beforeAction () {

	}

        function index() {
        
        }

        //Edit the text inside a div
        function edit($id) {
          $this->Text->where('DivID', $id, '=');
          $text = $this->Text->search();
          $this->set('text', $text[0]['Text']['text']);
          
          if (isset($_POST['save'])) {
            //If there is already a line in the database, update the text, else insert a new line
            if ($text) {
              $this->Text->custom("UPDATE `texts` SET `text`='".$_POST['text']."' WHERE `DivID`='$id'");
            }
            else {
              $data = array('DivID' => $id, 'text' => $_POST['text']);
              $this->Text->set($data);
              $this->Text->save();
            }
            header('Refresh: 0');
          }

        }

	function afterAction() {

	}
}