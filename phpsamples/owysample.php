<?php
	
	function custom_taxonomy_dropdown( $taxonomy, $orderby = 'slug', $order = 'ASC', $limit = '-1', $name, $show_option_all = null, $show_option_none = null ) {
		$args = array(
			'orderby' => $orderby,
			'order' => $order,
		);
		$terms = get_terms( $taxonomy, $args );
		$name = ( $name ) ? $name : $taxonomy;
		if ( $terms ) {
			printf( '<select name="%s" class="postform">', $name );
		if ( $show_option_all ) {
			printf( '<option value="0">%s</option>', $show_option_all );
		}
		if ( $show_option_none ) {
			printf( '<option value="-1">%s</option>', $show_option_none );
		}
			printf( '<option>Please Select</option>' );
		foreach ( $terms as $term ) {
			printf( '<option value="%s">%s</option>', $term->slug, $term->name );
		}
			print( '</select>' );
		}
	}

	function custom_override_checkout_fields( $fields ) {
		unset($fields['order']['order_comments']);
		unset($fields['account']['account_password-2']);
		$fields['account']['account_password-2'] = array(
			'type' => 'password',
			'label' => 'Re-type Password',
			'placeholder' => _x('Password', 'placeholder', 'woocommerce'),
			'class' => array('form-row-last')
		);
		$fields['order']  = array(
			'order_comments' => array(
				'type' => 'hidden',
				'class' => array('notes'),
				'value' => ''
			)
		);
		return $fields;
	}
	add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

	function custom_field_charity_donation( $checkout ) {
		echo '<div id="charity_donation"><h3>'.__('Charity Donation').'</h3>';
		woocommerce_form_field( 'charity_donation', array(
			'type'          => 'select',
			'options'  =>  array(
			                  'No Charity Selected' => __('Select a Charity...'),
			                  'Water.org' => __('Water.org'),
			                  'The Hunger Project' => __('The Hunger Project'),
			                  'Big Brothers Big Sisters of Massachusetts Bay' => __('Big Brothers Big Sisters of Massachusetts Bay'),
			                  'Leukemia Lymphoma Society' => __('Leukemia Lymphoma Society'),
			                  'Please Choose For Me' => __('Please Choose For Me')
			                ),
			'class'         => array('my-field-class form-row-wide'),
		), $checkout->get_value( 'charity_donation' ));
		echo '</div>';
	}

	global $current_user;
	get_currentuserinfo();
	if(WC_Subscriptions_Manager::user_has_subscription( $current_user->ID )){
	    $has_subscription = true;
	} else {
	    $has_subscription = false;
	}

	$bc_url = 'http://api.brightcove.com/services/library?token='.BC_TOKEN.'&command=';
	$preview = !$has_subscription;

	function query_bc_ids($video_ids){
		$command = 'find_videos_by_ids';
		$id_query = '&video_ids=';
		foreach($videos_ids as $key=>$video){
			if($key !== 0){
				$id_query .= ',';
			}
			if($preview){
				$id_query .= get_field('preview_id', $video->ID);
			} else {
				$id_query .= get_field('video_id', $video->ID);
			}
		}
	}

/*----------------
Playlist Functions
----------------*/

	function mp_get_user_id() {
	  global $current_user;
	  get_currentuserinfo();
	  return $current_user->ID;
	}

	function mp_add_to_usermeta($post_id) {
	  $user_id = mp_get_user_id();
	  $mp_read = mp_get_user_meta($user_id);
	  // add out post id to the end of the array
	  $mp_read[] = $post_id;
	  mp_update_user_meta($mp_read, $user_id);
	}

	function mp_add_playlist() { ?>
	  <form id="testForm" action="<?php bloginfo('stylesheet_directory'); ?>/process.php" method="post">
	    <input type="text" name="createPlaylist" id="createPlaylist" placeholder="Enter new playlist name">
	    <input type="submit" name="createPlaylistButton" id="createPlaylistButton" value="Create a Playlist">
	  </form>
	<?php }

	function queue_add_playlist() { ?>
	  <form id="saveAsPlaylistForm" action="<?php bloginfo('stylesheet_directory'); ?>/process.php" method="post">
	    <input type="text" name="queueCreatePlaylist" id="queueCreatePlaylist" placeholder="Enter new playlist name">
	    <input type="submit" name="queueCreatePlaylistButton" id="queueCreatePlaylistButton" value="Create a Playlist">
	  </form>
	<?php }

	function my_playlists() {
	  show_playlists_mp();
	}

	function show_playlists_videos() {
	  $user_id = mp_get_user_id();
	  $show_data = get_user_meta($user_id, 'createPlaylist');
	  foreach($show_data as $playlist_id) { 
	    $playlist = $playlist_id['id']; ?>
	    <a class="playlist add_to_playlist_control" href="#" rel="<?php echo esc_attr($playlist); ?>"><?php echo $playlist; ?></a><br />
	    <?php
	  }
	}

	function show_playlists_mp() {
	  $user_id = mp_get_user_id();
	  $show_data = get_user_meta($user_id, 'createPlaylist');
	  if($show_data) {
	    foreach($show_data as $playlist_id) { 
	      $playlist = $playlist_id['id']; 
	      if($playlist) { ?>
	        <div class="mpPlaylists <?php echo esc_attr($playlist); ?> clearfix">
	          <div class="playlist-header dropdown header-background">
	            <h1><?php echo $playlist; ?></h1>
	          </div>
	          <div class="playlistHidden">
	            <a class="playAll" href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/playall.png" /></a>
	            <ul class="sortable">
	              <?php
	              $videos = $playlist_id['videos'];
	              foreach($videos as $video) { 
	                $video_title = get_the_title($video); 
	                $video_link = get_permalink($video); ?>
	                <li class="ui-state-default"><a class="videoTitle" href="<?php echo $video_link; ?>"><?php echo $video_title; ?></a><a class="deleteVideo" href="#" rel="<?php echo esc_attr($video); ?>">Delete</a></li>
	                <div class="clear"></div>
	              <?php } ?>
	            </ul>
	            <a class="savePlaylist" href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/saveplaylist.png" /></a>
	            <a class="deletePlaylist" href="#" rel="<?php echo $playlist; ?>">Delete Playlist</a>
	          </div>
	        </div>
	      <?php }
	    }
	  } else { ?>
	    <p style="margin-bottom: 5%;">You have not created any playlists yet.</p>
	  <?php }
	}

	function save_playlist() {
	  $user_id = mp_get_user_id();
	  $playlist = array(
	      'id' => $_POST['playlist_name'],
	      'videos' => array()
	    );
	  add_user_meta($user_id, 'createPlaylist', $playlist);
	}

	function queue_save_playlist() {
	  $user_id = mp_get_user_id();
	  $queue_array = $_POST['queue_create_array'];
	  $queue_playlist = array(
	      'id' => $_POST['queue_playlist_name'],
	      'videos' => $_POST['queue_create_array']
	    );
	  add_user_meta($user_id, 'createPlaylist', $queue_playlist);
	}

	function save_to_playlist() {
	  $user_id = mp_get_user_id();
	  $show_data = get_user_meta($user_id, 'createPlaylist');
	  $target_list = urldecode($_POST['add_to_playlist']);
	  foreach($show_data as $playlist) {
	    $playlist_id = $playlist['id'];
	    $oldPlaylist = $playlist;
	    if($target_list == $playlist_id) {
	      $page_id = $_POST['add_page_id'];
	      $playlist['videos'][] = $page_id;
	      update_user_meta($user_id, 'createPlaylist', $playlist, $oldPlaylist);
	      return $playlist;
	    }
	  }
	}

	function remove_from_playlist() {
	  $user_id = mp_get_user_id();
	  $show_data = get_user_meta($user_id, 'createPlaylist');
	  foreach($show_data as $playlist) {
	    $playlist_id = $playlist['id'];
	    $oldPlaylist = $playlist;
	    if($_POST['playlist_id_delete_video'] == $playlist_id) {
	      $page_id = $_POST['delete_video'];
	      $videosArray = $playlist['videos'];
	      $unsetKey = array_search($page_id, $videosArray);
	      unset($playlist['videos'][$unsetKey]);
	      update_user_meta($user_id, 'createPlaylist', $playlist, $oldPlaylist);
	      return $playlist;
	    }
	  }
	}

/*This is where the magic happens*/

	function reorder_playlist() {
	  $user_id = mp_get_user_id();
	  $show_data = get_user_meta($user_id, 'createPlaylist');
	  foreach($show_data as $playlist) {
	    $playlist_id = $playlist['id'];
	    $oldPlaylist = $playlist;
	    if($_POST['playlist_id_reorder'] == $playlist_id) {
	        unset($playlist['videos']);
	        $playlist['videos'] = array();
	      foreach($_POST['array_values'] as $array_value) {
	        $playlist['videos'][] = $array_value;
	      }
	      update_user_meta($user_id, 'createPlaylist', $playlist, $oldPlaylist);
	      return $playlist;
	    }
	  }
	}

	function delete_playlist() {
	  $user_id = mp_get_user_id();
	  $show_data = get_user_meta($user_id, 'createPlaylist');
	  foreach($show_data as $playlist) {
	    if($_POST['delete_playlist'] == $playlist['id']) {
	      delete_user_meta($user_id, 'createPlaylist', $playlist);
	    }
	  }
	}

	function get_video_ids() {
	  $user_id = mp_get_user_id();
	  $response = wp_remote_get( 'http://api.brightcove.com/services/library?command=search_videos&token=cNNS5-37V1TZaYBMmcmb3bfRToWXhl5LyaCA6Mo87R6h8Twq7Vm0xQ..' );
	  $decodedResponse = json_decode($response['body']);
	  $items = $decodedResponse->items;
	  $video_array = array();
	  foreach($_POST['page_id_array'] as $page_id) {  
	    $video_id = get_field('video_id', $page_id);
	    $video_id = intval($video_id);
	    $video_array[] = $video_id;
	  }
	  $video_array_json = json_encode($video_array);
	  echo $video_array_json;
	}

	function mp_remove_from_usermeta($post_id) {
	  $user_id = mp_get_user_id();
	  $mp_read = mp_get_user_meta($user_id);
	  foreach ($mp_read as $read_post => $id) {
	    if ($id == $post_id) {
	      unset($mp_read[$read_post]);
	    }
	  } 
	  mp_update_user_meta($mp_read, $user_id);
	}

	function mp_update_user_meta($arr, $user_id) {
	  return update_user_option($user_id,'mp_read_posts', $arr);
	}

	function mp_post_mark_as_read() {
	  if ( isset( $_POST["post_read"] ) ) {
	    $post_id = intval($_POST["post_read"]);
	    $marked_as_read = mp_add_to_usermeta($post_id);
	    $update_count = mp_increase_count($post_id);
	    die();
	  }
	}
	add_action('wp_ajax_bookmark_post', 'mp_post_mark_as_read');
 
	// Our hooked in function - $fields is passed via the filter!
	function custom_remove_checkout_fields( $fields ) {
	     unset($fields['billing']['billing_company']);
	     unset($fields['billing']['billing_phone']);
	 
	     return $fields;
	}


	add_action('save_post', 'update_video_meta');
	function update_video_meta($post_id){
	  $slug = 'videos';
	  if( !isset($_POST['post_type']) ) {
	    return;
	  }
	  /* check whether anything should be done */
	  if ( ($slug != $_POST['post_type']) ) {
	      return;
	  }
	  if ( !current_user_can( 'edit_post', $post_id ) ) {
	      return;
	  }

	  $video_ids = get_field('preview_id', $post_id) . ',' . get_field('video_id', $post_id);
	  $bc_url = 'http://api.brightcove.com/services/library?command=find_videos_by_ids';

	  $bc_url .= '&video_ids=' . $video_ids;
	  $bc_url .= '&token='.BC_TOKEN;

	  $response = wp_remote_get( $bc_url );
	  $decodedResponse = json_decode($response['body']);
	  $items = $decodedResponse->items;
	  update_post_meta($post_id, '_preview_img', $items[0]->videoStillURL);
	  update_post_meta($post_id, '_video_img', $items[1]->videoStillURL);
	  update_post_meta($post_id, '_videos', $bc_url);
	}

	function display_video_thumb($post_id, $preview = true){
	  if(!get_field('_preview_img') || !get_field('_video_img')){
	    $video_ids = get_field('preview_id', $post_id) . ',' . get_field('video_id', $post_id);
	    $bc_url = 'http://api.brightcove.com/services/library?command=find_videos_by_ids';

	    $bc_url .= '&video_ids=' . $video_ids;
	    $bc_url .= '&token='.BC_TOKEN;
	    //echo $bc_url;
	    $response = wp_remote_get( $bc_url );
	    $decodedResponse = json_decode($response['body']);
	    $items = $decodedResponse->items;
	    //print_r($items);
	    if(!empty($items[0])){
	      update_post_meta($post_id, '_preview_img', $items[0]->videoStillURL);
	    }
	    if(!empty($items[1])){
	      update_post_meta($post_id, '_video_img', $items[1]->videoStillURL);
	    }
	  }
	  if($preview){
	    $src = get_field('_preview_img');
	  } else {
	    $src = get_field('_video_img');
	  }
	  echo '<img src="' . $src . '" />';
	}

?>