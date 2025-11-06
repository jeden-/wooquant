/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';
import { Card, CardHeader, CardBody, ToggleControl, SelectControl, Spinner, Notice, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * User Permissions Tab Component
 */
const UserPermissionsTab = () => {
	const [ users, setUsers ] = useState( [] );
	const [ roles, setRoles ] = useState( [] );
	const [ permissions, setPermissions ] = useState( {} );
	const [ isLoading, setIsLoading ] = useState( true );
	const [ isSaving, setIsSaving ] = useState( false );
	const [ notice, setNotice ] = useState( null );
	const [ rolePermissions, setRolePermissions ] = useState( {} );

	// Load users, roles and permissions on mount
	useEffect( () => {
		loadUsersAndPermissions();
	}, [] );

	/**
	 * Load users, roles and their permissions
	 */
	const loadUsersAndPermissions = async () => {
		setIsLoading( true );
		try {
			// Create form data for AJAX request
			const formData = new FormData();
			formData.append( 'action', 'mcpfowo_get_user_permissions' );
			formData.append( 'nonce', window.mcpfowoSettings.nonce );

			const response = await fetch( ajaxurl, {
				method: 'POST',
				body: formData,
				credentials: 'same-origin',
			} );

			const data = await response.json();

			if ( data.success ) {
				setUsers( data.data.users || [] );
				setRoles( data.data.roles || [] );
				setPermissions( data.data.permissions || {} );
				setRolePermissions( data.data.role_permissions || {} );
			} else {
				setNotice( {
					status: 'error',
					message: data.data?.message || __( 'Failed to load user permissions.', 'mcp-for-woocommerce' ),
				} );
			}
		} catch ( error ) {
			console.error( 'Error loading user permissions:', error );
			setNotice( {
				status: 'error',
				message: __( 'Failed to load user permissions.', 'mcp-for-woocommerce' ),
			} );
		} finally {
			setIsLoading( false );
		}
	};

	/**
	 * Handle user permission toggle
	 */
	const handleUserPermissionToggle = ( userId ) => {
		setPermissions( ( prev ) => ( {
			...prev,
			[ userId ]: ! prev[ userId ],
		} ) );
	};

	/**
	 * Handle role permission toggle
	 */
	const handleRolePermissionToggle = ( role ) => {
		setRolePermissions( ( prev ) => ( {
			...prev,
			[ role ]: ! prev[ role ],
		} ) );
	};

	/**
	 * Save permissions
	 */
	const savePermissions = async () => {
		setIsSaving( true );
		setNotice( null );

		try {
			// Create form data for AJAX request
			const formData = new FormData();
			formData.append( 'action', 'mcpfowo_save_user_permissions' );
			formData.append( 'nonce', window.mcpfowoSettings.nonce );
			formData.append( 'permissions', JSON.stringify( permissions ) );
			formData.append( 'role_permissions', JSON.stringify( rolePermissions ) );

			const response = await fetch( ajaxurl, {
				method: 'POST',
				body: formData,
				credentials: 'same-origin',
			} );

			const data = await response.json();

			if ( data.success ) {
				setNotice( {
					status: 'success',
					message: data.data?.message || __( 'User permissions saved successfully.', 'mcp-for-woocommerce' ),
				} );
			} else {
				setNotice( {
					status: 'error',
					message: data.data?.message || __( 'Failed to save user permissions.', 'mcp-for-woocommerce' ),
				} );
			}
		} catch ( error ) {
			console.error( 'Error saving user permissions:', error );
			setNotice( {
				status: 'error',
				message: __( 'Failed to save user permissions.', 'mcp-for-woocommerce' ),
			} );
		} finally {
			setIsSaving( false );
		}
	};

	if ( isLoading ) {
		return (
			<div className="mcpfowo-loading">
				<Spinner />
				<p>{ __( 'Loading user permissions...', 'mcp-for-woocommerce' ) }</p>
			</div>
		);
	}

	return (
		<div className="mcpfowo-user-permissions-tab">
			{ notice && (
				<Notice
					status={ notice.status }
					isDismissible={ true }
					onRemove={ () => setNotice( null ) }
				>
					{ notice.message }
				</Notice>
			) }

			<Card>
				<CardHeader>
					<h2>{ __( 'User Permissions for MCP', 'mcp-for-woocommerce' ) }</h2>
				</CardHeader>
				<CardBody>
					<p className="description">
						{ __( 'Control which users and roles can access MCP functionality. By default, only administrators have access.', 'mcp-for-woocommerce' ) }
					</p>

					<h3>{ __( 'Role-Based Permissions', 'mcp-for-woocommerce' ) }</h3>
					<p className="description">
						{ __( 'Enable MCP access for entire user roles. Individual user permissions override role permissions.', 'mcp-for-woocommerce' ) }
					</p>

					{ roles.length > 0 ? (
						<table className="wp-list-table widefat fixed striped">
							<thead>
								<tr>
									<th>{ __( 'Role', 'mcp-for-woocommerce' ) }</th>
									<th>{ __( 'MCP Access', 'mcp-for-woocommerce' ) }</th>
								</tr>
							</thead>
							<tbody>
								{ Object.entries( roles ).map( ( [ roleKey, roleData ] ) => (
									<tr key={ roleKey }>
										<td>
											<strong>{ roleData.name }</strong>
										</td>
										<td>
											<ToggleControl
												checked={ rolePermissions[ roleKey ] || false }
												onChange={ () => handleRolePermissionToggle( roleKey ) }
												disabled={ isSaving }
											/>
										</td>
									</tr>
								) ) }
							</tbody>
						</table>
					) : (
						<p>{ __( 'No roles found.', 'mcp-for-woocommerce' ) }</p>
					) }

					<br />
					<h3>{ __( 'Individual User Permissions', 'mcp-for-woocommerce' ) }</h3>
					<p className="description">
						{ __( 'Grant or revoke MCP access for specific users. These settings override role-based permissions.', 'mcp-for-woocommerce' ) }
					</p>

					{ users.length > 0 ? (
						<table className="wp-list-table widefat fixed striped">
							<thead>
								<tr>
									<th>{ __( 'User', 'mcp-for-woocommerce' ) }</th>
									<th>{ __( 'Role', 'mcp-for-woocommerce' ) }</th>
									<th>{ __( 'Email', 'mcp-for-woocommerce' ) }</th>
									<th>{ __( 'MCP Access', 'mcp-for-woocommerce' ) }</th>
								</tr>
							</thead>
							<tbody>
								{ users.map( ( user ) => (
									<tr key={ user.id }>
										<td>
											<strong>{ user.display_name }</strong>
											<br />
											<span className="description">@{ user.username }</span>
										</td>
										<td>{ user.roles.join( ', ' ) }</td>
										<td>{ user.email }</td>
										<td>
											<ToggleControl
												checked={ permissions[ user.id ] || false }
												onChange={ () => handleUserPermissionToggle( user.id ) }
												disabled={ isSaving }
											/>
										</td>
									</tr>
								) ) }
							</tbody>
						</table>
					) : (
						<p>{ __( 'No users found.', 'mcp-for-woocommerce' ) }</p>
					) }

					<div className="mcpfowo-form-actions">
						<Button
							isPrimary
							onClick={ savePermissions }
							isBusy={ isSaving }
							disabled={ isSaving }
						>
							{ __( 'Save Permissions', 'mcp-for-woocommerce' ) }
						</Button>
					</div>
				</CardBody>
			</Card>
		</div>
	);
};

export default UserPermissionsTab;





