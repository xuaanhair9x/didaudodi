
<div class="px-custom-fields-caf">

	<hr class="px-silver"/>

	<?php

	$d_count = 0;

	if ( isset( $data ) && is_array( $data ) ) :

		foreach ( $data as $fkey => $group_fields ) :

			switch ( $fkey ) {

				case 'single_value':
					?>
					<div class="single-value-fields">
						<?php
						if ( is_array( $group_fields ) && count( $group_fields ) > 0 ) {

							foreach ( $group_fields as $field ) {

								if ( ! isset( $field['field_type'] ) || '' === $field['value'] || ! isset( $field['post-display'] ) || 1 != $field['post-display'] ) {
									continue;
								}

								switch ( $field['field_type'] ) {

									case 'px_text':
										?>
										<div class="px-field px_text">

											<strong class="px-field-name"><?php echo esc_html( $field['name'] ) ?></strong>
											<span class="px-field-value"><?php echo esc_html( $field['value'] )?></span>
											<hr/>
										</div>
										<?php
										break;

									case 'px_date':
									?>

										<div class="px-field px_date">

											<strong class="px-field-name"><?php echo esc_html( $field['name'] ) ?></strong>
											<span class="px-field-value"><?php echo esc_html( $field['value'] ) ?></span>
											<hr/>
										</div>

										<?php
										break;

									case 'px_select_box':
									?>
										<div class="px-field px_select_box">

											<strong class="px-field-name"><?php echo esc_html( $field['name'] ) ?></strong>
											<span class="px-field-value"><?php echo esc_html( $field['value'] ) ?></span>
											<hr/>
										</div>
									<?php
										break;

									case 'px_radio_box':

									?>
									<div class="px-field px_radio_box">

										<strong class="px-field-name"><?php echo esc_html( $field['name'] ) ?></strong>
										<span class="px-field-value"><?php echo esc_html( $field['value'] ) ?></span>
										<hr/>
									</div>
									<?php

									break;
								}
							}
						}
					?>
					</div>
					<?php
					break;

				case 'multiple_values':

					?>
					<div class="multiple-value-fields">
						<?php
						$count = 0;
						if ( is_array( $group_fields ) ) :
							foreach ( $group_fields as $field ) {


								if ( ! isset( $field['field_type'] ) || ! isset( $field['value'] ) || '' == $field['value']  || ! isset( $field['post-display'] ) || 1 != $field['post-display'] ) {
									continue;
								}

								?>
								<div class="px-mf-row">
									<?php
									$count++;
									switch ( $field['field_type'] ) {

										case 'px_check_box':
										?>
											<div class="px-field px_check_box">
												<strong class="px-field-name"><?php echo esc_html( $field['name'] ) ?></strong>
												
												<?php if ( isset( $field['value'] ) && is_array( $field['value'] ) ) : ?>
													<ul class="px-field-values">
													<?php    foreach ( $field['value'] as $value ) : ?>
																<li><span><?php echo esc_html( $value ); ?></span></li>
													<?php    endforeach; ?>    
													</ul>
												<?php endif; ?>

											</div>
											<?php
											break;

										case 'px_icon_check_box':

										?>
											<div class="px-field px_icon_check_box">
												<strong class="px-field-name"><?php echo esc_html( $field['name'] ) ?></strong>
												<?php if ( isset( $field['ivalue'] ) && is_array( $field['ivalue'] ) ) : ?>
													<ul class="px-field-values">
												<?php    foreach ( $field['ivalue'] as $value ) : ?>
															<li><span class="icon"><img width="30" src="<?php echo esc_url( $value['icon'] ) ?>"></span><?php echo  esc_html( $value['opt'] ); ?></li>
												<?php    endforeach; ?>    
													</ul>
												<?php endif; ?>
											</div>
											<?php
											break;
									}
								?>
							</div>
						<?php
							};
						endif;
					?>

					</div>
					<?php
					break;
			}

			$d_count++;
		endforeach;
	endif;
?>

</div>
