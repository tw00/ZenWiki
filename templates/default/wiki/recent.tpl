<!--
<ul>
<?php foreach( self::get( 'edits' ) as $edit ): ?>
	<li>
		<span><strong><a href="<?php echo $edit['url'] ?>"><?php echo $edit['file'] ?></a></strong></span>
    	<span class=""><?php echo date( "F d Y H:i:s.", $edit['time'] ) ?></span>
		<span>by ???</span>
	</li>
<?php endforeach; ?>
</ul>
-->
<h1 style="color: #555; margin-bottom: .5em;">Letzte Änderungen</h1>
<table style="width:95%">
	<tr>
		<th>Artikel</th>
		<th>Letze Änderung</th>
<!--		<th>Dateiname</th> -->
	</tr>
<?php foreach( self::get( 'edits' ) as $edit ): ?>
	<tr>
		<td><strong><a href="<?php echo $edit['url'] ?>"><?php echo $edit['url'] ?></a></strong></td>
		<td style="color:#111">
			<?php echo StringHelper::localeDate( $edit['time'], true ) ?> 
			<?php if( $edit['user'] ): ?>von
            <a href="/special:userinfo?<?php echo $edit['user'] ?>"><?php echo $edit['user'] ?></a>
            <?php else: ?>
            (Autor unbekannt)
            <?php endif ?>
		</td>
<!--		<td style="color:#AFAFAF"><?php echo $edit['file'] ?></td> -->
	</tr>
<?php endforeach; ?>
</table>

<!--
TODO:
<a href="?rss">RSS</a>
-->
