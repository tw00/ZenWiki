<div id="upload">
  <form id="file_upload_form" method="post" enctype="multipart/form-data" action="upload.php"^>
    <input name="file" id="file" size="27" type="file" />
    <input type="submit" name="action" value="Upload" />
    <img src="/public/images/ajax-loader.gif" style="display:none" />
    <iframe id="upload_target" name="upload_target" src="" style="display:none"></iframe>
  </form>
  <div class="details" id="image_details"></div>
</div>

<h2>Uploaded images</h2>
<ul>
  <li><a href="/special:images">Uploaded images</a></li>
</ul>
