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

class Dispatcher
{
	private function __construct() {}

#	private static $modules = array(
#		"login"
#		"logout"
	const URI_REGEX = "/^\/(?:(?<special>special):|)(?<page>(?:[a-zA-Z0-9-_%]*)(?:\/(?:[a-zA-Z0-9-_%]*))*)(\?(?<view>[a-zA-Z0-9_]*)(|=(?<value>[a-zA-Z0-9_]+))|)$/";

	const TYPE_WIKI = 1;
	const TYPE_SPECIAL = 2;

	private static $_view = false;
    public static $pagePathArray = array();

	public static function run()
	{
		self::_dispatch();
	}

	public static function getView()
	{
		return self::$_view;
	}

	protected static function _dispatch()
	{
		//$requestUri  = $GLOBALS['HTTP_ENV_VARS']['REQUEST_URI'];
		//$redirect    = isset( $GLOBALS['HTTP_ENV_VARS']['REDIRECT_URI'] );
		//$redirectUri = $redirect ? $GLOBALS['HTTP_ENV_VARS']['REDIRECT_URI'] : false;
		//$remoteAddr  = $GLOBALS['HTTP_ENV_VARS']['REMOTE_ADDR'];
		$requestUri  = $_SERVER['REQUEST_URI'];
		$redirect    = isset( $_SERVER['REDIRECT_URI'] );
		$redirectUri = $redirect ? $_SERVER['REDIRECT_URI'] : false;
		$remoteAddr  = $_SERVER['REMOTE_ADDR'];

		#dx( $requestUri, "REQUEST" );

		$matches = false;
		preg_match( self::URI_REGEX, $requestUri, $matches );
		dx( $matches, "Dispatched URI", true );

		#if( isset( $_REQUEST[ 'username' ] ) and isset( $_REQUEST[ 'password' ] ) ) {
		#	UserManagement::login( trim( $_REQUEST[ 'username' ] ), trim( $_REQUEST[ 'password' ] ) );
		#}

		if( count( $matches ) > 0 ) {
			$path = $matches[ 'page' ];

            /* Create path array for further usage */
            self::$pagePathArray = explode( '/', $path );
            foreach( self::$pagePathArray as $key => $pathElement ) {
                if( trim( $pathElement ) == "" ) unset( self::$pagePathArray[$key] );
            }

			if( isset( $matches[ 'view' ] ) and $matches[ 'view' ] == "debug" ) {
                Configuration::changeRuntimeVariable( 'debug', 'enabled', "true" );
				TemplateManager::debugMode( $matches[ 'value' ] );
			}

			self::$_view = isset( $matches[ 'view' ] ) ? $matches[ 'view' ] : false;
			
			MarkupManager::addParam( 'VIEW', self::$_view );
				
			if( $matches[ 'special' ] == "special" ) {
				// Special Page
				return self::_load( $path, self::TYPE_SPECIAL, array( self::$_view ) );
				#return self::_specialModule( $path );
			} else {
				if( $path == "" ) {
					#return self::_load( "show", self::TYPE_WIKI, array( Configuration::get( "wiki", "mainpage", 'mainpage' ) ) );
					$default = Configuration::get( "wiki", "defaultpage", "mainpage" );
					$path =  Configuration::get( "wiki", $default, $default );
					return TemplateManager::redirect( $path );
					# return self::_wikiModule( Configuration::get( "wiki", "mainpage", 'mainpage' ) );
				}
				
				MarkupManager::addParam( 'PAGE', $path );
				// Wiki Page
				#if( $path != "mainpage" and $path != "mainpage/" ) {
				#	$path = "mainpage/" . $path;
				#}

				# return self::_load( "show", self::TYPE_WIKI, array( $path, $view ) ); // BUG HACK!!!
				return self::_load( "show", self::TYPE_WIKI, array( $path, false ) );
				#return self::_wikiModule( $path );
			}
		} else {
			throw new Exception( "Dispatcher Error ($requestUri)" );
		}
	}

    public static function createRelativeURL($link)
    {
        if( count( self::$pagePathArray ) > 0 )
            return end( self::$pagePathArray ) . '/' . $link;
        else return $link;
    }


#	protected static function _wikiModule( $path )
#	{
	/*
		$editForm = isset( $_REQUEST[ 'edit' ] ) ? $_REQUEST[ 'edit' ] : false;

		if( $editForm ) {
			self::_wikiEditAction( $path, $editForm );
		} else {
			self::_wikiShowAction( $path );
		}
		*/
#		TemplateManager::add( "content", "wikipage.tpl" );

#		self::_load( "show", self::TYPE_WIKI, array( $path ) );
#	}

#	protected static function _specialModule( $name )
#	{
	#	TemplateManager::add( "content", "special.tpl" );

		#$view = PluginManager::dispatch( $name );
		

		#if( isset( $view['params'] ) ) {
	#		TemplateManager::addArray( $view['params'] );
		#}

#		self::_load( $name, self::TYPE_SPECIAL );
#	}

	protected static function _load( $name, $type, $args = array() )
	{
		header( 'Content-type: text/html; charset=' . Configuration::get( "general", "charset", "utf-8" ) );

		TemplateManager::add( "theme", Configuration::get( "design", "theme", "default" ) );

		TemplateManager::add(
			"css_file_list",
			array_merge
			(
				Configuration::get( "design", "csslist", array() ),
				self::$_view == "print" ? array( "print.css" ) : array()
			)
		);
		TemplateManager::add(
			"js_file_list",
			Configuration::get( "design", "jslist", array() )
		);

		if( $name == "" ) $name = "*";

		$view = PluginManager::dispatch( $name, $args );

		if( isset( $view[ 'redirect' ] ) and
			( $redirect = $view[ 'redirect' ] ) != "" ) {
			TemplateManager::redirect( $redirect );
		}

		$page_tpl = isset( $view['page_tpl'] ) ? $view['page_tpl'] : false;

		if( !$page_tpl and $type == self::TYPE_WIKI ) {
			$page_tpl = "wikipage.tpl";
		}

		if( !$page_tpl and $type == self::TYPE_SPECIAL ) {
			$page_tpl = "special.tpl";
		}

		TemplateManager::add( "specialpage",  $name );
		TemplateManager::add( "scaffold_tpl", "scaffold.tpl" );
		TemplateManager::add( "page_tpl",     $page_tpl );
		TemplateManager::add( "content_tpl",  isset( $view['tpl'] ) ? $view['tpl'] : false );
		
		#TemplateManager::add( "specialtpl", $view['tpl'] );

		if( isset( $view['params'] ) ) {
			TemplateManager::addArray( $view['params'] );
		}
		
		dx( $view[ 'params' ], "view params", true );

		TemplateManager::load( "index.tpl" );
	}

#	protected static function _wikiEditAction( $path, $editForm )
#	{
#	}

#	protected static function _wikiShowAction( $path, $pageData = array(), $template = false )
#	{
#	}

/*			$data = array();
			$template = false;
			
			if( $editForm['name'] != $path ) { // TODO
				e( "Changing name is not supported" );
			}

			if( isset( $editForm['wikicode'] ) ) {

				if( isset( $editForm[ 'preview' ] ) ) {
					$content = stripslashes( $editForm['wikicode'] );
					$data = array(
						'pagename'  =>  $editForm['name'],
						'content'	=>	$content,
						'filsize'   =>  strlen( $content ), //?
						'lastedit'	=>  time(),
						'created'   =>  false,
						'preview'	=>  true
					);
					$template = "wikipagepreview.tpl";
				}
				else if( isset( $editForm[ 'save' ] ) ) {
					if( !FileDB::createNewVersion( $path, $editForm['wikicode'] ) ) {
						e( "Unkown Error, while creating new page: $path" );
					}
				}
			}

			self::_wikiShowAction( $path, $data, $template ); // TODO Redirect stattdessen
	*/
	/*	$pathArray = explode( "/", $path );

		foreach( $pathArray as $key => $element ) {
			if( $element == "" ) {
				unset( $pathArray[ $key ] );
			}
		}

		$article = end( $pathArray );

		if( FileDB::pageExists( $path ) ) {
			if( count( $pageData ) == 0 ) {
				$pageData = FileDB::readPage( $path );
				$pageData['pagename'] = $article; TODO
				dx( $pageData, "PageData" );
			}

			TemplateManager::add( "breadcrumb", $pathArray );
			TemplateManager::add( "data", $pageData );
			TemplateManager::add( "content", $template ? $template : "wikipage.tpl" );

		} else {
			e( "$path doesn't exist" );
			TemplateManager::add( "404page", $path );
			TemplateManager::add( "content", "404.tpl" );
		}*/
}
