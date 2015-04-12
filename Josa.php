<?php
/**
  * Extension:Josa
  * Author: JuneHyeon Bae <devunt@gmail.com>
  * Original implementation by Park Shinjo <peremen@gmail.com>
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
	'descriptionmsg' => 'josa-desc',
	'version'  => '0.1.1',
	'license-name' => 'MIT',
);
$wgHooks['ParserFirstCallInit'][] = 'Josa::onParserFirstCallInit';

// Load i18n files
$wgMessagesDirs['Josa'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['JosaMagic'] = __DIR__ . '/Josa.i18n.magic.php';

$wgAutoloadClasses['Josa'] = __DIR__ . '/Josa.class.php';
