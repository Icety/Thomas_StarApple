//Standard in each js file, to give the js file the basepath of the site
var basePath = "";
function setBasepath(value) {
  basePath = value;
}

//Script with functions belonging to uploaded.php

//Keeps track of the mouseposition
$(document).ready(function(){
   $(document).mousemove(function(e){
      window.mouseXPos = e.pageX;
      window.mouseYPos = e.pageY;
   }); 
})

function editRadio(id) {
  //Shows a box on location of the mouse with 3 radio buttons in it: Id, Class and none.
  //The current value of 'id' is already checked.
  //And shows a submit button, once the submit has been pressed, Ajax gets triggered to save the result.
  var selecter = $('#ci' + id).html();
  if($("#box").length > 0) $("#box").remove();
  newdiv = $('<div id="box" style="position: absolute; top: '+window.mouseYPos+'px; left: '+window.mouseXPos+'px; padding: 10px; background-color: #fff;">'+
                  '<form action="javascript:save(\'ci\',\'\','+id+');" name="radioform">'+
                  '<input type="radio" name="editRadio" value="id" ' + ((selecter == 'id')?'checked':'') + '/>id<br />'+
                  '<input type="radio" name="editRadio" value="class" ' + ((selecter == 'class')?'checked':'') + '/>class<br />'+
                  '<input type="radio" name="editRadio" value="" ' + ((selecter == '')?'checked':'') + '/>none'+
                  '<button>save</button></form></div>');
  $('body').append(newdiv);
}

function editName(id) {
  //Open a box with a input type=text in it, with value of the value of the span with id 'id'.
  //On submit edit in the html and trigger Ajax to save it
  var nameValue = $('#name' + id).html();
  if($("#box").length > 0) $("#box").remove();
  newdiv = $('<div id="box" style="position: absolute; top: '+window.mouseYPos+'px; left: '+window.mouseXPos+'px; padding: 10px; background-color: #fff;">'+
                  '<form action="javascript:save(\'name\',\'\','+id+');" name="nameform">'+
                  '<input type="text" name="editName" value="' + nameValue + '" />'+
                  '<button>save</button></form></div>');
  $('body').append(newdiv);
}

function editStyle(id) {
  //Open textarea to edit style, save on submit.
  var styleValue = $('#style' + id).html();
  if($("#box").length > 0) $("#box").remove();
  newdiv = $('<div id="box" style="position: absolute; top: '+window.mouseYPos+'px; left: '+window.mouseXPos+'px; padding: 10px; background-color: #fff;">'+
                  '<form action="javascript:save(\'style\',\'\','+id+');" name="styleform">'+
                  '<textarea name="editStyle" cols="35" rows="5">' + styleValue + '</textarea>'+
                  '<button>save</button></form></div>');
  $('body').append(newdiv);
}

function editText(id) {
  //Same as editStyle, but save the info as text in table texts
  //Temporary solution
  var script = $('#script' + id + ' option:selected').text();
  alert(script);
  if (script == 'linkbar') {
    path = basePath + '/properties/linkbar/' + id;
  }
  else if (script == 'text') {
    path = basePath + '/texts/edit/' + id;
  }
  if (script != '' && script != 'No script') {
    window.open(path);
  }
  //This has to open in a frame and after saving it has to update the file below
}

//Delete a row and all of its childs out of the database and screen
function deleteRow(id, message) {
  var check = true;
  check = confirm('Weet u zeker dat u deze en alle onderliggende divs wilt verwijderen?');
  if (check) {
    save('delete', '0', id);
    next = $('#div' + id);
    min = $('#ci' + id).css('margin-left').replace('px','');
    //A minimum margin-left has been found and the current div is set as 'next'
    while (true) {
      id = next.attr('id').replace('div','');      //Get the id of the current selected div
      next = next.next();                          //Get the next div from the table
      var element = $('#div' + id);
      element.parentNode.removeChild(element);     //Delete the row from the screen
      if (!next.attr('id')) {                      //If there's no next div, quit the while
        break;
      }
      tempId = next.attr('id').replace('div','');                       //Get the id from 'next'
      css = $('#ci' + tempId).css('margin-left').replace('px','');      //Get the indent from 'next'
      if (css <= min && tempId) {        //If there is a next div and it's indent is lower then the minimum, quit the while
        break;
      }
    }
  }
}

//This function saves a value from a certain type to the database, a value and a type are given: type is the column of the database
function save(type,value,DivID) {
  if(type=="ci") {                                     //ci is a radio button and needs to be retreived from the screen
    radio = document.radioform;
    for(var i = 0; i < radio.editRadio.length; i++) {  //Go through all radio buttons in the form to find the one that is checked
	if(radio.editRadio[i].checked) {
		value = radio.editRadio[i].value;
	}
    }
  }
  if(type=="name") {                                   //Same as ci, it needs to be received from the screen
    value = document.nameform.editName.value + ' ';
  }
  if(type=="style") {
    value = document.styleform.editStyle.value + ' ';  //Same as ci and name
  }

  $.ajax({
  type: "POST",
  url: basePath + "/divs/ajax/uploaded",
  data: { type: type,value: value,DivID: DivID}
  }).done(function( msg ) {
    if (type == 'ci' || type == 'name' || type == 'style') {
      $('#' + type + DivID).html(value);                        //Edit the screen with the result
    }
  });

  if($("#box").length > 0) $("#box").remove();
}

//Color all divs that are going to be moved and change the buttons at the beginning of each div
function prepareMove(id) {
  next = $('#div' + id);
  min = $('#ci' + id).css('margin-left').replace('px','');
  while (true) {
    id = next.attr('id').replace('div','');
    $("#div"+ id + " td").css("background-color","#7F683F");
    $("#move"+ id).css('display', 'none');
    next = next.next();                //Get the next div from the table
    if (!next.attr('id')) {
      break;
    }
    tempId = next.attr('id').replace('div','');                       //Get the id from 'next'
    css = $('#ci' + tempId).css('margin-left').replace('px','');      //Get the indent from 'next'
    if (css <= min && tempId) {        //If there is a next div and it's indent is lower then the minimum, quit the while
      break;
    }
  }
}
var moving = false;
var from = "";
//If a person presses a move button, this function is called and movement is started
function move(id) {
  if (moving == false) {        //If not moving yet-> start moving
    moving = true;
    from = id;
    //change images
    $('img').each(function() {
      if (this.src == basePath + '/public/ico/move.png') {
        this.src = basePath + '/public/ico/place.png';
      }
    });
    prepareMove(id);
    $("#move"+ id).css('display', 'block');
    $("div0").css('display', 'block');
  }
  else {                        //If moving already show boxes with options
    if($('#move' + id).css('display') != 'none') {
      if($('#box').length > 0) $("#box").remove();
      if(id == from) {
        //If this is the div it came from-> Show a cancel button
        newdiv = $('<div id="box" style="position: absolute; top: '+window.mouseYPos+'px; left: '+window.mouseXPos+'px; padding: 10px; background-color: #fff;">'+
                        '<span class="hand" onclick="endMove()"><img src="'+ basePath +'/public/ico/delete.png" width="18px" />'+
                        'Cancel move </span> <br /><br />'+
                        '</div>');
      }
      else {
        //If this is a different div than the div that is moved show 'Place as child' and 'Place below' buttons
        newdiv = $('<div id="box" style="position: absolute; top: '+window.mouseYPos+'px; left: '+window.mouseXPos+'px; padding: 10px; background-color: #fff;">'+
                        '<span class="hand" onclick="place('+ id +', \'child\')"><img src="'+ basePath +'/public/ico/arrow_right.png" width="18px" />'+
                        'Place as child </span> <br /><br />'+
                        '<span class="hand" onclick="place('+ id +', \'below\')"><img src="'+ basePath +'/public/ico/arrow_down.png" width="18px" />'+
                        'Place below </span> <br />'+
                        '</div>');
      }
      $('body').append(newdiv);
    }
  }
}

function moveDivs(id, id2, indent, min) {
  next = $('#div' + id);
  while (true) {
    id = next.attr('id').replace('div','');
    next = next.next();
    oldIndent = parseInt($('#ci' + id).css('margin-left').replace('px',''));
    newIndent = oldIndent + indent;
    //set the indent for the new location
    $('#ci' + id).css('margin-left',newIndent +'px');
    //delete the div from the formal location and remove it to it's new location
    $('#div' + id).remove().insertAfter('#div' + id2);
    id2 = id;
    if (!next.attr('id')) {            //If there is no next div, quit the while
      break;
    }
    tempId = next.attr('id').replace('div','');
    css = $('#ci' + tempId).css('margin-left').replace('px','');
    if (css <= min && tempId) {        //If there is a next div, but its indent is lower then the minimum, quit the while
      break;
    }
  }
}

function getDropLocation(id, min) {    //If a div is placed below another div it has to be placed below all of it's child in the table
  next = $('#div' + id);
  while (true) {
    next = next.next();                //Get the next div from the table
    if (!next.attr('id')) {
      break;
    }
    tempId = next.attr('id').replace('div','');                       //Get the id from 'next'
    css = $('#ci' + tempId).css('margin-left').replace('px','');      //Get the indent from 'next'
    if (css <= min && tempId) {        //If there is a next div and it's indent is lower then the minimum, quit the while
      break;
    }
  }
  prev = next.prev();
  if (!prev.attr('id')) {
    prev = $('#div' + id);
  }                                  //Since the 'next' div wasn't good we go back one
  if (prev.attr('id').replace('div','') == from) {      //If the one we are on now is the move that is being moved we go back one more
    prev = prev.prev();
  }
  return prev.attr('id').replace('div','');             //Return the id of the div which the new div should be placed below in the table
}

function place(id, type) {
  $.ajax({
  type: "POST",
  url: basePath + "/divs/ajax/placeChild",
  data: { type: type,from: from,to: id}
  }).done(function( msg ) {
    if (msg == 'Success') {
      toIndent = parseInt($('#ci' + id).css('margin-left').replace('px',''));      //Get the indent of the div it is placed below or as child
      fromIndent = parseInt($('#ci' + from).css('margin-left').replace('px',''));
      indent = toIndent - fromIndent;
      //If it is to be a child make the indent bigger
      if (type == 'child') {
        indent += 15;
      }
      if (type == 'below') {                                                      //If it has to be placed below another div, a check is done for children of the 'to' div
        min = $('#ci' + id).css('margin-left').replace('px','');
        id = getDropLocation(id, min);
      }

      min = $('#ci' + from).css('margin-left').replace('px','');                  //The minimal indent, this is used to know which divs are childs of the div that is being moved
      moveDivs(from, id, indent, min);
      endMove(from);
    }
    else {
      alert(msg);
    }
  });

}

function endMove() {
  moving = false;
  from = "";
  $('img').each(function() {
    if (this.src == basePath + '/public/ico/place.png') {
      this.src = basePath + '/public/ico/move.png';
      this.style.display = 'block';
    }
  });
  $('td').each(function() { 
    $("td").css("background-color","#FFD07F");
  });
  if($('#box').length > 0) $("#box").remove();
}
