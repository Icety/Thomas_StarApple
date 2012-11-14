<?php echo $html->includeJs('uploaded'); ?>
<div><h2>Html-file uploaded, plz select the right divs to stay:</h2>
<?php $html->includeJs('uploaded.js'); ?>
<table border="1px">
  <tr class="install-div">
    <th></th>
    <th width="120px">&nbsp;</th>
    <th>Name</th>
    <th>Style</th>
    <th>Type</th>
    <th>Extra</th>
    <th>Unique</th>
    <th>&nbsp;</th>
  </tr>
  <tr id="div0" class="install-div" style="display: none;">
    <td onclick="move('0')">
      <img id="move0" src="<?php echo BASE_PATH; ?>/public/ico/move.png" />
    </td>
    <td>Root</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
<?php function divs($divs, $indent, $scripts) { ?>
  <?php $count = 0; ?>
    <?php foreach ($divs as $div): ?>
      <tr id="div<?php echo $div['Div']['DivID']; ?>" class="install-div">
        <td onclick="move('<?php echo $div['Div']['DivID']; ?>')">
          <span id="parent<?php echo $div['Div']['BelongID'].'_'.$count; ?>"></span>
          <img id="move<?php echo $div['Div']['DivID']; ?>" src="<?php echo BASE_PATH; ?>/public/ico/move.png" />
        </td>
        <td onclick="editRadio('<?php echo $div['Div']['DivID']; ?>')">
          <?php echo '<span style="margin-left: '.$indent.'px;" id="ci'.$div['Div']['DivID'].'">'.$div['Div']['ci'].'</span>'; ?>
        </td>
        <td onclick="editName('<?php echo $div['Div']['DivID']; ?>')">
          <?php echo '<span id="name'.$div['Div']['DivID'].'"> '.$div['Div']['name'].'</span>'; ?>
        </td>
        <td onclick="editStyle('<?php echo $div['Div']['DivID']; ?>')">
          <?php echo '<span id="style'.$div['Div']['DivID'].'"> '.$div['Div']['style'].'</span>'; ?>
        </td>
        <td onclick="editSelect('<?php echo $div['Div']['DivID']; ?>')">
          <?php echo '<select id="script'.$div['Div']['DivID'].'" onchange="save(\'ScriptID\',this.value,'.$div['Div']['DivID'].')" ><option value="0">No script</option>'; ?>
          <?php foreach ($scripts as $script): ?>
            <?php echo '<option value="'. $script['Script']['ScriptID'] .'" '.(($script['Script']['ScriptID'] == $div['Div']['ScriptID'])?'selected':'').'>'. $script['Script']['shortcut'] .'</option>'; ?>

          <?php endforeach; ?>
          </select>
        </td>
        <td onclick="editText('<?php echo $div['Div']['DivID']; ?>')">
          <?php echo '<span id="text'.$div['Div']['DivID'].'"> '.htmlentities($div['Text']['text']).'</span>'; ?>
        </td>
        <td onclick="editUnique('<?php echo $div['Div']['DivID']; ?>')">
          <?php echo '<input type="checkbox" onchange="save(\'unique\',this.checked,'.$div['Div']['DivID'].')" name="unique'.$div['Div']['DivID'].'" '.(($div['Div']['unique'] == 'true')?'checked':'').'/>'; ?>
        </td>
        <td onclick="deleteRow(<?php echo $div['Div']['DivID']; ?>,'1')">
          <img width="16px" src="<?php echo BASE_PATH; ?>/public/ico/delete.png" />
        </td>
      </tr>
      <?php if ($div['Repeat']): ?>
        <?php divs($div['Repeat'], $indent+15, $scripts); ?>
      <?php endif; ?>
      <?php $count++; ?>
    <?php endforeach; ?>
<?php }
divs($divs, 0, $scripts);
?>

</table>
</div>