<?php
/*
 * ==========================================================================
 * Titel                  : run.php (TODO: hier via svn generate)
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

# TODO: Warning: run from root

include_once "autoloader.php";

function print_usage()
{
    echo "Usage: ", $argv[0], " <test>\n";
    echo "list, all\n";
}

if( $argc < 2 ) {
    print_usage();
    exit;
}

if( !Configuration::load( $_ENV["SERVER_NAME"] . ".ini" ) ) {
	Configuration::load( "settings.ini" );
}

$basepath = Configuration::get( "wiki", "basepath", "wiki" );

$test = $argv[1];

echo "* Running test <$test>\n";
echo "* Basepath: $basepath\n";

include( "test_" . $test . ".php" );

