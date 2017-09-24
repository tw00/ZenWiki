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

class filesModule implements zenModule
{
	const IMAGE_PATH = "wiki_wavefab/images/"; #HACK

	public static function actionList()
	{
		return array( "upload", "images" );
	}

	public static function uploadAction()
	{
		//TODO

		return array(
			"tpl" 	 => "files/upload.tpl",
			"params" => array( )
		);
	}

	public static function imagesAction()
	{
		//TODO
		$image_list = array();

		foreach( glob( self::IMAGE_PATH . "*" ) as $file )
		{
			$details = stat( $file );
			$size = floor( $details['size'] / 1024 );
			$mtime = filemtime( $file );

			$image_list[ $mtime ] = array( // HACK
				"name"   => basename( $file ),
				"file"   => $file,
				"editor" => "mm", // HACK
				"date"   => $mtime,
				"size"   => $size
			);
		}

		krsort( $image_list );

		return array(
			"tpl" 	 => "files/images.tpl",
			"params" => array(
				"img_list" => $image_list
			)
		);
	}
}

return "filesModule";
