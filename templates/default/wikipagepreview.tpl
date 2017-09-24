<div id="top-menu">
  <ul>
    <li><a href="#">preview</a></li>
    <li><a href="#"></a></li>
    <li><a href="#"></a></li>
  </ul>
</div>
<div id="breadcrumb">
  <span class="left">
    <?php $elementpath = "/" ?>
    index
    <?php foreach( self::get( 'breadcrumb' ) as $element ): ?>
      &raquo; <?php echo $element ?>
    <?php endforeach ?>
  </span>
  <span class="right">
    <a href="#">backlinks</a>
  </span>
</div>
<br />
<div id="contentContainer">
  <div class="tab preview" id="view"><?php TemplateManager::load( "wiki/view.tpl" ) ?></div>
  <div id="edit"><?php TemplateManager::load( "wiki/edit.tpl" ) ?></div>
</div>
