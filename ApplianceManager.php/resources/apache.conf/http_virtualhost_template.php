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
?>

ServerTokens Prod
ServerSignature Off


<VirtualHost <?php echo $HTTP_VHOST_ADDR . ":" . $HTTP_VHOST_PORT?>>
       ServerName <?php echo "$HTTP_VHOST_NAME\n"?>
       RequestHeader setifempty X-Forwarded-Proto "http"


       <?php
       $publicServerPrefix=constructURL("http", $HTTP_VHOST_NAME, $HTTP_VHOST_PORT, "");
       ?>


       SetEnv publicServerProtocol http://
       #SetEnv publicServerName <?php echo "$HTTP_VHOST_NAME\n"?>
       SetEnvIf Host "(.*)" publicServerName=$1
       SetEnv publicServerPort <?php  echo "$HTTP_VHOST_PORT\n"?>
       SetEnv publicServerTopDomain <?php  echo "$HTTP_VHOST_TOP_DOMAIN\n"?>
       SetEnv publicServerPrefix <?php echo $publicServerPrefix . "\n"?>
        
        


       CustomLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.access.log combined
       ErrorLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.error.log
       LogLevel warn

       SSLProxyEngine on
       SSLProxyVerify none
       SSLProxyCheckPeerName off
       SSLProxyCheckPeerCN off
       SSLProxyCheckPeerExpire off
       Options -Indexes


       RewriteEngine on
#       RewriteLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/rewrite.log
#       RewriteLogLevel 0


	SetEnvIf Authorization "(.*)" ORGAUTH=$1
    RequestHeader unset Authorization

    ProxyTimeout 120 
    DocumentRoot /var/www/local/empty	
    Include <?php echo runtimeApplianceConfigLocation?>/applianceManagerServices-node-<?php echo $NODE_NAME?>.endpoints
    <?php 
    $ADDITIONAL_CONFIGURATION=subsituteVar($ADDITIONAL_CONFIGURATION, "publicServerProtocol", "http://");
    $ADDITIONAL_CONFIGURATION=subsituteVar($ADDITIONAL_CONFIGURATION, "publicServerName", $HTTP_VHOST_NAME);
    $ADDITIONAL_CONFIGURATION=subsituteVar($ADDITIONAL_CONFIGURATION, "publicServerPort", $HTTP_VHOST_PORT);
    $ADDITIONAL_CONFIGURATION=subsituteVar($ADDITIONAL_CONFIGURATION, "publicServerTopDomain", $HTTP_VHOST_TOP_DOMAIN);
    $ADDITIONAL_CONFIGURATION=subsituteVar($ADDITIONAL_CONFIGURATION, "publicServerPrefix", $publicServerPrefix);
    echo $ADDITIONAL_CONFIGURATION . "\n"?>

    Header set Server OSA-<?php echo version?>

</VirtualHost>
