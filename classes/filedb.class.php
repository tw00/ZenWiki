<?php
/*
 * ==========================================================================
 * Titel                  : Debug Helper
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

/* TODO: 
 * - win32 compability
 * 
 */

/*
 * Class definition
 */
class FileDB
{
		private static $_basepath  = "/";
		private static $_imagepath = "";
		private static $_pagespath = "";
		private static $_userpath  = ""; 
		private static $_tmppath   = ""; 

		/* no instances */
		private function __construct() { } 

		public static function setBasepath( $path )
		{
			self::$_basepath  = $path;
			self::$_imagepath = $path . '/images';
			self::$_pagespath = $path . '/pages';
			self::$_userpath  = $path . '/users';
			self::$_tmppath   = $path . '/tmp';
		}

		public static function pageExists( $pagepath )
		{
            $org = $pagepath;

            // HACK
            $pathArray = explode( '/', $pagepath );
            foreach( $pathArray as &$element ) $element = urlencode( $element );
            $pagepath = implode( '/', $pathArray );
            // HACK

			$pagedir = self::$_pagespath . '/' . $pagepath;
			$pagedir2 = self::$_pagespath . '/' . $org;

			if ( !file_exists( $pagedir ) and !file_exists( $pagedir2 ) )
				return false;

			return true;
		}

		private static function _cleanPath( $path, $asArray = false )
		{
			/* Remove trailing slash */
			$pathArray = explode( "/", $path );
			foreach( $pathArray as $key => $pathElement ) {
				if( $pathElement == "" ) {
					unset( $pathArray[ $key ] );
				}
			}

			if( $asArray ) return $pathArray;
			
			return implode( "/", $pathArray );
		}

		public static function readPage( $pagepath, $isFolder = false )
		{
			$content  = array();
			$pagepath = self::_cleanPath( $pagepath );
			$pagedir  = self::$_pagespath . '/' . $pagepath;
			$pagename = basename( $pagepath );
			$articlepath  = $pagedir . '/text';
			$editspath    = $pagedir . '/edits';

			if( !self::pageExists( $pagepath ) ) {
				throw new Exception( "Page dir $pagedir not found" );
			}

			if( !$isFolder and !file_exists( $articlepath ) ) {
				throw new Exception( "Article $articlepath not found" );
			}

            // HACK
            $pagepath = urldecode( $pagepath );

			$content[ 'pagename' ] = ( $isFolder ? "Folder: " : '' ) . $pagepath;
			$content[ 'content'  ] = $isFolder ? "" : file_get_contents( $articlepath  );
			$content[ 'filesize' ] = $isFolder ? 0  : filesize( $articlepath  );
			$content[ 'lastedit' ] = $isFolder ? 0  : filemtime( $articlepath  );
			$content[ 'created'  ] = $isFolder ? 0  : filectime( $articlepath  );
			$content[ 'pagedir'  ] = $pagedir;
			$content[ 'editors'  ] = array();
			$content[ 'isfolder' ] = $isFolder;
			#$content[ 'view'     ] = rand( 1, 100 ); // HACK

			$content[ 'filelist' ] = array();
			$content[ 'subpages' ] = array();

			$pathArray = self::_cleanPath( $pagepath, true );

			$content[ 'breadcrumb' ] = $pathArray;
			$content[ 'article'    ] = end( $pathArray );


			foreach ( glob( $pagedir . "/*" ) as $filename )
			{
				if( is_dir( $filename  ) ) {

					$filePathArray = explode( '/', $filename );
					$pagePathArray = explode( '/', self::$_pagespath );

					$commonPathArray = array_diff_assoc( $filePathArray, $pagePathArray );

					$content[ 'subpages' ][] = array( 
						"filename" => $filename,
						"pagename" => implode( '/', $commonPathArray )
					);
					// preg_replace( '/^'.self::$_pagespath.'/', "", trim( $filename) ) ); // HACK
				} else {
					$content[ 'filelist' ][] = $filename;
#			    	$content['filelist'][] = array(
#						'filename' => $filename,
#						'filesize' => filesize($filename) );
				}
			}

            if( !$isFolder ) {
		    	$data = trim( file_get_contents( $editspath ) );
	    		$records = preg_split('/[\r\n]+/', $data,  -1, PREG_SPLIT_NO_EMPTY);
    			foreach( $records as $record ) {
				    $dd = explode( ":", $record );
			    	$content[ 'editors' ][ $dd[0] ] = $dd[1];
			    }
            }

			$content[ 'revisionlist' ] = array();
			#$content[ 'draftlist' ] = array();
			$content[ 'commentlist' ] = array();

			foreach ( $content[ 'filelist' ] as $filepath )
			{
				$filename = basename( $filepath );

				/* Revisions */
				if ( preg_match( "/^rev([0-9]+)$/",
								 $filename, $matches ) )
				{
					#$content[ 'revisionlist' ][ end( $matches ) ] = $filepath;
					$content[ 'revisionlist' ][ end( $matches ) ] = array(
						"filename" => $filepath,
						"lastedit" => filemtime( $filepath  ) );

#					$content[ 'revisionlist' ][] = array(
#						'revision' => end( $matches ),
#						'filename' => $filename );
				}

				/* Drafts */
/*				if ( preg_match( "/$pagename\.([a-zA-Z0-9]+)\.draft$/",
							     $filename, $matches ) )
				{
					$content[ 'draftlist' ][] = end( $matches );
				}*/

				/* Comments */
				if ( preg_match( '/^comment([0-9]+)$/', $filename, $matches ) )
				{
					$commentcontent = file_get_contents( $filepath );
					$lines = preg_split('/[\r\n]+/', $commentcontent,  -1, PREG_SPLIT_NO_EMPTY);
					$commentuser = trim( $lines[0] );
					unset( $lines[0] );
					$commentcontent = implode( "\n", $lines );

					$content[ 'commentlist' ][ end( $matches ) ] = array(
						"filename" => $filepath,
						"lastedit" => filemtime( $filepath  ),
						"user"     => $commentuser,
						"content"  => $commentcontent );

				}
			}

			$content[ 'revisionlist' ][] = array(
				'filename' => $content[ 'pagedir'  ] . "/text", // HACK?
				'lastedit' => $content[ 'lastedit' ]
			);


#			unset(  $content[ 'filelist' ] );

//			natsort( &$content[ 'revisionlist' ] );
#			array_multisort( $content[ 'revisionlist' ], SORT_DESC, $edition, SORT_ASC, $data);
// TODO
			$content[ 'revisionlist' ] = array_reverse( $content[ 'revisionlist' ] );

			natsort( $content[ 'commentlist' ] );

#			echo "<pre>"; print_r( $content ); die( );

			return $content;
		}

        public static function readFolder( $path )
        {
            return self::readPage( $path, true );
        }

		public static function readPageContent( $path  ) {}
		public static function readCommentContent( $path  )
		{}

		public static function createDiff( $rev1, $rev2 )
		{
			// TODO security
			$output = false;
			$text = "";

			exec( "diff -ruN "
				  . escapeshellarg( $rev1 ) . " "
				  . escapeshellarg( $rev2 ),
				  $output );

			if( count( $output ) == 0 ) return false;

			foreach( array_reverse( $output ) as $key => $line ) {
				     if( count( $output ) == 1 ) { $line  = '<div class="head">' . $line. "\n"; }
				else if( count( $output ) == 2 ) { $line .= "</div>"; }
				else if( $line{0} == '@' ) 		 { $line  = '<div class="sec">' . $line. '</div>'; }
				else if( $line{0} == '+' ) 	   	 { $line  = '<div class="add">' . $line. '</div>'; }
				else if( $line{0} == '-' ) 	     { $line  = '<div class="sub">' . $line. '</div>'; }
				else 						     { $line .= "\n"; }
				
				$text = $line . $text;
				unset( $output[ $key ] );
			}

			return $text; 
		}

		public static function createNewVersion( $page, $content )
		{
			if( !$content ) return false; // EXCEPTION

            // Strip slashes if Magic Quotes GPC is enabled
            $content = ( get_magic_quotes_gpc() == 0 ) ? $content : stripslashes( $content );

			#$pagefile = self::$_basepath . "/" . $page . "/" . $page . ".text"; // HACK
			$pagedir = self::$_basepath . '/pages/' . self::_cleanPath( $page ) . '/';
			$pagefile = $pagedir . 'text';
			$editsfile = $pagedir . 'edits';

			dx( $pagefile );

			if( !file_exists( $pagefile ) ) return false; // EXCEPTION

			$i = 0;
			$overflow = 99999;
			do {
				$revfile = $pagedir . "rev" . ($i++); // HACK
				dx( $revfile );
			} while( file_exists( $revfile ) and $i <= $overflow );

			if( $i == $overflow ) return false; // EXCEPTION
			
			dx( $content );
			dx( $pagefile, "pagefile" );
			dx( $revfile, "revfile" );

			if( !rename( $pagefile, $revfile ) ) return false; // EXCEPTION


			$edits = "\n" . "rev" . $i . ":" . userManager::currentUser();

			file_put_contents( $editsfile, $edits, LOCK_EX | FILE_APPEND );
			file_put_contents( $pagefile, $content, LOCK_EX );

			return true;
		}

		/*
		$fp = fopen('yourfile.txt', 'a') ; 
		if (flock($fp, LOCK_EX)) { 
				ftruncate($fp, 0) ; // <-- this will erase the contents such as 'w+' 
				fputs($fp, 'test string') ; 
				flock($fp, LOCK_UN) ; 
		} 
		fclose($fp) ; 
		*/

		public static function createArticle( $page )
		{
            // TODO: Remove / at Beginning
            // Exceptiosn
            // Check for invalid charcters
            
			#$pagedir = self::$_basepath . '/pages/' . self::_cleanPath( $page ) . '/';

			if( file_exists( $pagedir ) ) return false; // Exception
			
			$pagedir = self::$_basepath . '/pages/';

			foreach( explode( '/', self::_cleanPath( $page ) ) as $pathElement ) {
				$pagedir .= $pathElement . '/';
				if( !file_exists( $pagedir ) ) {
					mkdir( $pagedir );
				}
			}

			if( !file_exists( $pagedir ) ) return false; // Exception

			#$result = mkdir( $pagedir );
			#if( !$result ) return false;

			$pagefile = $pagedir . 'text';
			$editsfile = $pagedir . 'edits';

			dx( $pagefile );
			dx( $editsfile );

			$edits = "rev0:" . userManager::currentUser();
			$text = "Neuer Artikel von " . userManager::currentUser();

			file_put_contents( $editsfile, $edits, LOCK_EX );
			file_put_contents( $pagefile,  $text,  LOCK_EX );

			return true;
		}

		public static function recentEdits()
		{
			$output = false;
			$timelist = array();
			$meta = array();

			exec( "find -L ".self::$_pagespath."|grep -v '.svn'|grep '.text'|head -n 100", $output ); // TODO HACK HACK HACK

			if( $output ) {
				foreach( $output as $file ) {
					$pathArray = explode( '/', $file );
					unset( $pathArray[ count( $pathArray ) - 1] );
					unset( $pathArray[0] );
					unset( $pathArray[1] );

					#unset( $pathArray[1] ); // HACK?
					$url = implode( '/', $pathArray );

					// HACK
					$user = false;
					$editfile = self::$_pagespath . '/' . $url . '/edits';
					if( file_exists( $editfile ) ) {
						$edits = file_get_contents( $editfile );
						$editsArray = explode( chr(10), $edits );

						foreach( $editsArray as $key => $element )				// TODO PHP funk
							if( !$element ) unset( $editsArray[ $key ] );

						$edits = end( $editsArray );
						# $edits = substr( $edits, 0, strpos( $edits, chr(10) ) );

						if( $edits ) {
							$edits = explode( ':', $edits );
						}
						if( $edits[1] ) {
							$user = $edits[1];
						}
					}

					$mtime = filemtime( $file );
					if( !$mtime ) $mtime = 0;

                    // öäü-HACK
                    $url = urldecode( $url );

					$timelist[] = $mtime;
					$meta[] = array(
						"file" => $file,
						"url"  => $url,
						"user" => $user
					);
				}
			}
			array_multisort( $timelist, SORT_DESC, $meta );

			foreach( $meta as $key => &$metaelement )
				$metaelement = array_merge( $metaelement, array( 'time' => $timelist[ $key ] ) );

			return $meta;
		}

		public static function createIndex()
		{
#			exec( "find wiki_alt/ -name 'text'|sort", $result ); // TODO HACK HACK HACK
			exec( "find -L ".self::$_pagespath." -type d|grep -v \.svn|sort", $result ); // TODO HACK HACK HACK
			$arr = array();
			foreach( $result as $filename ) {
				$r = &$arr;
				foreach( explode( "/", $filename ) as $pathElement ) {
					if( $pathElement and !isset( $r[ $pathElement ] ) ) {
						if( strstr( $pathElement, ".text" ) ) {
							$r[ $pathElement ] = $filename;
						} else {
							$r[ $pathElement ] = array();
						}
					}

					$r = &$r[ $pathElement ];
				}
			}
			return $arr;
		}

		private static function _createPath( $path, $file = "", $absolute = true )
		{
		}
}
