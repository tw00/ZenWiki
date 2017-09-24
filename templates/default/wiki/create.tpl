<form id="page" class="userform" name="page" method="post" action="?" onSubmit="return zenBase.pagenameSubmit( this )">
  <h2>Neue Seite anlegen</h2>

  <?php if( self::get( 'error' ) == wikiModule::INVALID_PAGE_NAME ): ?>
  <h1>Der Name ist ungültig</h1>
  <?php endif; ?>

  <?php if( self::get( 'error' ) == wikiModule::CREATE_FAILED ): ?>
  <h1>Artikel konnte nicht angelegt werden</h1>
  <?php endif; ?>

  <h3>Unterseiten anlegen</h3>
  <p>
  Unterseiten könnnen angelegt werden,<br />
  indem der komplette Pfad angeben wird:<br />
  z.B.: "category/subcategory/articlename"<br />
  </p>
  <h3>Sonderzeichen</h3>
  <p>
  Erlaubt sind die Zeichen <em>a-Z 0-9 / _ -</em><br />
  Aus einem Leerzeichen wird automatisch ein _<br />
  </p>

  <label for="page_isfolder">Create empty Folder (TODO):</label>
  <input name="page_isfolder" type="checkbox" />
  <label for="page_name">Seitenname:</label>
  <input name="page[name]" id="page_name" type="text" value="<?php echo self::get( 'createpage' ) ?>" />
  <input type="submit" id="submit" name="page[create]" value="Anlegen" />

</form>
