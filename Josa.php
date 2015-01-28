<?php
/**
  * Extension:Josa
  * Author: JuneHyeon Bae <devunt@gmail.com>
  * Original implementation by Park Shinjo <peremen@gmail.com>
  * Improve Extension:Hanp (bug: T15712)
  * License: MIT License
*/

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a vaild entry point' );
}

$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'Josa',
	'author' => 'JuneHyeon Bae (devunt)',
	'url' => 'https://www.mediawiki.org/wiki/Extension:Josa',
	'description' => 'Automates some part of Korean transliteration process.',
	'version'  => '0.1',
);
$wgHooks['ParserFirstCallInit'][] = 'Josa::InitParserFunction';
$wgExtensionMessagesFiles['Josa'] = dirname( __FILE__ ) . '/Josa.i18n.php';
$wgAutoloadClasses['Josa'] = dirname( __FILE__ ) . '/Josa.class.php';
