<?php
/**
 * PHPUnit bootstrap file - simplified version without WordPress test library
 * 
 * This allows running basic syntax and structure tests without full WP environment
 *
 * @package Wordpress_Mcp
 */

echo "Loading simplified bootstrap (without WordPress test library)...\n";

// Load Composer autoloader
$autoloader = dirname( __DIR__ ) . '/vendor/autoload.php';
if ( ! file_exists( $autoloader ) ) {
	die( "Composer autoloader not found. Run: composer install\n" );
}
require_once $autoloader;

// Mock essential WordPress functions for syntax checking
if ( ! function_exists( 'add_action' ) ) {
	function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		return true;
	}
}

if ( ! function_exists( 'add_filter' ) ) {
	function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		return true;
	}
}

if ( ! function_exists( 'do_action' ) ) {
	function do_action( $hook, ...$args ) {
		return true;
	}
}

if ( ! function_exists( 'apply_filters' ) ) {
	function apply_filters( $hook, $value, ...$args ) {
		return $value;
	}
}

if ( ! function_exists( 'get_option' ) ) {
	function get_option( $option, $default = false ) {
		return $default;
	}
}

if ( ! function_exists( 'update_option' ) ) {
	function update_option( $option, $value, $autoload = null ) {
		return true;
	}
}

if ( ! function_exists( 'delete_option' ) ) {
	function delete_option( $option ) {
		return true;
	}
}

if ( ! function_exists( 'wp_json_encode' ) ) {
	function wp_json_encode( $data, $options = 0, $depth = 512 ) {
		return json_encode( $data, $options, $depth );
	}
}

if ( ! function_exists( '__' ) ) {
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'plugin_dir_path' ) ) {
	function plugin_dir_path( $file ) {
		return dirname( $file ) . '/';
	}
}

if ( ! function_exists( 'plugin_dir_url' ) ) {
	function plugin_dir_url( $file ) {
		return 'http://example.com/' . basename( dirname( $file ) ) . '/';
	}
}

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/' );
}

if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
	define( 'WP_PLUGIN_DIR', dirname( dirname( __DIR__ ) ) );
}

echo "✓ Simplified bootstrap loaded successfully\n";
echo "Note: This is a limited environment for syntax checking only.\n";
echo "For full tests, install WordPress test library.\n\n";

