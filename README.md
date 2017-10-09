# Consul-tree

Tree representation of hashicorp/consul https://www.consul.io/, https://github.com/hashicorp/consul KV section.

**Supported Methods:** Cut, Copy, Paste, Create, Delete, Rename.

**Available Features:** Search, Import, Export, Copy - CUT - PASTE between Consuls.

***Latest Features:*** *ACL (Access Control List) with authentication and authorization, based on a php file.*

Demo
------
Screen-shots of the application are [here](demo.md).

Quick Start
-----------
To use the Consul-tree you need PHP and a web server.

- Clone the repo into your web directory.
- Configure the `config.json` as mentioned [here](#consul-configuration-configconfigjson) and change their values to match your consul host and the title as well if you want a custom title.
- Configure the `auth.php` as mentioned [here](#acl-configuration-configauthphp).
- Access the consul-tree e.g. http://yourserver/consuldirectory
- To create a folder or a key, Right click inside the tree where you want the folder / key to be created. and then click on the create.

Consul Configuration *`config/config.json`*
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

ACL Configuration *`config/auth.php`*
------------------
```php
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
- Configure the `config.json` as mentioned [here](#consul-configuration-configconfigjson) and change their values to match your consul host and the title as well if you want a custom title.
- Configure the `auth.php` as mentioned [here](#acl-configuration-configauthphp).
- There are 2 ways to provide the config file.
    - Provide the config file via **HTTP** url.
        - `docker run -d -e CONFIG=http://test.abc.com/config.json -p 8123:80 --restart always --name consul-tree vagharsh/consul-tree:6.5-web`
    - Provide the config file via **Mounting**.
        - `docker run -d -v /opt/consul-tree/config:/var/www/html/config -p 8123:80 --restart always --name consul-tree vagharsh/consul-tree:6.5`
- Access the consul-tree e.g. http://yourserver/consuldirectory
- To create a folder or a key, Right click inside the tree where you want the folder / key to be created. and then click on the create.

Release Notes
---------
v 6.5 :
- Access Control List.
- Auto user login.
- Enhanced delete functionality ( now it deletes faster "recursively").
- Update value now does not reload the page.
- Copy, Cut, Paste, Import functions now asks for data overwrite (yes, no).
- UI/UX enhancement.
- Fixed issue with the CUT function.
- Fixed the fix-tree (delete temp folders function).
- Overall faster functionality and UI.

Release notes are available [here](release.md).

Issues / Features request tracker
-----------
Found an issue or have a feature request ?

Please create an issue [here](https://github.com/vagharsh/consul-tree/issues).

Copyright and License
---------------------
Copyright and License under the [MIT license](LICENSE).