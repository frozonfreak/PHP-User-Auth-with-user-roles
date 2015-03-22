<?php
/**
 * Database config variables
 */
define("DB_STRING","mysql:host=localhost;dbname=test");
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "root");


/*Used for mode detailed Error*/
define("DEBUG", true);

/* SET Default Session Time*/
define("DEFAULT_SESSION_TIME", 1800);

/*Default host for emailing*/
define("DEFAULT_HOST", 'http://localhost/');

/*Email Configurations*/
define("MANDRILL_KEY", UPDATE KEY HERE);
define("SENDER_EMAIL", 'test@test.com');
define("SENDER_NAME", 'sender_name');


define("ROOT", 'SUPER_ADMIN');
define("ADMIN", 'ADMIN');
define("MEMBER", 'MEMBER');

?>