<?php
// templates/admin-settings.php

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h1>Custom Location Weather Settings</h1>
    
    <div class="notice notice-info">
        <p><strong>Need an API key?</strong></p>
        <p>To use this plugin, you need a free API key from OpenWeatherMap. Follow these steps:</p>
        <ol>
            <li>Visit <a href="https://openweathermap.org/api" target="_blank">OpenWeatherMap API</a></li>
            <li>Sign up for a free account</li>
            <li>Once registered, go to your account's "API keys" section</li>
            <li>Copy your API key and paste it below</li>
        </ol>
        <p><em>Note: New API keys take approximately 2-4 hours to activate after creation.</em></p>
    </div>

    <form method="post" action="options.php">
        <?php
        settings_fields('clw_options');
        do_settings_sections('clw_options');
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">OpenWeatherMap API Key</th>
                <td>
                    <input type="text" 
                           name="clw_api_key" 
                           class="regular-text"
                           value="<?php echo esc_attr(get_option('clw_api_key')); ?>" />
                    <p class="description">Enter your OpenWeatherMap API key here.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">City Name</th>
                <td>
                    <input type="text" 
                           name="clw_city" 
                           class="regular-text"
                           value="<?php echo esc_attr(get_option('clw_city', 'Brandon')); ?>" />
                    <p class="description">Enter the city name (e.g., Brandon, London, Paris)</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Country Code</th>
                <td>
                    <input type="text" 
                           name="clw_country_code" 
                           class="small-text"
                           maxlength="2"
                           value="<?php echo esc_attr(get_option('clw_country_code', 'CA')); ?>" />
                    <p class="description">Enter the two-letter country code (e.g., CA, US, GB)</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Default Temperature Unit</th>
                <td>
                    <select name="clw_default_unit">
                        <option value="celsius" <?php selected(get_option('clw_default_unit', 'celsius'), 'celsius'); ?>>Celsius</option>
                        <option value="fahrenheit" <?php selected(get_option('clw_default_unit', 'celsius'), 'fahrenheit'); ?>>Fahrenheit</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <div class="card" style="max-width: 800px; margin-top: 20px;">
        <h2>How to Use</h2>
        <p>Use this shortcode to display the weather and time information on any page or post:</p>
        <code style="background: #f0f0f1; padding: 5px 10px;">[custom_weather_time]</code>
    </div>
</div>