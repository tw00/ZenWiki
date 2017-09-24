<?php
/*
 * ==========================================================================
 * Titel                  : Pages Module
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

class todoModule implements zenModule
{
	public static function actionList()
	{
		return array( "todos" );
	}

	public static function init()
	{
		MarkupManager::registerStage( MarkupManager::POST_STAGE, __CLASS__, 'stageInsertToDos' );
	}

    public static function stageInsertToDos( $text )
	{
		$text =  preg_replace(
			'/{{TODO:(.*)}}/',
			'TODO($1)',
			$text );

        return $text;
	}

	public static function todosAction( $path = false )
	{
	    $result = array(
			"page_tpl" => "TODO_TEMPLATE",
			"params" => array_merge(
				array()
			)
		);
		return $result;
	}
}

return "todoModule";
