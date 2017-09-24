<div id="top-menu">
  <ul>
    <li><a href="?view" onClick="return zenBase.switchTab( 'view' )">view</a></li>
	<?php if( !self::get( 'isfolder' ) ): ?>
	<?php if( userManager::currentUser() ): ?>
    <li><a href="?edit" onClick="return zenBase.switchTab( 'edit' )">edit</a></li>
	<?php endif; ?>
    <li><a href="?hist" onClick="return zenBase.switchTab( 'hist' )">history</a></li>
	<?php endif; ?>
  </ul>
</div>
<div id="contentContainer"> 
  <?php TemplateManager::load( "breadcrumb.tpl" ) ?>
  <div class="tab " id="view"><?php TemplateManager::load( "wiki/view.tpl" ) ?></div>
  <?php if( !self::get( 'isfolder' ) ): ?>
  <?php if( userManager::currentUser() ): ?>
  <div class="tab inactive" id="edit"><?php TemplateManager::load( "wiki/edit.tpl" ) ?></div>
  <?php endif; ?>
  <div class="tab inactive" id="hist"><?php TemplateManager::load( "wiki/hist.tpl" ) ?></div>
  <?php endif; ?>
</div>
