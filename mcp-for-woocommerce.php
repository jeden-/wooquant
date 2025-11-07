<?php
/**
 * Plugin name:       WooQuant
 * Description:       AI-powered WooCommerce & WordPress management through Model Context Protocol (MCP). Extended community version with full internationalization (EN/PL), 16 intelligent prompts, 99 tools, advanced admin panel, and user permissions. Connect with Claude Desktop, Cursor IDE, or any MCP client. Based on mcp-for-woocommerce by iOSDevSK.
 * Version:           1.2.1
 * Requires at least: 6.4
 * Tested up to:      6.8
 * Requires PHP:      8.0
 * Requires Plugins:  woocommerce
 * Author:            @jeden- (Extended version)
 * Author URI:        https://github.com/jeden-
 * Plugin URI:        https://github.com/jeden-/wooquant
 * GitHub Plugin URI: jeden-/wooquant
 * GitHub Branch:     main
 * Original Author:   Filip Dvoran (iOSDevSK)
 * License:           GPL-2.0-or-later
 * License URI:       https://spdx.org/licenses/GPL-2.0-or-later.html
 * Text Domain:       mcp-for-woocommerce
 * Domain Path:       /languages
 *
 * This plugin is an extended version of mcp-for-woocommerce by iOSDevSK.
 * Original work Copyright (C) 2024 Filip Dvoran (iOSDevSK)
 * Extended work Copyright (C) 2025 @jeden- and contributors
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * @package WordPress MCP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use McpForWoo\Core\McpStreamableTransport;
use McpForWoo\Core\WpMcp;
use McpForWoo\Core\McpStdioTransport;
use McpForWoo\Admin\Settings;
use McpForWoo\Auth\JwtAuth;
use McpForWoo\CLI\ValidateToolsCommand;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

define( 'MCPFOWO_VERSION', '1.2.1' );
define( 'MCPFOWO_PATH', plugin_dir_path( __FILE__ ) );
define( 'MCPFOWO_URL', plugin_dir_url( __FILE__ ) );
define( 'MCPFOWO_PLUGIN_FILE', __FILE__ );

// Check if Composer autoloader exists.
if ( ! file_exists( MCPFOWO_PATH . 'vendor/autoload.php' ) ) {
	wp_die(
		sprintf(
			'Please run <code>composer install</code> in the plugin directory: <code>%s</code>',
			esc_html( MCPFOWO_PATH )
		)
	);
}

require_once MCPFOWO_PATH . 'vendor/autoload.php';

/**
 * Get the WordPress MCP instance.
 *
 * @return WpMcp
 */
function WPMCP() { // phpcs:ignore
	return WpMcp::instance();
}

/**
 * Initialize automatic updates from GitHub
 */
function init_mcpfowo_updater() {
	$updateChecker = PucFactory::buildUpdateChecker(
		'https://github.com/jeden-/wooquant',
		MCPFOWO_PLUGIN_FILE,
		'mcp-for-woocommerce'
	);

	// Use main branch for updates
	$updateChecker->setBranch('main');
	
	// Optional: Enable debug mode (uncomment for testing)
	// $updateChecker->getDebugBarExtension();
}

/**
 * Initialize the plugin.
 */
function init_mcpfowo() {
	$mcp = WPMCP();

	// Initialize the STDIO transport.
	new McpStdioTransport( $mcp );

	// Initialize the Streamable transport.
	new McpStreamableTransport( $mcp );

	// Initialize the settings page.
	new Settings();

	// Initialize the JWT authentication.
	new JwtAuth();

	// Load plugin text domain for translations
	load_plugin_textdomain(
		'mcp-for-woocommerce',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}

/**
 * Register WP-CLI commands
 */
function register_mcpfowo_cli_commands() {
	if ( ! class_exists( 'WP_CLI' ) ) {
		return;
	}

	WP_CLI::add_command( 'mcp-for-woocommerce validate-tools', ValidateToolsCommand::class );
}

// Initialize automatic updates
add_action( 'init', 'init_mcpfowo_updater' );

// Initialize the plugin on plugins_loaded to ensure all dependencies are available.
add_action( 'plugins_loaded', 'init_mcpfowo' );

// Register CLI commands
add_action( 'cli_init', 'register_mcpfowo_cli_commands' );
