<?php $data = self::get( 'data' ) ?>
<?php if( $data ): ?>
<div class="edit-info">
  <strong>Last edit</strong>
  on <?php echo $data[ 'pagename' ] ?>, 
  <?php echo date( "F d Y H:i:s", $data[ 'lastedit' ] ) ?> by <em><?php echo $data[ 'editor' ] ?></em>,
  page views: <em><?php echo $data[ 'view' ] ?></em>
</div>
<?php endif; ?>
<div class="footer-links">
  <a href="/impressum">Impressum</a> |
  <a href="/FAQ">FAQ</a> |
  <a href="/Datenschutz">Datenschutz</a>
</div>
<div class="copyright">
  <a href="http://zenwiki.thomas-weustenfeld.de/">powered by zenwiki // 2009 // thomas-weustenfeld.de</a>
</div>
