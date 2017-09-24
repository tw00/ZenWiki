<?php
/*
 * ==========================================================================
 * Titel                  : Twitter Module
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

class twitterModule implements zenModule
{
	public static function actionList()
	{
		return array( "twitter" );
	}

	public static function twitterAction()
	{
		include_once( "lib/twitter/twitter.php" );

		$twitter = new Twitter(
			Configuration::get( "twitter", "username" ),
			Configuration::get( "twitter", "password" )
		);

        // set tokens
        $twitter->setOAuthToken('OlVLp1AiuPEx0zWU7oGdg');
        $twitter->setOAuthTokenSecret('6ZR8iFeVfUsTuVVKS3lmLuqTrD1o6EWfn49ljMGlIY');
        $twitter->setOAuthToken('r7hyTowy7UF8HHavupiIR9gzVoQl0y0b1ZI2nRUQtOs');

        // get tokens
#        $twitter->oAuthAccessToken('bC3lA4Gn0EeNvZsMJdMADvdngCOBZDfRsSYezLg', '6ylRwg5UnhRLqH0t1ClpIFMY1y6f7ctbOZXyWElyW0w');
                // set tokens
                #$twitter->setOAuthToken('bC3lA4Gn0EeNvZsMJdMADvdngCOBZDfRsSYezLg');
                #$twitter->setOAuthTokenSecret('6ylRwg5UnhRLqH0t1ClpIFMY1y6f7ctbOZXyWElyW0w');
                #$twitter->setOAuthToken('JCNKbcxMJPPnQOzb7ykEKmkGF0cYO9wGxCe6Pe8nEq0');
                #$twitter->setOAuthTokenSecret('R2t1pWZk6Z2dAsN4QcRZOg4XDClW0ZPFgn2TTVCE');


		// return self::actionResult( "twitter.tpl", array() ); 
		return array(
			"tpl" 	 => "twitter/twitter.tpl",
			"params" => array( 
				"twitter" => $twitter
			)
		);
	}
}

return "twitterModule";
