<?php
/**
 * Simple test runner for MCP for WooCommerce plugin
 * 
 * This script performs basic validation checks without requiring full PHPUnit setup
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	// If running standalone, we need to load WordPress
	if ( ! defined( 'WP_USE_THEMES' ) ) {
		// Try to find wp-load.php
		$wp_load_paths = array(
			dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/wp-load.php',
			dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php',
			__DIR__ . '/../../../../wp-load.php',
		);
		
		$wp_loaded = false;
		foreach ( $wp_load_paths as $path ) {
			if ( file_exists( $path ) ) {
				require_once $path;
				$wp_loaded = true;
				break;
			}
		}
		
		if ( ! $wp_loaded ) {
			die( "Error: Could not find wp-load.php. Please run this from WordPress environment.\n" );
		}
	}
}

/**
 * Test results collector
 */
class SimpleTestRunner {
	private array $tests = array();
	private int $passed = 0;
	private int $failed = 0;
	
	public function test( string $name, callable $callback ): void {
		$this->tests[] = array(
			'name' => $name,
			'callback' => $callback,
		);
	}
	
	public function run(): void {
		echo "Running basic plugin tests...\n";
		echo str_repeat( '=', 60 ) . "\n\n";
		
		foreach ( $this->tests as $test ) {
			try {
				$result = call_user_func( $test['callback'] );
				
				if ( $result === true || $result === null ) {
					echo "✓ PASS: {$test['name']}\n";
					$this->passed++;
				} else {
					echo "✗ FAIL: {$test['name']}\n";
					if ( is_string( $result ) ) {
						echo "  Reason: {$result}\n";
					}
					$this->failed++;
				}
			} catch ( Exception $e ) {
				echo "✗ ERROR: {$test['name']}\n";
				echo "  Exception: " . $e->getMessage() . "\n";
				$this->failed++;
			} catch ( Error $e ) {
				echo "✗ FATAL: {$test['name']}\n";
				echo "  Error: " . $e->getMessage() . "\n";
				$this->failed++;
			}
		}
		
		echo "\n" . str_repeat( '=', 60 ) . "\n";
		echo "Results: {$this->passed} passed, {$this->failed} failed\n";
		echo str_repeat( '=', 60 ) . "\n";
	}
}

// Initialize test runner
$runner = new SimpleTestRunner();

// Test 1: Check if plugin file exists and is readable
$runner->test( 'Plugin main file exists', function() {
	$plugin_file = dirname( __DIR__ ) . '/mcp-for-woocommerce.php';
	if ( ! file_exists( $plugin_file ) ) {
		return "Plugin file not found: {$plugin_file}";
	}
	if ( ! is_readable( $plugin_file ) ) {
		return "Plugin file is not readable: {$plugin_file}";
	}
	return true;
} );

// Test 2: Check if autoloader exists
$runner->test( 'Composer autoloader exists', function() {
	$autoloader = dirname( __DIR__ ) . '/vendor/autoload.php';
	if ( ! file_exists( $autoloader ) ) {
		return "Autoloader not found. Run: composer install";
	}
	return true;
} );

// Test 3: Check if required classes can be loaded
$runner->test( 'Core classes can be loaded', function() {
	if ( ! function_exists( 'WPMCP' ) ) {
		return "WPMCP() function not available. Plugin may not be loaded.";
	}
	
	try {
		$mcp = WPMCP();
		if ( ! is_object( $mcp ) ) {
			return "WPMCP() did not return an object";
		}
		return true;
	} catch ( Exception $e ) {
		return "Exception loading WPMCP: " . $e->getMessage();
	}
} );

// Test 4: Check namespace autoloading
$runner->test( 'Namespace autoloading works', function() {
	$classes_to_check = array(
		'McpForWoo\\Core\\WpMcp',
		'McpForWoo\\Auth\\JwtAuth',
		'McpForWoo\\Core\\McpStdioTransport',
		'McpForWoo\\Core\\McpStreamableTransport',
	);
	
	foreach ( $classes_to_check as $class ) {
		if ( ! class_exists( $class ) ) {
			return "Class not found: {$class}";
		}
	}
	return true;
} );

// Test 5: Check if plugin constants are defined
$runner->test( 'Plugin constants are defined', function() {
	$constants = array( 'MCPFOWO_VERSION', 'MCPFOWO_PATH', 'MCPFOWO_URL', 'MCPFOWO_PLUGIN_FILE' );
	
	foreach ( $constants as $constant ) {
		if ( ! defined( $constant ) ) {
			return "Constant not defined: {$constant}";
		}
	}
	return true;
} );

// Test 6: Check if WooCommerce is available (if plugin is active)
$runner->test( 'WooCommerce dependency check', function() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return "WooCommerce not loaded. This is expected if WooCommerce is not active.";
	}
	return true;
} );

// Test 7: Check if test files exist
$runner->test( 'Test files structure', function() {
	$test_dir = __DIR__ . '/phpunit';
	if ( ! is_dir( $test_dir ) ) {
		return "Test directory not found: {$test_dir}";
	}
	
	$required_test_files = array(
		'JwtAuthTest.php',
		'McpStdioTransportTest.php',
		'McpStreamableTransportTest.php',
	);
	
	foreach ( $required_test_files as $file ) {
		$file_path = $test_dir . '/' . $file;
		if ( ! file_exists( $file_path ) ) {
			return "Test file not found: {$file}";
		}
	}
	return true;
} );

// Test 8: Check PHP syntax of key files
$runner->test( 'PHP syntax validation', function() {
	$files_to_check = array(
		dirname( __DIR__ ) . '/mcp-for-woocommerce.php',
		dirname( __DIR__ ) . '/includes/Core/WpMcp.php',
		dirname( __DIR__ ) . '/includes/Auth/JwtAuth.php',
	);
	
	foreach ( $files_to_check as $file ) {
		if ( ! file_exists( $file ) ) {
			continue;
		}
		
		$output = array();
		$return_var = 0;
		exec( "php -l " . escapeshellarg( $file ) . " 2>&1", $output, $return_var );
		
		if ( $return_var !== 0 ) {
			return "Syntax error in {$file}: " . implode( "\n", $output );
		}
	}
	return true;
} );

// Test 9: Check if composer.json namespace matches actual namespaces
$runner->test( 'Composer namespace configuration', function() {
	$composer_file = dirname( __DIR__ ) . '/composer.json';
	if ( ! file_exists( $composer_file ) ) {
		return "composer.json not found";
	}
	
	$composer = json_decode( file_get_contents( $composer_file ), true );
	
	if ( ! isset( $composer['autoload']['psr-4']['McpForWoo\\'] ) ) {
		return "Composer autoload namespace 'McpForWoo\\' not configured correctly";
	}
	
	if ( $composer['autoload']['psr-4']['McpForWoo\\'] !== 'includes/' ) {
		return "Composer autoload path mismatch";
	}
	
	return true;
} );

// Run all tests
$runner->run();

exit( $runner->failed > 0 ? 1 : 0 );

