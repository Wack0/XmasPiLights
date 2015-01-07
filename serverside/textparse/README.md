# textparse  
Common code used in server-side bots.

MySQL database information needs to be added to `textparse.php` - check `treeapi`'s readme for more information on creating the database.

The main function used by bots here is `parseStar()`, which based on the input will insert rows to the MySQL database which can later be obtained by `treeclient`.