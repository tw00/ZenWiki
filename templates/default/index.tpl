<?php echo "<?xml version='1.0' encoding='utf-8'>" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="de">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="author" content="" />
    <meta name="copyright" content="" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <title><?php echo Configuration::get( 'wiki', 'title', "Zenwiki" ) ?><?php if( $title = self::get( 'pagename' ) ) { echo " - ", $title; } ?></title>
    <link rel="icon" type="image/gif" href="TODO" />
    <style>
	<?php foreach( self::get( 'css_file_list' ) as $css_file ): ?>
		@import url( /public/css/<?php self::show( 'theme' )?>/<?php echo $css_file ?> );
	<?php endforeach ?>
	</style>
	<?php foreach( self::get( 'js_file_list' ) as $js_file ): ?>
	<script type="text/javascript" src="/public/js/<?php echo $js_file ?>"></script>
	<?php endforeach ?>
    <link rel="alternate" type="application/rss+xml" title="Zen Wiki" href="/recent_changes.xml" />
  </head>
<body>
  <div id="site-container">
	<?php TemplateManager::load( self::get( 'scaffold_tpl' ) ) ?>
  </div>
<!--  <hr class="clear" />-->
  <br class="clear" />
  <?php if( trim( Configuration::get( "debug", "enabled", 'false' ) ) == 'true'): ?>
  <div id="debug">
    <h1 id="debug-heading">Debug</h1>
	<form method="GET" action="?" style="float: right">
		<?php if( !TemplateManager::isDebugEnabled() ): ?>
		<input type="hidden" name="debug" value="1" />
		<?php endif; ?>
		<input type="submit" value="<?php echo TemplateManager::isDebugEnabled() ? 'hide' : 'show'; ?> inline templates" />
	</form>
    <div id="debug-container">
	  <h3 id="debug-heading">Output</h3>
      <?php DebugManager::flush(); ?>
	  <h3 id="debug-heading">Templates</h3>
	  <?php DebugManager::flushTemplates(); ?>
	</div>
  </div>
  <?php endif; ?>
  <script type="text/javascript">
  /* <![CDATA[ */
      // initialize zenBase
	  zenBase.init( "<?php echo Dispatcher::getView() == "edit" ? "edit" : "view"  ?>" );
  /* ]]> */
  </script>
</body>
</html>

