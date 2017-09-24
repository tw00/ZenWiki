<?php
/*
 * ==========================================================================
 * Titel                  : Template Helper
 * Project				  : ZenWiki
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

/*
 * Class definition
 */
class TemplateManager
{
		/* Class is not instantiable */
		private function  __construct() {}

		private static $_varlist     = array();
		private static $_basepath    = "templates";
		private static $_notfoundtpl = "notfound.tpl";
		private static $_debug       = false;

		public static function debugMode( $mode )
		{
			self::$_debug = $mode;
		}

		public static function isDebugEnabled()
		{
			return self::$_debug;
		}

		public static function add( $name, $var )
		{		
			// TODO: array als parameter		
			self::$_varlist[ $name ] = $var;
		}

		public static function paramExists( $name )
		{
			return array_key_exists( self::$_varlist, $name );
		}

		public static function addArray( Array $data )
		{
			self::$_varlist = array_merge( self::$_varlist, $data );
		}

		public static function get( $name )
		{
            if( !array_key_exists( $name, self::$_varlist) )
                return Null;
			return self::$_varlist[ $name ];
		}

		public static function show( $name )
		{
			echo self::get( $name );
		}

		public static function load( $path, $return = false /* TODO */ )
	    {
			if( self::$_debug ) {
				echo "<div style='border: 1px dashed #666;margin:1px;'>";
				echo "<div style='background-color:red;color:white;padding:2px;'>", $path, "</div>";
			}

			$theme = Configuration::get( 'design', 'theme', "default" );

			$themepath   = self::$_basepath . DIRECTORY_SEPARATOR . $theme    . DIRECTORY_SEPARATOR . $path;
			$defaultpath = self::$_basepath . DIRECTORY_SEPARATOR . "default" . DIRECTORY_SEPARATOR . $path;

			if( file_exists( $themepath ) ) {
				$path = $themepath;
			} else {
				$path = $defaultpath;
			}
			#dx( $path );

	    	if( isset( $path ) and file_exists( $path ) ) {
				DebugManager::template( $path, $path == $themepath );
				include( $path );
			} else {
				e( "Template '$path' not found" );
				self::add( 'tpl', $path );
				include( self::$_basepath . DIRECTORY_SEPARATOR . "default" .  DIRECTORY_SEPARATOR . self::$_notfoundtpl );
			}

			if( self::$_debug ) echo "</div>";
		}

		public static function redirect( $target )
		{
			header( "Location: $target" );
			exit();
		}
}
