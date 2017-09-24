<?php
/*
 * ==========================================================================
 * Titel                  : index.php
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

define( 'ZENWIKI_VERSION', '0.03' );

ini_set('short_open_tag', 1);
date_default_timezone_set('Europe/Berlin');

#var_dump( $_ENV );

// TODO requirements
// install (wenn keine settings ini)

include_once "autoloader.php";

if( !Configuration::load( $_SERVER["SERVER_NAME"] . ".ini" ) ) {
	echo "foo";
	Configuration::load( "settings.ini" );
}

$basepath = Configuration::get( "wiki", "basepath", "wiki" );

print_r( Configuration::get( "design", "csslist", array() ) );

FileDB::setBasepath( $basepath );
UserManager::setBasepath(  $basepath . "/users" );
MarkupManager::setImagePath(  $basepath . "/images" );

PluginManager::loadModules();

UserManager::init();
Dispatcher::run();
