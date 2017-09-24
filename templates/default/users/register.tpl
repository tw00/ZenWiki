<?php if( self::get( 'registersuccess' ) ): ?>

<h1>Konto erfolgreich angelegt</h1>

<?php else: ?>

<?php $registerdata = self::get( 'registerdata' ) ?>
<form id="register" class="userform" name="register" method="post" action="?" onSubmit="return zenBase.registerSubmit( this )">

  <?php if( self::get( 'error' ) == userModule::WRONG_INPUT ): ?>
    <h1>Fehlerhafte Eingabe</h1>
  <?php endif; ?>

  <?php if( self::get( 'error' ) == userModule::USER_ALREADY_EXISTS ): ?>
    <h1>Der Benutzer existiert bereits</h1>
    <?php $userclass = "user-exists" ?>
  <?php endif; ?>

  <?php if( self::get( 'error' ) == userModule::INVALID_USERNAME ): ?>
  	<h1>Benutzername darf nur aus a-z, A-Z und 0-9 bestehen</h1>
    <?php $userclass = "invalid" ?>
  <?php endif; ?>
  <?php if( self::get( 'error' ) == userModule::INVALID_PASSWORD ): ?>
    <h1>Passwort zu kurz</h1>
    <?php $passwordclass = "invalid" ?>
  <?php endif; ?>
  <?php if( self::get( 'error' ) == userModule::INVALID_EMAIL ): ?>
    <h1>Ungültige eMail-Adresse</h1>
    <?php $emailclass = "invalid" ?>
  <?php endif; ?>

  <label for="register_username">Benutzername:</label>
  <input name="register[username]" id="register_username" type="text" class="<?php echo $userclass ?>" value="<?php echo $registerdata[ 'username' ] ?>" />

  <label for="register_password">Passwort:</label> 
  <input name="register[password1]" id="register_password1" type="password" class="<?php echo $passwordclass ?>" value="<?php echo $registerdata[ 'password1' ] ?>" />

  <label for="register_password2">Passwort wiederholen:</label>
  <input name="register[password2]" id="register_password2" type="password" class="<?php echo $passwordclass ?>" value="<?php echo $registerdata[ 'password2' ] ?>" />

  <label for="register_email">eMail:</label>
  <input name="register[email]" id="register_email" type="text" class="<?php echo $emailclass ?>" value="<?php echo $registerdata[ 'email' ] ?>" />

  <input type="reset" id="reset" name="register[cancel]" value="Abbrechen" />
  <input type="submit" id="submit" name="register[register]" value="Registrieren" />

</form>
<?php endif; ?>
