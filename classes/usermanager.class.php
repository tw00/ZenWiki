<?php
/*
 * ==========================================================================
 * Titel                  : Dispatcher
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

class UserManager /* = UserDB */
{
	private static $_basepath = "/";
	private static $_userlist = array();

	public static function setBasepath( $path )
	{
		self::$_basepath = $path;
	}
	private function __construct() {}

	public static function init()
	{
		session_start();

		MarkupManager::addParam( 'USER', self::currentUser() );
	}

	public static function currentUser()
	{
		return isset( $_SESSION[ 'user' ] ) ? $_SESSION['user'] : false;
	}

	public static function register( $username, $password, $email )
	{
		if( self::_userExists( $username ) )
			return false;

		$settings = array(
			'general' => array(
				'password' => md5( $password ),
				'email'    => $email
				)
			);

		$userdir  = self::$_basepath . '/' . $username;
		$userfile = $userdir .  '/' . 'config.ini';

		mkdir( $userdir ); // TODO

		Configuration::writeINI( $userfile, $settings );

		return true;
	}

	public static function login( $username, $password )
	{
		if( !self::_userExists( $username ) ) {
			return userModule::WRONG_USERNAME;
		}

		$user = Configuration::loadSettings( self::$_basepath . '/' . $username . '/' . 'config.ini' );

		if( md5( $password ) === $user['general']['password'] )
		{
			// Setting Session
			$_SESSION[ 'user' ] = $username;

			// Redirecting to the logged page.
			#header("Location: index.php");
			return userModule::LOGIN_SUCCESS;
		}
		else
		{
			return userModule::WRONG_PASSWORD;
		}
	}

	public static function logout()
	{
		unset( $_SESSION[ 'user' ] );
	}

	public static function userList()
	{
		if( !self::$_userlist or count( self::$_userlist ) == 0 )
		{
			foreach( glob( self::$_basepath . "/*" ) as $user )
			{
				// if is dir
				self::$_userlist[] = $user;
			}
		}
		dx( self::$_userlist );
		return self::$_userlist;
	}

	public static function checkPermissions( $module, $method, $user )
	{
		$user = trim( $user );
		if( !$user ) $user = "anonymous";

		dx( $user."@".$module."::".$method, "Checking Permission" );

		// HACK
		if( $user == "anonymous" and !( $module == 'userModule' and $method == 'loginAction') )
			return false;

		return true;
	}

	private static function _userExists( $username )
	{
		$userfile = self::$_basepath . '/' . $username . '/' . 'config.ini';

		dx( $userfile );

		return file_exists( $userfile );
	}
}
