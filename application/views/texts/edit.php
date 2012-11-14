<div><h2>Edit the text:</h2>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>/public/ckeditor/ckeditor.js"></script>

<form method="post">
  <textarea name="text" id="text"><?php echo stripslashes($text); ?></textarea>
  <input type="submit" name="save" value="Save" />
</form>


<script type="text/javascript">
	CKEDITOR.replace( 'text' );
</script>
</div>