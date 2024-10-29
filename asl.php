<?php
/*
Plugin Name: AppStore Lookup for Wordpress
Version: 1.5.1
Plugin URI: http://adamdionne.com
Description: Adds shortcodes that display data from iOS and Mac AppStore applications.
Author: Adam Dionne
Author URI: http://adamdionne.com/
License: GPL2
*/

/*  Copyright 2013  Adam Dionne  (email : adamdionne@me.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once("asl-admin.php");

//error_reporting(0);

//Available Actions
$actions = array('name', 'icon', 'description', 'genre', 'price', 'seller', 'filesize', 'release_notes', 'release_date', 'rating', 'content_rating', 'num_ratings', 'version', 'screenshots', 'link');
foreach ($actions as $action) { add_shortcode('asl_'.$action, 'asl_'.$action); }

//displays link to iTunes Store
//optional parameter linkshare for linkshare id
//optional parameter img to display a button
//optional parameter title to display text
//defaults to text "Download"
//heirarchy is img -> title -> default
function asl_link( $attributes ) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	$linkshare = asl_extract_linkshare($attributes);
	$img = asl_extract_img($attributes);
	$title = asl_extract_title($attributes);
	$baseurl = $app->trackViewUrl;
	if(!$linkshare) { $url = $baseurl; }
	else { $url = "http://click.linksynergy.com/fs-bin/stat?id=".$linkshare."&offerid=146261&type=3&subid=0&tmpid=1826&RD_PARM1=" . urlencode(urlencode($baseurl)) . "%2526uo%253D4%2526partnerId%253D30"; }
	$return = '<a href="'.$url.'" class="asl-link">';
	if ($img) { $return .= '<img src='.$img.' class="asl-link-img" alt="View in iTunes" />'; }
	elseif ($title) { $return .= $title; }
	else { $return .= 'Download'; }
	$return .= '</a>';
	return $return;
}

//displays appname
function asl_name( $attributes ) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	return $app->trackName;
}
//displays icon as img
function asl_icon ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	$url = $app->artworkUrl100;
	$w = asl_extract_icon_w($attributes);
	$q = asl_extract_q($attributes);
	return '<img src="'.$url.'"  alt="App Icon"  height="'.$w.'" width="'.$w.'" class="asl-icon-img" />';
}
//displays genre
function asl_genre ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	return $app->primaryGenreName;
}
//displays price
function asl_price ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	return $app->formattedPrice;
}
//displays version
function asl_version ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	return $app->version;
}
//displays description
function asl_description ($attributes) {
	$len = asl_extract_len($attributes);
	$app = asl_get_appdata(asl_extract_id($attributes));
	$return = nl2br($app->description);
		if ($len) { 
		$i=0; $text='';
		$arr = explode("\n", $return);
			while ($i < $len) { 
			$text .= $arr[$i];
			$i++;
			}
			$return=$text;
		}
	return $return;
}
//displays seller
//optional parameter link = true to display as a link
function asl_seller ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	$link = asl_extract_link($attributes);
		if ($link) { $return = '<a href="'.$app->sellerUrl.'" target="_blank">'.$app->sellerName.'</a>'; }
		else { $return = $app->sellerName; }
	return $return;
}

//displays filesize parsed based on... file size
function asl_filesize ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	return formatBytes($app->fileSizeBytes, 2);
}
//displays release notes
function asl_release_notes ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	return nl2br($app->releaseNotes);
}
//displays average user rating
//optional parameter to display only current version
function asl_rating ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	$current = asl_extract_current($attributes);
	if (!$current) { return $app->averageUserRating; }
	else { return $app->averageUserRatingForCurrentVersion; }
}
//displays number of ratings
//optional parameter to display only current version
function asl_num_ratings ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	$current = asl_extract_current($attributes);
	if (!$current) { return $app->userRatingCount; }
	else { return $app->userRatingCountForCurrentVersion; }
}

//displays release date
//optional parameter dateformat
function asl_release_date ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	$dateformat = asl_extract_dateformat($attributes);
	if (!$dateformat) { $dateformat = 'F j, Y'; }
	return date($dateformat, strtotime(substr($app->releaseDate, 0, 10)));
}
//displays content rating
function asl_content_rating ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	return $app->contentAdvisoryRating;
}

//displays one or multiple screenshots as an unordered list
//Optional parameter first to show only first screenshot
//Optional parameter type to prefer ipad.  Prefers iPhone/Mac AppStore by default.
//will verify ipad screenshots exist before displaying, and if not, will show iphone screenshots anyway
function asl_screenshots ($attributes) {
	$app = asl_get_appdata(asl_extract_id($attributes));
	$first = asl_extract_first($attributes);
	$type = asl_extract_type($attributes);
	$w = asl_extract_w($attributes);
	$q = asl_extract_q($attributes);
		$i=0;
		$return = '<ul class="asl-screenshot-list">
		';
		if ($type=='ipad' || isset($app->ipadScreenshotUrls[0])) {
			foreach ($app->ipadScreenshotUrls as $screenshot) {
				if (!$first) { 
					$return .= '<li class="asl-app-screenshot"><img src="'.$screenshot.'" alt="App Screenshot" width="'.$w.'" /></li>';
				}
				else {
					while ($i<1) {
						$return .= '<li class="asl-app-screenshot"><img src="'.$screenshot.'" alt="App Screenshot" width="'.$w.'" /></li>';
						$i++;
					}
				}
			}
		}
		else {
			foreach ($app->screenshotUrls as $screenshot) {
				if (!$first) { 
					$return .= '<li class="asl-app-screenshot"><img src="'.$screenshot.'"  alt="App Screenshot" width="'.$w.'"/></li>';
				}
				else {
					while ($i<1) {
						$return .= '<li class="asl-app-screenshot"><img src="'.$screenshot.'"  alt="App Screenshot" width="'.$w.'" /></li>';
						$i++;
					}
				}
			}
		}		
	$return .= '</ul>';
	return $return;
}

//utility functions

function asl_extract_id( $attributes ) {
	extract( shortcode_atts( array('id' => ''), $attributes ) );
	if (!$id) {
		$check = get_post_meta(get_the_ID(), 'appId', true);
		if ($check) { $id = $check;}
		else { $id='000000000'; }
	}
	return $id;
}
function asl_extract_len( $attributes ) {
	extract( shortcode_atts( array('len' => ''), $attributes ) );
	return $len;
}
function asl_extract_w( $attributes ) {
	extract(shortcode_atts( array('w' => ''), $attributes ) );
	if ($w=='') { $w = asl_setting('ssw'); }
	return $w;
}
function asl_extract_icon_w($attributes) { 
	extract(shortcode_atts( array('w' => ''), $attributes) );
	if ($w=='') { $w = asl_setting('iconsize'); }
	return $w;
}
function asl_extract_q( $attributes ) {
	extract( shortcode_atts( array('q' => ''), $attributes ) );
	return $q;
}
function asl_extract_first( $attributes ) {
	extract( shortcode_atts( array('first' => ''), $attributes ) );
	return $first;
}
function asl_extract_type ($attributes) {
	extract( shortcode_atts( array('type' => ''), $attributes ) );
	return $type;
}
function asl_extract_link ($attributes) {
	extract( shortcode_atts( array('link' => ''), $attributes ) );
	return $link;
}
function asl_extract_dateformat ($attributes) {
	extract( shortcode_atts( array('dateformat' => ''), $attributes ) );
	return $dateformat;
}
function asl_extract_current ($attributes) {
	$current = asl_setting('current');
	extract( shortcode_atts( array('current' => ''), $attributes ) );
	return $current;
}
function asl_extract_linkshare ($attributes) {
	extract( shortcode_atts( array('linkshare' => ''), $attributes ) );
	if ($linkshare=='') { $linkshare = asl_setting('linkshareid'); }
	return $linkshare;
}
function asl_extract_img ($attributes) {
	extract( shortcode_atts( array('img' => ''), $attributes ) );
	return $img;
}
function asl_extract_title ($attributes) {
	extract( shortcode_atts( array('title' => ''), $attributes ) );
	return $title;
}

function formatBytes($size, $precision = 2)
{
    $base = log($size) / log(1024);
    $suffixes = array('', 'k', 'M', 'G', 'T');   

    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}


//looking up of data.

//default lookup url
define('APPSTORE_LOOKUP_URL', 'http://itunes.apple.com/lookup?id=');
// creates url
function asl_page_url( $id ) {
	//set a default url if there is no id.  we will fill this with stock content
	if ($id == '000000000') { $url = plugins_url( '/asl-default-data.php' , __FILE__) ;}
	else { 
		$country = '&country='.asl_setting('default_store');
		$url = APPSTORE_LOOKUP_URL . $id. $country;
	}
	//echo "URL = ".$url;
	return $url;
}
//gets and returns app data
function asl_get_appdata( $id ) {
	$url = asl_page_url($id);
	$appdata = json_decode(asl_get_content($url));
	if ($appdata->resultCount == 0) {
     // we'll add in some error code here in case the app isn't found
	 $id = 000000000;
	 $url = asl_page_url($id);
	 $appdata = json_decode( asl_get_content($url));
	 return $appdata->results[0];
	 // end error code	
	}
	else { return $appdata->results[0]; }
}
//gets data via fopen
function asl_fopen ($url) {
	return file_get_contents($url);
}
//gets data via curl (preferred)
function asl_curl ($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $output = curl_exec($ch);
    curl_close($ch);
	return $output;
}
//chooses fopen or curl based on server
function asl_fopen_or_curl($url)
{
	if (function_exists('curl_exec'))
		return asl_curl($url);
	elseif(function_exists('file_get_contents') && ini_get('allow_url_fopen'))
		return asl_fopen($url);
	else
		wp_die('<p>You must have either file_get_contents() or curl_exec() enabled on your web server.</p>');
}


$asl_settings = array();
function asl_setting($name) {
	global $asl_settings;

	$asl_settings = get_option('asl_plugin');
	if(!$asl_settings) {
		asl_add_defaults();
		$asl_settings = get_option('asl_plugin');
	}

	return $asl_settings[$name];
}

function asl_set_setting($name, $value) {
	global $asl_settings;

	$asl_settings = get_option('asl_plugin');
	if(!$asl_settings) {
		asl_add_defaults();
		$asl_settings = get_option('asl_plugin');
	}

	$asl_settings[$name] = $value;
}

//utility functions
function is_iphone() {
    return (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod')) ? true : false;
}

// adds smart app banner automatically if appId is set
function asl_add_smart_app_banner() {
	if (is_single()) {
		$check = get_post_meta(get_the_ID(), 'appId', true);
		$show = true;
		//we do not want to show ipad app smart banners to iphone users
		//probably we should do this check either, but iphone apps do work on ipad, so we'll let that slide
		$iphone = is_iphone();
			if ($iphone) { //if so if the user is on an iphone, get the app data so we can check
				$app = asl_get_appdata($check);
					if (!isset($app->screenshotUrls[0])) { $show=false; }
					//if we don't have them, then we're not on an iphone-friendly build
			}
		if ($show) {
			$linkshare = asl_setting('linkshareid');
			if ($check) { 
				$metaString = '<meta name="apple-itunes-app" content="app-id='.$check.'"';
				if ($linkshare) { $metaString .= ', affiliate-data=siteID=30&partnerId='.$linkshare; }
				$metaString .= '>';
				echo $metaString;
			}
		}
	}
}

// now with caching
// lets get that mins variable as a setting
function asl_get_content($url) {
	
	// add cache directory if not exists
	$cacheDir = dirname(__FILE__).'/cache';
	
	 
	 if($cacheDir){
			if(! is_dir($cacheDir)){
				@mkdir($cacheDir);
				if(! is_dir($cacheDir)){
					$this->error("Could not create the file cache directory.");
					return false;
				}
			}
	 }
	 
	//vars
	$mins = asl_setting('json_cache');
	$cache = dirname(__FILE__).'/cache/asl_'.md5($url).'.txt';
	$current_time = time(); $expire_time = $mins * 60; $file_time = @filemtime($cache);
	//do we use the cache or the file?
	if(file_exists($cache) && ($current_time - $expire_time < $file_time)) {
		//echo 'returning from cached file';
		return file_get_contents($cache);
	}
	else {
		$content = asl_fopen_or_curl($url);
		file_put_contents($cache, $content);
		return file_get_contents($cache);
	}
}

?>
