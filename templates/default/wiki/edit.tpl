<form name="edit" method="post" action="?" onSubmit="return zenBase.pageSubmit( this )">
  <input name="edit[name]" id="editname" type="text" class="pagename" value="<?php echo self::get( 'pagename' ) ?>" disabled="disabled" />
  <div class="lastedit"><span>Letzte Änderung <?php echo StringHelper::localeDate( self::get( 'lastedit' ) ) ?> von <?php $x=self::get( 'editors' ); echo end($x) ?></span></div>

  <div id="editor">
    <textarea  name="edit[wikicode]" wrap="off" id="wikicode" cols="88" rows="18"><?php echo self::get( 'content' ) ?></textarea>
  </div>

  <input type="submit" name="edit[save]" value="Speichern" />
  <input type="submit" name="edit[preview]" class="preview" value="Vorschau" />
  <input type="button" name="edit[cancel]" class="cancel" value="Abbrechen" onClick="document.location.href = '?'" />
</form>
<form name="edit" method="post" action="?" onSubmit="return zenBase.pageSubmit( this )">
</form>

<!-- TODO -->
BenutzteTemplates:

<hr />
<img src="/public/images/quickref.png" id="quickref" />
