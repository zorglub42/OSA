<VirtualHost <? echo $HTTP_VHOST_ADDR . ":" . "$HTTP_VHOST_PORT"?>>
       ServerName <? echo $HTTP_VHOST_NAME?>



       SetEnv publicServerProtocol https://
       SetEnv publicServerName <?echo "$HTTP_VHOST_NAME\n"?>
       SetEnv publicServerPort <?echo "$HTTP_VHOST_PORT\n"?>
       SetEnv publicServerTopDomain <? echo "$HTTP_VHOST_TOP_DOMAIN\n"?>
       SetEnv publicServerPrefix https://<?echo $HTTP_VHOST_NAME . ":" . "$HTTP_VHOST_PORT\n"?>
        
        
       CustomLog <?echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.access.log combined
       ErrorLog <?echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.error.log
       LogLevel warn

       RewriteEngine on
#       RewriteLog <?echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/rewrite.log
#       RewriteLogLevel 0



       #   SSL Engine Switch:
       #   Enable/Disable SSL for this virtual host.
       SSLEngine on
       SSLProxyEngine on
       SSLProxyVerify none

       #   A self-signed (snakeoil) certificate can be created by installing
       #   the ssl-cert package. See
       #   /usr/share/doc/apache2.2-common/README.Debian.gz for more info.
       #   If both key and certificate are stored in the same file, only the
       #   SSLCertificateFile directive is needed.
    
<?if ($HTTPS_HAVE_CA_CERT){?>
	SSLCaCertificateFile /etc/ssl/certs/nursery-osa-node-<?echo $NODE_NAME?>-ca.pem
<?}?>
<?if ($HTTPS_HAVE_CHAIN_CERT){?>
	SSLCertificateChainFile /etc/ssl/certs/nursery-osa-node-<?echo $NODE_NAME?>-chain.pem
<?}?>

	SSLCertificateFile /etc/ssl/certs/nursery-osa-node-<?echo $NODE_NAME?>.pem
	SSLCertificateKeyFile /etc/ssl/private/nursery-osa-node-<?echo $NODE_NAME?>.key


       #   SSL Protocol Adjustments:
       #   The safe and default but still SSL/TLS standard compliant shutdown
       #   approach is that mod_ssl sends the close notify alert but doesn't wait for
       #   the close notify alert from client. When you need a different shutdown
       #   approach you can use one of the following variables:
       #   o ssl-unclean-shutdown:
       #     This forces an unclean shutdown when the connection is closed, i.e. no
       #     SSL close notify alert is send or allowed to received.  This violates
       #     the SSL/TLS standard but is needed for some brain-dead browsers. Use
       #     this when you receive I/O errors because of the standard approach where
       #     mod_ssl sends the close notify alert.
       #   o ssl-accurate-shutdown:
       #     This forces an accurate shutdown when the connection is closed, i.e. a
       #     SSL close notify alert is send and mod_ssl waits for the close notify
       #     alert of the client. This is 100% SSL/TLS standard compliant, but in
       #     practice often causes hanging connections with brain-dead browsers. Use
       #     this only for browsers where you know that their SSL implementation
       #     works correctly.
       #   Notice: Most problems of broken clients are also related to the HTTP
       #   keep-alive facility, so you usually additionally want to disable
       #   keep-alive for those clients, too. Use variable "nokeepalive" for this.
       #   Similarly, one has to force some clients to use HTTP/1.0 to workaround
       #   their broken HTTP/1.1 implementation. Use variables "downgrade-1.0" and
       #   "force-response-1.0" for this.
       BrowserMatch "MSIE [2-6]" \
               nokeepalive ssl-unclean-shutdown \
               downgrade-1.0 force-response-1.0
       # MSIE 7 and newer should be able to use keepalive
       BrowserMatch "MSIE [17-9]" ssl-unclean-shutdown
       RequestHeader unset Authorization

	   ProxyTimeout 120 
       Include <?echo runtimeApplianceConfigLocation?>/applianceManagerServices-node-<?echo $NODE_NAME?>.endpoints
	   <?echo $ADDITIONAL_CONFIGURATION . "\n"?>
	   Header set Server OSA-2.0
</VirtualHost>
