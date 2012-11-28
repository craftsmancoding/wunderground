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
 
$modx->regClientCSS(MODX_ASSETS_URL.'components/wunderground/forecast.css');
 
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
$apikey = $modx->getOption('apikey', $scriptProperties, $modx->getOption('apikey'));
$type = $modx->getOption('type', $scriptProperties, 'conditions');
$expire = (int) $modx->getOption('expire', $scriptProperties, 60);

// Check for valid &type
if (!isset($outerTpl[$type])) {
	$msg = $modx->lexicon('invalid_type', array('types' => implode(', ', array_keys($outerTpl))));
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Wunderground Snippet] (page '.$modx->resource->get('id').')'. $msg);
	return '<script type="text/javascript"> alert('.json_encode('[Wunderground Snippet] '.$msg).'); </script>';
}

$tpl = $modx->getOption('tpl', $scriptProperties, 'wunderground.'.$type);
$outerTpl = $modx->getOption('outerTpl', $scriptProperties, $outerTpl[$type]);

if (empty($city) || empty($state) || empty($apikey)) {
	$msg = $modx->lexicon('missing_params');
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Wunderground Snippet] (page '.$modx->resource->get('id').')'. $modx->lexicon('missing_params'));
	return '<script type="text/javascript"> alert('.json_encode('[Wunderground Snippet] '.$msg).'); </script>';
}

// Prepare the inputs
$city = str_replace(' ', '_', $city);
// Any additional arguments may get passed to some API methods
unset($scriptProperties['city']);
unset($scriptProperties['state']);
unset($scriptProperties['apikey']);
unset($scriptProperties['type']);
unset($scriptProperties['expire']);
unset($scriptProperties['tpl']);
unset($scriptProperties['outerTpl']);


// Do the lookup
// TODO: the .json has to be changed for the "Layer" lookups (e.g. radar)
$url = "http://api.wunderground.com/api/$apikey/$type/q/$state/$city.json";
$modx->log(xPDO::LOG_LEVEL_DEBUG, '[Wunderground Snippet] URL queried: '. $url);


$json = $modx->cacheManager->get($url, $cache_opts);

// If we don't have a cached copy, query the API
if (empty($json)) {

	// Query the API
	$json = file_get_contents($url);

	// Cache the lookup
	//$modx->cacheManager->set($url, $json, $expire, $cache_opts);
}

$data = json_decode($json,true);

// Were there errors querying the service?
if (isset($data['error'])) {
	$error_info = array();
	if(isset($data['error']['type'])) {
		$error_info['type'] = $data['error']['type'];
	}
	if(isset($data['error']['description'])) {
		$error_info['description'] = $data['error']['description'];
	}
	$msg = $modx->lexicon('service_error', $error_info);
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Wunderground Snippet] (page '.$modx->resource->get('id').')'. $msg);
	
	return $modx->getChunk('wunderground.error', array('msg'=>$msg));
}

// &type-specific behavior
$props = array();
$output = array();

switch ($type) {
	case 'almanac':
	case 'astronomy':
	case 'conditions':
		break;
	case 'forecast':
	case 'forecast10day':
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

// $props = $data['current_observation'];
// return print_r($props,true);
// $modx->toPlaceholders($props,$prefix); // optional?

$output['content'] = $modx->getChunk($tpl, $data);

if (!$output['content']) {
	$msg = $modx->lexicon('missing_chunk', array('tpl'=>$tpl));
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Wunderground Snippet] (page '.$modx->resource->get('id').')'. $msg);
	return $modx->getChunk('wunderground.error', array('msg'=>$msg));
}

// Optionally wrap the output.
if (empty($outerTpl)) {
	return $output['content'];
}
else {
	return $modx->getChunk($outerTpl, $output);
}

/*EOF*/