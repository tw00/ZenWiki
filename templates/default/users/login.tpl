<?php if( self::get( 'loginsuccess' ) ): ?>

<h2>Login erfolgreich </h2>
<br />
<a href="/">&raquo; Zur Hauptseite</a>
<br />
<br />

<?php else: ?>

<?php $logindata = self::get( 'logindata' ) ?>
<form id="login" class="userform" name="login" method="post" action="?" onSubmit="return zenBase.loginSubmit( this )">

  <?php if( self::get( 'error' ) == userModule::WRONG_INPUT ): ?>
    <h1>Fehlerhafte Eingabe</h1>
  <?php endif; ?>

  <?php if( self::get( 'error' ) == userModule::WRONG_USERNAME ): ?>
    <h1>Falscher Benutzername</h1>
	<?php $wronguser = "wrong-user" ?>
  <?php endif; ?>

  <?php if( self::get( 'error' ) == userModule::WRONG_PASSWORD ): ?>
    <h1>Falsches Password</h1>
	<?php $wrongpassword = "wrong-password" ?>
  <?php endif; ?>

  <label for="login_username">Benutzername:</label>
  <input name="login[username]" id="login_username" type="text" class="<?echo $wronguser ?>" value="<?php echo $logindata[ 'username' ] ?>" />

  <label for="login_password">Passwort:</label> 
  <input name="login[password]" id="login_password" type="password" class="<?php echo $wrongpassword ?>" value="<?php echo $logindata[ 'password' ] ?>" />

  <input type="submit" id="submit" name="login[login]" value="Login" />

</form>
<?php endif; ?>
