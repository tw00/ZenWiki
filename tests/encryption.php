<?php echo "<?xml version='1.0' encoding='utf-8'>" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="de">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Encryption test</title>
    <style>
	<?php foreach( array( 'core.css', 'page.css' ) as $css_file ): ?>
	@import url( /public/css/<?php echo $css_file ?> );
	<?php endforeach ?>
	</style>
	<?php foreach( array() as $js_file ): ?>
	<script type="text/javascript" src="/public/js/<?php echo $js_file ?>"></script>
	<?php endforeach ?>
	<script type="text/javascript" src="AES.js"></script>
    <link rel="alternate" type="application/rss+xml" title="Zen Wiki" href="/recent_changes.xml" />
  </head>
<body>
  <div id="site-container">
  <div id="left">

<form name="f" action="none!">
<table>
<tbody><tr> 
<td>Password:</td>
<td><input name="pw" size="16" value="password" type="text"></td>
</tr>
<tr> 
<td>Plaintext:</td>
<td><input name="pt" size="40" value="geheimer text" type="text"></td>
</tr>
<tr valign="bottom"> 
<td><input name="encrypt" value="Encrypt it:" accesskey="e" onclick="f.cipher.value = AESEncryptCtr(f.pt.value, f.pw.value, 256)" type="button"></td>
<td> <input name="cipher" size="80" type="text"></td>
</tr>
<tr valign="bottom"> 
<td><input name="decrypt" value="Decrypt it:" accesskey="d" onclick="f.plain.value = AESDecryptCtr(f.cipher.value, f.pw.value, 256)" type="button"></td>
<td> <input name="plain" size="40" type="text"></td>
</tr>
</tbody></table>
</form>

  </div>
  </div>
</body>
</html>
