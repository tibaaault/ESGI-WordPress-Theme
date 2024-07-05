<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link https://fr.wordpress.org/support/article/editing-wp-config-php/ Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'esgi_wordpress' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'root' );

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
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '.)5B1WQ(l`$t6Hm^[7#YT5v>$vMC(;aKV-pkBO 9htE*eRi:;Hu7IkH.,wdB`Rt>' );
define( 'SECURE_AUTH_KEY',  ']HtO1WW51OtX=Do7Q`~>A6B(fi#^P^yEozo9ml,S%AFAGT;gA/(QaEq}j))<k2(x' );
define( 'LOGGED_IN_KEY',    'yY5T3aR3ggEYMCy}YJ`d@+,F=J9n|{(iq{L~QVpmg<,llldd.@Y`iwS+GFajyb`H' );
define( 'NONCE_KEY',        '92ri$v{j&_A2%h?}L0Li?!fP>CCCHuB7MIx`*8:t^j70! *z1mD%/44VoyX0Zya>' );
define( 'AUTH_SALT',        'c=C~h^].=XrR)%u2<@F8AH^).$VC~2]yX@09m@&c2-SjZ+2eL.CVOw~N|K8<W2L*' );
define( 'SECURE_AUTH_SALT', 'dl;`NkMJ/pbLU||z)2I<+>tH(!i,_^^%e5SWLwp4V+*)~J7G:6aQ&%.?nyO9G6v&' );
define( 'LOGGED_IN_SALT',   ' 3+t 2Hm)DOTl6E1(K&xzI3e5!O~]Fsd(h|#im!_R 6c_4QDk{VBOfs-,1BTIG!,' );
define( 'NONCE_SALT',       'UF7?Uo&X$v:s$0f:LiO+E5b>:-N4|FV[?S+~Za]GMRpe;FT6qO1#&o-Ur@[sDzv4' );
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
 * Pour les développeurs et développeuses : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs et développeuses d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur la documentation.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
