<div id="breadcrumb">
  <span class="left">
    <?php $elementpath = "/" ?>
    <a href="/">index</a> 
    <?php foreach( self::get( 'breadcrumb' ) as $element ): ?>
      <?php $elementpath .= $element . '/' ?>
      &raquo; <a href="<?php echo $elementpath ?>"><?php echo $element ?></a> 
    <?php endforeach ?>
  </span>
  <span class="right">
    <a href="#">backlinks</a>
  </span>
</div>
