<?php

if (!class_exists('JfcOptions')) {

	class JfcOptions {

		var $name;
		var $link;
		var $caption;
		var $picture;
		var $action;
		var $id;

		/**
		 * Constructor
		 * @return
		 */
		function __construct() {
			
		}

		/**
		 * Initialisation function runs everything
		 * @return
		 */
		function init() {

			//form actions
			if(isset($_GET['frame'])) {
				$this->action = $_GET['frame'];

				//add a new frame to the gallery
				if($this->action == 'new') {
					$this->add_frame();
				
					$this->name = NULL;
					$this->link = NULL;
					$this->caption = NULL;
					$this->picture = NULL;
					$this->action = NULL;
					$this->id = NULL;
				}

				//edit a current frame in the gallery (loads values into form)
				if($this->action == 'edit') {
					$this->edit_frame();
				}

				//save changes to a current frame in the gallery
				if($this->action == 'save') {
					$this->save_frame();
				
					$this->name = NULL;
					$this->link = NULL;
					$this->caption = NULL;
					$this->picture = NULL;
					$this->action = NULL;
					$this->id = NULL;
				}

				//delete a frame from the gallery
				if($this->action == 'delete') {
					$this->id = $_GET['id'];
					$this->delete_frame();
				}
			}
		}

		/**
		 * Add a new frame to the database
		 * @return string result
		 */
		function add_frame() {

			if (isset($_POST['submitted'])) {
				
				$this->name = $_POST['name'];
				$this->link = $_POST['link'];
				$this->caption = $_POST['caption'];
				$this->picture = '/wp-content/uploads/jfc/default.jpg';
				$this->maxfilesize = $_POST['MAX_FILE_SIZE'];

				//add a row to the database and get the new row id
				global $wpdb;
				$table_name = $wpdb->prefix."jfc";

				$sql = $wpdb->prepare("INSERT INTO ".$table_name."(`name`, `link`, `caption`, `picture`) VALUES (%s, %s, %s, %s)", $this->name, $this->link, $this->caption, $this->picture);

				$dbresult = $wpdb->query($sql);

				$this->id = $wpdb->insert_id;

				//check a file has been uploaded
				if (isset($_FILES['picture'])){
					//try to upload picture
					$file = $this->upload_picture($_FILES['picture']);

					if (isset($file['error'])) {

						//Display error and remove entry from DB
						$sql = $wpdb->prepare("DELETE FROM ".$table_name." WHERE id = %d", $this->id);

						$dbresult = $wpdb->query($sql);

						$file['error'];

					} else {
				
						//Add picture path to database
						$sql = $wpdb->prepare("UPDATE ".$table_name." SET `picture` = %s WHERE `id` = %d", $file['url'], $this->id);

						$dbresult = $wpdb->query($sql);
	
					}
				}

			}
		}

		/**
		 * Handle Picture Upload
		 * @return array result
		 */
		function upload_picture($upload) {

			//check file is a decent picture
			if(!file_is_displayable_image($upload['tmp_name'])) {
				$file['error'] = 'File is not a valid picture';
				return $file;
			}

			/*
			 * re-name picture and upload
			 * the new name is constructed from the name of the new frame submitted in the form,
			 * id and size.
			 * e.g. my-new-frame_123_original.jpg
			 */
				
			//get filename
			$filename = basename($upload['name']);

			//get extension
			$ext = substr($filename, strrpos($filename, '.') + 1);
			$ext = strtolower($ext);
	
			$this->name = str_replace(' ', '_', $this->name);
			$this->name = str_replace('-', '_', $this->name);
			$this->name = strtolower($this->name);
			$this->name = $this->name.'_'.$this->id.'_original.'.$ext;

			$upload['name'] = $this->name;
	
			//wordpress now handles upload
			$overrides = array('test_form' => false);
			$file = wp_handle_upload($upload, $overrides);
			
			return $file;

		}


		/**
		 * Save changes to a current frame in the database
		 * @return 
		 */
		function save_frame() {

			if (isset($_POST['submitted'])) {
				
				$this->id = $_GET['id'];
				$this->name = $_POST['name'];
				$this->link = $_POST['link'];
				$this->caption = $_POST['caption'];
				$this->maxfilesize = $_POST['MAX_FILE_SIZE'];

				//update row in database apart from picture
				global $wpdb;
				$table_name = $wpdb->prefix."jfc";
				$sql = $wpdb->prepare("UPDATE ".$table_name." SET `name` = %s, `link` = %s, `caption` = %s WHERE `id` = %d", $this->name, $this->link, $this->caption, $this->id);

				$dbresult = $wpdb->query($sql);

				//check if a file has been uploaded
				if (isset($_FILES['picture'])){

					//try to upload picture
					$file = $this->upload_picture($_FILES['picture']);

					if (isset($file['error'])) {

						//Display error
						$file['error'];

					} else {
				
						//Add picture path to database
						$sql = $wpdb->prepare("UPDATE ".$table_name." SET `picture` = %s WHERE `id` = %d", $file['url'], $this->id);

						$dbresult = $wpdb->query($sql);
	
					}
				}

			}
		}

		/**
		 * Edit a current frame in the database
		 * @return 
		 */
		function edit_frame() {
				
			$this->id = $_GET['id'];
			
			global $wpdb;
			$table_name = $wpdb->prefix."jfc";

			//get frame
			$frame = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE id = ".$this->id);
	
			$this->name = $frame->name;
			$this->link = $frame->link;
			$this->caption = $frame->caption;
			$this->picture = $frame->picture;

		}

		/**
		 * Delete a frame from the database
		 * @return 
		 */
		function delete_frame() {
			global $wpdb;
			$table_name = $wpdb->prefix."jfc";

			//get frame
			$frame = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE id = ".$this->id);

			//delete frame
			$sql = $wpdb->prepare("DELETE FROM ".$table_name." WHERE id = %d", $this->id);

			$dbresult = $wpdb->query($sql);

			//delete picture
			if (!empty($frame->picture)) {
				//unlink(WWW.$frame->picture);
			}
		}

		/**
		 * Returns the frame data from the database
		 * @return result set object
		 */
		function get_data() {
			global $wpdb;
			$table_name = $wpdb->prefix."jfc";
			$rs = $wpdb->get_results("SELECT * FROM ".$table_name);
			return $rs;
		}

	}

}

if (class_exists('JfcOptions')) {
	
	$jfcoptions = new JfcOptions();
	//Run Options Page
	$jfcoptions->init();
}

//now print out the html...
?>
<div class="wrap">
<h2>Featured Content Gallery</h2>

<?php if (isset($jfcoptions->name)) {
	$action = "/wp-admin/options-general.php?page=jquery-featured-content&frame=save&id=".$jfcoptions->id;
} else {
	$action = "/wp-admin/options-general.php?page=jquery-featured-content&frame=new";
}?>

<form method="post" action="<?php echo get_option('siteurl').$action; ?>" enctype="multipart/form-data">
<table class="form-table">
	<tr valign="top">
		<th scope="row">Name (used for alt)</th>
		<td><input type="text" name="name" value="<?php echo($jfcoptions->name); ?>" /></td>
	</tr>
	<tr valign="top">
		<th scope="row">Link</th>
		<td><input type="text" name="link" value="<?php echo($jfcoptions->link); ?>" /></td>
	</tr>
	<tr valign="top">
		<th scope="row">Caption</th>
		<td><textarea rows="4" cols="40" name="caption" ><?php echo($jfcoptions->caption); ?></textarea></td>
	</tr>
	<tr valign="top">
		<th scope="row">Picture</th>
		<td><input name="picture" type="file" /></td>
	</tr>
<?php if (isset($jfcoptions->picture)) {?>
	<tr>
		<td colspan="2" id="jfc-form-image">
			<img width='580' src='<?php echo $jfcoptions->picture; ?>' />
		</td>
	</tr>
<?php } ?>
</table>
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
<input type="hidden" name="submitted" />

<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	<?php if (isset($jfcoptions->name)) {
		$cancel = get_option('siteurl')."/wp-admin/options-general.php?page=jquery-featured-content";
		echo '<input type="button" value="Cancel" class="button-primary" onclick="location.assign( \''.$cancel.'\' );">';
	}?></p>
</form>

<table id="frames_table">
	<thead>
		<tr>
			<th>Name</th>
			<th>Link</th>
			<th>Caption</th>
			<th>Picture</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	$frames = $jfcoptions->get_data();
	if(isset($frames)) {
		foreach ($frames as $frame) { 
	?>
		<tr>
			<td><?php echo($frame->name); ?></td>
			<td><?php echo($frame->link); ?></td>
			<td><?php echo($frame->caption); ?></td>
			<td><img height="50" width="50" src="<?php echo $frame->picture; ?>" /></td>
			<td><a
				href="<?php echo(get_option('siteurl')); ?>/wp-admin/options-general.php?page=jquery-featured-content&frame=edit&id=<?php echo($frame->id);?>"><img
				src="<?php echo(get_option('siteurl')); ?>/wp-content/plugins/jfc/edit_icon.png"></img></a></td>
			<td><a
				href="<?php echo(get_option('siteurl')); ?>/wp-admin/options-general.php?page=jquery-featured-content&frame=delete&id=<?php echo($frame->id);?>"><img
				src="<?php echo(get_option('siteurl')); ?>/wp-content/plugins/jfc/delete_icon.png"></img></a></td>
		</tr>
		<?php }}?>
		<tr>
			<td></td>
		</tr>
	</tbody>
</table>
</div>