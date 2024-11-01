<script>
    var plugin_dir_url = '<?php echo plugin_dir_url( __DIR__ ); ?>'
</script>
<div class="wrap">
    <h1 class="wp-heading-inline">New Translation Order</h1>

    <div class="cl_loading">
        <div class="overlay"></div>
        <div class="loading">
            <div class="loading-bar"></div>
            <div class="loading-bar"></div>
            <div class="loading-bar"></div>
            <div class="loading-bar"></div>
        </div>
    </div>

    <form name="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="post">
        <input type="hidden" name="action" value="cltp_create_order">

        <div id="postdiv">

            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" style="position: relative;">

                    <div id="titlediv">
                        <div id="titlewrap">
                            <label class="">Project name<span class="cl_required">*</span> </label>
                            <input type="text" class="cl_price_update" name="project_name" size="30" value="" id="title" spellcheck="true">
                        </div>
                        <br/>
                        <div id="titlewrap">
                            <label class="" style="float: left">Instructions to writer</label>

                            <div id="post_pages_btn" style="float: right; padding: 0px 0px 5px 0px;">
                                <div class="" style="float: right;">
                                    <button id="pages_list_opt" type="button" class="button button-primary button-large" onclick="jQuery('#pages_list').toggle('fast'); jQuery('#post_pages_btn').hide('fast');">Translate post</button>
                                </div>

                                <div class="" style="float: right;  margin-right: 10px">
                                    <button id="post_list_opt" type="button" class="button button-primary button-large" onclick="jQuery('#posts_list').toggle('fast'); jQuery('#post_pages_btn').hide('fast');">Translate page</button>
                                </div>
                            </div>

                        </div>
                        <div style="clear: both; text-align: right">

                            <div id="pages_list" style="display: none">
                                <select id="cltp_pages_list">
                                    <option value="">None</option>
			                        <?php
				                        foreach ( $pages as $p ) {
					                        echo '<option value="' . $p->ID . '">' . $p->post_title . '</option>';
				                        }
			                        ?>
                                </select>

                                <button type="button" class="button button-danger button-large" onclick="jQuery('#pages_list').toggle('fast'); jQuery('#post_pages_btn').show('fast');">Close</button>
                            </div>

                            <div id="posts_list" style="display: none">
                                <select id="cltp_posts_list">
                                    <option value="">None</option>
			                        <?php
				                        foreach ( $posts as $p ) {
					                        echo '<option value="' . $p->ID . '">' . $p->post_title . '</option>';
				                        }
			                        ?>
                                </select>

                                <button type="button" class="button button-danger button-large" onclick="jQuery('#posts_list').toggle('fast'); jQuery('#post_pages_btn').show('fast');">Close</button>
                            </div>
                        </div>

                        <textarea rows="5" cols="40" name="excerpt" style="width: 100%;"></textarea>
                    </div>
                </div>
                <hr/>

                <div id="source_div">
                    <label for="">Select source language<span class="cl_required">*</span></label>
                    <select name="from_id" id="source" data-placeholder="Select a state" class="js-example-basic-single cl_source_lng">
						<?php
							if ( $source_lng ) {
								foreach ( $source_lng as $l ) {
									if ( $l['iso_code'] == 'us' ) {
										echo '<option value="' . $l["iso_code"] . '" selected>' . $l["name"] . '</option>';
									} else {
										echo '<option value="' . $l["iso_code"] . '">' . $l["name"] . '</option>';
									}
								}
							}
						?>
                    </select>
                </div>
                <div id="target_div">
                    <label for="">Select target language(s)<span class="cl_required">*</span></label>
                    <select name="to_id[]" id="target" style="width: 100%" multiple class="js-example-basic-single cl_price_update">
						<?php
							if ( $target_lng ) {
								foreach ( $target_lng as $l ) {
									echo '<option value="' . $l["iso_code"] . '">' . $l["name"] . '</option>';
								}
							}
						?>
                    </select>
                </div>

                <div id="instructions">
                    <label class="">Text for translation:<span class="cl_required">*</span></label>
					<?php

						$settings = array(
							'textarea_name' => 'content',
							'media_buttons' => false,
							'quicktags'     => false,
							'teeny'         => false,
							'editor_class'  => 'cl_price_update',
							'tinymce'       => array(
								'toolbar1' => 'undo,redo, |, bold,italic,underline',
								'toolbar2' => '',
							)
						);
						wp_editor( '', 'content', $settings );
					?>
                </div>
            </div>

        </div>
</div>

<!-- <div id="postdiv" style="float:right;width:20%">
	<div id="submitdiv" class="postbox" style="padding: 10px; margin-top: 30px">
		<h2 style="margin-top: 0px;"><span>Publish</span></h2>
	</div>

</div>-->
<div id="overview">
    <div id="delivery_time">
        <h3>Order overview</h3>
        <hr/>
        <br/>
        <label for="">Deliver within</label>
        <select name="delivery" id="delivery" class="cl_price_update">
			<?php
				if ( $devilery ) {
					foreach ( $devilery as $d ) {
						echo '<option value="' . $d["id"] . '">' . $d["option"] . '</option>';
					}
				}

			?>
        </select>
    </div>
    <div id="major-publishing-actions">
        <div id="cl_lng_breakdown">
            <h4>Number of words: <span id="cl_words">0</span></h4>
            <hr>

            <h4>From <span id="cl_source_name"></span> to:</h4>
            <div id="cl_lng_breakdown_wrapper_result">

            </div>
        </div>
        <br>
        <hr>
        <div id="delete-action">
            <b>Total Cost: <span>$</span><span id="cl_total">0</span></b>
        </div>

        <div id="publishing-action">
            <span class="spinner"></span>
            <input name="original_publish" type="hidden" id="original_publish" value="Publish">
            <input type="submit" name="publish" id="cl_place_order" class="button button-primary button-large" disabled="" value="Place Order"></div>
        <div class="clear"></div>
    </div>
</div>
</form>
<?php require_once plugin_dir_path( dirname( __FILE__ ) ) . "partials/balance.php"; ?>

</div>

