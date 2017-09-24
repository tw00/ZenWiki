<?php

/**
 * Texy! - web text markup-language
 * --------------------------------
 *
 * Copyright (c) 2004, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the GNU GPL license that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://texy.info
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    GNU GENERAL PUBLIC LICENSE version 2 or 3
 * @link       http://texy.info
 * @package    Texy
 * @version    $Id: RegExp.Patterns.php 226 2008-12-31 00:16:35Z David Grudl $
 */



/**#@+
 * Regular expression patterns
 * @version $Revision: 226 $ $Date: 2008-12-31 01:16:35 +0100 (st, 31 XII 2008) $
 */

// Unicode character classes
define('TEXY_CHAR',        'A-Za-z\x{C0}-\x{2FF}\x{370}-\x{1EFF}');

// marking meta-characters
// any mark:               \x14-\x1F
// CONTENT_MARKUP mark:    \x17-\x1F
// CONTENT_REPLACED mark:  \x16-\x1F
// CONTENT_TEXTUAL mark:   \x15-\x1F
// CONTENT_BLOCK mark:     \x14-\x1F
define('TEXY_MARK',        "\x14-\x1F");


// modifier .(title)[class]{style}
define('TEXY_MODIFIER',    '(?: *(?<= |^)\\.((?:\\([^)\\n]+\\)|\\[[^\\]\\n]+\\]|\\{[^}\\n]+\\}){1,3}?))');

// modifier .(title)[class]{style}<>
define('TEXY_MODIFIER_H',  '(?: *(?<= |^)\\.((?:\\([^)\\n]+\\)|\\[[^\\]\\n]+\\]|\\{[^}\\n]+\\}|<>|>|=|<){1,4}?))');

// modifier .(title)[class]{style}<>^
define('TEXY_MODIFIER_HV', '(?: *(?<= |^)\\.((?:\\([^)\\n]+\\)|\\[[^\\]\\n]+\\]|\\{[^}\\n]+\\}|<>|>|=|<|\\^|\\-|\\_){1,5}?))');



// images   [* urls .(title)[class]{style} >]
define('TEXY_IMAGE',       '\[\*([^\n'.TEXY_MARK.']+)'.TEXY_MODIFIER.'? *(\*|>|<)\]');


// links
define('TEXY_LINK_URL',    '(?:\[[^\]\n]+\]|(?!\[)[^\s'.TEXY_MARK.']*?[^:);,.!?\s'.TEXY_MARK.'])'); // any url (nekon�� :).,!?
define('TEXY_LINK',        '(?::('.TEXY_LINK_URL.'))');       // any link
define('TEXY_LINK_N',      '(?::('.TEXY_LINK_URL.'|:))');     // any link (also unstated)
define('TEXY_EMAIL',       '[A-Za-z0-9.+_-]{1,64}@[0-9.+_'.TEXY_CHAR.'\x{ad}-]{1,252}\.[a-z]{2,6}');    // name@exaple.com
define('TEXY_URLSCHEME',   '[a-z][a-z0-9+.-]*:');    // http:  |  mailto:
/**#@-*/
