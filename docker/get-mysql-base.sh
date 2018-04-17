#!/bin/bash


C_ID=$(docker run -d $1 -pwd:foo.bar)

cat <<EOF >/tmp/$$.sh
#!/bin/bash
echo "Container configuration in progress. Please wait...."
sleep 20
service mysql stop
cd /var/lib/mysql
tar cvfz /tmp/mysql.tgz *
EOF
docker cp /tmp/$$.sh $C_ID:/tmp
docker exec -it $C_ID chmod u+x /tmp/$$.sh
docker exec -it $C_ID ls -l /tmp
docker exec -it $C_ID /tmp/$$.sh
docker cp $C_ID:/tmp/mysql.tgz .
docker rm -f $C_ID
rm  /tmp/$$.sh



