# OSA
Open Services Access: Apaches RP management tool

OSA is a tool to manage in click-and-paly mode an Apache reverse proxy
It's based on apache 2 web server.

In addition, it offers some extra functionalities like dual mode authentication (HTTP basic or cookie) for a same resource set or quota management.

##Install
To install some prerequisite are needed
  - root mysql user on target server should be able to create/delete users and databases, with a connection from the server where OSA is installed If it's not the case, run on target MySQL server:
      - to add this privileges: GRANT all on *.* to 'root'@'%' identified by 'password' WITH GRANT OPTION;
      - to remove priovileges: DELETE from mysql.user WHERE user='root' AND host='%'; flush privileges;
