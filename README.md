README
======

Description
-----------

This application allows the **configuration of bootstrap popins**.
By using a form, the user can easily generate a configuration example for one of his websites.

Requirements for the installation
---------------------------------

- NodeJs
    * apt-get install nodejs npm
- Bower
    * npm install -g bower
- Grunt cli
    * npm install -g grunt-cli
- Redis server
    * http://redis.io/topics/quickstart

Installation
------------

1. npm install
2. grunt install
3. grunt dump

Use
---

- Execute the following command : grunt server
- Run the application by using the following web address : http://127.0.0.1:8000


Manually delete the current configuration
-----------------------------------------

- Execute the following web address : http://127.0.0.1:8000/configs/delete