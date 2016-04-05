<?php

/*************SET POPUPS*************/
/************************************/
add_action( 'genesis_after', 'ca_stripe_cards_popups' );
function ca_stripe_cards_popups()
{
	?>

	<div class="stripe-popup">
		<div class="overlay"></div>

		<div class="stripe-popup-wrap">

			<div class="loading">
				<div class="spinner"></div>
			</div>

			<div class="stripe-add-edit-card">
				<div class="card-header">
					<!-- <img src="<?php echo get_stylesheet_directory_uri() . '/images/card-icon.png'; ?>" /> -->
					<p>Card details:</p>					
				</div>

				<div class="fields">

					<input type="text" name="card-number" placeholder="Credit Card Number" />
					<input type="text" name="card-cvc" placeholder="CVC" />

					<div class="exp">
						<select name="card-exp-month">
							<?php for( $i = 1; $i <= 12; $i++ ) printf( '<option value="%02d">%02d</option>', $i, $i ); ?>
						</select>

						<select name="card-exp-year">
							<?php for( $i = 2015; $i <= 2030; $i++ ) printf( '<option value="%d">%d</option>', $i, $i ); ?>
						</select>
					</div>

				</div>

				<div class="buttons">
					<a href="#" class="save-card">Save</a>
					<a href="#" class="save-cancel">Cancel</a>
				</div>
			</div> <!-- end .stripe-card-popup-content -->

			<div class="stripe-delete-card">
				<p>Are you sure you want to delete saved card ?</p>

				<div class="buttons">
					<a href="#" class="delete-yes">Yes</a>
					<a href="#" class="delete-no">No</a>
				</div>

			</div> <!-- end .stripe-card-delete -->

		</div> <!-- end .stripe-card-popup-wrap -->

	</div> <!-- end .stripe-card-popup -->

	<script type="text/javascript">

		Number.prototype.pad = function( size )
		{
			var s = String(this);
			while( s.length < ( size || 2 ) ) { s = "0" + s; }
			return s;
		}

		jQuery( document ).ready( function($)
		{
			// var ajaxurl = 'https://elite.hiitmax.com/wp-admin/admin-ajax.php';

			var overlay		= $( '.stripe-popup .overlay' )
			,	popUpWrap 	= $( '.stripe-popup .stripe-popup-wrap' );

			overlay.on( 'click', function()
			{
				close_popup();
			});

			function close_popup()
			{
				popUpWrap.fadeOut( 100, function() { overlay.fadeOut( 150 ); hide_modals(); });
			}

			function hide_modals()
			{
				popUpWrap.find( '> div' ).hide();
			}

			function loading_open()
			{
				popUpWrap.find( '.loading' ).fadeIn( 250 );
			}

			function loading_close()
			{
				popUpWrap.find( '.loading' ).fadeOut( 250 );
			}

			$( 'a.add-card' ).on( 'click', function( e )
			{
				e.preventDefault();

				overlay.fadeIn( 150, function()
				{
					var fieldsForm = popUpWrap.find( 'div.stripe-add-edit-card' );
					fieldsForm.removeData( 'cardid' ).show();

					fieldsForm.find( 'input[name="card-number"]' ).val( '' ).removeAttr( 'disabled' ).show();
					fieldsForm.find( 'input[name="card-cvc"]' ).val( '' ).removeAttr( 'disabled' ).show();
					fieldsForm.find( 'select[name="card-exp-month"]' ).val( '' );
					fieldsForm.find( 'select[name="card-exp-year"]' ).val( '' );

					popUpWrap.fadeIn( 350 );
				});
			});

			$( 'body' ).on( 'click', 'a.edit-card', function( e )
			{
				e.preventDefault();

				var dataCard = $( this ).data( 'card' );

				overlay.fadeIn( 150, function()
				{
					var fieldsForm = popUpWrap.find( 'div.stripe-add-edit-card' );

					fieldsForm.data( 'cardid', dataCard.id ).show();

					fieldsForm.find( 'input[name="card-number"]' ).val( 'xxxx xxxx xxxx ' + dataCard.last4 ).prop( 'disabled', true ).hide();
					fieldsForm.find( 'input[name="card-cvc"]' ).val( 'xxxx' ).prop( 'disabled', true ).hide();
					fieldsForm.find( 'select[name="card-exp-month"]' ).val( dataCard.exp_month );
					fieldsForm.find( 'select[name="card-exp-year"]' ).val( dataCard.exp_year );

					popUpWrap.fadeIn( 350 );
				});
			});

			$( 'a.save-card' ).on( 'click', function( e )
			{
				e.preventDefault();

				if ( typeof popUpWrap.find( 'div.stripe-add-edit-card' ).data( 'cardid' ) === 'undefined' )
					var cardID = '';
				else
					var cardID = popUpWrap.find( 'div.stripe-add-edit-card' ).data( 'cardid' );

				var cardNumber 	= popUpWrap.find( 'input[name="card-number"]' ).val();
				var cardCVC 	= popUpWrap.find( 'input[name="card-cvc"]' ).val();
				var cardEmon 	= popUpWrap.find( 'select[name="card-exp-month"]' ).val();
				var cardEyear 	= popUpWrap.find( 'select[name="card-exp-year"]' ).val();

				var data = {
					'action' : 'save_card',
					'cardid' : cardID,

					'card_number'	: cardNumber,
					'card_cvc'		: cardCVC,
					'card_exp_mon'	: cardEmon,
					'card_exp_year'	: cardEyear
				};

				loading_open();

				$.post(
					ajaxurl,
					data,
					function( response )
					{
						console.log(response);
						if( response.success )
						{
							var object = {
								'id'		: response.data.id,
								'last4'		: response.data.last4,
								'exp_month'	: parseInt( response.data.exp_month ).pad(),
								'exp_year'	: response.data.exp_year
							};

							if( cardID.length != 0 )
							{
								$( 'table.cards' ).find( 'tr[data-id="' + cardID + '"] td.card-exp' ).text( cardEmon + ' / ' + cardEyear );
								$( 'table.cards' ).find( 'tr[data-id="' + cardID + '"] td.card-options a.edit-card' ).data( 'card', object );
							}else{

								var cID 		= response.data.id
								,	last4 		= response.data.last4
								,	exp_month 	= response.data.exp_month
								,	exp_year 	= response.data.exp_year;

								$( 'table.cards' ).append(
									$( '<tr/>', { 'data-id' : cID } ).append(
										$( '<td/>', { text : 'xxxx xxxx xxxx ' + last4 } )
									).append(
										$( '<td/>', { 'class' : 'card-exp', text : ( exp_month ).pad() + ' / ' + exp_year } )
									).append(
										$( '<td/>', { 'class' : 'card-options' } ).append(
											$( '<a/>', { 'href' : '#', text : 'Edit', 'class' : 'edit-card', 'data-card' : JSON.stringify(object) } )
										).append(
											$( '<a/>', { 'href' : '#', text : 'Delete', 'class' : 'delete-card', 'data-cardid' : cID } )
										)
									)
								);
							}

							close_popup();
						}else{
							alert( 'There was an error. Try again after page reload.' );
							location.reload();
						}

						loading_close();
					}
				);
			});

			/* DELETE CARD */

			// Delete popup
			$( 'body' ).on( 'click', 'a.delete-card', function( e )
			{
				e.preventDefault();

				var el = $( this );

				if( typeof $( this ).data( 'cardid' ) === 'undefined' )
				{
					alert( 'There was an error, try again later.' );
					return;
				}

				var cID = $( this ).data( 'cardid' );

				overlay.fadeIn( 150, function()
				{
					popUpWrap.find( 'div.stripe-delete-card' ).data( 'cardid', cID ).show();
					popUpWrap.fadeIn( 350 );
				});
			});

			// Yes
			$( 'body' ).find( 'a.delete-yes' ).on( 'click', function( e )
			{
				e.preventDefault();

				var cID = $( this ).parent().parent().data( 'cardid' );
				$( this ).parent().parent().removeData( 'cardid' );

				var data = {
					'action' : 'delete_card',
					'cardid' : cID
				};

				loading_open();

				$.post(
					ajaxurl,
					data,
					function( response )
					{
						if( response.success )
						{
							var el = $( 'body' ).find( 'table.cards a.delete-card[data-cardid="' + cID + '"]' );

							close_popup();

							el.closest( 'tr' ).fadeOut( 250, function()
							{
								$( this ).remove();
							});
						}else{
							alert( 'There was an error. Try again after page reload.' );
							location.reload();

						}

						loading_close();
					}
				);
			});

			// No
			$( 'body' ).find( 'a.delete-no, a.save-cancel' ).on( 'click', function( e )
			{
				e.preventDefault();

				$( this ).parent().parent().removeData( 'cardid' );
				close_popup();
			});

			/* END DELETE CARD */

		});

	</script>

	<?php
}

/*************SET POPUPS*************/
/************************************/

/*************MANAGE ACCOUNT*************/
/****************************************/

/* FUNCTIONS */



/* END FUNCTIONS */

// add_action( 'genesis_after_header', 'ca_manage_account' );
function ca_manage_account()
{
	$u = get_current_user_id();
	$sID = get_user_stripe_id( $u );
	// var_dump($sID);
	
	if( empty( $sID ) ) return; ?>

	<div class="my-stripe-account">

		<div class="section cards">
			<h3 class="section-title">Your Saved Credit Card(s)</h3>
			<div class="section-wrap">
				<?php list_cards(); ?>

				<a href="#" class="add-card">+ Add New Card</a>
			</div>
		</div>

		<div class="section payments">
			<h3 class="section-title">Your Payments</h3>
			<div class="section-wrap">
				<?php list_payments(); ?>
			</div>
		</div>

		<div class="cancel-account">
			<p>If you want to cancel your account <a href="https://morellifit.typeform.com/to/sdsip7" target="_blank">click here</a></p>
		</div>

	</div> <!-- end .my-stripe-account -->

	<?php
}




function list_cards()
{
	$cards = get_cards();
	$cards = $cards->data;

	if( empty( $cards ) ) return; ?>

	<table class="cards">
		<tr>
			<th>Card Number</th>
			<th>Card Date</th>
			<th>Manage card</th>
		</tr>

		<?php
			foreach ( $cards as $card)
			{
				$dataCard = array();
				$dataCard[ 'id' ]			= $card->id;
				$dataCard[ 'last4' ]		= $card->last4;
				$dataCard[ 'exp_month' ]	= sprintf( '%02d', $card->exp_month );
				$dataCard[ 'exp_year' ]		= $card->exp_year;

				?>

				<tr data-id=<?php echo $card->id; ?>>
					<td>xxxx xxxx xxxx <?php echo $card->last4; ?></td>
					
					<td class="card-exp">
						<?php printf( '%02d', $card->exp_month ); ?>
						/
						<?php printf( '%d', $card->exp_year ); ?>
					</td>

					<td class="card-options">
						<a href="#" class="edit-card" data-card='<?php echo json_encode( $dataCard ); ?>'>Edit</a>
						<a href="#" class="delete-card" data-cardid="<?php echo $card->id; ?>">Delete</a>
					</td>
				</tr>

				<?php
			}
		?>	
	</table>

	<?php
}



function list_payments()
{
	$charges = get_payments();
	$charges = $charges->data;

	if( empty( $charges ) ) return; ?>

	<table class="payments">
		<tr>
			<th>Card number</th>
			<th>Date</th>
			<th>Amount</th>
		</tr>

		<?php
			$i 		= 1;

			foreach( $charges as $charge ) :
				if( empty( $charge->invoice ) ) continue;

				$invoice = get_invoice( $charge->invoice );

				$plan_id = $invoice->lines->data[0]->plan->id;
		?>

		<tr>
			<td>
				xxxx xxxx xxxx <?php echo $charge->source->last4; ?>
			</td>

			<td>
				<?php echo date( 'm / Y', $charge->created ); ?>
			</td>

			<td>
				<?php
					$amount = $charge->amount / 100;
					echo '$ ' . number_format( (float) $amount, 2, '.', '' );
				?>
			</td>
		</tr>

		<?php
				if( $i == 5 ) break;
				$i++;
			endforeach;
		?>
	</table>

	<?php
}






/*************MANAGE ACCOUNT*************/
/****************************************/
?>