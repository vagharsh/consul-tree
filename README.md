# Consul-tree

Tree representation of hashicorp/consul https://www.consul.io/, https://github.com/hashicorp/consul KV section.

**Used Technologies:** JS, HTML, CSS, PHP, JsTree, JQuery, Twitter Bootstrap.

**UI/UX:** : (Desktop, Tablet, Mobile) friendly - responsive (80+%).

-----------------

**Supported Methods:** Cut, Copy, Paste, Create, Delete, Rename, Duplicate.

**Available Features:** Search, Import, Export, Copy - CUT - PASTE between Consuls.

***Latest Features:*** ACL (Access Control List) with authentication and authorization, based on a php file.

Demo
------
Screen-shots of the application are [here](demo.md).

Quick Start
-----------
To use the Consul-tree you need PHP and a web server.

- Clone the repo into your web directory.
- Configure the `config/config.json` as mentioned [here](#consul-configuration-configconfigjson).
- Configure the `config/auth.php` as mentioned [here](#acl-configuration-configauthphp).
- Access the consul-tree e.g. http://yourserver/consuldirectory
- To create a folder or a key, Right click inside the tree where you want the folder/key to be created. and then click on the create.

Consul Configuration *`config/config.json`*
----------------
- Consul-tree on a physical host.
    - Inside this repo you can find the `config/config.json` which contains the configurations that need to be configured before accessing the Consult-tree, an example of the `config.json` file is as below.
- Consul-tree on Docker.
    - Create a directory and inside it create `config.json` and `auth.php` files.
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

ACL Configuration *`config/auth.php`*
------------------
```php
<?php
$mainTitle = "Consul-Tree";
$list = [
    [
        "user" => "qa",
        "pass" => "qa",
        "auto" => false,
        "rights" => "read"
    ], [
        "user" => "dev",
        "pass" => "dev",
        "auto" => true,
        "rights" => "write"
    ], [
        "user" => "admin",
        "pass" => "admin",
        "auto" => true,
        "rights" => "full"
    ]
];
```
- Main Title: Login screen title.
- Rights: `rights : string`
    - read = read only.
    - write = read and write.
    - full = read, write and delete.
- Auto login: `auto : boolean`
    - If the **`auto`** property for the user is set to `true`, then the user will log in automatically.
    - if multiple users had **`auto`** properties set to `true`, then the **first** user will be selected as auto-login user.

Consul-tree on Docker
-----------
Check my docker repo for a ready-made container at https://hub.docker.com/r/vagharsh/consul-tree/.

On the Docker host that you want to run the Consul-tree container from.
- Create a directory and inside it create `config.json` and `auth.php` files.
- Configure the `config.json` as mentioned [here](#consul-configuration-configconfigjson).
- Configure the `auth.php` as mentioned [here](#acl-configuration-configauthphp).
- There are 2 ways to provide the config file.
    - Provide the configs file via **HTTP** url. (**both files [auth.php, config.json] should be provided**)
        ```
        docker run -d -e CONFIG=http://test.abc.com/config.json \
                      -e AUTH=http://test.abc.com/auth.php \
                      -p 8123:80 \
                      --restart always \
                      --name consul-tree \ 
                      vagharsh/consul-tree:6.8-web                    
        ```
    **Note**: Make sure that the files are not shared publicly, and that the php file is not hosted on a php server.

    - Provide the config file via **Mounting**. 
        - Mounting the config folder.
        ```
        docker run -d -v /opt/consul-tree/config:/var/www/html/config \
                      -p 8123:80 \
                      --restart always \
                      --name consul-tree \
                      vagharsh/consul-tree:6.8
        ```
        - Mounting the config files separately.
        ```
        docker run -d -v /opt/consul-tree/config/config.json:/var/www/html/config/config.json \
                      -v /opt/consul-tree/config/auth.php:/var/www/html/config/auth.php \
                      -p 8123:80 \
                      --restart always \
                      --name consul-tree \
                      vagharsh/consul-tree:6.8
        ```
- Access the consul-tree e.g. http://yourserver/consuldirectory
- To create a folder or a key, Right click inside the tree where you want the folder / key to be created. and then click on the create.

Release Notes
---------
v 6.8 :
- Added scroll-bar to the tree for better UI/UX. [commit](https://github.com/vagharsh/consul-tree/commit/3c6ba5486109aad647def581a0aa37993d7fe4fe)
- Removed the fixed position of the key/value view box.
- Added the login.php in the backend to redirect to the home when a session gets broken. [commit](https://github.com/vagharsh/consul-tree/commit/32b6ccd347cfc8a4a6169b4464de2f560d2e7b01).
- Desktop / Tablet / Mobile friendly.
- Added an indicator to the search field when results are found the field's border gets green when no results it gets red.
- Re-organized files structure.[commit](https://github.com/vagharsh/consul-tree/commit/18fbd05a2506163a5b0aa6f5727477e3a3b2c969)
- Added version file for code centralization. [commit](https://github.com/vagharsh/consul-tree/commit/ae484a07584d7d97b657b0215705173c871766b0)

Release notes are available [here](release.md).

Issues / Features request tracker
-----------
Found an issue or have a feature request ?

Please create an issue [here](https://github.com/vagharsh/consul-tree/issues).

Copyright and License
---------------------
Copyright and License under the [MIT license](LICENSE).
