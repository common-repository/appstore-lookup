<?php
// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'asl_add_defaults');
register_uninstall_hook(__FILE__, 'asl_delete_plugin_options');

// Delete options table entries ONLY when plugin deactivated AND deleted
function asl_delete_plugin_options() {
	delete_option('asl_plugin');
}

// Define default option settings
function asl_add_defaults() {
	$tmp = get_option('asl_plugin');
    if(!$tmp || !is_array($tmp)) {
		delete_option('asl_plugin');
		$arr = array(
						"ssw" => "480",
						"iconsize" => "72",
						"current" => "0",
						"linkshareid" => "",
						"default_store" => "US",
						"json_cache" => "15"
		);
		update_option('asl_plugin', $arr);
	}
}


add_action('admin_init', 'asl_pluginoptions_init' );
add_action('admin_menu', 'asl_pluginoptions_add_page');

// Init plugin options to white list our options
function asl_pluginoptions_init(){
	$settings = get_option('asl_options');
	if(!$settings) asl_add_defaults();
	register_setting( 'asl_pluginoptions_options', 'asl_plugin', 'asl_pluginoptions_validate' );
}

// Add menu page
function asl_pluginoptions_add_page() {
	add_options_page('AppStore Lookup Options', 'AppStore Lookup', 'manage_options', 'asl_pluginoptions', 'asl_pluginoptions_do_page');
}

// Draw the menu page itself
function asl_pluginoptions_do_page() {
	?>
	<div class="wrap">
    	<?php screen_icon(); ?>
		<h2><?php _e('AppStore Lookup Options', 'appstore-lookup'); ?></h2>
		<form action="options.php" method="post">
			<?php settings_fields('asl_pluginoptions_options'); ?>
			<?php $options = get_option('asl_plugin'); ?>
			<table class="form-table">
                <tr valign="top"><th scope="row"><?php _e('Default screenshot width:', 'appstore-lookup'); ?></th>
					<td><input type="text" name="asl_plugin[ssw]" value="<?php echo $options['ssw']; ?>" size="4" />px<br /? />
                    <span style="color:#666666;margin-left:2px;"><?php _e('Set the default image width.  Screenshot aspect ratio will always be maintained.', 'appstore-lookup'); ?></span>
                 </td>
				</tr> 
                <tr valign="top"><th scope="row"><?php _e('Default icon dimensions (square):', 'appstore-lookup'); ?></th>
					<td><input type="text" name="asl_plugin[iconsize]" value="<?php echo $options['iconsize']; ?>" size="3"/>px<br />
                    <span style="color:#666666;margin-left:2px;"><?php _e('Set the default icon width.  Aspect ratio will always be maintained.', 'appstore-lookup'); ?></span></td>
				</tr>
                <tr valign="top"><th scope="row"><?php _e('Default to Current version:', 'appstore-lookup'); ?></th>
					<td><input name="asl_plugin[current]" type="checkbox" value="1" <?php checked('1', $options['current']); ?> /><br />
                    <span style="color:#666666;margin-left:2px;"><?php _e('Display total and average rating for only the current version of the application.', 'appstore-lookup'); ?></span>
                    </td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e('LinkShare ID:', 'appstore-lookup'); ?></th>
					<td><input type="text" name="asl_plugin[linkshareid]" value="<?php echo $options['linkshareid']; ?>" /><br />
                    <span style="color:#666666;margin-left:2px;"><?php _e('If you have a Linkshare ID, add it here to turn any download link into a Linkshare affiliate link.  This can be left blank.', 'appstore-lookup'); ?></span>
                    </td>
				</tr>
                <tr valign="top"><th scope="row"><?php _e('json Cache Time:', 'appstore-lookup'); ?></th>
					<td><input type="text" name="asl_plugin[json_cache]" value="<?php echo $options['json_cache']; ?>" /><br />
                    <span style="color:#666666;margin-left:2px;"><?php _e('Length of time in minutes to cache local app data before refreshing data from Apple servers.', 'appstore-lookup'); ?></span>
                    </td>
				</tr>
                <tr valign="top"><th scope="row">Default Store:</th>
					<td><select name="asl_plugin[default_store]">
<?php
$stores = array("AE" => "United Arab Emirates", "AG" => "Antigua and Barbuda", "AI" => "Anguilla", "AL" => "Albania", "AM" => "Armenia", "AO" => "Angola", "AR" => "Argentina", "AT" => "Austria", "AU" => "Australia", "AZ" => "Azerbaijan", "BB" => "Barbados", "BE" => "Belgium", "BF" => "Burkina Faso", "BG" => "Bulgaria", "BH" => "Bahrain", "BJ" => "Benin", "BM" => "Bermuda", "BN" => "Brunei", "BO" => "Bolivia", "BR" => "Brazil", "BS" => "Bahamas", "BT" => "Bhutan", "BW" => "Botswana", "BY" => "Belarus", "BZ" => "Belize", "CA" => "Canada", "CG" => "Republic Of Congo", "CH" => "Switzerland", "CL" => "Chile", "CN" => "China", "CO" => "Colombia", "CR" => "Costa Rica", "CV" => "Cape Verde", "CY" => "Cyprus", "CZ" => "Czech Republic", "DE" => "Germany", "DK" => "Denmark", "DM" => "Dominica", "DO" => "Dominican Republic", "DZ" => "Algeria", "EC" => "Ecuador", "EE" => "Estonia", "EG" => "Egypt", "ES" => "Spain", "FI" => "Finland", "FJ" => "Fiji", "FM" => "Federated States Of Micronesia", "FR" => "France", "GB" => "United Kingdom", "GD" => "Grenada", "GH" => "Ghana", "GM" => "Gambia", "GR" => "Greece", "GT" => "Guatemala", "GW" => "Guinea-Bissau", "GY" => "Guyana", "HK" => "Hong Kong", "HN" => "Honduras", "HR" => "Croatia", "HU" => "Hungary", "ID" => "Indonesia", "IE" => "Ireland", "IL" => "Israel", "IN" => "India", "IS" => "Iceland", "IT" => "Italy", "JM" => "Jamaica", "JO" => "Jordan", "JP" => "Japan", "KE" => "Kenya", "KG" => "Kyrgyzstan", "KH" => "Cambodia", "KN" => "St. Kitts and Nevis", "KR" => "Republic Of Korea", "KW" => "Kuwait", "KY" => "Cayman Islands", "KZ" => "Kazakstan", "LA" => "Lao People’s Democratic Republic", "LB" => "Lebanon", "LC" => "St. Lucia", "LK" => "Sri Lanka", "LR" => "Liberia", "LT" => "Lithuania", "LU" => "Luxembourg", "LV" => "Latvia", "MD" => "Republic Of Moldova", "MG" => "Madagascar", "MK" => "Macedonia", "ML" => "Mali", "MN" => "Mongolia","MO" => "Macau", "MR" => "Mauritania", "MS" => "Montserrat", "MT" => "Malta", "MU" => "Mauritius", "MW" => "Malawi", "MX" => "Mexico", "MY" => "Malaysia", "MZ" => "Mozambique", "NA" => "Namibia", "NE" => "Niger", "NG" => "Nigeria", "NI" => "Nicaragua","NL" => "Netherlands", "NO" => "Norway", "NP" => "Nepal", "NZ" => "New Zealand", "OM" => "Oman", "PA" => "Panama", "PE" => "Peru", "PG" => "Papua New Guinea", "PH" => "Philippines", "PK" => "Pakistan", "PL" => "Poland", "PT" => "Portugal", "PW" => "Palau", "PY" => "Paraguay", "QA" => "Qatar", "RO" => "Romania", "RU" => "Russia", "SA" => "Saudi Arabia", "SB" => "Solomon Islands", "SC" => "Seychelles", "SE" => "Sweden","SG" => "Singapore", "SI" => "Slovenia", "SK" => "Slovakia", "SL" => "Sierra Leone", "SN" => "Senegal", "SR" => "Suriname","ST" => "Sao Tome and Principe", "SV" => "El Salvador", "SZ" => "Swaziland","TC" => "Turks and Caicos", "TD" => "Chad", "TH" => "Thailand", "TJ" => "Tajikistan", "TM" => "Turkmenistan", "TN" => "Tunisia", "TR" => "Turkey","TT" => "Trinidad and Tobago", "TW" => "Taiwan", "TZ" => "Tanzania", "UA" => "Ukraine", "UG" => "Uganda", "US" => "United States", "UY" => "Uruguay", "UZ" => "Uzbekistan", "VC" => "St. Vincent and The Grenadines","VE" => "Venezuela", "VG" => "British Virgin Islands", "VN" => "Vietnam", "YE" => "Yemen", "ZA" => "South Africa", "ZW" => "Zimbabwe");

						foreach ($stores as $key=>$value) {
								echo '<option value="'.$key.'"';
									if ($key == $options['default_store']) { echo ' selected '; }
								 echo '>'.$value.'</option>';
						}
						?>
                        </select>
						<br />
                    <span style="color:#666666;margin-left:2px;"><?php _e('Default AppStore country to use for lookup data.', 'appstore-lookup'); ?></span>
                    </td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'appstore-lookup') ?>" />
			</p>
		</form>
	</div>
    
    <?php
    
	_e('<h3 style="margin-top:30px;">Shortcodes</h3>
    <p>These are the shortcodes you can use to display your app data.  An appstore id passed with the <code>id=</code> parameter is required for all shortcodes.</p>
    <p><strong>New:</strong> setting custom field in your post or page called <code>appId</code> with the value of your AppStore ID will eliminate the requirement for setting an id in the shortcode, and add a Smart App Banner for visitors on mobile Safari.</p>
    <ul class="ul-disc">
    	<li><code>asl_name</code> Will display the name of the application.</li>
        <li><code>asl_icon</code> Will output the icon image.  Optional parameter: <code>w</code></li>
        <li><code>asl_genre</code> Will display the Primary genre of the application.</li>
        <li><code>asl_price</code> Displays the formatted price of the application.</li>
        <li><code>asl_version</code> Displays the latest version number of the application.</li>
        <li><code>asl_description</code> Outputs the description using <a href="http://ca3.php.net/nl2br" target="_blank">nl2br</a>.</li>
        <li><code>asl_seller</code> Outputs the seller name. Optional parameter <code>link</code> to display seller (developer) name linked to seller URL.</li>
        <li><code>asl_filesize</code> Displays the filesize in B, KB, MB, GB, or TB (!) depending on the size.</li>
        <li><code>asl_release_notes</code> Displays the release notes for the current version using nl2br.</li>
        <li><code>asl_rating</code> Displays the Average User rating.  Optionally can display for only the current version using <code>current</code>.</li>
        <li><code>asl_num_ratings</code> Displays the total number of ratings the app has received in iTunes.  Optionally can display for only the current version using <code>current</code>.</li>
        <li><code>asl_release_date</code> Displays the release date in <code>F j, Y</code> format.  Optionally accepts <code>dateformat</code> and any PHP <a href="http://ca3.php.net/manual/en/function.date.php" target="_blank">date format</a>.</li>
        <li><code>asl_content_rating</code> Displays the content advisory rating for the app.</li>
        <li><code>asl_screenshots</code> Displays the application screenshots in an unordered list.  Optionally accepts: <code>w</code>, <code>q</code>, <code>type</code>, <code>first</code></li>
    </ul>)', 'appstore-lookup'); 
	
	 
	_e('<h3 style="margin-top:30px;">Parameters</h3>
    <p>Parameters for shortcodes.</p>
    <ul class="ul-disc">
    	<li><code>id</code> The app id is required for all shortcodes.  This can also be passed as a post custom attribute called <code>appId</code>.</li>
        <li><code>w</code> Optional.  Overrides the default Width setting for screenshots or icon.  Accepts a number in pixels.</li>
        <li><code>current</code> Optional for rating and num_ratings, overrides the default "current version only".  Accepts true, false, 1,0</li>
        <li><code>first</code>  Optional for screenshots, will display only the first screenshot.  Defaults to all.  Accepts true, false, 1,0</li>
        <li><code>link</code>  Optional for seller, will display the seller name as a link to seller url.  Defaults to no link.  Accepts true, false, 1,0</li>
        <li><code>img</code>  Optional for link, allows you to display the buy link as an image you choose (ie, Available in the AppStore image)</li>
        <li><code>type</code>  Optional for screenshots, for universal apps, this will allow to select between displaying iPad or iPhone screenshots.  Default is iPhone.</li>
        <li><code>title</code>  Optional for link, allows you to change the buy link text to something other than "Download"</li>
    </ul>', 'appstore-lookup'); 
}


// Sanitize and validate input. Accepts an array, return a sanitized array. 
function asl_pluginoptions_validate($input) {
	return $input;
}
?>