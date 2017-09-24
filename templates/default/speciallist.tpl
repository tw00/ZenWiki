<h1>Action List</h1>
<ul>
<?php foreach( self::get( 'action_list' ) as $action => $module ): ?>
  <li><a href="/special:<?php echo $action ?>"><?php echo $action ?></a> (<?php echo $module ?>)</li>
<?php endforeach ?>
</ul>

<h1>Loaded Modules</h1>
<ul>
<?php foreach( self::get( 'module_list' ) as $module ): ?>
  <li><?php echo $module ?></li>
  <!-- TODO call about() -->
<?php endforeach ?>
</ul>

