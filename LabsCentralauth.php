<?php
/**
 * LabsCentralauth - Create a specialpage for CentralAuth-project
 *
 * To activate this extension, add the following into your LocalSettings.php file:
 * require_once("$IP/extensions/LabsCentralauth/LabsCentralauth.php");
 *
 * @file
 * @ingroup Extensions
 * @author Jan Luca <jan@toolserver.org>
 * @version 1.0
 * @link http://centralauth.wmflabs.org/index.php/Extension Documentation
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported or later
 */

/**
 * Protect against register_globals vulnerabilities.
 * This line must be present before any global variable is referenced.
 */

// Die the extension, if not MediaWiki is used
if ( !defined( 'MEDIAWIKI' ) ) {
	echo( "This is an extension to the MediaWiki package and cannot be run standalone.\n" );
	die( -1 );
}

// Extension credits that will show up on Special:Version
$wgExtensionCredits['specialpage'][] = array(
	'name'           => 'LabsCentralauth',
	'version'        => '1.0',
	'path'           => __FILE__,
	'author'         => 'Jan Luca',
	'url'            => 'http://centralauth.wmflabs.org/index.php/Extension',
	'descriptionmsg' => 'labscentralauth-desc'
);

// Config
$wgLabsCentralauthRoots = array( );
$wgLabsCentralauthGerrit = 'gerrit.wikimedia.org';
$wgLabsCentralauthGerritProject = 'mediawiki/extensions/CentralAuth';
$wgLabsCentralauthGerritCentralAuthUrl = 'https://'. $wgLabsCentralauthGerrit .'/r/p/mediawiki/extensions/CentralAuth.git';
$wgLabsCentralauthUser = ''; // Gerrit user, you must set this in LocalSettings.php
$wgLabsCentralauthKey = ''; // Path to Gerrit private key, you must set this in LocalSettings.php

// New right: labscentralauth-admin
$wgGroupPermissions['sysop']['labscentralauth-admin'] = true;
$wgGroupPermissions['*']['labscentralauth-admin'] = false;
$wgAvailableRights[] = 'labscentralauth-admin';

// New right: labscentralauth-use-review
$wgGroupPermissions['user']['labscentralauth-use-review'] = true;
$wgGroupPermissions['*']['labscentralauth-use-review'] = false;
$wgAvailableRights[] = 'labscentralauth-use-review';

// New right: labscentralauth-use-dev
$wgGroupPermissions['user']['labscentralauth-use-dev'] = true;
$wgGroupPermissions['*']['labscentralauth-use-dev'] = false;
$wgAvailableRights[] = 'labscentralauth-use-dev';

$dir = __DIR__ . '/';

// Infomation about the Special Page "LabsCentralauth"
$wgAutoloadClasses['LabsCentralauth'] = $dir . 'LabsCentralauth_body.php'; # Tell MediaWiki to load the extension body.
$wgExtensionMessagesFiles['LabsCentralauth'] = $dir . 'LabsCentralauth.i18n.php';
$wgExtensionMessagesFiles['LabsCentralauthAlias'] = $dir . 'LabsCentralauth.alias.php';
$wgSpecialPages['LabsCentralauth'] = 'LabsCentralauth'; # Let MediaWiki know about your new special page.
$wgSpecialPageGroups['LabsCentralauth'] = 'other';

// Log
#$wgLogTypes[] = 'labscentralauth';
#$wgLogActionsHandlers['labscentralauth/*'] = 'LogFormatter';
#$wgLogNames['labscentralauth'] = 'labscentralauth-logpage';
#$wgLogHeaders['labscentralauth'] = 'labscentralauth-logpagetext';

