<?php

?>
<div class="wrap">
<h2>Featured Content Options (not used yet!!!)</h2>
<p>To add your Featured Content Gallery to a page, insert the shortcode [jfctag] in any page or use the following in a template file:</p>
<?php
//escape stuff in order to show template shortcode format
$mycode = "<?php do_action('jfc_gallery'); ?>";
$mycode = htmlentities($mycode);
echo "<pre>".$mycode."</pre>";
?>
<p>The Featured Content Gallery is contained in a &lt;div id="jfcg"&gt; tag.</p>

<form method="post" action="options.php">
	<?php settings_fields('jfc_options'); ?>
	<?php $options = get_option('jfc_options'); ?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">Show Caption</th>
			<td><input name="jfc_options[captions]" type="checkbox" value="1" <?php checked('1', $options['captions']); ?> /></td>
		</tr>
		<tr valign="top">
			<td colspan="2">Dimensions (0 or blank for auto)</td>
		</tr>
		<tr valign="top">
			<th scope="row">Height</th>
			<td><input type="text" name="jfc_options[height]" value="<?php echo $options['height']; ?>" /></td>
		</tr>
		<tr valign="top">
			<th scope="row">Width</th>
			<td><input type="text" name="jfc_options[width]" value="<?php echo $options['width']; ?>" /></td>
		</tr>
	</table>
	<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
</form>

</div>