<?php
/**
 * Plugin Name: Custom Location Weather & Time
 * Plugin URI: https://paulanunobi.com/custom-location-weather
 * Description: Displays current weather and time for any location with temperature unit toggle
 * Version: 1.0.0
 * Author: Paul Anunobi
 * License: GPL v2 or later
 * Text Domain: custom-location-weather
 * 
 * This plugin provides a customizable weather and time display widget
 * that can be used anywhere on your WordPress site through a shortcode.
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 * 
 * Handles all functionality of the Custom Location Weather plugin
 */
class CustomLocationWeather {
    /**
     * Singleton instance
     *
     * @var CustomLocationWeather|null
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     *
     * @return CustomLocationWeather
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     * 
     * Sets up hooks and initializes the plugin
     */
    private function __construct() {
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Frontend hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_shortcode('custom_weather_time', array($this, 'weather_time_shortcode'));
    }

    /**
     * Enqueue frontend assets
     * 
     * Loads CSS and JavaScript files for the weather display
     */
    public function enqueue_assets() {
        // Enqueue CSS
        wp_enqueue_style(
            'custom-location-weather',
            plugins_url('assets/css/style.css', __FILE__),
            array(),
            '1.0.0'
        );
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'custom-location-weather',
            plugins_url('assets/js/script.js', __FILE__),
            array('jquery'),
            '1.0.0',
            true
        );
    }

    /**
     * Add settings page to WordPress admin
     */
    public function add_admin_menu() {
        add_options_page(
            'Custom Location Weather Settings', // Page title
            'Weather Display',                  // Menu title
            'manage_options',                   // Capability required
            'custom-location-weather',          // Menu slug
            array($this, 'settings_page')       // Callback function
        );
    }

    /**
     * Register plugin settings
     * 
     * Sets up the options that will be stored in the database
     */
    public function register_settings() {
        // API Key setting
        register_setting('clw_options', 'clw_api_key');
        
        // Location settings
        register_setting('clw_options', 'clw_city');
        register_setting('clw_options', 'clw_country_code');
        
        // Display preferences
        register_setting('clw_options', 'clw_default_unit', array(
            'default' => 'celsius'
        ));
    }

    /**
     * Render settings page
     * 
     * Creates the admin interface for plugin configuration
     */
    public function settings_page() {
        // Include the settings page template
        require_once plugin_dir_path(__FILE__) . 'templates/admin-settings.php';
    }

    /**
     * Get weather icon path
     *
     * Maps OpenWeatherMap icon codes to local SVG icons
     * 
     * @param string $weather_code The weather condition code from OpenWeatherMap
     * @return string The URL to the appropriate weather icon
     */
    private function get_weather_icon($weather_code) {
        // Map weather codes to icon files
        $icon_mapping = array(
            // Clear sky
            '01d' => 'clear',
            '01n' => 'clear',
            // Clouds
            '02d' => 'clouds',
            '02n' => 'clouds',
            '03d' => 'clouds',
            '03n' => 'clouds',
            '04d' => 'clouds',
            '04n' => 'clouds',
            // Rain
            '09d' => 'rain',
            '09n' => 'rain',
            '10d' => 'rain',
            '10n' => 'rain',
            // Thunderstorm
            '11d' => 'thunderstorm',
            '11n' => 'thunderstorm',
            // Snow
            '13d' => 'snow',
            '13n' => 'snow',
            // Mist
            '50d' => 'mist',
            '50n' => 'mist'
        );
        
        $icon_name = isset($icon_mapping[$weather_code]) ? $icon_mapping[$weather_code] : 'clouds';
        return plugins_url('assets/images/' . $icon_name . '.svg', __FILE__);
    }

    /**
     * Fetch weather data from OpenWeatherMap API
     *
     * @return array|false Weather data array or false on failure
     */
    public function get_weather_data() {
        // Get settings
        $api_key = get_option('clw_api_key');
        $city = get_option('clw_city', 'Brandon');
        $country = get_option('clw_country_code', 'CA');
        
        // Verify API key exists
        if (empty($api_key)) {
            return false;
        }

        // Check cache first
        $cache_key = 'weather_data_' . sanitize_title($city . '_' . $country);
        $cached_data = get_transient($cache_key);
        if (false !== $cached_data) {
            return $cached_data;
        }

        // Build API URL
        $url = sprintf(
            'https://api.openweathermap.org/data/2.5/weather?q=%s,%s&appid=%s&units=metric',
            urlencode($city),
            urlencode($country),
            $api_key
        );

        // Make API request
        $response = wp_remote_get($url);
        if (is_wp_error($response)) {
            return false;
        }

        // Parse response
        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (isset($data['main']['temp'])) {
            // Cache for 30 minutes
            set_transient($cache_key, $data, 1800);
            return $data;
        }

        return false;
    }

    /**
     * Generate weather display shortcode output
     *
     * @param array $atts Shortcode attributes (unused currently)
     * @return string HTML output for weather display
     */
    public function weather_time_shortcode($atts) {
        // Get weather data and settings
        $weather_data = $this->get_weather_data();
        $current_time = current_time('Y-m-d H:i:s');
        $default_unit = get_option('clw_default_unit', 'celsius');
        $location = get_option('clw_city', 'Brandon') . ', ' . get_option('clw_country_code', 'CA');
        
        // Start output buffering
        ob_start();
        
        // Include the template file
        require plugin_dir_path(__FILE__) . 'templates/weather-display.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
}

/**
 * Initialize the plugin
 *
 * @return CustomLocationWeather
 */
function custom_location_weather() {
    return CustomLocationWeather::get_instance();
}

// Hook into WordPress init
add_action('plugins_loaded', 'custom_location_weather');