<?php
/*
 * ==========================================================================
 * Titel                  : Plugin Manager
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

class PluginManager
{
	private function __construct() {}
	private static $_moduleList = array();
	private static $_actionList = array();

	public static function loadModules()
	{
		foreach( glob( "modules/*.module.php" ) as $modulefile )
		{
			if( $modulename = require_once( $modulefile ) )
			{
				self::$_moduleList[] = $modulename;
	
#				self::$_moduleList[ $modulename ] = call_user_func( $modulename."::actionList" );
				if( method_exists( $modulename, "init" ) ) {
					call_user_func( $modulename."::init" );
				}

				foreach( call_user_func( $modulename."::actionList" ) as $action ) {
					self::$_actionList[ $action ] = $modulename;
				}
			}
		}
		#dx( self::$_moduleList );
		dx( self::$_actionList, "Action Module Mapping", true );
	}

	public static function dispatch( $action, $args )
	{
		if( !is_string( $action ) ) throw new Exception( "String expected, got " . gettype( $action ) );

		if( $action == "*" ) {
			return self::_pluginList();
		}

		$module = self::$_actionList[ $action ];
		$method = $action . "Action";

		// Pre - Stage
		if( method_exists( $module, "before" ) ) {
			call_user_func( $module."::before" );
		}

		if( !UserManager::checkPermissions( $module, $method, UserManager::currentUser() ) ) {
			e( "PERMISSION DENIED" );
			
			return array(
				"page_tpl"	=> "special.tpl",
				"tpl"	 	=> "permissiondenied.tpl",
				"params" 	=> array(
					"module" => $module,
					"method" => $method
				)
			);
		}

        if( !$module ) {
            e( "Module $module not found!" );
			return array(
				"page_tpl"	=> "special.tpl",
				"tpl"	 	=> "modulenotfound.tpl",
				"params" 	=> array(
					"method" => $method
				)
			);
        }

		$result = call_user_func_array( array( $module, $method ), $args );

		// Post - Stage
		if( method_exists( $module, "after" ) ) {
			call_user_func( $module."::after" );
		}

		return $result;
	#	$view =  call_user_func( array( Autoloader::$moduleList[0], 'action' ), $name );
	}

	#protected static function _specialModule( $name )
	#{
	#	$view =  call_user_func( array( Autoloader::$moduleList[0], 'action' ), $name );
	#}

	private static function _pluginList()
	{
		return array(
			"tpl"      => "speciallist.tpl",
			"params"   => array(
				"action_list" => self::$_actionList,
				"module_list" => self::$_moduleList
			)
		);
	}
}
