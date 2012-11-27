<?php
/**
 * Wunderground
 * 
 * Gets a weather forecast of type &type from Wunderground.com's API. 
 *
 * Types:
 * The following types of queries are supported:
 *
 * 	conditions - current temperature, weather condition, humidity, wind, feels 
 *				like temperature, barometric pressure, and visibility.
 *	forecast -	summary of the weather for the next 3 days. This includes high 
 *				and low temperatures, a string text forecast and the conditions.
 *	forecast10day - as for "forecast", but for 10 days.
 *	almanac - Historical average temperature for today.
 *	astronomy - Returns the moon phase, sunrise and sunset times.
 *	webcams - Returns locations of nearby Personal Weather Stations and URLs for images from their web cams.
 *	radar - Returns a static radar image for a given location.
 *  animatedradar - Returns an animated radar image for a given location.
 *	satellite - Returns a URL link to .gif visual and infrared satellite images.
 *	animatedsatellite - Returns animated visual and infrared satellite images.
 * 	radar/satellite - 
 *	animatedradar/animatedsatellite
 *	
 * Formatting:
 * The default formatting chunks rely on the &type parameter and follow
 * the following naming pattern: wunderground.{$type}
 * 
 *
 * @param string city: name of a city
 * @param string state: 2 letter abbreviation of a US state
 * @param string apikey: api key from wunderground.com. Defaults to wunderground.apikey System Setting.
 * @param string tpl Chunk used to format results. Default value depends on &type: wunderground.{$type}
 * @param string outerTpl Chunk used to wrap results. Some types of lookups do not use an outerTpl wrapper,
 *		others will use a Chunk named after the &type, e.g. wunderground.{$type}.outer. See docs for details.
 * // @param string prefix used when setting placeholders. Does not affect placeholders in chunks.
 * @param integer expire number of minutes the results last. Default: 60.  
 * @return array props: formatting array used by "forecast_conditions" chunk
 */
// Set up our own Cache folder
$cache_opts = array(xPDO::OPT_CACHE_KEY => 'wunderground');

// Sets optional outerTpls used to wrap output
$outerTpl = array();
$outerTpl['conditions'] = '';
$outerTpl['forecast'] = 'wunderground.forecast.outer';
$outerTpl['forecast10day'] = 'wunderground.forecast10day.outer';
$outerTpl['almanac'] = '';
$outerTpl['astronomy'] = '';
$outerTpl['webcams'] = '';
$outerTpl['radar'] = '';
$outerTpl['animatedradar'] = '';
$outerTpl['satellite'] = '';
$outerTpl['animatedsatellite'] = '';
$outerTpl['animatedradar/animatedsatellite'] = '';

 
$city = strtolower($modx->getOption('city', $scriptProperties));
$state = strtolower($modx->getOption('state', $scriptProperties));
$apikey = $modx->getOption('apikey', $scriptProperties,'a1dd26f4f33cd8c6');
$type = $modx->getOption('type', $scriptProperties, 'conditions');
$expire = (int) $modx->getOption('expire', $scriptProperties, 60);

// Check for valid &type
if (!isset($outerTpl[$type])) {
	$msg = $modx->lexicon('invalid_type', array('types' => implode(', ', array_keys($outerTpl))));
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Forecast Snippet] (page '.$modx->resource->get('id').')'. $msg);
	return '<script type="text/javascript"> alert('.json_encode('[Forecast Snippet] '.$msg).'); </script>';
}

$tpl = $modx->getOption('tpl', $scriptProperties, 'forecast.'.$type);
$outerTpl = $modx->getOption('outerTpl', $scriptProperties, $outerTpl[$type]);

if (empty($city) || empty($state) || empty($apikey)) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Forecast Snippet] (page '.$modx->resource->get('id').')'. $modx->lexicon('missing_params'));
	return '<script type="text/javascript"> alert('.json_encode('[Forecast Snippet] '.$msg).'); </script>';
}

// Prepare the inputs
$city = str_replace(' ', '_', $city);

// Do the lookup
$url = "http://api.wunderground.com/api/$apikey/conditions/q/$state/$city.json";
$modx->log(xPDO::LOG_LEVEL_DEBUG, '[Forecast Snippet] URL queried: '. $url);


$json = $modx->cacheManager->get($url, $cache_opts);

// if $refresh OR if not fingerprint is not cached, then lookup the address
if ($refresh || empty($json)) {

	// Query the API
	$json = file_get_contents($url);

	// Cache the lookup
	$modx->cacheManager->set($url, $json, $expire, $cache_opts);
}

$data = json_decode($json,true);

// &type-specific behavior
$props = array();
$output = array();

switch ($type) {
	case 'conditions':
		break;
	case 'forecast':
		break;
	case 'forecast10day':
		break;
	case 'almanac':
		break;
	case 'astronomy':
		break;
	case 'webcams':
		break;
	case 'radar':
		break;
	case 'animatedradar':
		break;
	case 'satellite':
		break;
	case 'animatedsatellite':
		break;
	case 'animatedradar/animatedsatellite';
		break;
}

$props = $data['current_observation'];
//return print_r($props,true);
// $modx->toPlaceholders($props,$prefix); // optional?

$output['content'] = $modx->getChunk($tpl, $props);

// Optionally wrap the output.
if (empty($outerTpl)) {
	return $output['content'];
}
else {
	return $modx->getChunk($outerTpl, $output);
}

/*EOF*/