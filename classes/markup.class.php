<?php
/*
 * ==========================================================================
 * Titel                  : Markup Manager
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
class MarkupManager
{
		const TEXY_PATH  = "lib/texy/texy/texy.php";
		const GESHI_PATH = "lib/geshi/";
		const PRE_STAGE  = 0;
		const POST_STAGE = 1;

		/* vars */
		private static $_params = array();
		private static $_image_path = '/';
	
		/* no instances */
		private function __construct() { } 

		private static $_pre_callbacks = array();
		private static $_post_callbacks = array();

		public static function addParam( $key, $param )
		{
			self::$_params[ $key ] = $param;
		}

		public static function setImagePath( $path )
		{
			self::$_image_path = '/' . $path;
		}

		public static function registerStage( $stage, $module, $callback )
		{
			dx( $module . '::' . $callback, "Added to Stage $stage" );

			switch( $stage ) {
				case self::PRE_STAGE:  self::$_pre_callbacks[]  = array( 'module' => $module, 'method' => $callback ); break;
				case self::POST_STAGE: self::$_post_callbacks[] = array( 'module' => $module, 'method' => $callback ); break;
			}
		}

		private static function preStage( $text )
		{
			foreach( self::$_pre_callbacks as $callback ) {
				# PHP 5.3.0 Api Change
				$text = call_user_func_array( array_values($callback), array($text));
				#$text = call_user_func_array( array( $callback['module'], $callback['method'] ), $text );
			}

			dx( $text, "Text after Prestage" );
			return $text;
		}

		private static function postStage( $text )
		{
			foreach( self::$_post_callbacks as $callback ) {
				# PHP 5.3.0 Api Change
				$text = call_user_func_array( array_values($callback), array($text));
				#$text = call_user_func_array( array( $callback['module'], $callback['method'] ), $text );
			}
			return $text;
		}

		public static function process( $markup, $params = array(), $processor = 'texy' )
		{
			switch( $processor )
			{
				case 'texy': return self::processTexy( $markup, $params ); break;
			}
		}

		private static function insertParams( $html, $params )
		{
			// TODO: Zugriff auf Settings

			$params = array_merge( $params, self::$_params );
			dx( $params, 'Wiki Template Params' );

			foreach( $params as $key => $param ) {
				$key  = is_int( $key ) ? $key + 1 : $key;
				$html = str_replace( '$'.$key, $param, $html );
			}
			return $html;
		}

		private static function processTexy( $markup, $params )
		{
			require_once dirname(__FILE__) . '/../' .  self::TEXY_PATH;
			
			$texy = new Texy();

			// other OPTIONAL configuration
			#$texy->encoding = 'windows-1250';      // disable UTF-8
			$texy->encoding = 'utf-8';
			$texy->imageModule->root = self::$_image_path;

			// include Texy!
			@include_once self::GESHI_PATH . 'geshi.php';

			if (!class_exists('Geshi')) {
				dx('DOWNLOAD <a href="http://qbnz.com/highlighter/">GESHI</a> AND UNPACK TO GESHI FOLDER FIRST!');
			} else {
				$texy->addHandler('block', 'blockHandler');
			}

			$texy->allowed['phrase/ins'] = TRUE;
			$texy->allowed['phrase/del'] = TRUE;
	    	$texy->allowed['phrase/sup'] = TRUE;
			$texy->allowed['phrase/sub'] = TRUE;
			$texy->allowed['phrase/cite'] = TRUE;
            
            $texy->addHandler('phrase', 'phraseHandler');

			// processing
			$markup = self::preStage( $markup );

			$html = $texy->process( $markup ); 

			$html = self::postStage( $html );

			// $texy->headingModule->title ??
			$html = self::insertParams( $html, $params );

			return array(
				'html'  =>	'<style type="text/css">' .
							$texy->styleSheet .
						   	'</style>' .
							$html,
				'title' => $texy->headingModule->title,
				'toc'	=> self::mapTOC( $texy->headingModule->TOC ),
				'levels'=> $texy->headingModule->levels
			);
		}

		private static function mapTOC( $texyTOC )
		{
			$result = array();
			$parent = &$result;
			$last_level = 10;
			$last_text  = '';
			$last_parent = null;

			foreach( $texyTOC as $item )
			{
				$element = $item[ 'el' ];
				$text    = $element->getText();
				$level   = $item[ 'level' ];

				if( $level > $last_level ) {
					$last_parent = &$parent;
					$parent = &$parent[ $last_text ];
				}
				if( $level < $last_level and $last_parent != null ) {
					$parent = &$last_parent;
				}
				$last_level = $level;
				$last_text  = $text;

				$parent[ $text ] = array();
			}
			return $result;
		}
}

/**
* @param TexyHandlerInvocation  handler invocation
* @param string
* @param string
* @param TexyModifier
* @param TexyLink
* @return TexyHtml|string|FALSE
*/
function phraseHandler($invocation, $phrase, $content, $modifier, $link)
{
    // is there link?
    if (!$link) return $invocation->proceed();

    dx( $content, "PHRASE_HANDLER" );

    if (Texy::isRelative($link->URL)) {
        $link->URL = ($link->URL); //????
        return $invocation->proceed();
    }

    if( count( $linkType = explode( ':', $link->URL ) ) == 2 )
    {
        $type = trim( $linkType[0] );
        $code = trim( $linkType[1] );

        switch( $type )
        {
            case 'special':
                $link->URL = '/special:' . $code;
            break;

            case 'youtube':
                $link->URL = 'http://www.youtube.com/watch?v=' . $code;
            break;

            case 'wiki':
                $link->URL = 'http://en.wikipedia.org/wiki/Special:Search?search=' . $code;
            break;

            case 'rfc':
                $link->URL = 'http://tools.ietf.org/html/rfc' . $code;
            break;
        }
    }
    return $invocation->proceed();
}

/**
* User handler for code block
*
* @param TexyHandlerInvocation  handler invocation
* @param string  block type
* @param string  text to highlight
* @param string  language
* @param TexyModifier modifier
* @return TexyHtml
*/
function blockHandler($invocation, $blocktype, $content, $lang, $modifier )
{
    if ($blocktype !== 'block/code') {
        return $invocation->proceed();
    }

    $texy = $invocation->getTexy();

    $geshiPath = MarkupManager::GESHI_PATH;

    if ($lang == 'html') $lang = 'html4strict';
    $content = Texy::outdent($content);
    $geshi = new GeSHi($content, $lang, $geshiPath.'geshi/');

    // GeSHi could not find the language
    if ($geshi->error) {
        return $invocation->proceed();
    }

    // do syntax-highlighting
    $geshi->set_encoding('UTF-8');
    $geshi->set_header_type(GESHI_HEADER_PRE);
    $geshi->enable_classes();
    $geshi->set_overall_style('color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;', true);
    $geshi->set_line_style('font: normal normal 95% \'Courier New\', Courier, monospace; color: #003030;', 'font-weight: bold; color: #006060;', true);
    $geshi->set_code_style('color: #000020;', 'color: #000020;');
    $geshi->set_link_styles(GESHI_LINK, 'color: #000060;');
    $geshi->set_link_styles(GESHI_HOVER, 'background-color: #f0f000;');

    // save generated stylesheet
    $texy->styleSheet .= $geshi->get_stylesheet();

    $content = $geshi->parse_code();

    // check buggy GESHI, it sometimes produce not UTF-8 valid code :-((
    $content = iconv('UTF-8', 'UTF-8//IGNORE', $content);

    // protect output is in HTML
    $content = $texy->protect($content, Texy::CONTENT_BLOCK);

    $el = TexyHtml::el();
    $el->setText($content);
    return $el;
}
