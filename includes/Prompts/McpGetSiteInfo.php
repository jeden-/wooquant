<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpGetSiteInfo
 *
 * Prompt for retrieving WordPress site information.
 * Provides access to site details, plugins, themes, and user information.
 *
 * @package McpForWoo\Prompts
 */
class McpGetSiteInfo {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_prompt' ) );
	}

	/**
	 * Register the prompt.
	 *
	 * @return void
	 */
	public function register_prompt(): void {
		new RegisterMcpPrompt(
			array(
				'name'        => 'get-site-info',
				'description' => __( 'Get detailed information about the WordPress site', 'mcp-for-woocommerce' ),
				'arguments'   => array(
					array(
						'name'        => 'info_type',
						'description' => __( 'The type of information to retrieve (e.g., general, plugins, theme, users, settings)', 'mcp-for-woocommerce' ),
						'required'    => false,
						'type'        => 'string',
					),
				),
			),
			$this->messages()
		);
	}

	/**
	 * Get the site info.
	 *
	 * @return array
	 */
	public function messages(): array {
		// the prompt.
		return array(
			array(
				'role'    => 'user',
				'content' =>
					array(
						'type' => 'text',
						'text' => 'Provide detailed information about this WordPress site. {{#if info_type}}Focus specifically on {{info_type}} information.{{/if}} Include site name, URL, WordPress version, active plugins, active theme, user roles, and any other relevant details that would be helpful for site management and troubleshooting.',
					),
			),
		);
	}
}
