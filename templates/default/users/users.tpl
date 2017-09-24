<h1>Benutzerliste</h1>
<ul>
<?php foreach( self::get( 'userlist' ) as $user ): ?>
<?php $user = StringHelper::wikiPath( $user ) ?>
	<li><a href="/special:userinfo?<?php echo $user ?>"><?php echo $user ?></a></li>
<?php endforeach; ?>
</ul>
