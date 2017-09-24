<h1 class="pagename" <?php if( Configuration::get( 'userinterface', 'quickedit' ) == "true" ):?>onDblclick="return zenBase.quickEdit( 'editname' )<?php endif; ?>"><?php echo self::get( 'pagename' ) ?></h1>
<div class="lastedit">
  <span>
  Letzte Änderung <?php StringHelper::localeDate( self::get( 'lastedit' ) ) ?>
  von <?php $x=self::get( 'editors' ); echo end($x); ?>
  </span>
</div>

<?php if( count( self::get( 'wikiTOC' ) ) > 1 and self::get( 'enableTOC' ) ): /*HACK? was ist mit subheadings*/?>
<table id="toc"> 
  <?php
	function recursivePrintTOC( $toc, $path = '', $depth = 0 ) {
		foreach( $toc as $key => $element ) {
			if( $key ) {
				echo "<li><a href='#'>", $key, "</a></li>";
			}
			if( is_array( $element ) and count( $element ) > 0 ) {
				echo "<ol>";
				recursivePrintTOC( $element, '', $depth + 1 );
				echo "</ol>";
			}
		}
	}
  ?>
  <tbody><tr><td>
  <h3>Inhaltsverzeichnis</h3>
  <ol> <?php recursivePrintTOC( self::get( 'wikiTOC' ) ) ?> </ol>
  </td></tr></tbody>
</table>
<?php endif; ?>

<div id="wikicontent" <?php if( Configuration::get( 'userinterface', 'quickedit' ) == "true" ):?>onDblclick="return zenBase.quickEdit( 'wikicode' )"<?php endif; ?> class="texy">
  <?php /** echo MarkupManager::process( self::get( 'content' ) ) **/ ?>
  <?php self::show( 'wikiHTML' ) ?>
</div>

<?php if( self::get( 'subpages' ) and count( self::get( 'subpages' ) ) > 0 ): ?>
<div class="subpages">
  <?php foreach( self::get( 'subpages' ) as $subpage ): ?>
  <span class="subpage">
  <a href="/<?php echo $subpage['pagename'] ?>"><?php echo basename( $subpage['pagename'] ) ?></a>
  </span> 
    |
  <?php endforeach ?>
</div>
<?php endif ?>

<div id="comments">
<?php if( count( self::get( 'commentlist' ) ) > 0 ): ?>
  <?php $style = array( "odd", "even" ) ?>
  <?php foreach( self::get( 'commentlist' ) as $comment ): ?>
    <?php $current = array_shift( $style ) ?>
	<?php $style[] = $current ?>
    <div class="comment <?php echo $current ?>">
    <!-- TODO -->
    <pre><?php print_r( $comment ) ?></pre>
    </div>
  <?php endforeach ?>
<?php endif ?>
</div>
