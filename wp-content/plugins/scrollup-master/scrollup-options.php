<div id="wpbody">
	<div class="wrap">
		<h2><?php _e('Scrollup Settings') ?></h2>
		<form action="options.php" method="post">
			<?php
				$options = Scrollup_Master::get_options();
				settings_fields( 'sis_scrooltotop_settings' );
			?>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label><?php _e('Button Position from Bottom') ?></label>
						</th>
						<td>
							<input type="text" name="sis_scrooltotop_settings[btn_bottom]" value="<?php esc_attr_e($options['btn_bottom']); ?>">
							<p class="description"><?php _e('Set button position from bottom. Defaults position is &lsquo; 20px &lsquo;') ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Button Position from Right') ?></label>
						</th>
						<td>
							<input type="text" name="sis_scrooltotop_settings[btn_right]" id="" value="<?php esc_attr_e($options['btn_right']); ?>">
							<p class="description"><?php _e('Set button position from right. Defaults position is &lsquo; 20px &lsquo;') ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Scrollup Tooltip Title') ?></label>
						</th>
						<td>
							<input type="text" name="sis_scrooltotop_settings[tooltiptitle]" id="" value="<?php esc_attr_e($options['tooltiptitle']); ?>">
							<p class="description"><?php _e('Set a custom title if required. This title will show when you put your mouse pointer on scrollup button. Defaults title is &lsquo; Scroll to top &lsquo;') ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Easing Effect Type') ?></label>
						</th>
						<td>
							
							<input type="text" name="sis_scrooltotop_settings[tooltipeasingtype]" id="" value="<?php esc_attr_e($options['tooltipeasingtype']); ?>">
							<p class="description"><?php _e('Select Scroll to top easing effect type. Default easing effect is linear. <a target="_blank" href="http://easings.net/">Easing Effect type live view</a>') ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Scrollup Button Animations') ?></label>
						</th>
						<td>
							<select name="sis_scrooltotop_settings[animation]" id="">
								<option value="fade" <?php selected( $options['animation'], 'fade' ); ?>>fade</option>
								<option value="slide" <?php selected( $options['animation'], 'slide' ); ?>>slide</option>
								<option value="none" <?php selected( $options['animation'], 'none' ); ?>>none</option>
							</select>
							<p class="description"><?php _e('Select scrollup button animation type. Default animation type is &lsquo; slide &lsquo; ') ?></a></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Scroll Speed') ?></label>
						</th>
						<td>
							<input type="text" name="sis_scrooltotop_settings[scrollspeed]" id="" value="<?php esc_attr_e($options['scrollspeed']); ?>">

							<p class="description"><?php _e('Speed back to top in millisecond. Default speed is &lsquo; 300 &lsquo; millisecond ') ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Scroll Distance') ?></label>
						</th>
						<td>
							<input type="text" name="sis_scrooltotop_settings[scrolldistance]" id="" value="<?php esc_attr_e($options['scrolldistance']); ?>">

							<p class="description"><?php _e('Distance from top/bottom before showing element pixels. Default distance is &lsquo; 300 &lsquo; pixels ') ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Scrollup Button Type') ?></label>
						</th>
						<td>
							<select name="sis_scrooltotop_settings[btn_type]" id="">
								<option value="image" <?php selected( $options['btn_type'], 'image' ); ?>>image</option>
								<option value="tab" <?php selected( $options['btn_type'], 'tab' ); ?>>tab</option>
								<option value="pill" <?php selected( $options['btn_type'], 'pill' ); ?>>pill</option>
								<option value="link" <?php selected( $options['btn_type'], 'link' ); ?>>link</option>
							</select>

							<p class="description"><?php _e('Select button type.') ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Tab Text Color') ?></label>
						</th>
						<td>
							<input type="text" class="colorpicker" id="tab_txt_color" name="sis_scrooltotop_settings[tab_txt_color]" id="" value="<?php esc_attr_e($options['tab_txt_color']); ?>" data-default-color="#828282">

							<p class="description"><?php _e('Select tab text color. This option will only work when your select scrollup button type as tab.') ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Tab Background Color') ?></label>
						</th>
						<td>
							<input type="text" class="colorpicker" id="tab_bg_color" name="sis_scrooltotop_settings[tab_bg_color]" id="" value="<?php esc_attr_e($options['tab_bg_color']); ?>" data-default-color="#E6E6E6">

							<p class="description"><?php _e('Select tab background color. This option will only work when your select scrollup button type as tab.') ?></p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Pill Text Color') ?></label>
						</th>
						<td>
							<input type="text" class="colorpicker" id="pill_txt_color" name="sis_scrooltotop_settings[pill_txt_color]" id="" value="<?php esc_attr_e($options['pill_txt_color']); ?>" data-default-color="#ffffff">

							<p class="description"><?php _e('Select pill text color. This option will only work when your select scrollup button type as pill.') ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Pill Background Color') ?></label>
						</th>
						<td>
							<input type="text" class="colorpicker" id="pill_bg_color" name="sis_scrooltotop_settings[pill_bg_color]" id="" value="<?php esc_attr_e($options['pill_bg_color']); ?>" data-default-color="#555555">

							<p class="description"><?php _e('Select pill background color. This option will only work when your select scrollup button type as pill.') ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php _e('Pill Background Color on Hover') ?></label>
						</th>
						<td>
							<input type="text" class="colorpicker" id="pill_bghover_color" name="sis_scrooltotop_settings[pill_bghover_color]" id="" value="<?php esc_attr_e($options['pill_bghover_color']); ?>" data-default-color="#000000">

							<p class="description"><?php _e('Select pill background color on hover. This option will only work when your select scrollup button type as pill.') ?></p>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit"><input type="submit" value="<?php _e('Save Changes') ?>" class="button button-primary" id="submit" name="submit"></p>
		</form>
	</div>
</div>