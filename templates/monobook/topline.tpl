<ul id="account">
	<li><img src="/public/images/user.gif" /></li>
	<?php if( $user = UserManager::currentUser() ): ?>
  	<?php /* <li><a href="/user:<?php echo $user ?>"><?php echo $user ?></a></li> */ ?>
  	<li>Eingeloggt als <?php echo $user ?></li>
  	<li><a href="/users/<?php echo $user ?>">Benutzerseite</a></li>
  	<li><a href="/special:settings">settings</a></li>
  	<li><a href="/special:logout">logout</a></li>
	<?php else: ?>
  	<li><a href="/special:login">login</a></li>
  	<li><a href="/special:register">register</a></li>
	<?php endif; ?>
</ul>
