<?php
	if (strstr ($_SERVER ["HTTP_REFERER"], "/login/"))
		header ("Location: /");
	$template->the_errors();
	global $user_ID;
if ($user_ID > 0):	?>
	<form id="your-profile" action="<?php $template->the_action_url( 'profile' ); ?>" method="post">
		<?php wp_nonce_field( 'update-user_' . $current_user->ID ); ?>
		<p>
			<input type="hidden" name="from" value="profile" />
			<input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />
		</p>


		<h1 class="profile-graph"><?php _e ("Варіанти імені", "theme-my-login"); ?></h1>

		<table class="form-table">
		<tr>
			<th><label for="user_login"><?php _e( 'Username', 'theme-my-login' ); ?></label></th>
			<td><input type="text" name="user_login" id="user_login" value="<?php echo esc_attr( $profileuser->user_login ); ?>" disabled="disabled" class="regular-text" /> <span class="description"><?php _e( 'Логін не може бути зміненим.', 'theme-my-login' ); ?></span></td>
		</tr>

		<tr>
			<th><label for="first_name"><?php _e( 'Ім\'я', 'theme-my-login' ); ?></label></th>
			<td><input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $profileuser->first_name ); ?>" class="regular-text" /></td>
		</tr>

		<tr>
			<th><label for="last_name"><?php _e( 'Прізвище', 'theme-my-login' ); ?></label></th>
			<td><input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $profileuser->last_name ); ?>" class="regular-text" /></td>
		</tr>

		<tr>





			<th><label for="display_name"><?php _e( 'Варіант імені для публічного відображення', 'theme-my-login' ); ?></label></th>
			<td>
				<select name="display_name" id="display_name">
				<?php
					$public_display = array();
					
    

					$public_display['display_nickname']  = $profileuser->nickname;
					$public_display['display_username']  = $profileuser->user_login;

					if ( ! empty( $profileuser->first_name ) )
						$public_display['display_firstname'] = $profileuser->first_name;

					if ( ! empty( $profileuser->last_name ) )
						$public_display['display_lastname'] = $profileuser->last_name;

					if ( ! empty( $profileuser->first_name ) && ! empty( $profileuser->last_name ) ) {
						$public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
						$public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
					}

					if ( ! in_array( $profileuser->display_name, $public_display ) )// Only add this if it isn't duplicated elsewhere
						$public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;

					$public_display = array_map( 'trim', $public_display );
					$public_display = array_unique( $public_display );

					foreach ( $public_display as $id => $item ) {
				?>
					<option <?php selected( $profileuser->display_name, $item ); ?>><?php echo $item; ?></option>
				<?php
					}
				?>
				</select>
			</td>
		</tr>
		</table>

		<h1 class="profile-graph"><?php _e ("Контактні дані", "theme-my-login"); ?></h1>

		<table class="form-table">
		<tr>
			<th><label for="email"><?php _e( 'E-mail', 'theme-my-login' ); ?> <span class="description"><?php _e( '(готова)', 'theme-my-login' ); ?></span></label></th>
			<td><input type="text" name="email" id="email" value="<?php echo esc_attr( $profileuser->user_email ); ?>" class="regular-text" /></td>
		</tr>

		<?php
			foreach ( wp_get_user_contact_methods() as $name => $desc ) {
		?>
		<tr>
			<th><label for="<?php echo $name; ?>"><?php echo apply_filters( 'user_'.$name.'_label', $desc ); ?></label></th>
			<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_attr( $profileuser->$name ); ?>" class="regular-text" /></td>
		</tr>
		<?php
			}
		?>
		</table>

		<h1 class="profile-graph"><?php _e ("Про Вас", "theme-my-login"); ?></h1>

		<table class="form-table">
		<tr>
			<th><label for="description"><?php _e( 'Біографічні дані', 'theme-my-login' ); ?></label></th>
			<td><textarea name="description" id="description" rows="10" cols="30"><?php echo esc_html( $profileuser->description ); ?></textarea><br />
			<span class="description"><?php _e( 'Тут ви можете задати ті або інші біографічні дані для загального доступу.', 'theme-my-login' ); ?></span></td>
		</tr>

		<?php
		$show_password_fields = apply_filters( 'show_password_fields', true, $profileuser );
		if ( $show_password_fields ) :
		?>
		<tr id="password">
			<th><label for="pass1"><?php _e( 'New Password', 'theme-my-login' ); ?></label></th>
			<td><input type="password" name="pass1" id="pass-1"  size="16" value="" autocomplete="off"/> <span class="description"><?php _e( 'Тут ви можете змінити пароль у випадку втрати.', 'theme-my-login' ); ?></span><br />
				<input type="password" name="pass2" id="pass-2"  size="16" value="" autocomplete="off"/> <span class="description"><?php _e( 'Повторити', 'theme-my-login' ); ?></span><br />
				<p class="description indicator-hint"><?php _e( 'Прикмітка: пароль повинен містити не меш за 7 літер. Щоб посилити його, Ви можете також використати великі та малі регістри, цифри та символи такі, як ! " ? $ % ^ &amp; ).', 'theme-my-login' ); ?></p>
			</td>
		</tr>
		<?php endif; ?>
		</table>

		<?php do_action( 'show_user_profile', $profileuser ); ?>

		<p class="submit">
			<input type="hidden" name="action" value="profile" />
			<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
			<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $current_user->ID ); ?>" />
			<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Обновити профіль', 'theme-my-login' ); ?>" name="submit" />
		</p>
	</form>
	<?php
	endif;
	?>
