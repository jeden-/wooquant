<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class McpWordPressUsersWrite
 *
 * Provides WordPress write operations for users.
 */
class McpWordPressUsersWrite {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register user write tools
	 */
	public function register_tools(): void {
		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Create User
		new RegisterMcpTool(
			array(
				'name'        => 'wp_create_user',
				'description' => __(  'Create a new WordPress user with username, email, password, and role.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'create_user' ),
				'permission_callback' => array( $this, 'check_create_users_permission' ),
				'annotations' => array(
					'title'           => 'Create User',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'username' => array(
							'type'        => 'string',
							'description' => __( 'Username (required)', 'mcp-for-woocommerce' ),
						),
						'email' => array(
							'type'        => 'string',
							'description' => __(  'Email address (required)', 'mcp-for-woocommerce' ),
						),
						'password' => array(
							'type'        => 'string',
							'description' => __(  'Password (auto-generated if not provided)', 'mcp-for-woocommerce' ),
						),
						'first_name' => array(
							'type'        => 'string',
							'description' => __(  'First name', 'mcp-for-woocommerce' ),
						),
						'last_name' => array(
							'type'        => 'string',
							'description' => __(  'Last name', 'mcp-for-woocommerce' ),
						),
						'role' => array(
							'type'        => 'string',
							'description' => __(  'User role', 'mcp-for-woocommerce' ),
							'default'     => 'subscriber',
						),
					),
					'required' => array( 'username', 'email' ),
				),
			)
		);

		// Update User
		new RegisterMcpTool(
			array(
				'name'        => 'wp_update_user',
				'description' => __(  'Update an existing WordPress user.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'update_user' ),
				'permission_callback' => array( $this, 'check_edit_users_permission' ),
				'annotations' => array(
					'title'           => 'Update User',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'User ID (required)', 'mcp-for-woocommerce' ),
						),
						'email' => array(
							'type'        => 'string',
							'description' => __(  'Email address', 'mcp-for-woocommerce' ),
						),
						'first_name' => array(
							'type'        => 'string',
							'description' => __(  'First name', 'mcp-for-woocommerce' ),
						),
						'last_name' => array(
							'type'        => 'string',
							'description' => __(  'Last name', 'mcp-for-woocommerce' ),
						),
						'password' => array(
							'type'        => 'string',
							'description' => __(  'New password', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Delete User
		new RegisterMcpTool(
			array(
				'name'        => 'wp_delete_user',
				'description' => __(  'Delete a WordPress user. DESTRUCTIVE OPERATION.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'delete_user' ),
				'permission_callback' => array( $this, 'check_delete_users_permission' ),
				'annotations' => array(
					'title'           => 'Delete User',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'User ID to delete (required)', 'mcp-for-woocommerce' ),
						),
						'reassign' => array(
							'type'        => 'integer',
							'description' => __(  'User ID to reassign posts and comments to', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Change User Role
		new RegisterMcpTool(
			array(
				'name'        => 'wp_change_user_role',
				'description' => __(  'Change a WordPress user role.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'change_user_role' ),
				'permission_callback' => array( $this, 'check_promote_users_permission' ),
				'annotations' => array(
					'title'           => 'Change User Role',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'User ID (required)', 'mcp-for-woocommerce' ),
						),
						'role' => array(
							'type'        => 'string',
							'description' => __(  'New role (required)', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'id', 'role' ),
				),
			)
		);
	}

	/**
	 * Check if user can create users
	 */
	public function check_create_users_permission(): bool {
		return current_user_can( 'create_users' );
	}

	/**
	 * Check if user can edit users
	 */
	public function check_edit_users_permission(): bool {
		return current_user_can( 'edit_users' );
	}

	/**
	 * Check if user can delete users
	 */
	public function check_delete_users_permission(): bool {
		return current_user_can( 'delete_users' );
	}

	/**
	 * Check if user can promote users
	 */
	public function check_promote_users_permission(): bool {
		return current_user_can( 'promote_users' );
	}

	/**
	 * Create a new user
	 */
	public function create_user( array $data ): array {
		try {
			if ( empty( $data['username'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Username is required',
					'code'    => 'missing_required_field',
				);
			}

			if ( empty( $data['email'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Email is required',
					'code'    => 'missing_required_field',
				);
			}

			if ( username_exists( $data['username'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Username already exists',
					'code'    => 'username_exists',
				);
			}

			if ( email_exists( $data['email'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Email already exists',
					'code'    => 'email_exists',
				);
			}

			$user_data = array(
				'user_login' => sanitize_user( $data['username'] ),
				'user_email' => sanitize_email( $data['email'] ),
				'user_pass'  => ! empty( $data['password'] ) ? $data['password'] : wp_generate_password(),
				'role'       => $data['role'] ?? 'subscriber',
			);

			if ( ! empty( $data['first_name'] ) ) {
				$user_data['first_name'] = sanitize_text_field( $data['first_name'] );
			}

			if ( ! empty( $data['last_name'] ) ) {
				$user_data['last_name'] = sanitize_text_field( $data['last_name'] );
			}

			$user_id = wp_insert_user( $user_data );

			if ( is_wp_error( $user_id ) ) {
				return array(
					'success' => false,
					'error'   => $user_id->get_error_message(),
					'code'    => 'user_creation_failed',
				);
			}

			do_action( 'mcpfowo_user_created', $user_id, $data );

			$user = get_userdata( $user_id );

			return array(
				'success' => true,
				'user_id' => $user_id,
				'username' => $user->user_login,
				'email'    => $user->user_email,
				'role'     => $user->roles[0] ?? '',
				'message'  => sprintf( 'User "%s" created successfully', $user->user_login ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'user_creation_exception',
			);
		}
	}

	/**
	 * Update an existing user
	 */
	public function update_user( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'User ID is required',
					'code'    => 'missing_user_id',
				);
			}

			$user = get_userdata( $data['id'] );

			if ( ! $user ) {
				return array(
					'success' => false,
					'error'   => 'User not found',
					'code'    => 'user_not_found',
				);
			}

			$user_data = array( 'ID' => $data['id'] );

			if ( isset( $data['email'] ) ) {
				if ( email_exists( $data['email'] ) && email_exists( $data['email'] ) !== $data['id'] ) {
					return array(
						'success' => false,
						'error'   => 'Email already exists',
						'code'    => 'email_exists',
					);
				}
				$user_data['user_email'] = sanitize_email( $data['email'] );
			}

			if ( isset( $data['password'] ) ) {
				$user_data['user_pass'] = $data['password'];
			}

			if ( isset( $data['first_name'] ) ) {
				$user_data['first_name'] = sanitize_text_field( $data['first_name'] );
			}

			if ( isset( $data['last_name'] ) ) {
				$user_data['last_name'] = sanitize_text_field( $data['last_name'] );
			}

			$user_id = wp_update_user( $user_data );

			if ( is_wp_error( $user_id ) ) {
				return array(
					'success' => false,
					'error'   => $user_id->get_error_message(),
					'code'    => 'user_update_failed',
				);
			}

			do_action( 'mcpfowo_user_updated', $user_id, $data );

			$updated_user = get_userdata( $user_id );

			return array(
				'success' => true,
				'user_id' => $user_id,
				'username' => $updated_user->user_login,
				'email'    => $updated_user->user_email,
				'message'  => sprintf( 'User "%s" updated successfully', $updated_user->user_login ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'user_update_exception',
			);
		}
	}

	/**
	 * Delete a user
	 */
	public function delete_user( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'User ID is required',
					'code'    => 'missing_user_id',
				);
			}

			$user = get_userdata( $data['id'] );

			if ( ! $user ) {
				return array(
					'success' => false,
					'error'   => 'User not found',
					'code'    => 'user_not_found',
				);
			}

			// Prevent deleting current user
			if ( get_current_user_id() === $data['id'] ) {
				return array(
					'success' => false,
					'error'   => 'Cannot delete current user',
					'code'    => 'cannot_delete_current_user',
				);
			}

			$username = $user->user_login;

			if ( ! empty( $data['reassign'] ) ) {
				$result = wp_delete_user( $data['id'], $data['reassign'] );
			} else {
				$result = wp_delete_user( $data['id'] );
			}

			if ( ! $result ) {
				return array(
					'success' => false,
					'error'   => 'Failed to delete user',
					'code'    => 'user_deletion_failed',
				);
			}

			do_action( 'mcpfowo_user_deleted', $data['id'], $username );

			return array(
				'success' => true,
				'message' => sprintf( 'User "%s" deleted successfully', $username ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'user_deletion_exception',
			);
		}
	}

	/**
	 * Change user role
	 */
	public function change_user_role( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'User ID is required',
					'code'    => 'missing_user_id',
				);
			}

			if ( empty( $data['role'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Role is required',
					'code'    => 'missing_role',
				);
			}

			$user = get_userdata( $data['id'] );

			if ( ! $user ) {
				return array(
					'success' => false,
					'error'   => 'User not found',
					'code'    => 'user_not_found',
				);
			}

			// Check if role exists
			if ( ! get_role( $data['role'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Role does not exist',
					'code'    => 'role_not_found',
				);
			}

			$user_obj = new \WP_User( $data['id'] );
			$user_obj->set_role( $data['role'] );

			do_action( 'mcpfowo_user_role_changed', $data['id'], $data['role'] );

			$updated_user = get_userdata( $data['id'] );

			return array(
				'success' => true,
				'user_id' => $data['id'],
				'username' => $updated_user->user_login,
				'old_role' => $user->roles[0] ?? '',
				'new_role' => $data['role'],
				'message'  => sprintf( 'User "%s" role changed to "%s"', $updated_user->user_login, $data['role'] ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'user_role_change_exception',
			);
		}
	}
}

