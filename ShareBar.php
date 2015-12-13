<?php
/**
 * WikiRights ShareBar extension - provides sharing tools
 * @author Dror S. [FFS]
 * @copyright © 2014, 2015 Dror S. & Kol-Zchut Ltd.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 3.0
 *
 * @todo Parameters for share urls!
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

/* Setup */
$wgExtensionCredits['parserhook'][] = array(
	'path'           => __FILE__,
	'name'           => 'WikiRights ShareBar',
	'author'         => 'Dror S. [FFS] ([http://www.kolzchut.org.il Kol-Zchut])',
	'version'        => '0.1.0',
	'url'            => 'http://www.kolzchut.org.il/he/כל-זכות:Extensions/ShareBar',
	'descriptionmsg' => 'ext-sharebar-desc',
	'license-name' => 'GPL-3.0' // GNU General Public License v3.0
);

// @todo add a "valid services" const to evaluate $egShareBarEnabledServices against

require_once ( 'ShareBar.settings.php' );

$wgAutoloadClasses['ExtShareBarHooks'] = __DIR__ . '/ShareBar.hooks.php';
$wgAutoloadClasses['ExtShareBar'] = __DIR__ . '/ShareBar_body.php';
$wgExtensionMessagesFiles['ShareBar'] = __DIR__ . '/ShareBar.i18n.php';
$wgExtensionMessagesFiles['ShareBarMagic'] = __DIR__ . '/ShareBar.i18n.magic.php';

$wgHooks['ParserFirstCallInit'][] = 'ExtShareBarHooks::onParserFirstCallInit';
$wgHooks['ResourceLoaderGetConfigVars'][] = 'ExtShareBarHooks::onResourceLoaderGetConfigVars';
$wgHooks['BeforePageDisplay'][] = 'ExtShareBarHooks::onBeforePageDisplay';
$wgHooks['SkinHelenaSidebar::Buttons'][] = 'ExtShareBarHooks::onSkinHelenaSidebarButtons';


$wrShareBarResourceTemplate = array(
	'localBasePath' => __DIR__ . '/modules',
	'remoteExtPath' => 'WikiRights/ShareBar/modules'
);

/*
$wgResourceModules['ext.wrSharing.addThis'] = $wrSharingResourceTemplate + array(
	'scripts' => 'ext.wrSharing.addThis.js',
	'styles' => 'ext.wrSharing.addThis.css',
	'messages' => array(
		'wrsharing-facebook',
		'wrsharing-twitter',
		'wrsharing-email',
		'wrsharing-print',
		'wrsharing-twitter-msg',
	),
);
*/
$wgResourceModules['ext.wr.ShareBar'] = $wrShareBarResourceTemplate + array(
	'styles' => 'ext.shareBar.less',
	'position' => 'top',
	'dependencies' => array( 'skins.helena.bootstrap', 'skins.helena.bootstrapOverride' ),
);

$wgResourceModules['ext.wr.ShareBar.js'] = $wrShareBarResourceTemplate + array(
	'scripts' => 'ext.shareBar.js',
	'position' => 'bottom',
	'messages' => array(
	    'ext-sharebar-loading',
	    //'ext-sharebar-feedback-form-title'
	),
	'dependencies' => array( 'skins.helena.bootstrap.js' ),
);
