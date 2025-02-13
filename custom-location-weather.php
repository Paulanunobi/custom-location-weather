<?php
/**
 * Custom Location Weather
 *
 * @package           CustomLocationWeather
 * @author            Paul Anunobi
 * @copyright         2025 Paul Anunobi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Location Weather
 * Plugin URI:        https://paulanunobi.com/custom-location-weather
 * Description:       Displays current weather and time for any location with temperature unit toggle
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Paul Anunobi
 * Author URI:        https://paulanunobi.com
 * Text Domain:       custom-location-weather
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
 
 // Prevent direct file access
 if (!defined('ABSPATH')) {
     exit;
 }
 
 // Define plugin constants with unique prefix
 define('CUSTWEATHER_VERSION', '1.0.0');
 define('CUSTWEATHER_PLUGIN_DIR', plugin_dir_path(__FILE__));
 define('CUSTWEATHER_PLUGIN_URL', plugin_dir_url(__FILE__));
 
 /**
  * Main plugin class
  */
 class Custweather_Main {
     /**
      * Singleton instance
      *
      * @var Custweather_Main|null
      */
     private static $instance = null;
     
     /**
      * Get singleton instance
      *
      * @return Custweather_Main
      */
     public static function get_instance() {
         if (null === self::$instance) {
             self::$instance = new self();
         }
         return self::$instance;
     }
 
     /**
      * Constructor
      */
     private function __construct() {
         add_action('init', array($this, 'load_textdomain'));
         add_action('admin_menu', array($this, 'add_admin_menu'));
         add_action('admin_init', array($this, 'register_settings'));
         add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
         add_shortcode('custweather_display', array($this, 'weather_time_shortcode'));
     }
 
     /**
      * Load plugin textdomain
      */
     public function load_textdomain() {
         load_plugin_textdomain(
             'custom-location-weather',
             false,
             dirname(plugin_basename(__FILE__)) . '/languages'
         );
     }
 
     /**
      * Enqueue frontend assets
      */
     public function enqueue_assets() {
         wp_enqueue_style(
             'custweather-styles',
             CUSTWEATHER_PLUGIN_URL . 'assets/css/style.css',
             array(),
             CUSTWEATHER_VERSION
         );
         
         wp_enqueue_script(
             'custweather-scripts',
             CUSTWEATHER_PLUGIN_URL . 'assets/js/script.js',
             array('jquery'),
             CUSTWEATHER_VERSION,
             true
         );
 
         wp_localize_script(
             'custweather-scripts',
             'custweatherData',
             array(
                 'ajaxurl' => admin_url('admin-ajax.php'),
                 'nonce' => wp_create_nonce('custweather-nonce')
             )
         );
     }
 
     /**
      * Add settings page to WordPress admin
      */
     public function add_admin_menu() {
         add_options_page(
             esc_html__('Custom Location Weather & Time Settings', 'custom-location-weather'),
             esc_html__('Weather Display', 'custom-location-weather'),
             'manage_options',
             'custweather-settings',
             array($this, 'settings_page')
         );
     }
 
     /**
      * Register plugin settings with proper sanitization
      */
     public function register_settings() {
         $common_args = array(
             'show_in_rest' => false,
         );
 
         // API Key setting
         register_setting(
             'custweather_options',
             'custweather_api_key',
             wp_parse_args(
                 array(
                     'type' => 'string',
                     'description' => esc_html__('OpenWeatherMap API Key', 'custom-location-weather'),
                     'sanitize_callback' => 'sanitize_text_field',
                     'default' => '',
                 ),
                 $common_args
             )
         );
 
         // City setting
         register_setting(
             'custweather_options',
             'custweather_city',
             wp_parse_args(
                 array(
                     'type' => 'string',
                     'description' => esc_html__('City Name', 'custom-location-weather'),
                     'sanitize_callback' => 'sanitize_text_field',
                     'default' => 'Brandon',
                 ),
                 $common_args
             )
         );
 
         // Country code setting
         register_setting(
             'custweather_options',
             'custweather_country_code',
             wp_parse_args(
                 array(
                     'type' => 'string',
                     'description' => esc_html__('Country Code', 'custom-location-weather'),
                     'sanitize_callback' => array($this, 'sanitize_country_code'),
                     'default' => 'CA',
                 ),
                 $common_args
             )
         );
 
         // Temperature unit setting
         register_setting(
             'custweather_options',
             'custweather_default_unit',
             wp_parse_args(
                 array(
                     'type' => 'string',
                     'description' => esc_html__('Default Temperature Unit', 'custom-location-weather'),
                     'sanitize_callback' => array($this, 'sanitize_temperature_unit'),
                     'default' => 'celsius',
                 ),
                 $common_args
             )
         );
     }
 
     /**
      * Sanitize country code input
      *
      * @param string $input
      * @return string
      */
     public function sanitize_country_code($input) {
         $input = sanitize_text_field($input);
         return strtoupper(substr($input, 0, 2));
     }
 
     /**
      * Sanitize temperature unit input
      *
      * @param string $input
      * @return string
      */
     public function sanitize_temperature_unit($input) {
         $input = sanitize_text_field($input);
         return in_array($input, array('celsius', 'fahrenheit'), true) ? $input : 'celsius';
     }
 
     /**
      * Get weather icon path
      *
      * @param string $weather_code
      * @return string
      */
     private function get_weather_icon($weather_code) {
         $icon_mapping = array(
             '01d' => 'clear',
             '01n' => 'clear',
             '02d' => 'clouds',
             '02n' => 'clouds',
             '03d' => 'clouds',
             '03n' => 'clouds',
             '04d' => 'clouds',
             '04n' => 'clouds',
             '09d' => 'rain',
             '09n' => 'rain',
             '10d' => 'rain',
             '10n' => 'rain',
             '11d' => 'thunderstorm',
             '11n' => 'thunderstorm',
             '13d' => 'snow',
             '13n' => 'snow',
             '50d' => 'mist',
             '50n' => 'mist'
         );
         
         $icon_name = isset($icon_mapping[$weather_code]) ? $icon_mapping[$weather_code] : 'clouds';
         return esc_url(CUSTWEATHER_PLUGIN_URL . 'assets/images/' . $icon_name . '.svg');
     }
 
     /**
      * Fetch and cache weather data
      *
      * @return array|false
      */
     public function get_weather_data() {
         $api_key = get_option('custweather_api_key');
         $city = get_option('custweather_city', 'Brandon');
         $country = get_option('custweather_country_code', 'CA');
         
         if (empty($api_key)) {
             return false;
         }
 
         $cache_key = 'custweather_data_' . sanitize_title($city . '_' . $country);
         $cached_data = get_transient($cache_key);
         if (false !== $cached_data) {
             return $cached_data;
         }
 
         $url = sprintf(
             'https://api.openweathermap.org/data/2.5/weather?q=%s,%s&appid=%s&units=metric',
             urlencode($city),
             urlencode($country),
             $api_key
         );
 
         $response = wp_remote_get($url);
         if (is_wp_error($response)) {
             return false;
         }
 
         $data = json_decode(wp_remote_retrieve_body($response), true);
         if (isset($data['main']['temp'])) {
             set_transient($cache_key, $data, 1800); // 30 minutes cache
             return $data;
         }
 
         return false;
     }
 
     /**
      * Render settings page
      */
     public function settings_page() {
         if (!current_user_can('manage_options')) {
             return;
         }
 
         // Sanitize and verify nonce
         $nonce = isset($_POST['_wpnonce']) ? sanitize_text_field(wp_unslash($_POST['_wpnonce'])) : '';
         if (!empty($nonce) && wp_verify_nonce($nonce, 'custweather_options-options')) {
             // Handle form submission
         }
 
         include CUSTWEATHER_PLUGIN_DIR . 'templates/admin-settings.php';
     }
 
     /**
      * Generate weather display shortcode output
      *
      * @param array $atts Shortcode attributes
      * @return string
      */
     public function weather_time_shortcode($atts) {
         $weather_data = $this->get_weather_data();
         $current_time = current_time('Y-m-d H:i:s');
         $default_unit = get_option('custweather_default_unit', 'celsius');
         $location = sprintf(
             '%s, %s',
             get_option('custweather_city', 'Brandon'),
             get_option('custweather_country_code', 'CA')
         );
         
         ob_start();
         include CUSTWEATHER_PLUGIN_DIR . 'templates/weather-display.php';
         return ob_get_clean();
     }
 }
 
 /**
  * Initialize the plugin
  *
  * @return Custweather_Main
  */
 function custweather_init() {
     return Custweather_Main::get_instance();
 }
 
 // Hook into WordPress init
 add_action('plugins_loaded', 'custweather_init');