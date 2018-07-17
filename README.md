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
Check my docker repo for a ready-made container at [https://hub.docker.com/r/vagharsh/consul-tree/](https://hub.docker.com/r/vagharsh/consul-tree/).

On the Docker host that you want to run the Consul-tree container from.
- Create a directory and inside it create `config.json` and `auth.php` files.
- Configure the `config.json` as mentioned [here](#consul-configuration-configconfigjson).
- Configure the `auth.php` as mentioned [here](#acl-configuration-configauthphp).
- To deploy `consul-tree` under a virtual-directory e.g. `http://yourserver/consuldirectory`
    - add `--env "BASE_URI=consul-dev"` if you are planning to access it via `http://test.domain.com/consul-dev/`
    - ```
          docker run -d -v /opt/consul-tree/config:/var/www/html/config \
                    -p 8123:80 \
                    --restart always \
                    --name consul-tree \
                    --env "BASE_URI=consuldirectory" \
                    vagharsh/consul-tree:7.5
      ```
- There are 2 ways to provide the config file.
    - Provide the configs file via **HTTP** url. (**both files [auth.php, config.json] should be provided**)
        ```
        docker run -d -e CONFIG=http://test.abc.com/config.json \
                      -e AUTH=http://test.abc.com/auth.php \
                      -p 8123:80 \
                      --restart always \
                      --name consul-tree \ 
                      vagharsh/consul-tree:7.5-web                    
        ```
    **Note**: Make sure that the files are not shared publicly, and that the php file is not hosted on a php server.

    - Provide the config file via **Mounting**. 
        - Mounting the config folder.
        ```
        docker run -d -v /opt/consul-tree/config:/var/www/html/config \
                      -p 8123:80 \
                      --restart always \
                      --name consul-tree \
                      vagharsh/consul-tree:7.5
        ```
        - Mounting the config files separately.
        ```
        docker run -d -v /opt/consul-tree/config/config.json:/var/www/html/config/config.json \
                      -v /opt/consul-tree/config/auth.php:/var/www/html/config/auth.php \
                      -p 8123:80 \
                      --restart always \
                      --name consul-tree \
                      vagharsh/consul-tree:7.5
        ```
- Access the `consul-tree` e.g. `http://test.domain.com/consuldirectory` or `http://test.domain.com/`
- To create a folder or a key, Right click inside the tree where you want the folder / key to be created. and then click on the create.

External API for importing JSON file into Consul
------------------
This feature has been added in order to Import large amount of data to the consul without using the UI.
a simple basic `token` is used to authenticate to import the data through `backend/api.php`.

**Authorization** : `Basic GbYpjCigzBM4PayeozwG`, which is configured in `api.php`
```
  - Method : POST
  - URL : http://localhost/consul-tree/backend/api.php
  - form-data :
    - method : IMPORT
    - consul : http://192.168.220.145:8500/v1/kv/
    - file : exported JSON file which will be imported.
  - Headers : 
    - Authorization : Basic GbYpjCigzBM4PayeozwG 
```

Release Notes
---------
[v7.5.1]()
- Fixed : Consul client not executing under linux.

Release notes are available [here](release.md).

Issues / Features request tracker
-----------
Found an issue or have a feature request ?

Please create an issue [here](https://github.com/vagharsh/consul-tree/issues).

Copyright and License
---------------------
Copyright and License under the [MIT license](LICENSE).
