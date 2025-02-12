=== Custom Location Weather ===
Contributors: paulanunobi
Donate link: https://paulanunobi.com/
Tags: weather, location, temperature, weather-widget, openweathermap
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display current weather conditions and local time for any specified location using OpenWeatherMap API.

== Description ==

Custom Location Weather Plugin is a versatile WordPress plugin that displays real-time weather information and local time for any location worldwide. Perfect for travel websites, local businesses, or any site needing to display weather information.

= Key Features =

* Real-time weather data display using OpenWeatherMap API
* Customizable location settings
* Temperature unit toggle (Celsius/Fahrenheit)
* Custom weather icons
* Responsive design
* Cached data to minimize API calls
* Easy implementation via shortcode

= Basic Usage =

Simply use the shortcode `[custom_weather_time]` to display weather information on any post or page.

= Display Options =

The weather display includes:
* Current temperature
* Weather condition
* Humidity level
* Wind speed
* Current date and time
* Temperature unit toggle button

== Installation ==

1. Upload the `custom-location-weather` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin settings under Settings > Weather Display
4. Obtain an API key from OpenWeatherMap:
   * Visit https://openweathermap.org/api
   * Create a free account
   * Generate an API key
   * Wait 2-4 hours for key activation
5. Enter your API key and location settings in the plugin configuration

== Frequently Asked Questions ==

= Do I need an API key? =

Yes, you need a free API key from OpenWeatherMap to use this plugin. Sign up at https://openweathermap.org/api.

= How often is the weather data updated? =

Weather data is cached for 30 minutes to optimize performance and respect API limits.

= Can I display weather for multiple locations? =

Currently, you can display one location per shortcode. Multiple location support is planned for future releases.

= What temperature units are supported? =

The plugin supports both Celsius and Fahrenheit, with an easy toggle button for users to switch between units.

= Is the plugin GDPR compliant? =

Yes, the plugin does not collect any personal data. It only retrieves weather data from OpenWeatherMap based on the configured location.

== Screenshots ==

1. Weather display on frontend (screenshot-1.png)
2. Plugin settings page (screenshot-2.png)
3. Weather display with temperature toggle (screenshot-3.png)
4. Mobile responsive view (screenshot-4.png)

== Changelog ==

= 1.0.0 =
* Initial release
* Basic weather display functionality
* Location customization
* Temperature unit toggle
* Custom weather icons
* Responsive design implementation
* API integration with caching
* Shortcode implementation

== Upgrade Notice ==

= 1.0.0 =
Initial release of Custom Location Weather Plugin. Includes all basic functionality for weather display.

== Technical Details ==

= File Structure =
```
custom-location-weather/
├── custom-location-weather.php (Main plugin file)
├── templates/
│   ├── admin-settings.php (Admin interface template)
│   └── weather-display.php (Frontend display template)
├── assets/
│   ├── css/
│   │   └── style.css (Plugin styles)
│   ├── js/
│   │   └── script.js (Frontend functionality)
│   └── images/
│       └── [weather icons]
```

= API Integration =
* Provider: OpenWeatherMap
* Endpoint: api.openweathermap.org/data/2.5/weather
* Method: GET
* Cache Duration: 30 minutes

= Security =
* Direct file access prevention
* Data sanitization
* XSS prevention
* CSRF protection
* Secure API key storage

== Support ==

For support queries, please email hello@paulanunobi.com or visit our support page at https://paulanunobi.com/custom-location-weather.

== Future Updates ==

Planned features for upcoming releases:
* Multiple location support
* Extended forecast display
* Additional customization options
* Widget implementation
* Weather alerts integration
