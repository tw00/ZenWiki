<?php
/*
 * ==========================================================================
 * Titel                  : Search Module
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

class searchModule implements zenModule
{
	public static function actionList()
	{
		return array( "search" );
	}

	public static function searchAction()
	{
		$query = $_REQUEST['search']['q']; // TODO GET

		// HACK
		$result = array();
		$resultList = array();

		if( strlen( $query) > 2 ) {
			exec( "find -L wiki_wavefab/pages|grep text|grep -v \.svn|xargs grep -i '$query'", $result ); // HACK HACK HACK
		}

		foreach( $result as $line ) {
			$data = explode( ':', $line );

			if( count( $data ) < 2 ) continue;

			$file = trim( $data[0] );
			unset( $data[0] );
			$text = implode( ':', $data );

			$resultList[ $file ][] = $text;
			/*array(
				'file' => $file,
				'text' => $text
			);*/
		}

		return array(
			"tpl" 	 => "search/result.tpl",
			"params" => array(
				"query"  => $query,
				"result" => $resultList
			)
		);
	}
}

return "searchModule";
