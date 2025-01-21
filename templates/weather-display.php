<?php
// templates/weather-display.php

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="custom-location-weather">
    <div class="location">
        <?php echo esc_html($location); ?>
    </div>
    
    <div class="datetime">
        <?php echo esc_html(wp_date('l, F j, Y g:i A', strtotime($current_time))); ?>
    </div>
    
    <?php if ($weather_data): ?>
        <div class="weather-main">
            <?php if (isset($weather_data['weather'][0]['icon'])): ?>
                <img class="weather-icon" 
                     src="<?php echo esc_url($this->get_weather_icon($weather_data['weather'][0]['icon'])); ?>" 
                     alt="<?php echo esc_attr($weather_data['weather'][0]['description']); ?>"
                     width="50" height="50">
            <?php endif; ?>
            
            <span class="temperature" data-celsius="<?php echo esc_attr($weather_data['main']['temp']); ?>">
                <?php 
                $temp = $weather_data['main']['temp'];
                if ($default_unit === 'fahrenheit') {
                    $temp = ($temp * 9/5) + 32;
                    echo esc_html(number_format($temp, 1)) . '°F';
                } else {
                    echo esc_html(number_format($temp, 1)) . '°C';
                }
                ?>
            </span>
            
            <button class="unit-toggle" data-unit="<?php echo esc_attr($default_unit); ?>">
                Switch to <?php echo $default_unit === 'celsius' ? 'Fahrenheit' : 'Celsius'; ?>
            </button>
        </div>
        
        <div class="weather-details">
            <div>Condition: <?php echo esc_html(ucfirst($weather_data['weather'][0]['description'])); ?></div>
            <div>Humidity: <?php echo esc_html($weather_data['main']['humidity']); ?>%</div>
            <?php if (isset($weather_data['wind']['speed'])): ?>
                <div>Wind Speed: <?php echo esc_html(number_format($weather_data['wind']['speed'] * 3.6, 1)); ?> km/h</div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php if (!get_option('clw_api_key')): ?>
            <p>Please configure your OpenWeatherMap API key in the WordPress admin settings.</p>
        <?php else: ?>
            <p>Weather data currently unavailable. Please check back later.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>