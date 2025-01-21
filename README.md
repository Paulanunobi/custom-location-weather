# custom-location-weather

# Custom Location Weather Plugin Documentation

## Overview
The Custom Location Weather Plugin is a WordPress plugin that displays current weather conditions and local time for any specified location. It provides a customizable weather widget that can be inserted anywhere on your WordPress site using a shortcode.

### Key Features
- Real-time weather data display using OpenWeatherMap API
- Customizable location settings
- Temperature unit toggle (Celsius/Fahrenheit)
- Custom weather icons
- Responsive design
- Cached data to minimize API calls
- Easy implementation via shortcode

## Installation

1. Upload the `custom-location-weather` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin settings under Settings > Weather Display

## Configuration

### Required Setup
1. Obtain an API key from OpenWeatherMap:
   - Visit https://openweathermap.org/api
   - Create a free account
   - Generate an API key
   - Wait 2-4 hours for key activation

2. Configure plugin settings:
   - Navigate to Settings > Weather Display
   - Enter your OpenWeatherMap API key
   - Set your desired location (city and country)
   - Choose default temperature unit

## Usage

### Basic Implementation
Insert the weather display using the shortcode:
```
[custom_weather_time]
```

### Display Options
The weather display includes:
- Current temperature
- Weather condition
- Humidity level
- Wind speed
- Current date and time
- Temperature unit toggle button

## Technical Documentation

### File Structure
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
│       ├── clear.svg
│       ├── clouds.svg
│       ├── rain.svg
│       ├── snow.svg
│       ├── thunderstorm.svg
│       └── mist.svg
```

### Class Documentation

#### CustomLocationWeather
Main plugin class handling core functionality.

##### Properties:
- `$instance` (private static): Stores the singleton instance

##### Methods:
1. `get_instance()`
   - Purpose: Implements singleton pattern
   - Returns: CustomLocationWeather instance

2. `enqueue_assets()`
   - Purpose: Loads CSS and JavaScript files
   - Hooks: wp_enqueue_scripts

3. `add_admin_menu()`
   - Purpose: Creates admin settings page
   - Hooks: admin_menu

4. `register_settings()`
   - Purpose: Registers plugin settings
   - Hooks: admin_init

5. `get_weather_data()`
   - Purpose: Fetches and caches weather data
   - Returns: Array|false
   - Caching: 30 minutes

6. `get_weather_icon()`
   - Purpose: Maps weather conditions to SVG icons
   - Parameters: $weather_code (string)
   - Returns: String (icon URL)

7. `weather_time_shortcode()`
   - Purpose: Generates shortcode output
   - Parameters: $atts (array)
   - Returns: String (HTML)

### Settings Storage
The plugin uses the following WordPress options:
- `clw_api_key`: OpenWeatherMap API key
- `clw_city`: Target city name
- `clw_country_code`: Two-letter country code
- `clw_default_unit`: Default temperature unit

### API Integration
- API Provider: OpenWeatherMap
- Endpoint: `api.openweathermap.org/data/2.5/weather`
- Request Method: GET
- Parameters:
  - q: City,Country
  - appid: API Key
  - units: metric

### Caching
- Weather data is cached using WordPress transients
- Cache duration: 30 minutes
- Cache key format: `weather_data_{city}_{country}`

## Security Measures
- Direct file access prevention
- Data sanitization on input/output
- API key secure storage
- XSS prevention
- CSRF protection in forms

## Best Practices Implementation
- WordPress coding standards compliance
- Proper nonce verification
- Responsive design principles
- Accessible markup
- Performance optimization through caching

## Troubleshooting

Common issues and solutions:
1. Weather data not displaying
   - Verify API key is entered correctly
   - Confirm API key is activated
   - Check city/country spelling

2. Incorrect location data
   - Verify city name spelling
   - Ensure correct country code
   - Clear WordPress cache

3. Temperature not updating
   - Clear browser cache
   - Check JavaScript console
   - Verify jQuery loading

## Support and Maintenance

### Version Updates
- Current Version: 1.0.0
- Last Updated: December 2024
- WordPress Compatibility: 6.0+
- PHP Compatibility: 7.4+

### Support Resources
- Plugin Homepage: nil
- Documentation: nil
- Support Email: anunobip@assiniboine.net

### Future Updates
Planned features for future releases:
- Multiple location support
- Extended forecast display
- Additional customization options
- Widget implementation
- Weather alerts integration
