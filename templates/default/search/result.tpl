<h3>Query: ´<?php self::show( 'query' ) ?>´</h3>

<h2>Search Result:</h2>
<ul>
<?php foreach(  self::get( 'result' ) as $file => $result ): ?>
<?php $url = StringHelper::wikiPath( $file, true ) ?>
	<li>
		<a href='<?php echo $url ?>'><?php echo $url ?></a>
		<?php foreach( $result as $line ): ?>
		<div style='border: 1px dashed #AAA; padding: 5px; margin: 2px;'><?php echo $line ?></div>
		<?php endforeach ?>
	</li>
<?php endforeach ?>
</ul>
