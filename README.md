# Consul-tree

It's a Tree for Consul https://www.consul.io/, https://github.com/hashicorp/consul KV section.

**Supported Methods:** Cut, Copy, Paste, Create, Delete.

**Available Features:** Search, Import, Export.

Demo
------
Screenshots of the application are [here](https://github.com/vagharsh/consul-tree/blob/master/demo.md).

Quick Start
-----------
To use the Consul-tree you need PHP and a web server.

- Clone the repo into your web directory.
- Configure the `config.php` file as mentioned [here](https://github.com/vagharsh/consul-tree/blob/master/README.md#configuration).
- Access the consul-tree e.g. http://yourserver/consuldirectory
- To create a folder or a key, Right click inside the tree where you want the folder / key to be created. and then click on the create.

Configuration
----------------
If you are using the consul-tree on a physical host, then inside this repo you can find the `config/php` which contains the configurations that need to be configured before accessing the Consult-tree, an example of the `config.php` file is as below.

```javascript
consulUrl = "http://192.168.220.145:8500/v1/kv/";
consulTitle = "My Consul Tree";
```

- Modify the `consulUrl` value to match your consul url with it's `ipaddress:port` and the kv store section.
- Modify the `consulTitle` value with your custom title to your Consul-Tree.
- **Do not change the syntax of the config.php content, since it will be echo-ed into the JS.**

NOTE
------
**Consul tree does not work if there isn't a root folder to start with, therfore wither you create a root folder from Consul-UI or import previosly exported JSON data from Consul-tree**

Consul-tree on Docker
-----------
Check my docker repo for a ready made container at https://hub.docker.com/r/vagharsh/consul-tree/.

On the Docker host that you want to run the Consul-tree container from.
- Create a `config.php` file.
- Copy and paste the configuration file contents from [here](https://github.com/vagharsh/consul-tree/blob/master/README.md#configuration) and change their values to match your consul host and the title as well if you want a custom title.
- Mount it to the container. 
- Example of how to start the consul-tree container:

`docker run -d -v /opt/consul-tree/config.php:/var/www/html/config.php -p 8123:80 --restart always --name consul-tree vagharsh/consul-tree:5.0`

**Current stable version corresponds to the Image on Docker hub vagharsh/consul-tree:5.0**

Release Notes 
---------
Release notes are available [here](https://github.com/vagharsh/consul-tree/blob/master/release.md).

Issues / Features request tracker
-----------
Found an issue or have a feature request ?

Please create an issue [here](https://github.com/vagharsh/consul-tree/issues).

Copyright and License
---------------------
Copyright and License under the [MIT license](https://github.com/vagharsh/consul-tree/blob/master/LICENSE).
