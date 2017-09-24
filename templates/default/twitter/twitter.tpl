<table border="1">
<?php foreach( self::get( 'twitter' )->statusesHomeTimeline() as $update ): ?>
  <tr>
	<td><img style="border: 1px solid #666; padding: 1px; margin: 1px; width: 48px; height: 48px;" src="<?php echo $update['user']['profile_image_url'] ?>" /></td>
	<td style="padding: 1em; border-right: 1px solid #555;"><strong><?php echo $update['user']['name'] ?></strong></td>
    <td style="padding: 1em;"><?php echo utf8_encode( StringHelper::linkify( $update['text'] ) ) ?></td>
  </tr>
<?php endforeach; ?>
</table>
