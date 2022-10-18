<?php
/**
 * Plugin Name: WeatherShortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


function alex_weather_shortcode($atts){

    $a = shortcode_atts( array(
		'cityname' => $cityname
	), $atts );

    echo var_dump($a);

    //API variables
    $url = 'https://api.openweathermap.org/data/2.5/weather';
    $apiKey = '6b1cd5a24a18ee83c55372465790bed5';

    $fullUrl = $url . '?q=' . $a['cityname'] . '&appid=' . $apiKey;
    $response = wp_remote_get($fullUrl);

   
    if (is_wp_error($response)) {
		error_log("Error: ". $response->get_error_message());
		return false;
	}

    if ($cityname !== ''){

    $body = wp_remote_retrieve_body($response);

	$data = json_decode($body);

       
   $city = $data->name;
   $temp = $data->main->temp;
   $cityweather = $data->weather[0]->description;
   $hummidity = $data->main->humidity;
   $speed = $data->wind->speed;
   $weatherIcon = $data->weather[0]->icon;

  
   ob_start();
    ?>
        <section class="weather-card">
            <div class="main-weather">
                <div class="city">
                    <h3><?php echo esc_html( $city );?></h3>
                    <p> <?php echo esc_html( $cityweather );?></p>
                </div>
                <div class="weather-icon">
                    <img src="http://openweathermap.org/img/wn/<?php echo $weatherIcon ?>@2x.png" />
                </div>
            </div>
            
            <div class="temp-info">
                <p class="temp"> <span><?php echo esc_html( $temp )?></span> F </p>         
                <div class="humidity">
                    <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/humidity.png'  ?>"/>
                    <p><?php echo esc_html($hummidity ) . ' ' . '%'; ?></p>
                </div>
               <div class="wind">
                    <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/wind.png'  ?>"/>
                    <p><?php echo esc_html($speed ) . ' ' . 'mi/h'; ?></p>
               </div>
            </div>
        </section>


    <?php

   return ob_get_clean();
   }

}

add_shortcode('weather','alex_weather_shortcode' );