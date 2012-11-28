<?php
/**
 * Wunderground
 *
 * Copyright 2012 by Everett Griffiths <everett@craftsmancoding.com>
 *
 * This file is part of Wunderground, a component for MODx Revolution.
 *
 * Wunderground is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Forecast is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Forecast; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package Forecast
 */
/**
 * Wunderground English language file
 *
 * @package wunderground
 * @subpackage lexicon
 */
// Basic Labelling Stuff used in the default chunks
$_lang['today'] = 'Today';
$_lang['temperature'] = 'Temperature';
$_lang['low_temperature'] = 'Low Temperature';
$_lang['high_temperature'] = 'High Temperature';
$_lang['normal'] = 'Normal';
$_lang['record'] = 'Record';
$_lang['sunrise'] = 'Sunrise';
$_lang['sunset'] = 'Sunset';
$_lang['sunset'] = 'Moon';
$_lang['prev'] = 'Prev';
$_lang['next'] = 'Next';
$_lang['help_title'] = 'Wunderground Snippet Help';
$_lang['help_msg'] = 'The following placeholders are available to this Snippet call.';
$_lang['help_data'] = 'Raw Data';

// Errors
$_lang['missing_params'] = '&query and a valid &apikey are required.';
$_lang['invalid_type'] = 'Invalid &features parameter.  &features may use or or more of the following: [[+types]]';
$_lang['service_error'] = 'An error with the wunderground.com service was encountered. [[+type]]: [[+description]]';
$_lang['missing_chunk'] = 'Chunk not found: [[+tpl]]';

// System Settings
$_lang['setting_wunderground.apikey'] = 'Wunderground API Key';
$_lang['setting_wunderground.formatting_string_desc'] = 'Sign up for a Weather Underground API key at <a href="http://www.wunderground.com/?apiref=231d790b629b253a">http://www.wunderground.com/</a>';


/*EOF*/