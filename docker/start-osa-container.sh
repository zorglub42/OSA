if [ ! -d /var/lib/mysql/mysql ]; then
	cd /
   	tar xvzf /root/mysql.def.tgz
	cd -
fi
find /var/lib/mysql -type f -exec touch {} \; && chown -R mysql:mysql /var/lib/mysql && service mysql restart
service apache2 restart
service cron restart
echo "'$*'"
/usr/local/bin/configure-osa-container.sh "$*"||exit 1

tail -f /dev/null
