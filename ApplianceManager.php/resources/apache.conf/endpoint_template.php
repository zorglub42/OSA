<?php
/**
 * Reverse Proxy as a service
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 * 
 * @codingStandardsIgnoreStart
*/
/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : 
 *  ApplianceManager/ApplianceManager.php/resources/apache.conf/endpoint_template.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      .../...
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
require_once '../include/Settings.ini.php'; 
require_once '../include/Constants.php'; 
?>
<Location  <?php echo $FRONT_END?>>

    Include <?php echo runtimeApplianceConfigLocation?>/osa-endpoints-settings.inc	
<?php
if (isset($BASIC_AUTH_TOKEN) && $BASIC_AUTH_TOKEN != "" ) {
        echo "\tRequestHeader add Authorization \"Basic $BASIC_AUTH_TOKEN\"\n";
}
if (isset($FORWARD_AUTH_TOKEN) && $FORWARD_AUTH_TOKEN  ) {
        echo "\tRequestHeader add Authorization %{ORGAUTH}e\n";
}
if ($USER_AUTHENTICATION_ENABLE=="On") {
    echo "\tAuthBasicAuthoritative Off\n";
    echo "\tOSAAuthoritative On\n";
    echo "\tOSAEnable on\n";

    if ($BASIC_AUTH_ENABLED) {
        echo "\tOSABasicAuthEnable On\n";
        //echo "\tOSAAuthName API access\n";
    }
    if ($COOKIE_AUTH_ENABLED) {
        echo "\tOSACookieAuthEnable On\n";
        echo "\tOSACookieAuthName OSAAuthToken\n";
        echo "\tOSACookieAuthTTL " . authTokenTTL . "\n";
        if (!empty($FRONT_END_TOP_DOMAIN)) {
            echo "\tOSACookieAuthDomain " . $FRONT_END_TOP_DOMAIN . "\n";
        }
        echo "\tOSAServerName " . $PUBLIC_SERVER_PREFIX . "\n";
        
        if ($LOGIN_FORM_URI != "") {
            echo "\tOSACookieAuthLoginForm " . $LOGIN_FORM_URI . "\n";
        }
    }
    if ($GROUP_NAME != "valid-user") {
        echo "\tOSARequire group  " . $GROUP_NAME ."\n";
    } else {
        echo "\tOSARequire valid-user\n";
    }
    if ($ANONYMOUS_ALLOWED) {
        echo "\tOSAAllowAnonymous On\n";
    }
    
    if (!$FORWARD_IDENT) {
        echo "\tRequestHeader unset " . $defaultHeadersName["userName"] . "\n";
    } else {
        echo "\tOSAIdentityHeadersMapping " . $IDENTITY_MAPPING . "\n";
    }
    
} else {
    echo "\tOSAEnable Off\n";
}
    
?>
    
    
    
    OSAResourceName <?php echo "$SERVICE_NAME\n"?>

    OSACheckGlobalQuotas <?php echo "$GLOBAL_QUOTA_ENABLE\n"?>
    OSACheckUserQuotas <?php echo "$USER_QUOTA_ENABLE\n"?>


    OSALogHit <?php echo "$HIT_LOGGING_ENABLE\n"?>
    
    <?php
    $urlParts=getUrlParts($BACK_END);
    echo "ProxyPassReverseCookieDomain " . 
         $urlParts["domain"] . 
         " $FRONT_END_DOMAIN\n";
    if (startsWith($SERVICE_NAME, ADMIN_SERVICE)) {
        echo "	ProxyPassReverseCookiePath /  /\n";
    }
    echo $ADDITIONAL_CONFIGURATION . "\n";
    ?>




    ProxyPassReverse <?php echo "$BACK_END\n"?>
    
</Location>
