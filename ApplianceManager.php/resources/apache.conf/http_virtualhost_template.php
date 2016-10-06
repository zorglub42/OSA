ServerTokens Prod
ServerSignature Off


<VirtualHost <?php echo $HTTP_VHOST_ADDR . ":" . $HTTP_VHOST_PORT?>>
       ServerName <?php echo "$HTTP_VHOST_NAME\n"?>




       SetEnv publicServerProtocol http://
       #SetEnv publicServerName <?php echo "$HTTP_VHOST_NAME\n"?>
       SetEnvIf Host "(.*)" publicServerName=$1
       SetEnv publicServerPort <?php  echo "$HTTP_VHOST_PORT\n"?>
       SetEnv publicServerTopDomain <?php  echo "$HTTP_VHOST_TOP_DOMAIN\n"?>
       SetEnv publicServerPrefix http://<?php echo $HTTP_VHOST_NAME . ":" . $HTTP_VHOST_PORT . "\n"?>
        
        


       CustomLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.access.log combined
       ErrorLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.error.log
       LogLevel warn

       SSLProxyEngine on
       SSLProxyVerify none
       Options -Indexes


       RewriteEngine on
#       RewriteLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/rewrite.log
#       RewriteLogLevel 0


	SetEnvIf Authorization "(.*)" ORGAUTH=$1
       RequestHeader unset Authorization

	   ProxyTimeout 120 
	   DocumentRoot /var/www/local/empty	
       Include <?php echo runtimeApplianceConfigLocation?>/applianceManagerServices-node-<?php echo $NODE_NAME?>.endpoints
       <?php echo $ADDITIONAL_CONFIGURATION . "\n"?>
	   Header set Server OSA-3.0

</VirtualHost>
