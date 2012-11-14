<?php

class PropertiesController extends VanillaController {

	function beforeAction () {

	}

        //Get properties of a certain div, unserialize it and send it back
        function getProperties($DivID) {
          $this->Property->where('DivID',$DivID,'=');
          $results = $this->Property->search();
          foreach ($results as $result) {
            $properties[$result['Property']['name']] = $this->desql(unserialize($result['Property']['value']));
          }
          return($properties);
        }

        function linkbar($DivID = 0) {
          if (isset($_POST['submit'])) {
            $this->Property->id = $DivID;
            $this->Property->delete();                        //Delete all old configurations of the linkbar, so new ones can be saved instead
            $data['DivID'] = $DivID;
            $data['startHtml'] = $_POST['startHtml'];         //The html before the links eg. <ul> or <ol>
            $data['endHtml'] = $_POST['endHtml'];             //The html after the links eg. </ul> or </ol>
            $data['link'] = $_POST['link'];                   //The html for the actual link with %link% for the link and %name% for the name shown
            $data['rights_add'] = $_POST['rights_add'];       //The type of rights needed to add/delete this type of link
            $data['rights_edit'] = $_POST['rights_edit'];     //The type of rights needed to edit this type of link
            $data['maxdepth'] = $_POST['maxdepth'];           //The maximal amount of sublinks allowed
            $data['set'] = $_POST['set'];                     //The order at which the above should be shown
            $data['set_repeat'] = $_POST['set_repeat'];       //The order at which the repeat of the above should be shown
            $this->Property->insertLinkbar($data);            //Save all information of above
          }

          $properties = $this->getProperties($DivID);
          $rights = performAction('rights','getList',array());
          $this->set('properties', $properties);
          $this->set('rights', $rights);
        }

	function afterAction() {

	}
}