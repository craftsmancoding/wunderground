<?php
/**
 * Wunderground
 * 
 * Gets a weather forecast or related data from Wunderground.com's API.
 * 
 *	
 * Formatting:
 * The default formatting chunks rely on the &type parameter and follow
 * the following naming pattern: wunderground.{$features}
 * 
 * @param string query Requred: usually a state/City or country/city
 * @param string apikey: api key from wunderground.com. Defaults to wunderground.apikey System Setting.
 * @param string tpl Chunk used to format results. Default value depends on &features: wunderground.{$features}
 * @param integer expire number of minutes the results last. Default: 60.  
 * @param boolean help -- if set, the output will be a list of available placeholders for the call.
 * @return array props: formatting array used by "forecast_conditions" chunk
 */
 
$modx->regClientCSS(MODX_ASSETS_URL.'components/wunderground/css/forecast.css');
$modx->regClientStartupScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
$modx->regClientStartupScript(MODX_ASSETS_URL.'components/wunderground/js/cycle.js');
 
// Set up our own Cache folder
$cache_opts = array(xPDO::OPT_CACHE_KEY => 'wunderground');

// Used to validate &features input
$valid_features = array('almanac','astronomy','conditions','forecast','forecast10day','webcams',
'radar','satellite','animatedradar','animatedsatellite');
$layer_features = array('radar','satellite','animatedradar','animatedsatellite');
 
$query = $modx->getOption('query', $scriptProperties);
$apikey = $modx->getOption('apikey', $scriptProperties, $modx->getOption('apikey'));
$features = $modx->getOption('features', $scriptProperties, 'conditions');
$expire = (int) $modx->getOption('expire', $scriptProperties, 60);
$help = (int) $modx->getOption('help', $scriptProperties);

// Check for valid &features
$requested_features = explode('/',$features);
foreach($requested_features as $f) {
	if (!in_array($f, $valid_features)) {
		$msg = $modx->lexicon('invalid_type', array('types' => implode(', ',$valid_features)));
		$modx->log(xPDO::LOG_LEVEL_ERROR, '[Wunderground Snippet] (page '.$modx->resource->get('id').')'. $msg);
		return '<script type="text/javascript"> alert('.json_encode('[Wunderground Snippet] '.$msg).'); </script>';
	}
}
sort($requested_features);
$features = implode('/',$requested_features);

$tpl = $modx->getOption('tpl', $scriptProperties, 'wunderground.'.$features);

if (empty($query) || empty($apikey)) {
	$msg = $modx->lexicon('missing_params');
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Wunderground Snippet] (page '.$modx->resource->get('id').')'. $modx->lexicon('missing_params'));
	return '<script type="text/javascript"> alert('.json_encode('[Wunderground Snippet] '.$msg).'); </script>';
}

// Prepare the inputs
$query = strtolower(str_replace(' ', '_', $query));

// Any additional arguments may get passed to some of the "Layer" API methods
unset($scriptProperties['city']);
unset($scriptProperties['state']);
unset($scriptProperties['apikey']);
unset($scriptProperties['type']);
unset($scriptProperties['expire']);
unset($scriptProperties['tpl']);
unset($scriptProperties['help']);


// Do the lookup
// TODO: the .json has to be changed for the "Layer" lookups (e.g. radar)
$url = "http://api.wunderground.com/api/$apikey/$features/q/$query.json";
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

// &feature-specific behavior
$output = '';

switch ($features) {
	case 'almanac':
	case 'astronomy':
	case 'conditions':
		$output = $modx->getChunk($tpl, $data);
		break;
	case 'forecast':
		foreach($data['forecast']['simpleforecast']['forecastday'] as $d) {
			$output .= $modx->getChunk($tpl, $d);
		}
		break;
	case 'forecast10day':
		foreach($data['forecast']['txt_forecast']['forecastday'] as $d) {
			$output .= $modx->getChunk($tpl, $d);
		}
		break;
	case 'webcams':
		foreach($data['webcams'] as $d) {
			$output .= $modx->getChunk($tpl, $d);
		}
		break;
	case 'radar':
		break;
	case 'animatedradar':
		break;
	case 'satellite':
		break;
	case 'animatedsatellite':
		break;
}

// For debugging+developers
$modx->toPlaceholder('wunderground.json',$json);
$modx->toPlaceholder('wunderground.data',print_r($data,true));

// Show Help Message if &help=`1` (overrides output)
if ($help) {
	$placeholders_array = $modx->toPlaceholders($data);
	$placeholders = '';
	foreach($placeholders_array['keys'] as $p) {
		$placeholders .= "&#91;&#91;+$p&#93;&#93;<br />";
	}
	$output = $modx->getChunk('wunderground.help', array('placeholders'=>$placeholders));
}

// Show error message if the Chunk was not found.
if (empty($output)) {
	$msg = $modx->lexicon('missing_chunk', array('tpl'=>$tpl));
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Wunderground Snippet] (page '.$modx->resource->get('id').')'. $msg);
	return $modx->getChunk('wunderground.error', array('msg'=>$msg));
}


return $output;

/*EOF*/