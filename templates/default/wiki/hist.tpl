<?php
	$pagename      = self::get( 'pagename' );
#	$basedir	   = self::get( 'basedir' ) . "/";
	$revisionlist  = self::get( 'revisionlist' );
	$editors       = self::get( 'editors' );
/*	$revisionlist  = array_merge(
						array( array(
							'filename' => "text",
							'lastedit' => 0
						) ),
						$revisionlist,
						array( array(
							'filename' => "blank",
							'lastedit' => 0
						) )
					);*/
?>
<h1>history: <?php echo $pagename ?></h1>
<div class="history">
<table border="0" width="100%">
<tr>
  <th width="4">A</th>
  <th width="4">B</th>
  <th>rev</th>
  <th>user</th>
  <th>date</th>
  <th>-</th>
</tr>
<?php foreach( $revisionlist as $revnum => $revision ): ?>
  <?php $revnum = count( $revisionlist ) - $revnum - 1 ?>
  <?php $user = $editors[ 'rev' . $revnum ] ?>
  <tr>
    <td><input type="radio" name="A"/></td>
    <td><input type="radio" name="B"/></td>
    <td><span class="revision">rev<?php echo $revnum ?> by </span></td>
    <td><span class="user"><a href="/users:<?php echo $user ?>"><?php echo $user ?></a></span></td>
	<td><span class="lastedit"> Letzte Änderung <?php echo StringHelper::localeDate( self::get( 'lastedit' ) ) ?></span></td>
    <td><?php echo $revision[ 'filename' ] ?></td>
  </tr>
<?php endforeach ?>
</table>
</div>
<br  />
<input type="button" value="compare" disabled="disabled" />

<!-- Creator: <?php echo $editors[ 'rev0' ] ?> -->
<?php /*
<div class="history">
<?php foreach( $revisionlist as $key => $revision ): ?>
  <?php $rev1 = $revisionlist[ $key + 0 ]['filename'] ?>
  <?php $rev2 = $revisionlist[ $key + 1 ]['filename'] ?>
  <?php $diff = fileDB::createDiff( $basedir . $rev2, $basedir . $rev1 ) ?>
  <?php if( $rev2  ): ?>
    <?php $ftime = $revision['lastedit'] ?> 
    <h2 class="revision"><?php echo $rev1 ?> - <?php echo $rev2 ?></h2>
    <span class="lastedit"><?php echo date( "F d Y H:i:s.", $ftime ) ?></span>
	<a href="#" id="rln_<?php echo $key ?>" onClick="return zenBase.showRev( '<?php echo $key ?>' )">show</a>
    <div id="rev_<?php echo $key ?>" class="diff"><?php echo $diff ? $diff : "none" ?></div>
  <?php endif; ?>
<?php endforeach; ?>
</div>
*/ ?>
