<h2>index</h2>

<ul>
<?php echo recursivePrint( self::get( 'index' ) ) ?>
</ul>

<?php
/*TODO: DOPPELT!*/
function recursivePrint( $list, $path = '', $depth = 0 )
{
	if( $depth < 3 ) $path = '';

	foreach( $list as $key => $element ) {
		if( $key ) {
			echo "<li><a href='$path/$key'>",
				 $key,
				 "</a></li>";
			} if( is_array( $element ) and count( $element ) > 0 ) {
			echo "<ul>";
			recursivePrint( $element, $path . '/' . $key, $depth + 1 );
			echo "</ul>";
		}
	}
}
?>
<pre>
<?php /** print_r( self::get( 'index' ) ) **/ ?>
<?php /** system( "tree wiki_wavefab/|grep -v 'rev'|grep -v 'edits'|grep -v 'text'" ) **/ ?>
</pre>
