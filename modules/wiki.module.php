<?php
/*
 * ==========================================================================
 * Titel                  : Wiki Module
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

class wikiModule implements zenModule
{
	const CREATE_FAILED = 1;
	const INVALID_PAGE_NAME = 2;

	public static function actionList()
	{
		return array( "show", /*"edit",*/ "recent", "create", "index" );
	}

	public static function init()
	{
		MarkupManager::registerStage( MarkupManager::PRE_STAGE, __CLASS__, 'stageInterWikiLinks' );
		MarkupManager::registerStage( MarkupManager::PRE_STAGE, __CLASS__, 'stageTemplates' );
	}

    /* @TODO
     *  - Whitespaces??
     *  - Shouldnt match code!!
     *  -
     */
	public static function stageInterWikiLinks( $text )
	{
        $references = "\n";

        // Replace [[service:link|text]]
		$text =  preg_replace(
			'/\[\[(.*):(.*)\|(.*)\]\]/',
			'"$3":[$1:$2]',
			$text );

        // Replace [[link|text]] #FIXME
		$text =  preg_replace(
			'/\[\[(.*)\|(.*)\]\]/',
			'"$2":[$1]',
			$text );

        // Replace [[service:link]]
		$text =  preg_replace(
			'/\[\[(.*):(.*)\]\]/',
			'"$2":[$1:$2]',
			$text );

        // Simplify [[link]] to [link]
		$text =  preg_replace(
			'/\[\[([öäüßÖÄÜa-zA-Z0-9\/_\-\|\.:\s]+)\]\]/',
			'[$1]',
			$text );

        // Find all inter wiki links
        preg_match_all(
            "/\[(?<InterWikiLink>[öäüßÖÄÜa-zA-Z0-9\/_\-\s]+)\]/",
            $text,
            $matches );

        // Nötig? bug!!
        #if( isset( $matches['InterWikiLink'] ) or count( $matches['InterWikiLink'] ) == 0 ) {
        #    dx( "No InterWikiLinks found" ); 
        #    return $text;
        #}

		//$requestUri = $GLOBALS['HTTP_ENV_VARS']['REQUEST_URI'];
		$requestUri = $_SERVER['REQUEST_URI'];

        foreach( $matches['InterWikiLink'] as $link ) {
            /* If URL does not end with trailing slash, parent article
            must be added to the URL (which is a HTML problem) */

            /*if( substr( $requestUri, -1 ) != '/' ) {
                $link_url = Dispatcher::createRelativeURL($link);
                #dx( $link_url, "Fixed trailing slash bug" );
            } else {
                $link_url = $link;
            }*/
            $link_url = '/'.$link; # BUG: Links sollen doch absolut behandelt werden.

            $existence = FileDB::pageExists( $link ) ? 'page-existent' : 'page-non-existent';
            dx( $existence, "Existence for page $link" ); 

            $references .= "[$link]: $link_url $link .[$existence]\n";
        }

        // Put references on the beginning, so it can be override by user references
        return $references . $text;
	}

	private static function _replace( $result )
	{
		$template = isset( $result['template'] ) ? $result['template'] : "";
		$params   = isset( $result['params']   ) ? $result['params']   : "";
		$params   = explode( '|', $params );
		$params   = array_filter( $params );

		try {
			$page = FileDB::readPage( "templates/$template" );
			$content = $page['content'];
		} catch(Exception $e){
			$content = "*Template `$template` nicht gefunden*";
		}

		$markup = MarkupManager::process( $content, $params );

		##echo "<pre>", print_r( $template, true ), "</pre>";

		return $markup;
	}

	public static function stageTemplates( $text )
	{
		$text = preg_replace_callback(
			'/\{\{(?<template>[a-zA-Z0-9_]+)(?<params>(?:\|[\w]+)*)\}\}/',
			'wikiModule::_replace',
	   		$text );

		return $text;

		#$results = array();
		#preg_match_all(
		#	'/\{\{(?<template>[a-zA-Z0-9_]+)(?<params>(?:\|[\w]+)*)\}\}/',
		#	$text,
	   	#	$results );
		#
		#foreach( $results[0] as $key => $value ) {
		#
		#	$text = preg_replace(
		#		'/\{\{('.$template.'((\|[\w]+)*))\}\}/',
		#		$markup,
		#		$text );
		#}
		#
		#return $text;
	}

	public static function showAction( $path = false )
	{
		/* Anonymous User */
		if( !UserManager::currentUser() )
			return array(
				"page_tpl" => "empty.tpl"
			);

		/* Modul wurde direkt aufgerufen */
		if( !$path ) {
			return array( "tpl" => "empty.tpl" );
		}

		$editForm = isset( $_REQUEST[ 'edit' ] ) ? $_REQUEST[ 'edit' ] : false;

		if( $editForm ) {
			return self::_wikiEditAction( $path, $editForm );
		} else {
		 	return self::_wikiShowAction( $path );
		}
	}

	public static function _wikiShowAction( $path, $pageData = array(), $template = false )
	{
		if( FileDB::pageExists( $path ) ) {
			if( count( $pageData ) == 0 ) {

                try {
    				$pageData = FileDB::readPage( $path );
                } catch( Exception $e ) {
                    // TODO only ...
                    e( "Can not find $path" );
                    $pageData = FileDB::readFolder( $path );
                }

	    		#$pageData['pagename'] = $article; /*TODO*/
				dx( $pageData, "PageData", false );
				
				if( preg_match( "/#REDIRECT([\s]*)(?<target>[\S]+)/i", $pageData[ 'content' ], $matches ) ) {
					TemplateManager::redirect( $matches[ 'target' ] );
				}
			}

#			TemplateManager::add( "breadcrumb", $pathArray );
#			TemplateManager::add( "data", $pageData );
#			TemplateManager::add( "content", $template ? $template : "wikipage.tpl" );
			if( !$template )
				$template = "wikipage.tpl";

		} else {
			e( "$path doesn't exist" );
#			TemplateManager::add( "404page", $path );
			$template = "404.tpl";
		}

        // HACK
        $pageData[ 'content' ] = str_replace( "__NOTOC__", "", $pageData[ 'content' ], $count );
        $enableTOC = ( $count  == 0 );

		if( isset( $pageData[ 'content' ] ) ) {
	  		$markupResult = MarkupManager::process( $pageData['content'] );
		} 		

		$result = array(
			"page_tpl" => $template,
			"params" => array_merge(
				$pageData,
				array(
					#"breadcrumb" => $pathArray,
					"404page"    => $path,
					#"wikiTitle"	 => $markupResult[ 'title' ],
					"wikiHTML"	 => $markupResult[ 'html' ],
					"wikiTOC"    => $markupResult[ 'toc' ],
					"enableTOC"  => $enableTOC
				)
			)
		);
		return $result;
	}

	public static function _wikiEditAction( $path, $editForm )
	{
			$data = array();
			$template = false;
			
			if( isset( $editForm['name'] ) &&
                $editForm['name'] != $path ) {
                 // TODO
				e( "Changing name is not supported" );
			}

			if( isset( $editForm['wikicode'] ) ) {

				if( isset( $editForm[ 'preview' ] ) ) {
					$content = $editForm['wikicode'];
                    $content = ( get_magic_quotes_gpc() == 0 ) ? $content : stripslashes( $content );
					$data = array(
						'pagename'   =>  $editForm['name'],
						'content'	 =>	 $content,
						'filsize'    =>  strlen( $content ), //?
						'lastedit'   =>  time(),
						'editors'    =>  array( UserManager::currentUser() ),
						'breadcrumb' =>  Dispatcher::$pagePathArray, // explode( '/', $path ),
						'created'    =>  false,
						'preview'	 =>  true
					);
					$template = "wikipagepreview.tpl";
				}
				else if( isset( $editForm[ 'save' ] ) ) {
					if( !FileDB::createNewVersion( $path, $editForm['wikicode'] ) ) {
						e( "Unkown Error, while creating new page or revision: $path" );
					}
				}
			}

			return self::_wikiShowAction( $path, $data, $template ); // TODO Redirect stattdessen

	#	return array(
	#		"tpl" 	 => $template,
	#		"params" => array( )
	#	);
	}
	public static function recentAction()
	{
		$edits = FileDB::recentEdits();

		return array(
			"tpl" 	 => "wiki/recent.tpl",
			"params" => array( "edits" => $edits )
		);
	}

	public static function createAction()
	{
		$params = array();
		$redirect = false;

		if( isset( $_REQUEST['page'] ) and
		    isset( $_REQUEST['page']['create'] ) )
		{
			$success = false;
			$pagename = isset( $_REQUEST['page']['name'] ) ? trim( $_REQUEST['page']['name'] ) : false;
			
			dx( $pagename, "CREATE PAGE" );

			#return self::_wikiShowAction( $pagename,
			#	array(
			#		"name"	   => $pagename,
			#		"wikicode" => ''
			#	));

			$params[ 'createpage' ] = $pagename;
			
			// TODO
			#if( self::_validateArticleName( $pagename ) )
			#if( FileDB::articleExists( .. )

			if( $pagename != "" ) {

			// TODO MAINPAGE
				$success = FileDB::createArticle( $pagename );

				if( $success ) {
					$redirect = $pagename . "?edit";
				}
			}
			if( !$success ) {
				$params[ 'error' ] = self::CREATE_FAILED;
			}
		}

		return array(
			"redirect" => $redirect,
			"tpl"      => "wiki/create.tpl",
			"params"   => $params
		);
	}

	public static function indexAction()
	{
		$index = FileDB::createIndex();

		return array(
			"tpl" => "wiki/index.tpl",
			"params" => array( "index" => $index )
		);
	}
}

return "wikiModule";
