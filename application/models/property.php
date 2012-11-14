<?php

class Property extends VanillaModel {

        //Serialize the main information and insert it, after that insert maxdepth, set and set_repeat
        function insertLinkbar($data) {
          $columns = array('DivID', 'name', 'value');
          for ($i=0;$i<count($data['link']);$i++) {
            $values = array($data['DivID'], 'link'.$i, serialize(array($data['startHtml'][$i], $data['link'][$i], $data['endHtml'][$i], $data['rights_add'][$i], $data['rights_edit'][$i])));
            $this->insertQuery($columns, $values);
          }
          $this->insertQuery($columns, array($data['DivID'], 'maxdepth', serialize($data['maxdepth'])));
          $this->insertQuery($columns, array($data['DivID'], 'set', serialize($data['set'])));
          $this->insertQuery($columns, array($data['DivID'], 'set_repeat', serialize($data['set_repeat'])));
        }
}