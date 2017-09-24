<div id="menu">
  <h5>navigation</h5>
  <ul id="toolbox">
  	<li><a href="/">Hauptseite</a></li>
  	<li><a href="/special:recent">recent changes</a></li>
  	<li><a href="/special:index">index page</a></li>
	<?php if( userManager::currentUser() ): ?>
  	<li><a href="/special:users">users</a></li>
  	<li><a href="/special:create">create new page</a></li>
  	<li><a href="/special:upload">upload image</a></li>
	<?php endif; ?>
  	<li><a href="/special:?">special pages</a></li>
  </ul>
  <h5>suche</h5>
  <div id="search">
  	<form action="/special:search" method="POST" name="search">
	  <input type="text" onfocus="return zenBase.newSearch()" name="search[q]" id="searchfield" value="search" />
	  <input type="submit" value="Suche" />
	</form>
  </div>
  <h5>werkzeuge</h5>
  <ul id="special">
  	<li><a href="/special:twitter">twitter (test)</a></li>
  	<li><a href="/">index</a></li>
  	<li><a href="?print">print page</a></li>
  </ul>
</div>
<br class="clear" />
