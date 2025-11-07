<?php
/**
 * Test script for checking translations
 * 
 * Usage: Open in browser: http://yoursite.local/wp-content/plugins/mcp-for-woocommerce/test-translations.php
 * Or run via WP CLI: wp eval-file test-translations.php
 */

// Load WordPress
require_once(__DIR__ . '/../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('You must be logged in as administrator to run this test.');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test tłumaczeń WooQuant</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Test tłumaczeń WooQuant</h1>
    
    <?php
    // Load plugin text domain
    load_plugin_textdomain('mcp-for-woocommerce', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    echo '<h2>Informacje o środowisku</h2>';
    echo '<ul>';
    echo '<li><strong>WordPress:</strong> ' . get_bloginfo('version') . '</li>';
    echo '<li><strong>Locale:</strong> ' . get_locale() . '</li>';
    echo '<li><strong>Text Domain:</strong> mcp-for-woocommerce</li>';
    echo '<li><strong>Plugin Path:</strong> ' . plugin_dir_path(__FILE__) . '</li>';
    echo '</ul>';
    
    // Check if plugin is active
    $plugin_file = 'mcp-for-woocommerce/mcp-for-woocommerce.php';
    $is_active = is_plugin_active($plugin_file);
    echo '<h2>Status pluginu</h2>';
    if ($is_active) {
        echo '<p class="success">✅ Plugin jest aktywny</p>';
    } else {
        echo '<p class="error">❌ Plugin nie jest aktywny</p>';
    }
    
    // Check translation files
    echo '<h2>Pliki tłumaczeń</h2>';
    $lang_dir = plugin_dir_path(__FILE__) . 'languages/';
    $files_to_check = [
        'mcp-for-woocommerce-pl_PL.po',
        'mcp-for-woocommerce-pl_PL.mo',
        'mcp-for-woocommerce-pl_PL.json',
        'mcp-for-woocommerce-pl_PL-e38ec5a49f598f8c2e6f.json'
    ];
    
    echo '<table>';
    echo '<tr><th>Plik</th><th>Status</th><th>Rozmiar</th></tr>';
    foreach ($files_to_check as $file) {
        $file_path = $lang_dir . $file;
        if (file_exists($file_path)) {
            $size = filesize($file_path);
            $size_kb = round($size / 1024, 2);
            echo '<tr><td>' . $file . '</td><td class="success">✅ Istnieje</td><td>' . $size_kb . ' KB</td></tr>';
        } else {
            echo '<tr><td>' . $file . '</td><td class="error">❌ Brak</td><td>-</td></tr>';
        }
    }
    echo '</table>';
    
    // Test translations
    echo '<h2>Test tłumaczeń PHP (backend)</h2>';
    $test_strings = [
        'Enable MCP functionality',
        'Settings',
        'Authentication Tokens',
        'Tools',
        'Resources',
        'Prompts',
        'Documentation',
        'Save Settings',
        'Never expires',
        'Active (Never expires)'
    ];
    
    echo '<table>';
    echo '<tr><th>Oryginał (EN)</th><th>Tłumaczenie (PL)</th><th>Status</th></tr>';
    foreach ($test_strings as $string) {
        $translated = __($string, 'mcp-for-woocommerce');
        $is_translated = ($translated !== $string);
        $status_class = $is_translated ? 'success' : 'error';
        $status_text = $is_translated ? '✅ Przetłumaczone' : '❌ Brak tłumaczenia';
        echo '<tr>';
        echo '<td>' . htmlspecialchars($string) . '</td>';
        echo '<td>' . htmlspecialchars($translated) . '</td>';
        echo '<td class="' . $status_class . '">' . $status_text . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    
    // Check JSON translations
    echo '<h2>Test tłumaczeń JSON (frontend)</h2>';
    $json_file = $lang_dir . 'mcp-for-woocommerce-pl_PL-e38ec5a49f598f8c2e6f.json';
    if (file_exists($json_file)) {
        $json_content = file_get_contents($json_file);
        $json_data = json_decode($json_content, true);
        
        if ($json_data && isset($json_data['locale_data']['mcp-for-woocommerce'])) {
            $translations = $json_data['locale_data']['mcp-for-woocommerce'];
            $translation_count = count($translations);
            echo '<p class="success">✅ Plik JSON zawiera ' . $translation_count . ' wpisów</p>';
            
            // Test some translations from JSON
            echo '<table>';
            echo '<tr><th>Oryginał (EN)</th><th>Tłumaczenie (PL)</th><th>Status</th></tr>';
            foreach ($test_strings as $string) {
                if (isset($translations[$string])) {
                    $translated = $translations[$string][1] ?? $string;
                    $is_translated = ($translated !== $string && !empty($translated));
                    $status_class = $is_translated ? 'success' : 'warning';
                    $status_text = $is_translated ? '✅ Przetłumaczone' : '⚠️ Puste';
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($string) . '</td>';
                    echo '<td>' . htmlspecialchars($translated) . '</td>';
                    echo '<td class="' . $status_class . '">' . $status_text . '</td>';
                    echo '</tr>';
                } else {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($string) . '</td>';
                    echo '<td>-</td>';
                    echo '<td class="error">❌ Brak w JSON</td>';
                    echo '</tr>';
                }
            }
            echo '</table>';
        } else {
            echo '<p class="error">❌ Nieprawidłowa struktura pliku JSON</p>';
        }
    } else {
        echo '<p class="error">❌ Plik JSON z hashem nie istnieje</p>';
    }
    
    // Check plugin settings
    echo '<h2>Ustawienia pluginu</h2>';
    $settings = get_option('mcpfowo_settings', []);
    $jwt_required = get_option('mcpfowo_jwt_required', true);
    echo '<ul>';
    echo '<li><strong>MCP enabled:</strong> ' . (isset($settings['enabled']) && $settings['enabled'] ? 'Tak' : 'Nie') . '</li>';
    echo '<li><strong>JWT required:</strong> ' . ($jwt_required ? 'Tak' : 'Nie') . '</li>';
    echo '<li><strong>Write operations:</strong> ' . (isset($settings['enable_write_operations']) && $settings['enable_write_operations'] ? 'Tak' : 'Nie') . '</li>';
    echo '</ul>';
    
    // Check REST API
    echo '<h2>REST API</h2>';
    $rest_url = rest_url('mcpfowo/v1/tools');
    echo '<p><strong>REST API URL:</strong> <a href="' . esc_url($rest_url) . '" target="_blank">' . esc_html($rest_url) . '</a></p>';
    ?>
    
    <h2>Instrukcje testowania</h2>
    <ol>
        <li>Przejdź do <strong>WordPress Admin → Ustawienia → WooQuant</strong></li>
        <li>Sprawdź czy interfejs wyświetla się poprawnie</li>
        <li>Sprawdź czy wszystkie teksty są po polsku</li>
        <li>Sprawdź czy tłumaczenia działają w zakładkach: Settings, Tools, Resources, Prompts, Documentation</li>
        <li>Sprawdź czy tłumaczenia działają w sekcji Authentication Tokens</li>
    </ol>
    
    <p><strong>Uwaga:</strong> Po zakończeniu testów usuń ten plik ze względów bezpieczeństwa.</p>
</body>
</html>






