<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo $order['name']; ?></h1>
    <?php
        if($order['jobs'] && $order["status"] == "Completed") {
            foreach($order['jobs'] as $j) {
                ?>
                <div class="order_box">
                    <div class="order_description">
                        <div class="order_language">
                            <p>
                                <span class="flag">
                                    <img src="<?php echo plugin_dir_url( __DIR__ ); ?>img/flags_iso/16/<?php echo $j['sourceable']['languages']['from_lang']['iso_code']; ?>.png">
                                </span>
	                            <?php echo $j['sourceable']['languages']['from_lang']['name']; ?>
                                <span class="divider"> &gt; </span>
                                <span class="flag">
                                    <img src="<?php echo plugin_dir_url( __DIR__ ); ?>img/flags_iso/16/<?php echo $j['sourceable']['languages']['to_lang']['iso_code']; ?>.png">
                                </span>
	                            <?php echo $j['sourceable']['languages']['to_lang']['name']; ?>
                            </p>
                            <a class="order_status completed"><?php echo $j["status_tag"]; ?></a>
                        </div>
                        <div class="order_price">
                            <p>
                                Price: $<?php echo number_format ($j['price'], 2, '.', ''); ?>
                            </p>
                        </div>                        

                        <div class="order_completed">
                            <div class="order_main_content" id="<?php echo $j['id']; ?>">
                                <div class="translation_box">
                                    <h5>
											    <span class="flag">
											    	<img src="<?php echo plugin_dir_url( __DIR__ ); ?>img/flags_iso/16/<?php echo $j['sourceable']['languages']['from_lang']['iso_code']; ?>.png">
											    </span>
	                                    <?php echo $j['sourceable']['languages']['from_lang']['name']; ?>
                                    </h5>
	                                <?php echo $j['sourceable']['raw_content']; ?>
                                </div>
                                <div class="translation_box target_language">
                                    <h5>
											    <span class="flag">
											    	<img src="<?php echo plugin_dir_url( __DIR__ ); ?>img/flags_iso/16/<?php echo $j['sourceable']['languages']['to_lang']['iso_code']; ?>.png">
											    </span>
	                                    <?php echo $j['sourceable']['languages']['to_lang']['name']; ?>
                                    </h5>
                                    <?php echo $j['sourceable']['translated']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <?php
            }
        }else {
            echo '<h1>Order still in progress</h1>';
        }
    ?>
    <!-- Items -->
	<p class="total">TOTAL PRICE $<?php echo number_format ($order['price'], 2, '.', ''); ?></p>
    <!-- End items -->
</div>

