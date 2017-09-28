# Consul-tree

Tree representation of hashicorp/consul https://www.consul.io/, https://github.com/hashicorp/consul KV section.

**Supported Methods:** Cut, Copy, Paste, Create, Delete, Rename.

**Available Features:** Search, Import, Export.

**Latest Features:** Copy - CUT - PASTE between Consuls.
 
Demo
------
Screen-shots of the application are [here](https://github.com/vagharsh/consul-tree/blob/master/demo.md).

Quick Start
-----------
To use the Consul-tree you need PHP and a web server.

- Clone the repo into your web directory.
- Configure the `config.json` file as mentioned [here](https://github.com/vagharsh/consul-tree/blob/master/README.md#configuration).
- Access the consul-tree e.g. http://yourserver/consuldirectory
- To create a folder or a key, Right click inside the tree where you want the folder / key to be created. and then click on the create.

Configuration
----------------

- Consul-tree on a physical host.
    - Inside this repo you can find the `config/config.json` which contains the configurations that need to be configured before accessing the Consult-tree, an example of the `config.json` file is as below.
- Consul-tree on Docker.
    - Create a directory and inside it create a `config.json` file.
    - Copy and paste the configuration file contents from below.    

```json
[
  {
    "url": "http://192.168.220.145:8500/v1/kv/",
    "title": "My-Consul 1"
  },{
    "url": "http://192.168.220.146:8500/v1/kv/",
    "title": "My-Consul 2"
  }
]
```
**From version 6.x and UP the Consul-Tree supports browsing multiple consuls from one UI therefore you can add as many consul (urls, titles) as you would like to connect to as an array / json**
- Modify the `url` value to match your consul url with it's `ipaddress:port` and the kv store section.
- Modify the `title` value with your custom title to your Consul-Tree.

Consul-tree on Docker
-----------
Check my docker repo for a ready-made container at https://hub.docker.com/r/vagharsh/consul-tree/.

On the Docker host that you want to run the Consul-tree container from.
- Configure the `config.json` as mentioned in the second section [here](https://github.com/vagharsh/consul-tree#configuration) and change their values to match your consul host and the title as well if you want a custom title.
- Mount the config directory to the container. 

Example of how to start the consul-tree container:

`docker run -d -v /opt/consul-tree:/var/www/html/config -p 8123:80 --restart always --name consul-tree vagharsh/consul-tree:6.1`

**Current stable version corresponds to the Image on Docker hub vagharsh/consul-tree:6.1**

Release Notes 
---------
v 6.1 : 
- Rename Feature.
- Copy - CUT - PASTE between Consuls.
- Fix-tree modal hide issue-fix.
- Fixed issue with showing root files properly.
- Warning Modal header colors.
- Code structural change and cleanup.
- UI enhancement.

Release notes are available [here](https://github.com/vagharsh/consul-tree/blob/master/release.md).

Issues / Features request tracker
-----------
Found an issue or have a feature request ?

Please create an issue [here](https://github.com/vagharsh/consul-tree/issues).

Copyright and License
---------------------
Copyright and License under the [MIT license](https://github.com/vagharsh/consul-tree/blob/master/LICENSE).
