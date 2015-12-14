# phlogger
A demo bloging platform in PHP created for the students in FED15 of Mediainstututet in Stockholm, Sweden

## db.conf.php 
For this project to work please add a db.conf.php to the root of the project with the credentials for your database through the following code:

```php
<?php
define('CONF_DB_SERVER', 'localhost');
define('CONF_DB_USERNAME', 'root');
define('CONF_DB_PASSWORD', '');
define('CONF_DB_DATABASE', 'phlogger');
```