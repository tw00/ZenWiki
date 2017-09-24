<?php
/*
 * ==========================================================================
 * Titel                  : Autoloader
 * Licence                : GPL
 * URL                    : http://zenwiki.thomas-weustenfeld.de
 * Author                 : Thomas Weustenfeld
 * ==========================================================================
 *
 * This file is part of ZenWiki.
 *
 * ZenWiki is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * ZenWiki is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ZenWiki; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * ==========================================================================
 */

/* Default classes */
require_once( "classes/debug.helper.php" );
require_once( "classes/template.helper.php" );

/* Overwrite autoload function */
function __autoload( $class )
{
	AutoLoader::autoload( $class );
}

/*
* AutoLoader class definition
*/
class AutoLoader
{
		// TODO: move to file
		public static $moduleList = array();
		private static $autoloadTable = array(
			"StringHelper"	  => "classes/string.helper.php",
			"TemplateManager" => "classes/template.helper.php",
			"MarkupManager"   => "classes/markup.class.php",
			"FileDB"		  => "classes/filedb.class.php",
			"Dispatcher"	  => "classes/dispatcher.class.php",
			"Configuration"	  => "classes/configuration.class.php",
			"PluginManager"	  => "classes/pluginmanager.class.php",
			"UserManager"	  => "classes/usermanager.class.php",
			"zenModule"		  => "modules/module.interface.php"
		);

		static function autoload( $class )
		{
				$class_lower = strtolower( $class );

				i( "loading class $class" );

				if( isset( self::$autoloadTable[ $class ] ) )
				{
						include( self::$autoloadTable[ $class ] );
				}

				if( !class_exists( $class , false ) and !interface_exists( $class, false ) )
				{
						eval('class $class { public function __construct() { throw new Exception( "Class $class not found." ); }}');
						return false;
				}
				return false;
		}

}
