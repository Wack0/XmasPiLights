# treeapi  
The server-side API that treeclient contacts.

To set up, create a mysql db and user, add that information to `index.php` then create tables thus:

    create table `gates` (
	  `id` bigint unsigned primary key auto_increment,
	  `name` text,
	  `time` bigint unsigned,
	  `codes` longtext
	);create table `counter` ( `hits` bigint unsigned );

You'll also need to configure your HTTPd so that index.php handles any non-existing file. For Apache you can use .htaccess, for lighttpd use mod_magnet scripts, etc.

Make sure it works by doing some test requests. /add?name=test&codes=test should give '1', then /get?name=test should give 'test'.

If it all works, change treeclient to use your treeapi instance.