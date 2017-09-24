<div id="menu">
  <div id="search">
  	<form action="/special:search" method="POST" name="search">
	  <input type="text" onfocus="return zenBase.newSearch()" name="search[q]" id="searchfield" value="search" />
	</form>
  </div>
  <hr class="menu-separation left" />
  <ul id="toolbox">
  	<li><a href="/special:recent">recent changes</a></li>
  	<li><a href="/special:index">index page</a></li>
	<?php if( userManager::currentUser() ): ?>
  	<li><a href="/special:users">users</a></li>
  	<li><a href="/special:create">create new page</a></li>
  	<li><a href="/special:upload">upload image</a></li>
	<?php endif; ?>
  	<li><a href="/special:?">special pages</a></li>
  </ul>
  <hr class="menu-separation right" />
  <ul id="account">
  	<li><a href="/special:twitter">twitter (test)</a></li>
	<?php if( userManager::currentUser() ): ?>
  	<li><a href="/special:settings">settings</a></li>
  	<li><a href="/special:logout">logout</a></li>
	<?php else: ?>
  	<li><a href="/special:login">login</a></li>
  	<li><a href="/special:register">register</a></li>
	<?php endif; ?>
  </ul>
  <hr class="menu-separation left" />
  <ul id="special">
  	<li><a href="/">index</a></li>
  	<li><a href="?print">print page</a></li>
  </ul>
</div>
