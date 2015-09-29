<VirtualHost <?php echo $HTTP_VHOST_ADDR . ":" . $HTTP_VHOST_PORT?>>
       ServerName <?php echo "$HTTP_VHOST_NAME\n"?>




       SetEnv publicServerProtocol http://
       SetEnv publicServerName <?php echo "$HTTP_VHOST_NAME\n"?>
       SetEnv publicServerPort <?php  echo "$HTTP_VHOST_PORT\n"?>
       SetEnv publicServerTopDomain <?php  echo "$HTTP_VHOST_TOP_DOMAIN\n"?>
       SetEnv publicServerPrefix http://<?php echo $HTTP_VHOST_NAME . ":" . $HTTP_VHOST_PORT . "\n"?>
        
        


       CustomLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.access.log combined
       ErrorLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.error.log
       LogLevel warn

       SSLProxyEngine on
       SSLProxyVerify none


       RewriteEngine on
#       RewriteLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/rewrite.log
#       RewriteLogLevel 0


       RequestHeader unset Authorization

	   ProxyTimeout 120 
       Include <?php echo runtimeApplianceConfigLocation?>/applianceManagerServices-node-<?php echo $NODE_NAME?>.endpoints
       <?php echo $ADDITIONAL_CONFIGURATION . "\n"?>
	   Header set Server OSA-2.0

</VirtualHost>
