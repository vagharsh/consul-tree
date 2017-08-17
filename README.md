# Consul-tree

It's a Tree for Consul https://github.com/hashicorp/consul KV section.

**Supported Methods:** Cut, Copy, Paste, Create, Delete.

**Available Features:** Search, Import, Export (Full, Custom).

The applicaiton is based on: 
JQuery, Twitter Bootstrap, JStree.

Demo
------
Screenshots of the application are [Here](https://github.com/vagharsh/consul-tree/blob/master/demo.md).

Quick Start
-----------
To use the Consul-tree you need PHP and a web server.

- Clone the repo into your web directory 
- Configure the config.php file, modify the consul url with it's port and IP address of the consul and kv store section
do not change the syntax of the line inside the php since it is going to be echo-ed into the JS.
The config line should look like this
`consulUrl = "http://192.168.220.145:8500/v1/kv/";`
- Access the consul-tree e.g. http://yourserver/consuldirectory
- To create a folder or a key, Right click inside the tree where you want the folder / key to be created

NOTE
------
**Consul tree does not work if there isn't a root folder to start with, therfore wither you create a root folder from Consul-UI or import previosly exported JSON data from Consul-tree**

Consul-tree on Docker
-----------
Check my docker repo for a ready made container at https://hub.docker.com/r/vagharsh/consul-tree/.
run it next to the consul container, just make sure you to create a `config.php` file on the same host which consul is hosted and mount that file with the container and run it with this command. 

`docker run --name consul-tree -d -v /opt/consul-tree/config.php:/var/www/html/config.php -p 8123:80 --restart always vagharsh/consul-tree:4.2`

**Current working version corresponds to the Image on Docker hub vagharsh/consul-tree:4.2**


Release Notes 
---------
Release notes are available [Here](https://github.com/vagharsh/consul-tree/blob/master/release.md).


Issues / Features request tracker
-----------
Found an issue or have a feature request ?

Please create an issue [Here](https://github.com/vagharsh/consul-tree/issues).


Copyright and License
---------------------
Copyright and License information are available [Here](https://github.com/vagharsh/consul-tree/blob/master/LICENSE).
