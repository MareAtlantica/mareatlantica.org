<?php
/** 
 * A configuración básica do WordPress.
 *
 * Este ficheiro define os seguintes parámetros: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, e ABSPATH. Podes obter máis información
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} no Codex. As definicións de MySQL estarán dispoñibles no teu servizo de aloxamento.
 *
 * Este ficheiro é usado para crear o script  wp-config.php, durante
 * a instalación, mais non tes que usar esa funcionalidade se non o desexas. 
 * Garda este ficheiro como "wp-config.php" e completa os valores.
 *
 * @package WordPress
 */

// ** Definicións de MySQL - obtén estes datos no teu servizo de aloxamento** //
/** O nome da base de datos do WordPress */
define('DB_NAME', 'nome_da_base_de_datos_aquí');

/** O nome do usuario de MySQL */
define('DB_USER', 'nome_de_usuario_aquí');

/** O contrasinal do usuario de MySQL  */
define('DB_PASSWORD', 'contrasinal_aquí');

/** O nome do servidor de  MySQL  */
define('DB_HOST', 'localhost');

/** O "Database Charset" a usar na creación das táboas. */
define('DB_CHARSET', 'utf8');

/** O "Database Collate type". Se tes dúbidas non o cambies. */
define('DB_COLLATE', '');

/**#@+
 * Chaves Únicas de autenticación.
 *
 * Cámbiaas para frases únicas e diferentes!
 * Podes xerar frases automaticamente no {@link https://api.wordpress.org/secret-key/1.1/salt/ servizo de chaves secretas de WordPress.org}
 * Podes cambiar estes valores en calquera momento para invalidar todas as cookies existentes, o que terá como resultado obrigar a todos os usuarios a reiniciar a sesión.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'pon a túa frase única aquí');
define('SECURE_AUTH_KEY',  'pon a túa frase única aquí');
define('LOGGED_IN_KEY',    'pon a túa frase única aquí');
define('NONCE_KEY',        'pon a túa frase única aquí');
define('AUTH_SALT',        'pon a túa frase única aquí');
define('SECURE_AUTH_SALT', 'pon a túa frase única aquí');
define('LOGGED_IN_SALT',   'pon a túa frase única aquí');
define('NONCE_SALT',       'pon a túa frase única aquí');

/**#@-*/

/**
 * Prefixo das táboas de WordPress.
 *
 * Podes dar soporte a múltiples instalacións nunha soa base de datos, ao dar a cada
 * instalación un prefixo único. Só números, letras e guións baixos, por favor.
 */
$table_prefix  = 'wp_';

/**
 * Idioma de localización do WordPress, Galego por omisión.
 *
 * Cambia isto para localizar o WordPress. Un ficheiro MO correspondendo ao idioma
 * escollido deberá existir no cartafol wp-content/languages. Instala por exemplo
 * fr_FR.mo en wp-content/languages e define WPLANG como 'fr_FR' para activar o
 * soporte para a lingua francesa.
 */
define ('WPLANG', 'gl_ES');

/**
 * Para desenvolvedores: WordPress en modo debugging.
 *
 * Cambia isto para true para amosar avisos durante o desenvolvemento.
 * É especialmente recomendado para os autores de temas e plugins usaren WP_DEBUG
 * no seu ambiente de desenvolvemento.
 */
define('WP_DEBUG', false);

/* Isto é todo. para de editar! Bo blogging! */

/** Camiño absoluto para o cartafol do WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Define as variables do WordPress e ficheiros a incluír. */
require_once(ABSPATH . 'wp-settings.php');
