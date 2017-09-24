<?php
/*
 * ==========================================================================
 * Titel                  : User Module
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

class userModule implements zenModule
{
	const NONE				  = 0;
	const WRONG_INPUT 		  = 1;
	const USER_ALREADY_EXISTS = 2;
	const WRONG_USERNAME      = 3;
	const WRONG_PASSWORD      = 4;
	const LOGIN_SUCCESS		  = 5;
	const INVALID_USERNAME    = 6;
	const INVALID_PASSWORD    = 7;
	const INVALID_EMAIL       = 8;

	public static function actionList()
	{
		return array( "login", "logout", "register", "users", "settings", "userinfo" );
	}

	public static function userinfoAction( $user = false )
	{
		return array(
			"redirect" => "/users/$user"
		);
	}

	public static function settingsAction()
	{
		$success = false;
		$error   = 0;
		$registerdata = null;

		return array(
			"tpl" 	 => "users/settings.tpl",
			"params" => array(
				"registersuccess" => $success,
				"error"		  => $error,
				"registerdata"    => $registerdata
			)
		);
	}

	public static function registerAction()
	{
		$success = false;
		$error   = 0;
		$registerdata = null;

		if( isset( $_REQUEST[ 'register' ] ) ) {
			$registerdata = $_REQUEST[ 'register' ];
		}

		$username  = isset( $registerdata[ 'username' ] )  ? $registerdata[ 'username' ]  : "";
		$password1 = isset( $registerdata[ 'password1' ] ) ? $registerdata[ 'password1' ] : "";
		$password2 = isset( $registerdata[ 'password2' ] ) ? $registerdata[ 'password2' ] : "";
		$email     = isset( $registerdata[ 'email' ] )     ? $registerdata[ 'email' ]     : "";

		if( isset( $registerdata[ 'register' ] ) )
		{
			if(
				$username  	   != ""
				and $email     != ""
			    and $password1 != ""
				and $password2 != ""
				and $password1 == $password2
			) {
				if( !self::_validateUsername( $username ) ) {
					$error = self::INVALID_USERNAME;
				}
				else
				if( !self::_validatePassword( $password1 ) ) {
					$error = self::INVALID_PASSWORD;
				}
				else
				if( !self::_validateEmail( $email ) ) {
					$error = self::INVALID_EMAIL;
				}
				else {
					$success = UserManager::register( $username, $password1, $email );
					if( !$success ) {
						$error = self::USER_ALREADY_EXISTS;
					}
				}
			} else {
				$error = self::WRONG_INPUT;
			}
		} else {
			$error = self::NONE;
		}


		return array(
			"tpl" 	 => "users/register.tpl",
			"params" => array(
				"registersuccess" => $success,
				"error"			  => $error,
				"registerdata"    => $registerdata
			)
		);
	}

	public static function loginAction()
	{
		$success   = false;
		$error     = 0;
		$logindata = null;

		if( isset( $_REQUEST[ 'login' ] ) ) {
			$logindata = $_REQUEST[ 'login' ];
		}

		if( $logindata and isset( $logindata[ 'login' ] ) )
		{
			$username = isset( $logindata[ 'username' ] ) ? trim( $logindata[ 'username' ] ) : "";
			$password = isset( $logindata[ 'password' ] ) ? trim( $logindata[ 'password' ] ) : "";

			if(  $username != "" and $password != "" ) {
				$error = UserManager::login( $username, $password );

				if( $error == self::LOGIN_SUCCESS ) {
					$success = true;
				}
			} else {
				$error = self::WRONG_INPUT;
			}
		} else {
			$error = self::NONE;
		}

		return array(
			"tpl" 	 => "users/login.tpl",
			"params" => array(
				"loginsuccess" => $success,
				"error"		   => $error,
				"logindata"    => $logindata
			)
		);
	}

	public static function logoutAction()
	{
		UserManager::logout();
		return array(
			"tpl" => "users/logout.tpl",
		);
	}

	public static function usersAction()
	{
		return array(
			"tpl"    => "users/users.tpl",
			"params" => array(
				"userlist" => UserManager::userList()
			)
		);
	}

	private static function _validateUsername( $username )
	{
		return preg_match( "/^[a-zA-Z0-9]+$/", $username );
	}

	private static function _validatePassword( $password )
	{
		return strlen( $password ) <= 5 ? false : true;
	}

	private static function _validateEmail( $email )
	{
		return preg_match( "/^[A-Z0-9\._%+-]+@[A-Z0-9\.-]+\.[A-Z]{2,4}$/i", $email );
	}
}

return "userModule";
