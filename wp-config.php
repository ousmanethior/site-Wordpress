<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'saimmo' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'ouze' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'malamine10' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '?{.EZ:ks|BV1NG9;ry,{h)[I:m?88z)zg)im?k6><vN(1;?}QhegYo=uttiU%r_c' );
define( 'SECURE_AUTH_KEY',  'JsnvF,VRF}l b-[PA-rl+[b2M}:lU@`.Q`I0pg;B$LV,}U@N?Ny&6f]?k^.SqU m' );
define( 'LOGGED_IN_KEY',    'F%{QxIqm4)k-o.D9HOI>-B=sBQN)N`BRyD^4O 3Uy(2A8;m9A9%Pf (xMa ?Gh%U' );
define( 'NONCE_KEY',        '~foJSUu^hwn(46poltJ(uSA@!M-|`>c4HbUC|=z]G/)$Z{9ymEd{T|%/T Mx+m$6' );
define( 'AUTH_SALT',        'u}T*uy3E%%Zl$Ag:FsEoSm!Z#/i_;u*_8he#s1rrO/ZiH80MwUr3&pMAK5{os=6?' );
define( 'SECURE_AUTH_SALT', 'W^3vAT3:;>4o(MC*0D88mY<]:omVuNL`/Bi$VVbPf^_WWB!GsUI(e{v?Y~?r3!sy' );
define( 'LOGGED_IN_SALT',   '6FJBN)f,!&.;|T*6GkK#9ln8BQyXa}=T<b?P ga]Rafv5FahRQ!{y`g,Feo}2wN%' );
define( 'NONCE_SALT',       'GarSxm7;EIz<hJ}a/Y.{LOfxr>a/8ihYOS?&`-LFdDc6.NElc@x)fHvuo}oqaWhp' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
define('FS_METHOD', 'direct');
