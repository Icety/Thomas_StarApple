<?php

class DivsController extends VanillaController {
	
	function beforeAction () {

	}

        //The first page, here you upload your html file using upload() and trigger findDivs to go through the html and store it in the db
        function index() {
          $error = "";
          if ($file = $this->Div->upload()) {
            $this->Div->findDivs($file, 0, mysql_insert_id());
            header('Location: '.BASE_PATH .'/divs/uploaded/'.$PageID);
          }
          elseif ($_POST) {
            $this->set('error', "The file u uploaded has been declined. Maybe the extention wasn't .htm or .html!");
          }
        }

        //On this page you can see all divs from the html file you uploaded and edit them the way you want to
        function uploaded($PageID, $BelongID = 0) {
          //Each div has a belongID, this BelongID stands for which div is his parent, if it's 0, he has no parent and therefor is the highest parent
          $this->Div->where('PageID',$PageID,'=');
          $this->Div->where('BelongID',$BelongID,'=');
          $this->Div->orderBy('order');
          $this->Div->showHasOne();
          //Get all divs from the given PageID and BelongID and select them by a certain order
          $divs = $this->Div->search();
          if (count($divs) > 0) {
            $ndivs = array();
            //Go through all divs and save an additional ['Repeat'] with in it all information of it's children and their children, etc
            foreach($divs as $div) {
              $div['Repeat'] = $this->uploaded($PageID, $div['Div']['DivID']);
              $ndivs[] = $div;
            }
          }
          if ($BelongID == 0) {                                //Me->  Rights need to be edited
            $scripts = performAction('scripts','getList',array(0));
            $this->set('divs',$ndivs);
            $this->set('scripts',$scripts);
          }
          else {
            return $ndivs;
          }
        }

        //These are scripts called by jqueries ajax calls and will save certain information given by uploaded.php in views
        function ajax($from) {
          //A simple save script, the name of the column is given through type, and with that the value is received and stored.
          if ($from == 'uploaded') {
            $this->render = 0;                 //Don't render the layout
            $this->Div->id = $_POST['DivID'];
            $type = $this->sql($_POST['type']);
            if ($type == 'delete') {              //If the type is delete, delete the entire row with id=DivID
              $this->Div->delete();
            }
            else {
              $this->Div->{$type} = $_POST['value'];
              $this->Div->save();
            }
          }
          elseif($from == 'placeChild') {                      //Save the new order and belongID's of divs that have been moved
            $this->render = 0;
            $from = $_POST['from'];                            //The div that is being moved
            $to = $_POST['to'];                                //The div it is being moved to
            $type = $_POST['type'];                            //Whether it is moved to as a child or as a brother
            //check if the div with id 'from' excists
            $this->Div->id = $from;
            $divFrom = $this->Div->search();
            if ($from != $to) {
              if (count($divFrom > 0)) {
                //check if the target position is no child of the div it came from
                $temp = $to;
                while (true) {
                  $this->Div->id = $temp;
                  $check = $this->Div->search();
                  if ($check['Div']['BelongID'] == '0') {
                    break;
                  }
                  elseif($check['Div']['BelongID'] == $from) {
                    echo $check['Div']['BelongID'] ." == $from";
                    echo "The div you're trying to move towards is a child of the div you're moving. This is not allowed. Try reloading your page.";
                    return;
                  }
                  else {
                    $temp = $check['Div']['BelongID'];
                  }
                }
                //Set the order of the old brothers of div 'move' to 1 less
                $this->Div->custom("UPDATE `divs` SET `order`=`order`-1 WHERE `order`>'".$divFrom['Div']['order']."' AND `BelongID`='".$divFrom['Div']['BelongID']."'");
                if ($type == 'child') {               //Edit the belongID of the div that is moved to the id of the div it is moved to
                  $this->Div->id = $from;
                  $this->Div->order = "0";
                  $this->Div->BelongID = $to;
                  $this->Div->save();
                }
                elseif ($type == 'below') {           //Edit the belongID of the div that is moved to the belongID of the div it is moved to and edit the order of all divs below
                  $this->Div->id = $to;
                  $divTo = $this->Div->search();
                  $this->Div->custom("UPDATE `divs` SET `order`=`order`+1 WHERE `order`>'".$divTo['Div']['order']."' AND `BelongID`='".$divTo['Div']['BelongID']."'");
                  $this->Div->id = $from;
                  $this->Div->order = $divTo['Div']['order'] + 1;
                  $this->Div->BelongID = $divTo['Div']['BelongID'];
                  $this->Div->save();
                }
                echo "Success";
              }
              else {
                echo "You are trying to move a non-existing div. Refreshing the page might resolve this problem.";
              }
            }
            else {
              echo "It's not possible to make a div it's own child/parent.";
            }
          }
        }

	function afterAction() {

	}
}