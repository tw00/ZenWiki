<div id="imagelist" class="grid">
  <div class="toolbar">
    <a href="?list" onClick="return zenBase.setThumbnailMode( 'list' )">Als Liste anzeigen</a> |
    <a href="?grid" onClick="return zenBase.setThumbnailMode( 'grid' )">Als Gitter anzeigen</a>
  </div>
  <?php foreach( self::get( 'img_list' ) as $img ): ?>
  <div class="thumbnail">
    <div class="title"><strong><?php echo StringHelper::shorten( $img['name'] ) ?></strong></div>
    <div class="imgcontainer"><img src="image.php?path=<?php echo $img['file'] ?>"/></div>
    <div class="text"><?php echo StringHelper::localeDate( $img['date'] ) ?>
      <?php if( $img['editor'] ): ?>von <a  href="/special:userinfo?<?php echo $img['editor'] ?>"><?php echo $img['editor'] ?></a><?php endif ?>
      <span><em><?php echo $img['size'] ?> KB</em></span>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<div class="clear"></div>
