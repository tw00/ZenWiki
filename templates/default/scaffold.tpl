<div id="top">
	<?php TemplateManager::load( "topline.tpl" ) ?>
</div>
<div id="left">
  <div id="logo">
  	<a href="/"><img src="<?php echo Configuration::get( 'wiki', 'logo', "/public/images/logo.png" ) ?>" alt="ZenWiki Logo" /></a>
  </div>
  <div id="menu-container">
	<?php TemplateManager::load( "menu.tpl" ) ?>
  </div>
</div>
<div id="right">
<?php TemplateManager::load( self::get( 'page_tpl' ) ) ?>
</div>
<div id="footer">
<?php TemplateManager::load( "footer.tpl" ) ?>
</div>
