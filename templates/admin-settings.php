<?php
// templates/admin-settings.php

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

// Verify user capabilities
if (!current_user_can('manage_options')) {
    return;
}
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="notice notice-info">
        <p><strong><?php esc_html_e('Need an API key?', 'custom-location-weather'); ?></strong></p>
        <p><?php esc_html_e('To use this plugin, you need a free API key from OpenWeatherMap. Follow these steps:', 'custom-location-weather'); ?></p>
        <ol>
            <li><?php 
                printf(
                    '<a href="%s" target="_blank">%s</a>',
                    esc_url('https://openweathermap.org/api'),
                    esc_html__('Visit OpenWeatherMap API', 'custom-location-weather')
                );
            ?></li>
            <li><?php esc_html_e('Sign up for a free account', 'custom-location-weather'); ?></li>
            <li><?php esc_html_e('Once registered, go to your account\'s "API keys" section', 'custom-location-weather'); ?></li>
            <li><?php esc_html_e('Copy your API key and paste it below', 'custom-location-weather'); ?></li>
        </ol>
        <p><em><?php esc_html_e('Note: New API keys take approximately 2-4 hours to activate after creation.', 'custom-location-weather'); ?></em></p>
    </div>

    <form method="post" action="options.php">
        <?php
        settings_fields('custweather_options');
        do_settings_sections('custweather_options');
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php esc_html_e('OpenWeatherMap API Key', 'custom-location-weather'); ?></th>
                <td>
                    <input type="text" 
                           name="custweather_api_key" 
                           class="regular-text"
                           value="<?php echo esc_attr(get_option('custweather_api_key')); ?>" />
                    <p class="description"><?php esc_html_e('Enter your OpenWeatherMap API key here.', 'custom-location-weather'); ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('City Name', 'custom-location-weather'); ?></th>
                <td>
                    <input type="text" 
                           name="custweather_city" 
                           class="regular-text"
                           value="<?php echo esc_attr(get_option('custweather_city', 'Brandon')); ?>" />
                    <p class="description"><?php esc_html_e('Enter the city name (e.g., Brandon, London, Paris)', 'custom-location-weather'); ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Country Code', 'custom-location-weather'); ?></th>
                <td>
                    <input type="text" 
                           name="custweather_country_code" 
                           class="small-text"
                           maxlength="2"
                           value="<?php echo esc_attr(get_option('custweather_country_code', 'CA')); ?>" />
                    <p class="description"><?php esc_html_e('Enter the two-letter country code (e.g., CA, US, GB)', 'custom-location-weather'); ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Default Temperature Unit', 'custom-location-weather'); ?></th>
                <td>
                    <select name="custweather_default_unit">
                        <option value="celsius" <?php selected(get_option('custweather_default_unit', 'celsius'), 'celsius'); ?>>
                            <?php esc_html_e('Celsius', 'custom-location-weather'); ?>
                        </option>
                        <option value="fahrenheit" <?php selected(get_option('custweather_default_unit', 'celsius'), 'fahrenheit'); ?>>
                            <?php esc_html_e('Fahrenheit', 'custom-location-weather'); ?>
                        </option>
                    </select>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <div class="card" style="max-width: 800px; margin-top: 20px;">
        <h2><?php esc_html_e('How to Use', 'custom-location-weather'); ?></h2>
        <p><?php esc_html_e('Use this shortcode to display the weather and time information on any page or post:', 'custom-location-weather'); ?></p>
        <code style="background: #f0f0f1; padding: 5px 10px;">[custweather_display]</code>
    </div>
</div>