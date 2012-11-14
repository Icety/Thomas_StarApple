<div>
  <form method="post" enctype="multipart/form-data">
    <span onClick="addLink()" class="hand">Add link</span>
    <table border="1" width="80%" id="table">
      <?php
        $count = 0;
        while ($properties['link'.$count] || $count == 0) :
          $property = $properties['link'.$count];
          ?>
          <tr id="total<?php echo $count+1; ?>">
            <td id="test">
              <span id="count<?php echo $count+1; ?>"><?php echo $count+1; ?></span>
              <img src="<?php echo BASE_PATH; ?>/public/ico/minus.png" class="hand" width="24px" id="image<?php echo $count+1; ?>" onClick="showLink('<?php echo $count+1; ?>')"/>
              <img src="<?php echo BASE_PATH; ?>/public/ico/delete.png" class="hand" width="24px" onClick="deleteLink('<?php echo $count+1; ?>')"/>
            </td>
            <td>
              <span id="title<?php echo $count+1; ?>" style="display:none;"><?php echo htmlentities($property[1]); ?></span>
              <table id="link<?php echo $count+1; ?>" style="display:block;">
                <tr>
                  <td>Start Html</td>
                  <td><textarea name="startHtml[]" rows="3" cols="70"><?php echo $property[0]; ?></textarea></td>
                </tr>
                <tr>
                  <td>Link: Use %link% where the link needs to be and %name% for the name.</td>
                  <td><textarea name="link[]" rows="3" cols="70" onBlur="updateTitle('<?php echo $count+1; ?>',this.value)"><?php echo $property[1]; ?></textarea></td>
                </tr>
                <tr>
                  <td>End Html</td>
                  <td><textarea name="endHtml[]" rows="3" cols="70"><?php echo $property[2]; ?></textarea></td>
                </tr>
                <tr>
                  <td>Rights to add/delete this link:</td>
                  <td>
                    <select name="rights_add[]">
                      <?php foreach($rights as $value): ?>
                        <option value="<?php echo $value['Right']['RightID']; ?>" <?php echo (($property[3] == $value['Right']['RightID'])?'selected':''); ?> > <?php echo $value['Right']['name']; ?> </option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Rights to edit this link:</td>
                  <td>
                    <select name="rights_edit[]">
                      <?php foreach($rights as $key => $value): ?>
                        <option value="<?php echo $value['Right']['RightID']; ?>" <?php echo (($property[4] == $value['Right']['RightID'])?'selected':''); ?> > <?php echo $value['Right']['name']; ?> </option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <?php
          $count++;
        endwhile;
      ?>
      <tr>
        <td>Maximal linkdepth</td>
        <td><input type="text" name="maxdepth" value="<?php echo $properties['maxdepth']; ?>" size="30" /></td>
      </tr>
      <tr>
        <td>Link-order</td>
        <td><input type="text" name="set" value="<?php echo $properties['set']; ?>" size="30" /></td>
      </tr>
      <tr>
        <td>Link-order repeat</td>
        <td><input type="text" name="set_repeat" value="<?php echo $properties['set_repeat']; ?>" size="30" /></td>
      </tr>
      <tr>
        <td>
          &nbsp;
          <input type="hidden" id="count" value="<?php echo $count; ?>" />
          <input type="hidden" id="nr" value="<?php echo $count+1; ?>" />
        </td>
        <td><input type="submit" name="submit" value="Save" /></td>
      </tr>
    </table>


  </form>
</div>