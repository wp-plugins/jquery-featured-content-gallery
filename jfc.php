<?php
/*
 Plugin Name: JQuery Featured Content Gallery
 Plugin URI: http://www.cibydesign.co.uk/resources-and-downloads/
 Description: Similar to the famous Featured Content Gallery but using JQuery thus avoiding conflicts with other plugins.  I also tried to use a different, I think simpler, way of administrating the options.
 Version: 1.2
 Author: David Smith
 Author URI: http://www.cibydesign.co.uk
 */

/*  
 Copyright 2010  David Smith  (email : david@cibydesign.co.uk)
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if (!class_exists('JfcPlugin')) {
    class JfcPlugin {

	static $add_script;

        function __construct() {
		
        }
	
	/**
	 * Activation function
	 * @return
	 */
        function install() {
	
		global $wpdb;
		$jfc_db_version = "1.0";

		$table_name = $wpdb->prefix."jfc";

		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
	
			$sql = "CREATE TABLE ".$table_name." (
					id BIGINT NOT NULL AUTO_INCREMENT,
					name VARCHAR(255) NOT NULL,
					link VARCHAR(255) NOT NULL,
					caption TEXT,
					picture VARCHAR(255) NOT NULL,
					UNIQUE KEY id (id)
					);";

			require_once (ABSPATH.'wp-admin/includes/upgrade.php');
			dbDelta($sql);
	
			add_option("jfc_db_version", $jfc_db_version, '', 'yes');

		}
        }

	/**
	 * Deactivate function
	 * @return
	 */
        function uninstall() {
		
		global $wpdb;

		$table_name = $wpdb->prefix."jfc";

		$sql = "DROP TABLE IF EXISTS ".$table_name.";";

		$wpdb->query($sql);

		delete_option("jfc_db_version");
        }

	/**
	 * Get data from database
	 * @return
	 */
        function get_slideshow() {

		global $wpdb;
		$table_name = $wpdb->prefix."jfc";
		$rs = $wpdb->get_results("SELECT * FROM ".$table_name." ORDER BY `id`");

		return $rs;
	}

	/**
	 * Shortcode function
	 * @return
	 */
        function jfctag($atts) {

		//load javascript needed
		self::$add_script = true;

		//build html markup
		$slideshow_code = '<div id="jfcg">' . "\n";

		//get data from DB
		$frames = self::get_slideshow();
		$options = get_option('jfc_options');

		//tags for each row
		$counter = 0;
		foreach ($frames as $frame) {
			if($counter == 0) {
				//note the class unitPng, this is used to fix png fade issues with IE using the Unit PNG fix (http://labs.unitinteractive.com/unitpngfix.php)

				if($options['height'] > 0) {
					$height_tag = 'height="'.$options['height'].'" ';
				}
				if($options['width'] > 0) {
					$width_tag = 'width="'.$options['width'].'" ';
				}
				$slideshow_code .= '    <div class="active"><a href="'.$frame->link.'" ><img class="unitPng" src="'.$frame->picture.'" alt="'.$frame->name.'" '.$width_tag.' '.$height_tag.'/><h2>'.$frame->caption.'</h2></a></div>'. "\n";
			} else {
				$slideshow_code .= '    <div><a href="'.$frame->link.'" ><img class="unitPng" src="'.$frame->picture.'" alt="'.$frame->name.'" '.$width_tag.' '.$height_tag.'/><h2>'.$frame->caption.'</h2></a></div>' . "\n";
			}
			$counter++;
		}
		
		$slideshow_code .= '</div>' . "\n";

		echo $slideshow_code;
		
        }

	/**
	 * load javascript
	 * @return
	 */
        function add_script() {

		if ( ! self::$add_script )
			return;
 
		wp_register_script('jfcscript', plugins_url('jfcscript.js', __FILE__), array('jquery'), '1.0', true);
		wp_print_scripts('jfcscript');

		
        }

	/**
	 * load CSS
	 * this could be loaded only on pages that need it but I haven't found a good way to do this with css yet and as it's fairly small, I have just loaded it for all pages.
	 * @return
	 */
        function add_css() {
		
		$css_url = plugins_url('jfc.css.php', __FILE__);
		$css_options = get_option('jfc_options');
		echo '<link rel="stylesheet" href="'.$css_url.'" type="text/css" media="screen" charset="utf-8" />' . "\n";
		if (1==2 && $css_options['height'] > 0){
			echo '<style>#jfcg a img{ height: '. $css_options['height'] .'px;}</style>' . "\n";
		}
		
        }

	/**
	 * load admin CSS
	 * @return
	 */
        function add_admin_css() {
		
		$css_url = plugins_url('jfc-options.css', __FILE__);
		echo '<link type="text/css" rel="stylesheet" media="screen" href="'.$css_url.'" />' . "\n";
		
        }

	/**
	 * Add admin menu
	 * @return
	 */
        function add_menu() {

		add_options_page('JQuery Featured Content Options', 'Featured Content Options', 'manage_options', 'jquery-featured-content-options', array(__CLASS__, 'jfc_options'));

		add_options_page('JQuery Featured Content Gallery', 'Featured Content Gallery', 'manage_options', 'jquery-featured-content', array(__CLASS__, 'jfc_gallery'));

		//admin css
		$hook = get_plugin_page_hook('jquery-featured-content', 'options-general.php'); 
		add_action("admin_head-{$hook}", array(__CLASS__, 'add_admin_css'));

	}

	/**
	 * Add admin menu
	 * @return
	 */
        function jfc_options_init() {

		register_setting( 'jfc_options', 'jfc_options', array(__CLASS__, 'jfc_options_validate'));

	}

	/**
	 * Validate and sanitize options
	 * @return
	 */
        function jfc_options_validate($input) {

		// Show caption is either 0 or 1
		$input['captions'] = ( $input['captions'] == 1 ? 1 : 0 );

		// Dimensions are integers
		$input['height'] =  intval($input['height']);
		$input['width'] =  intval($input['width']);

		return $input;

	}

	/**
	 * Include options page
	 * @return
	 */
        function jfc_options() {
		include 'jfc-options.php';
	}

	/**
	 * Include gallery page
	 * @return
	 */
        function jfc_gallery() {
		include 'jfc-gallery.php';
	}

	/**
	 * Initialisation function runs everything
	 * @return
	 */
        function init() {

		add_shortcode('jfctag', array(__CLASS__, 'jfctag'));
		add_action ('jfc_gallery', array(__CLASS__, 'jfctag'));
		add_action('wp_head', array(__CLASS__, 'add_css'));
		add_action('wp_footer', array(__CLASS__, 'add_script'));
		add_action('admin_menu', array(__CLASS__, 'add_menu'));
		add_action('admin_init', array(__CLASS__, 'jfc_options_init'));

	}


    }
}

if (class_exists("JfcPlugin")) {

	//Run Plugin
	$jfcplugin = new JfcPlugin();

	register_activation_hook(__FILE__, array(&$jfcplugin, 'install'));
	register_deactivation_hook(__FILE__, array(&$jfcplugin, 'uninstall'));
	
	JfcPlugin::init();

}

?>
