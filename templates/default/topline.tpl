<?php if( $user = UserManager::currentUser() ): ?>
	<span>Eingeloggt als <?php echo $user ?></span>
<?php endif; ?>
