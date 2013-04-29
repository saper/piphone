PiPhone - a phone calls campaign manager for activists
======================================================

This is the PiPhone, 

A website to manage phone call campaigns, made by activists, for activists.
The aim of this project is to allow any group of citizen to create political campaigns to call list of elected representative about a certain topic.

for an online demo of the piphone, please go to http://demo.piphone.eu/

(C) La Quadrature du Net and others, 2012-2013
This program is distributed under the GPL-v3 license as specified in the <COPYRIGHT> file.
See <AUTHORS> for a complete list of authors 

Installation Instructions
-------------------------

To install it proceed as follow : 

* Deploy a nice php/mysql/apache (or whatever) virtual host, that should point to /campaign/
* If you are using Apache, ensure that rewrite rules module is enabled, and use "allowOverride all" to enable the .htaccess parsing. 
* If you are not using Apache, make it so that /static/ folder is served "as it is" and everything else is pointed to index.php
* Configure a MySQL database access in campaign/config.php.sample, which should be renamed to config.php
* (not sure it's still needed) clone the plivo-helper files into campaign/plivo by using :  cd campaign/ && git clone https://github.com/digination/plivohelper-php plivo
* Inject piphone.sql into your MySQL database to create the basic structure
* use freeswitch/ folder to preconfigure freeswitch and its sounds/ and scripts/ folders

* You also need both [Plivo](https://github.com/plivo/plivoframework/) and [Freeswitch](http://www.freeswitch.org/) installed. 
* In Plivo, you need to define two keys in default.conf called "Auth ID" and "Auth Token". Change them to something unique, and put the same information into the AccountSid and AuthToken of config.php

If you need help, don't hesitate to bump at **#laquadrature on irc.freenode.net**, and ask _okhin_ or _vincib_ (or others) for piphone-related questions.

