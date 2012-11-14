<?php

class PagesController extends VanillaController {

	function beforeAction () {

	}

        //This script gets all pages from the database and sends it's information down so all links can be shown
        function linkbar($DivID, $SubID = 0) {
          $this->Page->where('DivID',$DivID,'=');
          $this->Page->where('SubID',$SubID,'=');
          if ($rights >= 0) 
            $this->Page->where('rights',$rights,'<=');
          $links = $this->Page->search();
          foreach ($links as $link) {                             //Go through all links and then find their children -> Recursion
            $link['Page']['subs'] = linkbar($DivID, $link['Page']['PageID']);
          }
          if ($SubID = 0) {
            $properties = performAction('properties','getProperties',array($DivID));
            $this->set('properties', $properties);
            $this->set('links', $links);
          }
          else {
            return($links);
          }
        }

	function afterAction() {

	}
}