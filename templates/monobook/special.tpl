<div id="top-menu">
  <ul>
    <li><strong><a href="?view" onClick="return false">special page</a></strong></li>
  </ul>
</div>
<div id="contentContainer">
  <div class="tab" id="view">
  	<h1 class="pagenotfound"><?php self::show( 'specialpage' ) ?></h1>
	<?php TemplateManager::load( self::get( 'content_tpl' ) ) ?>
  </div>
</div>
