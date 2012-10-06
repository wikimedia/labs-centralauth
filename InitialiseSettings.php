<?PHP

$wgConf->settings = array(

'wgServer' => array(
    // if you want to allow also usage of https, just use '//localhost'
    // and set 'http://localhost' at 'wgCanonicalServer'
    'default' => '//centralauth.wmflabs.org',
	'enreview1wiki' => '//review1.centralauth.wmflabs.org',
),

'wgCanonicalServer' => array(
    'default' => 'http://centralauth.wmflabs.org',
	'enreview1wiki' => 'http://review1.centralauth.wmflabs.org',
),

'wgScriptPath' => array(
    'default' => '',
),

'wgScriptExtension' => array(
    'default' => '.php',
),

#'wgArticlePath' => array(
#    'default' => '/wiki',
#),

'wgSitename' => array(
    'default' => 'CentralAuth',
	'mainwiki' => 'CentralAuth Main-Wiki',
	'review1wiki' => 'CentralAuth Review 1-Wiki',
),

'wgMetaNamespace' => array(
    'default' => 'CentralAuth',
),

'wgLanguageCode' => array(
    'default' => 'en',
),

'wgLocalInterwiki' => array(
    'default' => 'en',
),

'wgEnableEmail' => array(
    'default' => true,
),

'wgEnableUserEmail' => array(
    'default' => true,
),

'wgEmergencyContact' => array(
    'mainwiki' => 'jan@toolserver.org',
	'review1wiki' => 'jan@toolserver.org',
),

'wgPasswordSender' => array(
    'mainwiki' => 'wiki@centralauth.wmflabs.org',
	'review1wiki' => 'wiki@review1.centralauth.wmflabs.org',
),

'wgEnotifUserTalk' => array(
    'default' => true,
),

'wgEnotifWatchlist' => array(
    'default' => false,
),

'wgEmailAuthentication' => array(
    'default' => true,
),

'wgDBtype' => array(
    'default' => 'mysql',
),

'wgDBserver' => array(
    'default' => 'centralauth-mysql.pmtpa.wmflabs',
),

'wgDBprefix' => array(
    'default' => "",
),

'wgDBname' => array(),

'wgDBuser' => array(),

'wgDBpassword' => array(),

'wgSecretKey' => array(),

'wgUpgradeKey' => array(),

'wgDBTableOptions' => array(
    'default' => "ENGINE=InnoDB, DEFAULT CHARSET=utf8",
),

'wgDBmysql5' => array(
    'default' => false,
),

'wgMainCacheType' => array(
    'default' => CACHE_MEMCACHED,
),

'wgMemCachedServers' => array(
    'default' => array( 'centralauth-daemons.pmtpa.wmflabs:11211' ),
),

'wgEnableUploads' => array(
    'default' => true,
),

'wgUseImageMagick' => array(
    'default' => true,
),

'wgImageMagickConvertCommand' => array(
    'default' => "/usr/bin/convert",
),

'wgUseInstantCommons' => array(
    'default' => false,
),

'wgShellLocale' => array(
    'default' => "en_US.utf8",
),

'wgDefaultSkin' => array(
    'default' => 'vector',
),

'wgRightsPage' => array(
    'default' => "",
),

'wgRightsUrl' => array(
    'default' => 'http://creativecommons.org/licenses/by-sa/3.0/',
),

'wgRightsText' => array(
    'default' => 'Creative Commons Attribution-Share Alike 3.0 Unported',
),

#'wgRightsIcon' => array(
#    'default' => 'http://creativecommons.org/images/public/somerights20.png',
#),

'wgDiff3' => array(
    'default' => '/usr/bin/diff3',
),

'wgResourceLoaderMaxQueryLength' => array(
    'default' => -1,
),

'+wgGroupPermissions' => array(
	'default' => array( '*' => array(
		'edit' => false,
	) )
)
);

$wgConf->settings['wgStylePath'] =
    array( 'default' => $wgConf->settings['wgScriptPath']['default'].'/skins' );

$wgConf->settings['wgLogo'] =
    array( 'default' => $wgConf->settings['wgStylePath']['default'].'/common/images/wiki.png' );

$wgConf->settings['wgRightsIcon'] =
    array( 'default' => $wgConf->settings['wgStylePath']['default'].'/common/images/cc-by-sa.png' );