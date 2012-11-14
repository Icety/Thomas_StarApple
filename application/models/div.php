<?php

class Div extends VanillaModel {
	var $hasOne = array('Text' => 'Text');

        function file_get_html($url, $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT) {
            // We DO force the tags to be terminated.
            $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $defaultBRText);
            // For sourceforge users: uncomment the next line and comment the retreive_url_contents line 2 lines down if it is not already done.
            $contents = file_get_contents($url, $use_include_path, $context, $offset);
            // Paperg - use our own mechanism for getting the contents as we want to control the timeout.
            // $contents = retrieve_url_contents($url);
            if (empty($contents)) {
                return false;
            }
            // The second parameter can force the selectors to all be lowercase.
            $dom->load($contents, $lowercase, $stripRN);
            return $dom;
        }

        //The upload of an html-file
        function upload() {
          if (isset($_POST['send'])) {
            $filename = $_FILES['file']['name'];
            $extension = explode('.', $filename);
            $extension = $extension[count($extension)-1];
            if ($extension == "htm" || $extension == "html") {
              if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                //Add a new page in the db
                $this->custom("INSERT INTO `pages` (`name`,`edit`,`view`) VALUES
                                                   ('$name','3','3')");
                return $this->file_get_html($_FILES['file']['tmp_name']);
              }
            }
            else {
              return false;
            }
          }
          else {
            return false;
          }
        }

        function findDivs($file, $BelongID = 0, $PageID) {
          //Search for the first div in $file
          $element = $file->find('div',0);
          while ($element) {
            //Get all information from the div and insert it into the database
            $ci = ($element->id)?'id':'class';
            $name = mysql_real_escape_string(($ci == "id")?$element->id:$element->class);
            $style = mysql_real_escape_string($element->style);
            $order = $count;

            //Check if a special div is required for a certain div.
            $script = 0;
            $innertext = $element->innertext;
            $script = (substr_count($innertext,"<div") == 0)?'text':'';
            $ScriptID = performAction('scripts','getId',array($script));
            $this->custom("INSERT INTO `divs` (`name`,`BelongID`,`PageID`,`ScriptID`,`ci`,`style`,`order`) VALUES
                                              ('$name','$BelongID','$PageID','$ScriptID','$ci','$style','$order')");
            $id = mysql_insert_id();
            if ($script == "text") {
              $this->custom("INSERT INTO `texts` (`DivID`,`text`) VALUES
                                                 ('$id','$innertext')");
            }
            $this->findDivs($element, $id, $PageID);
            $count++;
            //Search for div that is in the same layer as the div of $element
            $element = $element->next_sibling();            
          }
        }


}