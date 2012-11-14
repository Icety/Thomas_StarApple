<!DOCTYPE HTML>
<html>
<head>
  <title>Linkbar - ThCMS</title>
  <?php echo $html->includeCss('thcms'); ?>
  <script type="text/javascript">
    function updateTitle(id, value) {
      value = value.replace(/</g, '&#60;');
      value = value.replace(/>/g, '&#62;');
      document.getElementById('title'+id).innerHTML = value;
    }

    function showLink(id) {
      type = document.getElementById('link'+id).style.display;
      if (type == 'block') {
        document.getElementById('link'+id).style.display = 'none';
        document.getElementById('title'+id).style.display = 'block';
        document.getElementById('image'+id).src = '<?php echo BASE_PATH; ?>/public/ico/plus.png';
      }
      else {
        document.getElementById('link'+id).style.display = 'block';
        document.getElementById('title'+id).style.display = 'none';
        document.getElementById('image'+id).src = '<?php echo BASE_PATH; ?>/public/ico/minus.png';
      }
    }

    function addLink() {
      var table = document.getElementById('table');
      var row = document.getElementById('count').value;
      var nr = document.getElementById('nr').value;
      var newRow = table.insertRow(nr-1);

      var cell1 = newRow.insertCell(0);
      var cell2 = newRow.insertCell(1);
      row++;
      newRow.id = 'total' + row;
      cell1.innerHTML = '<span id="count' + nr + '">' + nr + '</span>                                                                                    \
      <img src="<?php echo BASE_PATH; ?>/public/ico/minus.png" class="hand" width="24px" id="image' + row + '" onClick="showLink(' + row + ')"/>      \
      <img src="<?php echo BASE_PATH; ?>/public/ico/delete.png" class="hand" width="24px" onClick="deleteLink(' + row + ')"/>';
      cell2.innerHTML = '<span id="title' + row + '" style="display:none;"></span>                                                        \
        <table id="link' + row + '" style="display:block;">                                                                               \
          <tr>                                                                                                                            \
            <td>Start Html</td>                                                                                                           \
            <td><textarea name="startHtml[]" rows="3" cols="70"></textarea></td>                                                          \
          </tr>                                                                                                                           \
          <tr>                                                                                                                            \
            <td>Link: Use %link% where the link needs to be and %naam% for the name.</td>                                                 \
            <td><textarea name="link[]" rows="3" cols="70" onBlur="updateTitle(' + row + ',this.value)"></textarea></td>                  \
          </tr>                                                                                                                           \
          <tr>                                                                                                                            \
            <td>End Html</td>                                                                                                             \
            <td><textarea name="endHtml[]" rows="3" cols="70"></textarea></td>                                                            \
          </tr>                                                                                                                           \
          <tr>                                                                                                                            \
            <td>Rights to add/delete this link:</td>                                                                                      \
            <td>                                                                                                                          \
              <select name="rights_add[]">                                                                                                \
                <?php foreach($rights as $value): ?>
                  <option value="<?php echo $value['Right']['RightID']; ?>"> <?php echo $value['Right']['name']; ?> </option>             \
                <?php endforeach; ?>
              </select>                                                                                                                   \
            </td>                                                                                                                         \
          </tr>                                                                                                                           \
          <tr>                                                                                                                            \
            <td>Rights to edit this link:</td>                                                                                            \
            <td>                                                                                                                          \
              <select name="rights_edit[]">                                                                                               \
                <?php foreach($rights as $key => $value): ?>
                  <option value="<?php echo $value['Right']['RightID']; ?>"> <?php echo $value['Right']['name']; ?> </option>             \
                <?php endforeach; ?>                                                                                                      \
              </select>                                                                                                                   \
            </td>                                                                                                                         \
          </tr>                                                                                                                           \
        </table>                                                                                                                          \
      ';
      document.getElementById('count').value = row;
      nr++;
      document.getElementById('nr').value = nr;

    }

    function deleteLink(id) {
      if (confirm('Weet u zeker dat u dit wilt verwijderen?')) {
        var element = document.getElementById('total' + id);
        var from = element.getElementsByTagName('td')[0].getElementsByTagName('span')[0].innerHTML;
        var nr = document.getElementById('nr');
        from++;
        element.parentNode.removeChild(element);
        for(i=from;i<nr.value;i++) {
          update = document.getElementById('count' + i);
          newnr = i - 1;
          update.innerHTML = newnr;
          update.id = 'count' + newnr;
        }
        nr.value--;
      }
    }

  </script>
</head>
<body>
  <div id="wrapper">
    <div id="header">
      <div id="inHeader1"></div>
      <div id="inHeader2"></div>
      <div id="inHeader3"></div>
      <div id="inHeader4"></div>
      <div id="headerTitle">
        <span class="headerText1">Th</span><span class="headerText2">CMS</span>
      </div>
      <div id="headerLinks">
        <a href="#">Home</a> -
        <a href="#">Instellingen</a> -
        <a href="#">Bekijk de site</a>
      </div>
      <div id="headerReferrer">
        Pagina's > Aanpassen
      </div>
    </div>
    <div id="barLeft">
      <div id="barLeftBlock">
        <div id="barLeftBlockHeader1"></div>
        <div id="barLeftBlockHeader2"></div>
        <div id="barLeftBlockHeaderText">
          Algemeen
        </div>
        <div id="barLeftBlockText">
          Pagina's                 <br />
          Layouts                  <br />
          Afbeeldingen             <br />
          Rechten                  <br />
        </div>
      </div>
    </div>

    <div id="barLeft">
      <div id="barLeftBlock">
        <div id="barLeftBlockHeader1"></div>
        <div id="barLeftBlockHeader2"></div>
        <div id="barLeftBlockHeaderText">
          Statistieken
        </div>
        <div id="barLeftBlockText">
          Site bezoeken            <br />
          Algemeen geheugen        <br />
          Afbeeldingen             <br />
          Etc                      <br />
        </div>
      </div>

    </div>
    <div id="main">