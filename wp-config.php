<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'seoul');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '2n3H$guuR@2IV88HVY6DYU.,3Xv#x!EtcG=FGp]4w,:,`$wQno/6w$gX}hseNZ]*');
define('SECURE_AUTH_KEY',  '1itgFn3qpGCB2(:UPrCf!EArIVu*bobU:j`Gqrud8bM}xk!Rf3wE0V3(C0mK.+ )');
define('LOGGED_IN_KEY',    '-`;XAuJQ0vjn3RyKrSCRs]LG!.!Bquj*;mY089Ddd8:#1-r&^*~_2W6idbbxIvj.');
define('NONCE_KEY',        'BGEvp%^Gd|;3U9-t^WYY-aO,e=}P.raeYcu:Dpm>0/`O%vkp+en9R&qfn_w<C~QV');
define('AUTH_SALT',        's9[_MSukh,<f@Ib_RQlH}-Jb=r}&U64NzzO/ZS|a9b(dX*-k)B43`>-^nRQ01%R2');
define('SECURE_AUTH_SALT', '(ENdjmoPa}1/_e#iS0[X@:}3IdOfXpX$ Q>n$&9lp`3ZG*[#bMg(jw=xeSMnL?Lc');
define('LOGGED_IN_SALT',   'H|WQJ_(=@AN*K{v8dx<-136#p3NKa<coA`Bf2+.O#5fNt/@ijpq_M[_c5FAXmvOi');
define('NONCE_SALT',       '$p6D;DXZeQ8Sm>?<hhNP$SvRT[x@@^DvAA%EUXoR]~m7Biq)wT;^.<`bnHnbJ;)t');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'seoul_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
