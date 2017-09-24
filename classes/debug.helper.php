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

/* Shortcuts
 * 
 * o   = Output
 * d   = Dump
 * dx  = Dump (extended)
 * dxx = Dump direct (extended) 
 * i   = Info
 * e   = Error
 * v   = Trigger Event
 */

function o( $str )              { DebugManager::out( $str ); }
function i( $str )			    { DebugManager::info( $str ); }
function e( $str )     			{ DebugManager::error( $str ); }
function v( $name, $info )      { DebugManager::event( $name, $info ); }
function d( $str )              { DebugManager::dump( $str ); }
function dx( $str, $name = 0, $fold = false ) { DebugManager::dump_ex( $str, $name, $fold ); }

function dxx( $str, $name = 0)
{
		$method = DebugManager::$output;
		DebugManager::$output = DebugManager::DIRECT;
		dx( $str, $name );
		DebugManager::$output = $method;
}

function _die( $str )
{
		echo( $str );
		throw new Exception( "dead!" );
}

/*
 * Class definition
 */
class DebugManager
{
		/*
		 * Class constants
		 */
		const FLASH  = 1;   // Save ouput to flash
		const DIRECT = 2;   // Print output directly
		const FILE   = 3;   // Print output file
		const CLI    = 4;   // Print ouput to console using CLI

		/*
		 * Static variables
		 */    
		static public  $output   = self::FLASH;
		static private $instance = null;
		static private $flash    = '';


		/*
		 * Private members
		 */    
		private $events          = array();
		private $monitoring_list = array();
		private $observer_list   = array();
		private $template_list   = array();
		private $buffer          = "";

		/*
		 * Singleton
		 */
		private function __construct() {}

		public static function instance()
		{
				if( !self::$instance )
				{
						self::$instance = new DebugManager();
				}
				return self::$instance;
		}


		/*
		* Private static methods
		*/     
		private static function _echo( $str )
		{
				switch( self::$output )
				{
						case self::FLASH:
						self::$flash .= $str;
						break;

						case self::FILE:
						//TODO
						break;

						case self::CLI:
						//TODO
						break;

						case self::DIRECT:
						default:
						echo $str;
						break;                        
				}
		}


		/*
		* Public static methods
		*/        
		public static function flush( $return = false )
		{
				if( $return ) return self::$flash;
				else           print self::$flash;
		}

		public static function flushTemplates( $return = false /*TODO*/ )
		{
				$dm = self::instance();
				$dm->template_flush();        
		}

		public static function out( $str )
		{
				$dm = self::instance();
				$dm->text_out( $str );
		}

		public static function event( $name, $info )
		{
				$dm = self::instance();
				$dm->event_occur( $name, $info );
		}    

		public function debug( $on = true )
		{
				// ..
		}
		
		/*
		* Template functions
		*/
		static function template( $name, $override = false )
		{
				$dm = self::instance();
				$dm->template_add( $name, $override );
		}    
			
		public function template_add( $name, $override )
		{
				$this->template_list[] = array( 'name' => $name, 'override' => $override );
		}

		public function template_flush()
		{
				if( !is_array( $this->template_list ) ) return;

				$count = 0;

				print "<table class='template-list'>";
				print "<tr><th>#</th><th>Filename</th><th>Override</th></tr>";
				foreach( $this->template_list as $template )
				{
						$style = $template['override'] ? 
									"style='color:red'" : '';

						echo "<tr>";
						echo "<td ", $style, ">", ++$count, "</td>";
						echo "<td ", $style, ">", $template['name'], "</td>";
						echo "<td ", $style, ">", $template['override'] ? "yes" : "no", "</td>";
						echo "</tr>";
				}
				print "</table>";        
		}


		/*
		* Event management
		*/          
		public function event_register( $name, $desc )
		{
				$this->events[ $name ] = array();
				$this->events[ $name ][ 'desc' ]  = $desc;
				$this->events[ $name ][ 'count' ] = 0;
				$this->events[ $name ][ 'infos' ] = array();        
		}

		public function event_occur( $name, $info )
		{
				if( !array_key_exists($name, $this->events) )
				throw new Exception( "Event doesn't exists!" );

				$this->events[ $name ][ 'count' ] += 1;
				$this->events[ $name ][ 'infos' ][] = $info;
		}


		public function event_print_map()
		{
				$this->dump( $this->events );
		}


		/*
		* Debug Output
		*/
		public static function info( $str, $css_class = "debug-list-item" )
		{
				self::_echo( "<ul><li class='$css_class'><b>[i]</b> $str</li></ul>" );
		}

		public static function error( $str, $css_class = "debug-list-item error" )
		{
				self::_echo( "<ul><li class='$css_class'><b>[E]</b> $str</li></ul>" );
		}

		public static function text_out( $str, $plain = false )
		{
				$new_line = ( $plain ? "<br />\n" : "\n" );
				self::_echo( "[$str]".$new_line );
		}

		public static function dump( $str )
		{
				self::_echo( "<pre>" );
				self::_echo( print_r( $str, true ) );
				self::_echo( "</pre>" );
		}

		public static function dump_ex( $obj, $name = 0, $foldable = false, $css_class = "debug-box" )
		{
				$str 	 = print_r( $obj, true );
				$id 	 = md5( serialize( $obj ) . time() );
				$display = "block";
				
				self::_echo( "<div class='$css_class'>" );
				if( $name !== 0 )
				{
						self::_echo( "<div class='$css_class-heading'>" );
						self::_echo( "<span>" . $name . "</span>" );
						if( $foldable ) {
							self::_echo( "<a href=\"#\" onClick=\"$( '$id' ).style.display = 'block'; return false;\">unfold</a>" );
							$display = "none";
						}
						self::_echo( "</div>" );
				}
				self::_echo( "<div class='$css_class-content' id='$id' style='display:$display'>" );
				self::_echo( htmlspecialchars( $str ) );
				self::_echo( "</div></div>" );    	
		}

		public static function dump_table_schema( $schema, $title = 0 )
		{
				$str = "";
				foreach( $schema as $key => $field )
				{
						$ts = "\t";
						$c = 3 - floor( (strlen( $key ))/8 );
						for($i = 0; $i < $c; $i++ ) $ts .= "\t";
						$str .= $key.$ts.$field['Type']."\t\t".$field['Key']."\n";
				}
				$this->dump_ex( $str, $title );        
		}

		public static function dump_class_methods()    
		{

		}
		public static function start( $name ) {}
		public static function stop( $name ) {}
		public static function print_performance_map() {}        

		/*
		* Observer methods (TODO)
		*/
		public static function observe( &$varname )
		{
			$this->observer_list[] =& $var;
		}
}
