# consul-tree v 2.7

Its a Tree for Consul https://github.com/hashicorp/consul KV section, which supports, Cut, Copy, Paste, Create, Delete methods.

The applicaiton is based on: 
JQuery, Twitter Bootstrap, JStree, PHP

DEMO
------
Screenshots of the application are here https://github.com/vagharsh/consul-tree/blob/master/demo.md

Quick Start
-----------
To use the Consul-tree you need PHP and a web server.

- Clone the repo into your web directory 
- Configure the config.php file, modify the consul url with it's port and IP address of the consul and kv store section
do not change the syntax of the line inside the php since it is going to be echo-ed into the JS.
The config line should look like this

`consulUrl = "http://192.168.220.145:8500/v1/kv/";`


Consul-tree on Docker
-----------
Check my docker repo for a ready made container at https://hub.docker.com/r/vagharsh/consul-tree/ .
run it next to the consul container, just make sure you to create a `config.php` file on the same host which consul is hosted and mount that file with the container and run it with this command. 

`docker run --name consul-tree -d -v /opt/consul-tree/config.php:/var/www/html/config.php -p 8123:80 --restart always vagharsh/consul-tree:2.7`

**Current working version corresponds to the Image on Docker hub vagharsh/consul-tree:2.7**


Release Notes 
---------
v2.7 :
- fixed the delete node issue 
- update fields are disabled and hidden when the selected item is a folder and not a key

v2.6 : 
- New icons
- fixed value box position
- last updated date in the footer


Bug tracker
-----------

Have a bug? Please create an issue here on GitHub!

https://github.com/vagharsh/consul-tree/issues


Copyright and License
---------------------

MIT License

Copyright (c) 2017 Vagharsh Kandilian

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
