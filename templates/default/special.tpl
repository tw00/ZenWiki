<div id="top-menu">
  <ul>
    <li><a href="?view" onClick="return false"></a></li>
    <li><a href="?edit" onClick="return false"></a></li>
    <li><a href="?hist" onClick="return false"></a></li>
  </ul>
</div>
<div id="contentContainer">
  <div class="tab" id="view">
  	<h1 class="pagenotfound"><?php self::show( 'specialpage' ) ?></h1>
	<?php TemplateManager::load( self::get( 'content_tpl' ) ) ?>
  </div>
</div>
