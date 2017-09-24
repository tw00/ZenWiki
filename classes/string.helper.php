<?php
/*
 * ==========================================================================
 * Titel                  : String Helper
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
class StringHelper
{
		/* Class is not instantiable */
		private function  __construct() {}

		static function startsWith( $str, $char )
	    {
            return ( $str{0} == $char );
		}

		public static function shorten( $str, $max = 20, $rep = '...' )
		{
			if(strlen($str) > $max) {
				$leave = $max - strlen($rep);
				return substr_replace($str, $rep, $leave);
			} else {
				return $str;
			}
		}

		public static function localeDate( $timestamp, $colored = false )
		{
			$day        = mktime( 0, 0, 0, date( "m", $timestamp), date( "d", $timestamp ), date( "Y", $timestamp ) );
			$yesterday  = mktime( 0, 0, 0, date( "m" ), date( "d" ) - 1, date( "Y" ) );
			$today      = mktime( 0, 0, 0, date( "m" ), date( "d" ), date( "Y" ) );

			$daysleft = ( $today - $day ) / ( $today - $yesterday );

			$date = '';
			switch( $daysleft ) {
				case 0: $date = "Heute"; break;
				case 1: $date = "Gestern"; break;
				case 2: $date = "Vorgestern"; break;
				case 3: case 4: case 5: case 6:
					$date = "Diese Woche, am ";
				default:
					$date .= date( "j. F Y", $timestamp );
			}

			/* HACK */
			switch( $date )
			{
				case 'Heute': $date = "<span style='background-color: #AAFFAA'>" . $date . "</span>"; break;
				case 'Gestern': $date = "<span style='background-color: #AADDAA'>" . $date . "</span>"; break;
				case 'Vorgestern': $date = "<span style='background-color: #88CC88'>" . $date . "</span>"; break;
			}

 			return $date . ' um ' . date( "H:i:s", $timestamp );
		}

		public static function wikiPath( $path, $removeLast = false, $area = null )
		{
			$pathArray = explode( '/', $path );
			if( count( $pathArray ) > 2) {
				if( $removeLast ) {
					array_splice($pathArray, -1, 1);
				}

				if( $area ) $area = $pathArray[1];

				unset( $pathArray[0] ); //HACK
				unset( $pathArray[1] ); //HACK
			}
			return implode( '/', $pathArray );
		}

		static function wikiUrlEncode()
		{
            // 1.) Mapping einer URL auf einen Dateinamen
            // 2.) Mapping eines Dateinames/Ordners auf eine gültige URL
            // 3.) Validierung eines neuen Seitennamens

            $FSAllowedCharacters         = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.#%';
            $FSAllowedEscapedCharacters  = ':,-_+^@$?=';
            $FSAllowedButDangerous       = '(){}[]!&|~';
			$URLAllowedEscapedCharacters = 'öäüéèêáàâúùûîíìóòôÖÄÜÉÈÊÁÀÂÚÙÛÍÌÎÓÒÔ~œ°€∆∞´ ';
            $URLPathSeparators           = '/';
            // FS Path Check (..)

			$URLAllowedCharacters        = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.:,;-_+*^(){}[]@!%$&=|~';
			$URLAllowedEscapedCharacters = 'öäüéèêáàâúùûîíìóòôÖÄÜÉÈÊÁÀÂÚÙÛÍÌÎÓÒÔ~œæç°´`€∆∞ ';
            $URLForbiddenCharacters      = '@#?%';
            $URLPathSeparators           = '\/';
            $URLMapping                  = array( ' ' => '_' );


			#$allowedCharacters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.:,;-_+*^@!%$&=|~";
			#$allowedButEscaped = "'\"§\\ß\[\]{}<>"
			#$specialSymbols    = "öäüéèêáàâúùûîíìóòôÖÄÜÉÈÊÁÀÂÚÙÛÍÌÎÓÒÔ~œæç°´`€∆∞";
			#$shellEscape       = "()<>\"'`"

		}

		static function wikiUrlDecode()
		{
		}

		private static function _cleanPath( $path, $asArray = false )
		{
			/* Remove trailing slash */
			$pathArray = explode( "/", $path );
			foreach( $pathArray as $key => $pathElement ) {
				if( $pathElement == "" ) {
					unset( $pathArray[ $key ] );
				}
			}

			if( $asArray ) return $pathArray;
			
			return implode( "/", $pathArray );
		}

		public static function linkify( $text )
		{
				$url_patterns = array(
					// @link http://internet.ls-la.net/folklore/url-regexpr.html
					'http' 	 => "(?:https?://(?:(?:(?:(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:\.(?:\d+)){3}))(?::(?:\d+))?)(?:/(?:(?:(?:(?:[a-zA-Z\d$\-_.+!*'(),]|(?:%[a-fA-F\d]{2}))|[;:@&=])*)(?:/(?:(?:(?:[a-zA-Z\d$\-_.+!*'(),]|(?:%[a-fA-F\d]{2}))|[;:@&=])*))*)(?:\?(?:(?:(?:[a-zA-Z\d$\-_.+!*'(),]|(?:%[a-fA-F\d]{2}))|[;:@&=])*))?)?)",
					'ftp' 	 => "(?:ftp://(?:(?:(?:(?:(?:[a-zA-Z\d$\-_.+!*'(),]|(?:%[a-fA-F\d]{2}))|[;?&=])*)(?::(?:(?:(?:[a-zA-Z\d$\-_.+!*'(),]|(?:%[a-fA-F\d]{2}))|[;?&=])*))?@)?(?:(?:(?:(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:\.(?:\d+)){3}))(?::(?:\d+))?))(?:/(?:(?:(?:(?:[a-zA-Z\d$\-_.+!*'(),]|(?:%[a-fA-F\d]{2}))|[?:@&=])*)(?:/(?:(?:(?:[a-zA-Z\d$\-_.+!*'(),]|(?:%[a-fA-F\d]{2}))|[?:@&=])*))*)(?:;type=[AIDaid])?)?)",
					'mailto' => "(?:mailto:(?:(?:[a-zA-Z\d$\-_.+!*'(),;/?:@&=]|(?:%[a-fA-F\d]{2}))+))"
				);

				$pattern = '/(' . addcslashes( $url_patterns['http'],   chr(0x2F) )
						 . '|'  . addcslashes( $url_patterns['ftp'],    chr(0x2F) )
						 . '|'  . addcslashes( $url_patterns['mailto'], chr(0x2F) ) . ')/';

				return preg_replace( $pattern, '<a href="\\1">\\1</a>', $text );
		}
}
