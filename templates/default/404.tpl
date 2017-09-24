<div id="top-menu">
  <ul>
    <li><a href="?view" onClick="return false"></a></li>
    <li><a href="?edit" onClick="return false"></a></li>
    <li><a href="?hist" onClick="return false"></a></li>
  </ul>
</div>
<div id="contentContainer">
  <div class="tab" id="view">
  	<h1 class="pagenotfound">page not found: "<?php self::show( '404page' ) ?>"</h1>
	<form id="page" class="userform" name="page" method="post" action="/special:create" onSubmit="return zenBase.pagenameSubmit( this )">
      <input name="page[name]" id="page_name" type="hidden" value="<?php self::show( '404page' ) ?>" />
      <input type="submit" id="submit" name="page[create]" value="Seite anlegen" />
    </form>
  </div>
</div>
