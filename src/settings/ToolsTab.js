/**
 * WordPress dependencies
 */
import {
	Card,
	CardHeader,
	CardBody,
	Spinner,
	ToggleControl,
	SelectControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState, useEffect, useMemo } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

/**
 * Tools Tab Component
 */
const ToolsTab = () => {
	const [ tools, setTools ] = useState( [] );
	const [ loading, setLoading ] = useState( true );
	const [ error, setError ] = useState( null );
	const [ saving, setSaving ] = useState( false );
	const [ filterType, setFilterType ] = useState( 'all' );
	const [ searchQuery, setSearchQuery ] = useState( '' );

	useEffect( () => {
		const fetchTools = async () => {
			try {
				setLoading( true );
				const response = await apiFetch( {
					path: '/wp/v2/wpmcp',
					method: 'POST',
					data: {
						jsonrpc: '2.0',
						method: 'tools/list/all',
						params: {},
					},
				} );

			if ( response && response.tools ) {
				setTools( response.tools );
			} else {
					setError(
						__( 'Failed to load tools data', 'mcp-for-woocommerce' )
					);
				}
			} catch ( err ) {
				setError(
					__( 'Error loading tools: ', 'mcp-for-woocommerce' ) + err.message
				);
			} finally {
				setLoading( false );
			}
		};

		fetchTools();
	}, [] );

	const handleToggleChange = async ( toolName, newState ) => {
		try {
			setSaving( true );
			// Update local state immediately for better UX
			setTools( ( prevTools ) =>
				prevTools.map( ( tool ) =>
					tool.name === toolName
						? { ...tool, tool_enabled: newState }
						: tool
				)
			);

			// Create form data for AJAX request
			const formData = new FormData();
			formData.append( 'action', 'mcpfowo_toggle_tool' );
			formData.append( 'nonce', window.mcpfowoSettings.nonce );
			formData.append( 'tool', toolName );
			formData.append( 'tool_enabled', newState );

			// Send AJAX request
			const response = await fetch( ajaxurl, {
				method: 'POST',
				body: formData,
				credentials: 'same-origin',
			} );

			const data = await response.json();

			if ( ! data.success ) {
				throw new Error(
					data.data.message ||
						window.mcpfowoSettings.strings.settingsError
				);
			}

			// Show success message
			setError( null );
		} catch ( err ) {
			// Revert the state if the save fails
			setTools( ( prevTools ) =>
				prevTools.map( ( tool ) =>
					tool.name === toolName
						? { ...tool, tool_enabled: ! newState }
						: tool
				)
			);
			setError(
				err.message || window.mcpfowoSettings.strings.settingsError
			);
			console.error( 'Error saving tool state:', err );
		} finally {
			setSaving( false );
		}
	};

	// Get translated label for tool type
	const getTypeLabel = ( type ) => {
		const labels = {
			read: __( 'Read', 'mcp-for-woocommerce' ),
			create: __( 'Create', 'mcp-for-woocommerce' ),
			update: __( 'Update', 'mcp-for-woocommerce' ),
			delete: __( 'Delete', 'mcp-for-woocommerce' ),
			action: __( 'Action', 'mcp-for-woocommerce' ),
		};
		return labels[ type ] || type;
	};

	// Get unique tool types for filter dropdown
	const toolTypes = useMemo( () => {
		const types = [ ...new Set( tools.map( ( tool ) => tool.type ) ) ];
		return [
			{ label: __( 'All Types', 'mcp-for-woocommerce' ), value: 'all' },
			...types.map( ( type ) => ( {
				label: getTypeLabel( type ),
				value: type,
			} ) ),
		];
	}, [ tools ] );

	// Filter tools based on type and search query
	const filteredTools = useMemo( () => {
		return tools.filter( ( tool ) => {
			const matchesType = filterType === 'all' || tool.type === filterType;
			const matchesSearch = ! searchQuery || 
				tool.name.toLowerCase().includes( searchQuery.toLowerCase() ) ||
				( tool.description && tool.description.toLowerCase().includes( searchQuery.toLowerCase() ) );
			return matchesType && matchesSearch;
		} );
	}, [ tools, filterType, searchQuery ] );

	return (
		<Card>
			<CardHeader>
				<h2>{ __( 'Registered Tools', 'mcp-for-woocommerce' ) }</h2>
			</CardHeader>
			<CardBody>
				<p>
					{ __(
						'List of all registered tools in the system. Use the toggles to enable or disable individual tools.',
						'mcp-for-woocommerce'
					) }
				</p>

				{/* Filters */}
				{ tools.length > 0 && (
					<div style={ { marginBottom: '20px', display: 'flex', gap: '15px', flexWrap: 'wrap', alignItems: 'center' } }>
						<div style={ { flex: '1', minWidth: '200px' } }>
							<SelectControl
								label={ __( 'Filter by Type', 'mcp-for-woocommerce' ) }
								value={ filterType }
								options={ toolTypes }
								onChange={ setFilterType }
							/>
						</div>
						<div style={ { flex: '1', minWidth: '200px' } }>
							<input
								type="text"
								placeholder={ __( 'Search tools...', 'mcp-for-woocommerce' ) }
								value={ searchQuery }
								onChange={ ( e ) => setSearchQuery( e.target.value ) }
								style={ { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' } }
							/>
						</div>
						<div style={ { paddingTop: '24px' } }>
							{ __( 'Total:', 'mcp-for-woocommerce' ) } { filteredTools.length } / { tools.length }
						</div>
					</div>
				) }

				{ loading ? (
					<div className="mcpfowo-loading">
						<Spinner />
						<p>{ __( 'Loading tools...', 'mcp-for-woocommerce' ) }</p>
					</div>
				) : error ? (
					<div className="mcpfowo-error">
						<p>{ error }</p>
					</div>
				) : tools.length === 0 ? (
					<p>
						{ __(
							'No tools are currently registered.',
							'mcp-for-woocommerce'
						) }
					</p>
				) : filteredTools.length === 0 ? (
					<p>
						{ __(
							'No tools match your filters.',
							'mcp-for-woocommerce'
						) }
					</p>
				) : (
					<table className="mcpfowo-table">
						<thead>
							<tr>
								<th>{ __( 'Name', 'mcp-for-woocommerce' ) }</th>
								<th>
									{ __( 'Description', 'mcp-for-woocommerce' ) }
								</th>
								<th>
									{ __(
										'Functionality Type',
										'mcp-for-woocommerce'
									) }
								</th>
								<th>{ __( 'Status', 'mcp-for-woocommerce' ) }</th>
							</tr>
						</thead>
						<tbody>
							{ filteredTools.map( ( tool ) => (
								<tr key={ tool.name }>
									<td>
										<strong>{ tool.name }</strong>
									</td>
									<td>{ tool.description }</td>
									<td>
										{ getTypeLabel( tool.type ) }
									</td>
									<td>
										<ToggleControl
											checked={
												tool.tool_enabled &&
												tool.tool_type_enabled
											}
											onChange={ ( value ) =>
												handleToggleChange(
													tool.name,
													value
												)
											}
											disabled={
												saving ||
												! tool.tool_type_enabled
											}
											label={
												tool.tool_enabled &&
												tool.tool_type_enabled
													? __(
															'Enabled',
															'mcp-for-woocommerce'
													  )
													: __(
															'Disabled',
															'mcp-for-woocommerce'
													  )
											}
										/>
									</td>
								</tr>
							) ) }
						</tbody>
					</table>
				) }
			</CardBody>
		</Card>
	);
};

export default ToolsTab;
