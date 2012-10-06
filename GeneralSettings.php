<?PHP
$sharedextension = "/data/project/shared/extensions";
$settingspath = "/data/project/shared/wiki_config";

require_once "$sharedextension/Nuke/Nuke.php";
require_once "$sharedextension/SpamBlacklist/SpamBlacklist.php";
require_once "$sharedextension/ConfirmEdit/ConfirmEdit.php";
require_once("$sharedextension/ConfirmEdit/FancyCaptcha.php");
$wgCaptchaClass = 'FancyCaptcha';
$wgCaptchaDirectory = "$sharedextension/ConfirmEdit/FancyCaptcha";
$wgCaptchaDirectoryLevels = 0;
require_once("$sharedextension/ConfirmEdit/FancyCaptcha/FancyCaptchaSecret.php");

// CentralAuth
require_once "$centralauthpath/CentralAuth.php";
$wgCentralAuthAutoNew = true;
$wgCentralAuthAutoMigrate = true;
$wgCentralAuthCookies = true;
$wgCentralAuthCookieDomain = 'centralauth.wmflabs.org';
//$wgCentralAuthLoginIcon = $settingspath . "/20p.conf";

$wgMainCacheType    = CACHE_MEMCACHED;
$wgMemCachedServers = array( 'centralauth-daemons.pmtpa.wmflabs:11211' );

//$wgSharedDB     = 'centralauth'; // or whatever database you use for central data
//$wgSharedTables = array( 'objectcache' ); // remember to copy the table structure's to the central database first
//$wgMainCacheType = CACHE_DB; // Tell mediawiki to use objectcache database instead of nothing

require_once "$settingspath/DatabaseList.php";

$wgConf->wikis = $wgLocalDatabases;
$wgConf->suffixes = array( 'mainwiki', 'review1wiki', 'review2wiki', 'review3wiki', 'devwiki' );
$wgConf->localVHosts = array( 'localhost' );

require_once "$settingspath/InitialiseSettings.php";