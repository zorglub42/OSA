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
############ HTTPS Configuration
#
#
# See https://mozilla.github.io/server-side-tls/ssl-config-generator/ for up to date config
#
#
#
#

<VirtualHost <?php  echo $HTTP_VHOST_ADDR . ":" . "$HTTP_VHOST_PORT"?>>
       ServerName <?php  echo $HTTP_VHOST_NAME?>



       SetEnv publicServerProtocol https://
       #SetEnv publicServerName <?php echo "$HTTP_VHOST_NAME\n"?>
       SetEnvIf Host "(.*)" publicServerName=$1
       SetEnv publicServerPort <?php echo "$HTTP_VHOST_PORT\n"?>
       SetEnv publicServerTopDomain <?php  echo "$HTTP_VHOST_TOP_DOMAIN\n"?>
       SetEnv publicServerPrefix https://<?php echo $HTTP_VHOST_NAME . ":" . "$HTTP_VHOST_PORT\n"?>
        
        
       CustomLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.access.log combined
       ErrorLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/main.error.log
       LogLevel warn

       RewriteEngine on
#       RewriteLog <?php echo runtimeApplianceConfigScriptLogDir . "/" . $NODE_NAME?>/rewrite.log
#       RewriteLogLevel 0
       Options -Indexes




       #   SSL Engine Switch:
       #   Enable/Disable SSL for this virtual host.
       SSLEngine on
       SSLProxyEngine on
       SSLProxyVerify none 
       SSLProxyCheckPeerCN off
       SSLProxyCheckPeerName off
       SSLProxyCheckPeerExpire off

       #   A self-signed (snakeoil) certificate can be created by installing
       #   the ssl-cert package. See
       #   /usr/share/doc/apache2.2-common/README.Debian.gz for more info.
       #   If both key and certificate are stored in the same file, only the
       #   SSLCertificateFile directive is needed.
    
<?php if ($HTTPS_HAVE_CA_CERT) {?>
	#SSLCaCertificateFile /etc/ssl/certs/osa-node-<?php echo $NODE_NAME?>-ca.pem
<?php }?>
<?php if ($HTTPS_HAVE_CHAIN_CERT) {?>
	SSLCertificateChainFile /etc/ssl/certs/osa-node-<?php echo $NODE_NAME?>-chain.pem
<?php }?>

	SSLCertificateFile /etc/ssl/certs/osa-node-<?php echo $NODE_NAME?>.pem
	SSLCertificateKeyFile /etc/ssl/private/osa-node-<?php echo $NODE_NAME?>.key


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


	Header always set Strict-Transport-Security "max-age=15768000"


	SetEnvIf Authorization "(.*)" ORGAUTH=$1
       RequestHeader unset Authorization

	   ProxyTimeout 120 
	   DocumentRoot /var/www/local/empty
       Include <?php echo runtimeApplianceConfigLocation?>/applianceManagerServices-node-<?php echo $NODE_NAME?>.endpoints
	   <?php echo $ADDITIONAL_CONFIGURATION . "\n"?>
	   Header set Server OSA-<?php echo version?>   
</VirtualHost>
ServerTokens Prod
ServerSignature Off




# modern configuration, tweak to your needs
SSLProtocol             all -SSLv3 -TLSv1 -TLSv1.1
SSLCipherSuite          ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA256
SSLHonorCipherOrder     on
SSLCompression          off


# OCSP Stapling, only in httpd 2.3.3 and later
SSLUseStapling          on
SSLStaplingResponderTimeout 5
SSLStaplingReturnResponderErrors off
SSLStaplingCache        shmcb:/var/run/ocsp(128000)

