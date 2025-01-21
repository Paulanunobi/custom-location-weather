// assets/js/script.js
jQuery(document).ready(function($) {
    function convertTemp(value, to) {
        if (to === 'fahrenheit') {
            return (value * 9/5) + 32;
        } else {
            return (value - 32) * 5/9;
        }
    }

    $('.custom-location-weather .unit-toggle').on('click', function() {
        const temperatureElement = $(this).siblings('.temperature');
        let currentTemp;
        let newTemp, newUnit, newButtonText;
        
        if ($(this).data('unit') === 'celsius') {
            // Get the current temperature in Celsius
            currentTemp = parseFloat(temperatureElement.data('celsius'));
            // Convert to Fahrenheit
            newTemp = convertTemp(currentTemp, 'fahrenheit');
            newUnit = '°F';
            newButtonText = 'Switch to Celsius';
            $(this).data('unit', 'fahrenheit');
        } else {
            // Get original Celsius temperature
            currentTemp = parseFloat(temperatureElement.data('celsius'));
            newTemp = currentTemp;
            newUnit = '°C';
            newButtonText = 'Switch to Fahrenheit';
            $(this).data('unit', 'celsius');
        }
        
        // Update display with animation
        temperatureElement.fadeOut(200, function() {
            $(this).text(newTemp.toFixed(1) + newUnit).fadeIn(200);
        });

        // Update button text with animation
        $(this).fadeOut(200, function() {
            $(this).text(newButtonText).fadeIn(200);
        });
    });
});