<VirtualHost <?echo $HTTP_VHOST_ADDR . ":" . $HTTP_VHOST_PORT?>>
       ServerName <?echo "$HTTP_VHOST_NAME\n"?>




       SetEnv publicServerProtocol http://
       SetEnv publicServerName <?echo "$HTTP_VHOST_NAME\n"?>
       SetEnv publicServerPort <? echo "$HTTP_VHOST_PORT\n"?>
       SetEnv publicServerTopDomain <? echo "$HTTP_VHOST_TOP_DOMAIN\n"?>
       SetEnv publicServerPrefix http://<?echo $HTTP_VHOST_NAME . ":" . $HTTP_VHOST_PORT . "\n"?>
        
        


       CustomLog <?echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.access.log combined
       ErrorLog <?echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.error.log
       LogLevel warn

       SSLProxyEngine on
       SSLProxyVerify none


       RewriteEngine on
#       RewriteLog <?echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/rewrite.log
#       RewriteLogLevel 0


	SetEnvIf Authorization "(.*)" ORGAUTH=$1
       RequestHeader unset Authorization

	   ProxyTimeout 120 
       Include <?echo runtimeApplianceConfigLocation?>/applianceManagerServices-node-<?echo $NODE_NAME?>.endpoints
       <?echo $ADDITIONAL_CONFIGURATION . "\n"?>
	   Header set Server OSA-2.0

</VirtualHost>
