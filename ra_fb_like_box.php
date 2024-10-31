<?php
   /*
   Plugin Name: RA-FB Like Box
   Plugin URI: http://blog.ecafechat.com/rashids-facebook-like-box-plugin-for-wordpress/
   Description: RA-FB Like Box enables you to display the facebook page likes in your website.
   Version: 1.4
   Author: Rashid Azar
   Author URI: http://blog.ecafechat.com
   
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

   */

add_action("plugins_loaded", array("RAFacebookLikeBox", "ra_fb_like_init"));
add_action("admin_menu", array("RAFacebookLikeBox", "ra_fb_likebox_options"));
add_shortcode("rashid_fb_lb", array("RAFacebookLikeBox", "ra_fb_likebox_sc"));

add_filter('plugin_row_meta', array("RAFacebookLikeBox", "ra_fb_plugin_row_meta"), 10, 2);

class RAFacebookLikeBox {
	/*
	 * following are the field to be stored in database
	 */
	static protected $_ra_title 	   = "crafb_title";
	static protected $_ra_url 		   = "crafb_fb_url";
	static protected $_ra_width		   = "crafb_width";
	static protected $_ra_height	   = "crafb_height";
	static protected $_ra_color_scheme = "crafb_color_scheme";
	static protected $_ra_show_faces   = "crafb_show_faces";
	static protected $_ra_border_color = "crafb_border_color";
	static protected $_ra_stream 	   = "crafb_stream";
	static protected $_ra_header 	   = "crafb_header";
	static protected $_ra_linkback 	   = "crafb_linkback";

	static protected $_ra_option_page_title = 'Rashid\'s Facebook Like Box';
	static protected $_ra_option_menu_title = 'RA-FB Like Box';
	static protected $_ra_option_capability = 'manage_options';
	static protected $_ra_option_menu_slug  = 'ra_fb_likebox';
	static protected $_ra_option_icon       = 'rashid.jpg';
	
	static function ra_retrieve_options(){
		$_ra_options = array(
				'title' 		=> stripslashes(get_option(self::$_ra_title)),
				'fb_url' 		=> stripslashes(get_option(self::$_ra_url)),
				'width' 		=> stripslashes(get_option(self::$_ra_width)),
				'height' 		=> stripslashes(get_option(self::$_ra_height)),
				'color_scheme' 	=> stripslashes(get_option(self::$_ra_color_scheme)),
				'show_faces' 	=> stripslashes(get_option(self::$_ra_show_faces)),
				'border_color'  => stripslashes(get_option(self::$_ra_border_color)),
				'stream' 		=> stripslashes(get_option(self::$_ra_stream)),
				'header' 		=> stripslashes(get_option(self::$_ra_header)),
				'linkback' 		=> stripslashes(get_option(self::$_ra_linkback)),
		);
		
		return $_ra_options;
	}
	
	static function ra_plugin_basename($file = __FILE__) {
		$file = str_replace('\\','/',$file); // sanitize for Win32 installs
		$file = preg_replace('|/+|','/', $file); // remove any duplicate slash
		$plugin_dir = str_replace('\\','/',WP_PLUGIN_DIR); // sanitize for Win32 installs
		$plugin_dir = preg_replace('|/+|','/', $plugin_dir); // remove any duplicate slash
		$mu_plugin_dir = str_replace('\\','/',WPMU_PLUGIN_DIR); // sanitize for Win32 installs
		$mu_plugin_dir = preg_replace('|/+|','/', $mu_plugin_dir); // remove any duplicate slash
		$file = preg_replace('#^' . preg_quote($plugin_dir, '#') . '/|^' . preg_quote($mu_plugin_dir, '#') . '/#','',$file); // get relative path from plugins dir
		$file = trim($file, '/');
		return $file;
	}
	
	static function ra_print_likebox($option_value) {
	?>
		<iframe 
		src="https://www.facebook.com/plugins/likebox.php?href=<?php echo $option_value['fb_url'];?>&amp;
		width=<?php echo $option_value['width'];?>&amp;
		height=<?php echo $option_value['height'];?>&amp;
		colorscheme=<?php echo $option_value['color_scheme'];?>&amp;
		show_faces=<?php echo $option_value['show_faces'];?>&amp;
		border_color=%23<?php echo $option_value['border_color'];?>&amp;
		stream=<?php echo $option_value['stream'];?>&amp;
		header=<?php echo $option_value['header'];?>&amp;
		"
		scrolling="no" 
		frameborder="0" 
		style="border:none; overflow:hidden; width:<?php echo $option_value['width'];?>px; height:<?php echo $option_value['height'];?>px;">
		</iframe>
		<small <?php echo $option_value['linkback'] == "false" ? 'style="display:none"' : "" ?>>
			<a href="http://www.ecafechat.com">By: Rashid Azar</a>
		</small>

	<?php
	}
	
	static function ra_fb_likebox_options(){
		add_menu_page(
				__(self::$_ra_option_page_title), 
				self::$_ra_option_menu_title, 
				self::$_ra_option_capability, 
				self::$_ra_option_menu_slug, 
				array('RAFacebookLikeBox', 'ra_fb_like_options_page'),
				plugin_dir_url(__FILE__).self::$_ra_option_icon
			);
	}
	
	static function ra_fb_like_options_page(){
		if(isset($_POST['ra_submit'])){
			if(true || !empty($_POST['ra_title'])) 	update_option(self::$_ra_title		, $_POST['ra_title']);
			if(!empty($_POST['ra_url'])) 		update_option(self::$_ra_url		, $_POST['ra_url']);
			if(!empty($_POST['ra_width'])) 		update_option(self::$_ra_width		, $_POST['ra_width']);
			if(!empty($_POST['ra_height'])) 	update_option(self::$_ra_height		, $_POST['ra_height']);
			if(!empty($_POST['ra_color_scheme'])) 	update_option(self::$_ra_color_scheme	, $_POST['ra_color_scheme']);
			if(!empty($_POST['ra_show_faces'])) 	update_option(self::$_ra_show_faces	, $_POST['ra_show_faces']);
			if(!empty($_POST['ra_border_color'])) 	update_option(self::$_ra_border_color	, $_POST['ra_border_color']);
			if(!empty($_POST['ra_stream'])) 	update_option(self::$_ra_stream		, $_POST['ra_stream']);
			if(!empty($_POST['ra_header'])) 	update_option(self::$_ra_header		, $_POST['ra_header']);
			if(!empty($_POST['ra_linkback'])) 	update_option(self::$_ra_linkback	, $_POST['ra_linkback']);
	?>
			<div id="message" class="updated fade"><p><strong><?php _e('Options saved successfully.'); ?></strong></p></div>
	<?php	
		}
		$option_value = self::ra_retrieve_options();
	?>
		<div class="wrap">
			<h2><?php _e("Rashid's Facebook Like Box Options");?></h2><br />
			<!-- Administration panel form -->
			<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<h3>General Settings</h3>
				<table>
					<tr>
						<td width="150"><b>Title:</b></td>
						<td><input type="text" name="ra_title" size="50" value="<?php echo $option_value['title'];?>"/></td>
					</tr>
			        <tr>
			        	<td width="150"></td>
			        	<td>(Title of the facebook like box)</td>
			        </tr>
			        <tr>
			        	<td width="150"><b>Facebook Page URL:</b></td>
			        	<td><input type="text" name="ra_url" size="50" value="<?php echo $option_value['fb_url'];?>"/></td>
			        </tr>
			        <tr>
			        	<td width="150"></td>
			        	<td>(Copy and paste your facebook page url here)</td>
			        </tr>
					<tr>
						<td width="150"><b>Width:</b></td>
						<td><input type="text" name="ra_width" value="<?php echo $option_value['width'];?>"/>px</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(Width of the facebook like box)</td>
					</tr>
					<tr>
						<td width="150"><b>Height:</b></td>
				        <td><input type="text" name="ra_height" value="<?php echo $option_value['height'];?>"/>px</td>
			        </tr>
					<tr>
						<td width="150"></td>
						<td>(Height of the facebook like box)</td>
					</tr>
			        <tr>
			        	<td width="150"><b>Color Scheme:</b></td>
						<td>
							<select name="ra_color_scheme">
								<option value="light" <?php if($option_value['color_scheme']=="light") echo "selected='selected'";?>>light</option>
								<option value="dark" <?php if($option_value['color_scheme']=="dark") echo "selected='selected'";?>>dark</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(Select the color scheme you want to display)</td>
					</tr>
					<tr>
						<td width="150"><b>Show Faces:</b></td>
						<td>
							<select name="ra_show_faces">
								<option value="true" <?php if($option_value['show_faces']=="true") echo "selected='selected'";?>>Yes</option>
								<option value="false" <?php if($option_value['show_faces']=="false") echo "selected='selected'";?>>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(Select the option to show the faces)</td>
					</tr>
					<tr>
						<td width="150"><b>Border Colour:</b></td>
				        <td><input type="text" name="ra_border_color" value="<?php echo $option_value['border_color'];?>"/></td>
			        </tr>
					<tr>
						<td width="150"></td>
						<td>(Enter hexadecimal colour code, example FFFFFF)</td>
					</tr>
					<tr>
						<td width="150"><b>Stream:</b></td>
						<td>
							<select name="ra_stream">
								<option value="true" <?php if($option_value['stream']=="true") echo "selected='selected'";?>>Yes</option>
								<option value="false" <?php if($option_value['stream']=="false") echo "selected='selected'";?>>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(Select the option to display the stream)</td>
					</tr>
					<tr>
						<td width="150"><b>Header</b></td>
						<td>
							<select name="ra_header">
								<option value="true" <?php if($option_value['header']=="true") echo "selected='selected'";?>>Yes</option>
								<option value="false" <?php if($option_value['header']=="false") echo "selected='selected'";?>>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(Select the option to display the title)</td>
					</tr>
					<tr>
						<td width="150"><b>Linkback</b></td>
						<td>
							<select name="ra_linkback">
								<option value="true" <?php if($option_value['linkback']=="true") echo "selected='selected'";?>>Yes</option>
								<option value="false" <?php if($option_value['linkback']=="false") echo "selected='selected'";?>>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"></td>
						<td>(Linkback to plugin site)</td>
					</tr>
					<tr height="60">
						<td></td>
						<td><input type="submit" name="ra_submit" value="Update Options" style="background-color:#CCCCCC;font-weight:bold;"/></td>
					</tr>	
				</table>
			</form>
		</div>
	<?php
	}
	
	static function ra_fb_likebox_widget($args){
		$option_value = self::ra_retrieve_options($opt_val);
		$option_value['fb_url'] = str_replace(":", "%3A", $option_value['fb_url']);
		$option_value['fb_url'] = str_replace("/", "%2F", $option_value['fb_url']);
		extract($args);
		echo $before_widget;
		echo $before_title;
		//if(empty($option_value['title'])) $option_value['title'] = "Facebook Likes";
		echo $option_value['title'];
		echo $after_title;
		self::ra_print_likebox($option_value);
		echo $after_widget;
	}
	static function ra_fb_likebox_sc($atts){
	    $option_value = self::ra_retrieve_options($opt_val);
	    $option_value['fb_url'] = str_replace(":", "%3A", $option_value['fb_url']);
	    $option_value['fb_url'] = str_replace("/", "%2F", $option_value['fb_url']);
	    self::ra_print_likebox($option_value);
	}
	
	static function ra_fb_plugin_row_meta($meta, $file) {
		if ($file == self::ra_plugin_basename()) {
			$meta[] = '<a href="options-general.php?page=ra_fb_likebox">' . __('Settings') . '</a>';
			$meta[] = '<a href="http://blog.ecafechat.com/donations/" target="_blank">' . __('Donate') . '</a>';
		}
		return $meta;
	}
	
	static function ra_fb_like_init(){
		wp_register_sidebar_widget(__('ra-fb-lb'), __('Rashid\'s Facebook Like Box'), array('RAFacebookLikeBox', 'ra_fb_likebox_widget'));
	}
}

?>
