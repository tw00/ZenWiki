<?php
/*
 * ==========================================================================
 * Titel                  : Zen Module Interface
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

interface zenModule
{
	public static function actionList();

	/*public static function action( $action, array $params = array() )
	{
		if( !is_string( $action ) ) throw new Exception( "String expected, got " . gettype( $action ) );

		$action = $action . "Action";

//		call_user_func( "self::".$action."Action" , $params );
echo get_class( self );
echo __CLASS__;

//		call_user_func( "zen::". $action, 'quiptim', 'qwertyuiop');

		switch( $action )
		{
			case "recent":   return self::_recentAction( $params );  break;
			case "create":   return self::_createAction( $params );  break;
			case "index":    return self::_indexAction( $params );   break;

			default: {
				throw new Exception( "Unknown Action $action" );
			} break;
		}
	}*/
}
