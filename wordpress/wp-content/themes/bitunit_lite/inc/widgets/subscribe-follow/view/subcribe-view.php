<?php
/**
 * Template part to display subscribe form.
 *
 * @package Bitunit_lite
 * @subpackage widgets
 */
?>
<div class="subscribe-block">

	<?php echo $this->get_block_title( 'subscribe' ); ?>
	<?php echo $this->get_block_message( 'subscribe' ); ?>

	<form method="POST" action="#" class="subscribe-block__form"><?php
		wp_nonce_field( 'bitunit_lite_subscribe', 'bitunit_lite_subscribe' );
	?><div class="subscribe-block__input-group"><?php
		echo $this->get_subscribe_input();
		echo $this->get_subscribe_submit( 'btn' );
	?></div><?php
		echo $this->get_subscribe_messages();
	?></form>
</div>
