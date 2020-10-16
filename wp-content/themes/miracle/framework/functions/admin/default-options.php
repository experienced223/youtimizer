<?php

function miracle_options_predefined_skin_variables() {
	$options = array();
	$options['orange'] = array(
		'general_skin_color' => '#ff6600',
		'general_skin_light_bgcolor' => '#ff7d26',
		'general_skin_light_font_color' => '#ffc299'
	);
	$options['green'] = array(
		'general_skin_color' => '#97cc24',
		'general_skin_light_bgcolor' => '#a2d13a',
		'general_skin_light_font_color' => '#d8eaaf'
	);
	$options['purple'] = array(
		'general_skin_color' => '#b53cd3',
		'general_skin_light_bgcolor' => '#bd50d7',
		'general_skin_light_font_color' => '#f1c5fb'
	);
	$options['blue'] = array(
		'general_skin_color' => '#00a2ee',
		'general_skin_light_bgcolor' => '#1aabf0',
		'general_skin_light_font_color' => '#c8e9f9'
	);
	$options['yellow'] = array(
		'general_skin_color' => '#ffae00',
		'general_skin_light_bgcolor' => '#ffb61a',
		'general_skin_light_font_color' => '#f0deb8'
	);
	$options['gray'] = array(
		'general_skin_color' => '#acacac',
		'general_skin_light_bgcolor' => '#b4b4b4',
		'general_skin_light_font_color' => '#d9d9d9'
	);
	$options['navy'] = array(
		'general_skin_color' => '#006cff',
		'general_skin_light_bgcolor' => '#1a7bff',
		'general_skin_light_font_color' => '#c9dcf7'
	);
	$options['sea'] = array(
		'general_skin_color' => '#0ab596',
		'general_skin_light_bgcolor' => '#23bda1',
		'general_skin_light_font_color' => '#cef2eb'
	);
	$options['red'] = array(
		'general_skin_color' => '#ff1818',
		'general_skin_light_bgcolor' => '#ff3030',
		'general_skin_light_font_color' => '#eec8c8'
	);
	$options['gold'] = array(
		'general_skin_color' => '#d9be2b',
		'general_skin_light_bgcolor' => '#ddc541',
		'general_skin_light_font_color' => '#eee9cb'
	);
	$options['default'] = $options['orange'];
	return $options;
}