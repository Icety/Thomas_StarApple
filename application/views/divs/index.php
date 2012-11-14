<div><h2>Please upload your HTML file:</h2>
  <form method="post" enctype="multipart/form-data">
    <?php echo "$error"; ?>
    <input type="file" name="file" />
    <input type="submit" name="send" value="Send" />
  </form>
</div>