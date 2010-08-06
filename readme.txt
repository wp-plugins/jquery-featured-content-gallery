=== JQuery Featured Content Gallery ===
Author: David Smith
Author URI: http://www.cibydesign.co.uk
Plugin URI: http://www.cibydesign.co.uk/resources-and-downloads/
Contributors: David Smith
Donate link: http://www.cibydesign.co.uk/resources-and-downloads/
Tags: slideshow, featured content, FCG, JQuery
Requires at least: 2.7
Tested up to: 3.0
Stable tag: trunk

Originally intended to be similar to the famous Featured Content Gallery but using JQuery thus avoiding conflicts with other plugins.

== Description ==

Originally intended to be similar to the famous Featured Content Gallery but using JQuery thus avoiding conflicts with other plugins.  However I tried to use a different, I think simpler, way of administrating the options.  I believe FCG would now say that they are trying to achieve something different.  JFCG is now essentially a rotating banner.

When I started making this plugin I wanted to do it in the most 'proper' way possible, avoiding a quick fix for the website I wrote this for (www.camberleyengineers.com).  I have tried to make all the code clean and use the recommended ways of doing things.  As is always the case with these things, it's probably veered away from this ideal somewhat so any helpful comments would be greatly appreciated.

The original inspiration came from Jon Raasch's excellent tutorial http://jonraasch.com/blog/a-simple-jquery-slideshow

== Installation ==

1. Upload the JFC folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('jfc_gallery'); ?>` in your templates or insert the shortcode [jfctag] in any page or post

== Frequently Asked Questions ==

= Will feature x be added? =

This plugin was created because I needed the current functionality.  If I need extra features in the future I will add them and if I have any spare time I will add features that people request or I think would be good.  If you think you can help with the coding, it's open source, hack away to add those features and tell me what you've done so we can all improve this plugin.  I would be happy to mention your name/website in this readme document.  Indeed, if you feel you could improve this readme, please do :)

== Screenshots ==



== Changelog ==

= 1.0 =
Initial Release

= 1.1 =
Removed unnecessary CSS from jfc.css.php

= 1.2 =
*Fixed gallery icons path issue
*Added ability to change order of frames

== To Do ==

* Validate all fields on admin pages
* Clean up CSS and tables on gallery page
* Change caption to a div tag. It needs to be able to hold HTML as well as having 1.0 opacity forground with lower opacity background.  It is currently in an h2 tag which looks ok but the text has the same opacity level as the background (0.5). I think this will make the biggest difference as to how the plugin is viewed by others.

== Browser compatibility Issues ==


== For the future ==

* Extra JS animations
* Options for how caption shows up rather than users adding all the html etc in the field
* Resize images with imagick rather than HTML/CSS
* JS optimization
