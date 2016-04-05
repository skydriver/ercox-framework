<?php

/**
 *	Template Name: Payment Details
 **/

require_once 'lib/mediator-member-loader.php';

global $member;
$member = new Mediator_Member;



add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );




add_filter('body_class', 'codeart_payment_details_body_classes');
function codeart_payment_details_body_classes( $classes )
{
	$classes[] = 'codeart-payment-details';
	return $classes;
}




remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_payment_details_loop');
function codeart_payment_details_loop()
{
	$u = get_current_user_id();
	$sID = get_user_stripe_id( $u ); ?>

	<div class="entry">

		<div class="cancel-account">
		<p>If you want to cancel your account <a href="#" target="_blank">click here</a></p>
	</div>

		<div class="entry-content">

			<?php codeart_member_navigation_buttons(); ?>

			<div class="fullblock">
				<div class="box">
					<h4>Your Card(s) Details</h4>

					<?php
					$cards = get_cards();
					$cards = isset($cards->data) ? $cards->data : false;
					?>

					<?php if( empty($cards) ): ?>
					<p class="no-result no-cards">No cards yet!</p>
					<?php else: ?>
					<div class="list-table cart-table">
					    <div class="table-border">
						    <div class="row head">
							<div class="col item-1"><span>#</span></div>
							<div class="col item-2"><span>Card Number</span></div>
							<div class="col item-3"><span>Card Date</span></div>
							<div class="col item-4"><span>Manage Card</span></div>
						</div>

						<?php $card_counter = 0; ?>
						<?php foreach ($cards as $card): ?>
						<?php
							$dataCard 					= array();
							$dataCard[ 'id' ]			= $card->id;
							$dataCard[ 'last4' ]		= $card->last4;
							$dataCard[ 'exp_month' ]	= sprintf( '%02d', $card->exp_month );
							$dataCard[ 'exp_year' ]		= $card->exp_year;
						?>
						<div class="row <?php echo $card_classes % 2 == 0 ? 'even' : 'odd'; ?>" data-id="<?php echo $card->id; ?>">
							<div class="col item-1"><span><?php echo $card_counter + 1; ?></span></div>
							<div class="col item-2"><span>XXXX-XXXX-XXXX-<?php echo $card->last4; ?></span></div>
							<div class="col item-3">
								<span>
									<?php printf( '%02d', $card->exp_month ); ?>
									/
									<?php printf( '%d', $card->exp_year ); ?>
								</span>
							</div>
							<div class="col item-4">
								<a href="#" class="edit edit-card" data-card='<?php echo json_encode( $dataCard ); ?>'>Edit</a>
								<a href="#" class="delete delete-card" data-cardid="<?php echo $card->id; ?>">Delete</a>
							</div>
						</div>
						<?php endforeach; ?>

                        </div>
						<a href="#" class="add-new-cart add-card">Add New Cart</a>
					</div>
					<?php endif; ?>

					<h4>Your Payments</h4>

					<?php
					$charges = get_payments();
					$charges = isset($charges->data) ? $charges->data : false;
					?>

					<?php if(empty($charges)): ?>
					<p class="no-result no-charges">No payments yet!</p>
					<?php else: ?>
					<div class="list-table payment-table">
					    <div class="table-border">
						<div class="row head">
							<div class="col item-1"><span>#</span></div>
							<div class="col item-2"><span>Date</span></div>
							<div class="col item-3"><span>Description</span></div>
							<div class="col item-4"><span>Total</span></div>
							<div class="col item-5"><span>Cart</span></div>
							<div class="col item-6"><span>Details</span></div>
						</div>

						<?php $payment_counter = 0; ?>
						<?php foreach($charges as $charge): ?>
							<?php
								if( empty($charge->invoice) ) continue;
								$invoice = get_invoice($charge->invoice);
								$plan_id = $invoice->lines->data[0]->plan->id;
							?>
							<div class="row <?php echo $payment_counter++ % 2 == 0 ? 'even' : 'odd'; ?>">
								<div class="col item-1"><span><?php echo $payment_counter+1; ?></span></div>
								<div class="col item-2"><span><?php echo date( 'm / Y', $charge->created ); ?></span></div>
								<div class="col item-3"><span>N/A</span></div>
								<div class="col item-4">
									<span>
										<?php
										$amount = $charge->amount / 100;
										echo '$ ' . number_format( (float) $amount, 2, '.', '' );
										?>
									</span>
								</div>
								<div class="col item-5"><span>XXXX-XXXX-XXXX-<?php echo $charge->source->last4; ?></span></div>
								<div class="col item-6"><a href="#" class="download-invoice">Download invoice</a></div>
							</div>
							<?php endforeach; ?>
						</div>

					</div>
					<?php endif; ?>

				</div>
			</div>

		</div>
	</div>
	<?php
}


genesis();

?>