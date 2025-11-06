<?php
/**
 * Test class for McpWooReports
 *
 * @package McpForWoo\Tests\Tools
 */

namespace McpForWoo\Tests\Tools;

use McpForWoo\Tools\McpWooReports;
use WP_UnitTestCase;
use WP_REST_Request;
use WP_User;
use WC_Product_Simple;
use WC_Order;

/**
 * Test class for McpWooReports
 */
final class McpWooReportsTest extends WP_UnitTestCase {

	/**
	 * The admin user.
	 *
	 * @var WP_User
	 */
	private WP_User $admin_user;

	/**
	 * Set up the test.
	 */
	public function setUp(): void {
		parent::setUp();

		// Create an admin user.
		$this->admin_user = $this->factory->user->create_and_get(
			array(
				'role' => 'administrator',
			)
		);

		// Activate WooCommerce if not already active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			activate_plugin( 'woocommerce' );
		}

		// Initialize WooCommerce.
		if ( ! did_action( 'woocommerce_init' ) ) {
			do_action( 'woocommerce_init' );
		}

		// Initialize the REST API and MCP.
		do_action( 'rest_api_init' );
	}

	/**
	 * Tear down the test.
	 */
	public function tearDown(): void {
		parent::tearDown();

		// Deactivate WooCommerce after tests.
		deactivate_plugins( 'woocommerce' );
	}

	/**
	 * Test the wc_get_sales_report tool.
	 */
	public function test_wc_get_sales_report_tool(): void {
		// Skip test if WooCommerce is not active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->markTestSkipped( 'WooCommerce is not active.' );
		}

		// Create test products.
		$product1 = new WC_Product_Simple();
		$product1->set_name( 'Test Product 1' );
		$product1->set_regular_price( 100 );
		$product1->save();

		$product2 = new WC_Product_Simple();
		$product2->set_name( 'Test Product 2' );
		$product2->set_regular_price( 200 );
		$product2->save();

		// Create test orders.
		$order1 = wc_create_order();
		$order1->add_product( $product1, 2 );
		$order1->add_product( $product2, 1 );
		$order1->set_status( 'completed' );
		$order1->calculate_totals();
		$order1->save();

		$order2 = wc_create_order();
		$order2->add_product( $product1, 1 );
		$order2->set_status( 'completed' );
		$order2->calculate_totals();
		$order2->save();

		// Create a REST request.
		$request = new WP_REST_Request( 'POST', '/wp/v2/wpmcp' );

		// Set the request body as JSON.
		$request->set_body(
			wp_json_encode(
				array(
					'method'    => 'tools/call',
					'name'      => 'wc_get_sales_report',
					'arguments' => array(
						'period' => 'last_30_days',
						'status' => array( 'completed', 'processing' ),
					),
				)
			)
		);

		// Set content type header.
		$request->add_header( 'Content-Type', 'application/json' );

		// Set the current user.
		wp_set_current_user( $this->admin_user->ID );

		// Dispatch the request.
		$response = rest_do_request( $request );
		$response_json = json_decode( $response->get_data()['content'][0]['text'], true );

		// Check the response.
		$this->assertEquals( 200, $response->get_status() );
		$this->assertArrayHasKey( 'content', $response->get_data() );
		$this->assertIsArray( $response->get_data()['content'] );

		// Verify sales report structure.
		$this->assertTrue( $response_json['success'] );
		$this->assertArrayHasKey( 'totals', $response_json );
		$this->assertArrayHasKey( 'total_sales', $response_json['totals'] );
		$this->assertArrayHasKey( 'total_orders', $response_json['totals'] );
		$this->assertArrayHasKey( 'average_order_value', $response_json['totals'] );
	}

	/**
	 * Test the wc_get_stock_report tool.
	 */
	public function test_wc_get_stock_report_tool(): void {
		// Skip test if WooCommerce is not active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->markTestSkipped( 'WooCommerce is not active.' );
		}

		// Create test products with different stock statuses.
		$product_instock = new WC_Product_Simple();
		$product_instock->set_name( 'In Stock Product' );
		$product_instock->set_manage_stock( true );
		$product_instock->set_stock_quantity( 50 );
		$product_instock->set_stock_status( 'instock' );
		$product_instock->save();

		$product_outofstock = new WC_Product_Simple();
		$product_outofstock->set_name( 'Out of Stock Product' );
		$product_outofstock->set_stock_status( 'outofstock' );
		$product_outofstock->save();

		$product_lowstock = new WC_Product_Simple();
		$product_lowstock->set_name( 'Low Stock Product' );
		$product_lowstock->set_manage_stock( true );
		$product_lowstock->set_stock_quantity( 5 );
		$product_lowstock->set_stock_status( 'instock' );
		$product_lowstock->save();

		// Create a REST request.
		$request = new WP_REST_Request( 'POST', '/wp/v2/wpmcp' );

		// Set the request body as JSON.
		$request->set_body(
			wp_json_encode(
				array(
					'method'    => 'tools/call',
					'name'      => 'wc_get_stock_report',
					'arguments' => array(
						'stock_status' => 'all',
						'low_stock_threshold' => 10,
					),
				)
			)
		);

		// Set content type header.
		$request->add_header( 'Content-Type', 'application/json' );

		// Set the current user.
		wp_set_current_user( $this->admin_user->ID );

		// Dispatch the request.
		$response = rest_do_request( $request );
		$response_json = json_decode( $response->get_data()['content'][0]['text'], true );

		// Check the response.
		$this->assertEquals( 200, $response->get_status() );
		$this->assertTrue( $response_json['success'] );
		$this->assertArrayHasKey( 'report', $response_json );
		$this->assertArrayHasKey( 'totals', $response_json['report'] );
		$this->assertArrayHasKey( 'instock_count', $response_json['report']['totals'] );
		$this->assertArrayHasKey( 'outofstock_count', $response_json['report']['totals'] );
		$this->assertArrayHasKey( 'low_stock_count', $response_json['report']['totals'] );
	}

	/**
	 * Test the wc_get_customer_report tool.
	 */
	public function test_wc_get_customer_report_tool(): void {
		// Skip test if WooCommerce is not active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->markTestSkipped( 'WooCommerce is not active.' );
		}

		// Create a test customer.
		$customer = $this->factory->user->create(
			array(
				'role' => 'customer',
				'user_email' => 'testcustomer@example.com',
			)
		);

		// Create test product.
		$product = new WC_Product_Simple();
		$product->set_name( 'Test Product' );
		$product->set_regular_price( 100 );
		$product->save();

		// Create test order for customer.
		$order = wc_create_order();
		$order->set_customer_id( $customer );
		$order->add_product( $product, 2 );
		$order->set_status( 'completed' );
		$order->calculate_totals();
		$order->save();

		// Create a REST request.
		$request = new WP_REST_Request( 'POST', '/wp/v2/wpmcp' );

		// Set the request body as JSON.
		$request->set_body(
			wp_json_encode(
				array(
					'method'    => 'tools/call',
					'name'      => 'wc_get_customer_report',
					'arguments' => array(
						'customer_id' => $customer,
					),
				)
			)
		);

		// Set content type header.
		$request->add_header( 'Content-Type', 'application/json' );

		// Set the current user.
		wp_set_current_user( $this->admin_user->ID );

		// Dispatch the request.
		$response = rest_do_request( $request );
		$response_json = json_decode( $response->get_data()['content'][0]['text'], true );

		// Check the response.
		$this->assertEquals( 200, $response->get_status() );
		$this->assertTrue( $response_json['success'] );
		$this->assertArrayHasKey( 'customer', $response_json );
		$this->assertArrayHasKey( 'order_count', $response_json['customer'] );
		$this->assertArrayHasKey( 'total_spent', $response_json['customer'] );
		$this->assertArrayHasKey( 'order_history', $response_json['customer'] );
	}
}

