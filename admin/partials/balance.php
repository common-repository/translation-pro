<?php
	$balance = \Contentlocalized\ContentlocalizedAPI::GetBalance();

	if(isset($balance["prepaid"]) && $balance["prepaid"]) {
		?>

		<div class="cl_balance">
			<p>Balance
			$<?php echo $balance['balance']; ?>
			</p>

            <form target="_blank" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
                <input type="hidden" name="action" value="cltp_add_balance">

                <input type="submit" value="ADD CREDITS" />
            </form>
		</div>
		<?php
	}
	?>