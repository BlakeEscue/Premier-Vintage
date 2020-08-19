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

if (strstr($_SERVER['SERVER_NAME'], 'premier-vntg.local')) {
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );
} else {
	define( 'DB_NAME', 'local' );

	/** MySQL database username */
	define( 'DB_USER', 'root' );
	
	/** MySQL database password */
	define( 'DB_PASSWORD', 'root' );
	
	/** MySQL hostname */
	define( 'DB_HOST', '127.0.0.1' );
}


/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'JDks+ZtYoKO4r0XRtdjZYIE1x6zitTWLJyCmQcmDL7GZPtFxnXAm7qQbt4I1tNnS1W2PktjYVMFIsANQLhtqeQ==');
define('SECURE_AUTH_KEY',  'Px77PqupWqbaY/crJ1eqFq4ogsnvG0N1/IKpM2QydDh7FIuWwykX2Oh9pLJ2F2m900TCgxmj7Id4Cwadph5+iQ==');
define('LOGGED_IN_KEY',    'Fk15Nxjp+fr7+QjYLoh6Sw1kOKKFVScJhK6ImHMiUw8fZDn1iXD+m5z+ws9ZRYA7x3vRHTBnk0Vxlt/fKOdetg==');
define('NONCE_KEY',        'HLmlqHbMaejoWU2HSSayBba9Jeri25oF4dkZaI9/5wjNsWL/hgr4j4v/9pibLHGmfpWt+uew4CmvgS2T69/9KQ==');
define('AUTH_SALT',        'qq9ZrCU8x1CaGruOLiPXmT3AScuDbMZyCjYxSTPOn21fZfhofR9YJUHEpvxqV57l+QH+xMXjiNCO3YCa1zt+Ww==');
define('SECURE_AUTH_SALT', 'HMFRO9LAmMSO3ReryYxTPPWAYGAvgQSAi561hRSSmlAwBAhSe1xG82yGuyFo7vtVmyWtNP3tjIHwDuKsGiAmAQ==');
define('LOGGED_IN_SALT',   'CTHn6lmMP5Z++uFgrQ7uMrsXM0DQLEeiEYLJTpudjUdNO+Rga9GxoutZgF9edhAQ2V2hrwIlFnJ8TQQ06vH/WA==');
define('NONCE_SALT',       'hZk+7bHCWHDdFr5HOUIcwgcIfmvhOFWohCbHtpf5uXLGn22J+gOaaTq0hGLBA9X10bFBxWD/kdVB6tM1bL8BPw==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
