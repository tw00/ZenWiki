<?php
/*
 * ==========================================================================
 * Titel                  : Configuration
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

class Configuration
{
	private function __construct() {}

	private static $_inisettings = array();

	public static function load( $filename )
	{
		dx( $filename, "Loading config file..." );

		if( !file_exists( $filename ) ) return false;

		self::$_inisettings = parse_ini_file( $filename, true );

		#dx( ( print_r( self::$_inisettings, true ) ) );
		return true;
	}

	public static function loadSettings( $filename )
	{
		if( !file_exists( $filename ) ) return false;

		return parse_ini_file( $filename, true );
	}

	public static function get( $section, $value /* key? */, $default = null )
	{
		return isset( self::$_inisettings[ $section ][ $value ] ) ?
				self::$_inisettings[ $section ][ $value ] :
				$default;
	}

    public static function changeRuntimeVariable( $section, $key, $value )
    {
		if( isset( self::$_inisettings[ $section ][ $key ] ) ) {
				self::$_inisettings[ $section ][ $key ] = $value;
        }
    }

	// einfacher: addSection, addVariable, updateVariable

	public static function writeINI( $filename, $ini )
	{
		$string = '';
		
		foreach( array_keys( $ini ) as $key )
		{
			$string .= '[' . $key . ']';
			$string .= "\n";
			$string .= self::_write_get_string( $ini[ $key ], '' );
			$string .= "\n";
		}

		file_put_contents( $filename, $string );
	}
	
	public static function _write_get_string( &$ini, $prefix )
	{
		$string = '';

		ksort( $ini );
		foreach( $ini as $key => $val )
		{
			if( is_array( $val ) )
			{
				$string .= self::_write_get_string( $ini[ $key ], $prefix . $key . '.' );
			}
			else
			{
				$string .= $prefix . $key . ' = '
				        . str_replace( "\n", "\\\n", self::_set_value( $val ) )
						. "\n";
			}
		}
		return $string;
	}

	private static function _set_value($val)
	{
		if ($val === true) { return 'true'; }
		else if ($val === false) { return 'false'; }
		return $val;
	}
}
