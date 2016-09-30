<?php

/**
 * Created by PhpStorm.
 * User: yousan
 * Date: 2016/09/22
 * Time: 20:35
 */
class FSC_Option {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	const OPTION_NAME = 'fsc_options';
	const DOMAIN = 'fsc';

	/**
	 * Start up
	 */
	public function __construct() {
		FB_Share_Count::load_text_domain();
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	public static function get_defaults() {
		return array(
			'app_id' => '',
			'app_secret' => '',
		);
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		// This page will be under "Settings"
		add_options_page(
			'Settings Admin',
			'FB Share Count',
			'manage_options',
			'fsc-setting-admin',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * getter
	 */
	public static function get_( $varname ) {
		$options = get_option( self::OPTION_NAME );
		if ( isset( $options[ $varname ] ) ) {
			return $options[ $varname ];
		} else {
			$defaults = self::get_defaults();
			return $defaults[ $varname ];
		}
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( self::OPTION_NAME );
		?>
		<div class="wrap">
			<?php //screen_icon();
			?>
			<h2><?php _e( 'FB Share Count', self::DOMAIN ) ?></h2>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'fsc_option_group' );
				do_settings_sections( 'fsc-setting-admin' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
			'fsc_option_group', // Option group
			self::OPTION_NAME, // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_fsc', // ID
			__( 'FB Share Count Custom Settings', self::DOMAIN ), // Title
			array( $this, 'option_description_callback' ), // Callback
			'fsc-setting-admin' // Page
		);

		add_settings_field(
			'app_id', // ID
			__( 'App ID', self::DOMAIN ), // Title
			array( $this, 'app_id_callback' ), // Callback
			'fsc-setting-admin', // Page
			'setting_fsc' // Section
		);

		add_settings_field(
			'secret', // ID
			__( 'App Secret', self::DOMAIN ), // Title
			array( $this, 'app_secret_callback' ), // Callback
			'fsc-setting-admin', // Page
			'setting_fsc' // Section
		);


//		register_setting(
//			'apft_option_group', // Option group
//			self::OPTION_NAME, // Option name
//			array($this, 'sanitize') // Sanitize
//		);
//
//		add_settings_section(
//			'setting_apft', // ID
//			__('APFT Custom Settings', 'apft'), // Title
//			null, // Callback
//			'apft-setting-admin' // Page
//		);
//
//		add_settings_field(
//			'is_aggressive', // ID
//			__("'Aggressive' flush_rewrite", 'apft'), // Title
//			array($this, 'is_aggressive_callback'), // Callback
//			'apft-setting-admin', // Page
//			'setting_apft' // Section
//		);
//
//		add_settings_field(
//			'base_dir',
//			__('Base Directory', 'apft'),
//			array($this, 'base_dir_callback'),
//			'apft-setting-admin',
//			'setting_apft'
//		);
//
//		add_settings_field(
//			'template_files',
//			__('Template Files', 'apft'),
//			array($this, 'template_files_callback'),
//			'apft-setting-admin',
//			'setting_apft'
//		);

	}


	/**
	 * 開発者用ページに行ってね、と促す
	 */
	public function option_description_callback() {
		?>
		<p>
			<?php _e( 'App ID and Secret can be got at developers page. see <a href="https://developers.facebook.com/apps">https://developers.facebook.com/apps</a> ', self::DOMAIN ); ?>
		</p>
		<p>
			<?php _e('Insert shortcode [fb-share-count].', self::DOMAIN); ?>
		</p>
		<p>
			<?php _e('At a template file, call the shortcode such as:', self::DOMAIN); ?>
		</p>
		<code>
			<?php echo htmlentities('<div class=“fb_icon”><?php do_shortcode(‘[fb-share-count]’); ?></div>'); ?>
		</code>
		<?php
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function app_id_callback() {
		if ( isset( $this->options['app_id'] ) ) {
			$app_id = $this->options['app_id'];
		} else {
			$app_id = '';
		}
		?>
		<p>
			<label for="fsc_app_id" style="width: 100%;">
				<input type="text" id="fsc_app_id" name="fsc_options[app_id]" value="<?php echo $app_id; ?>"
				       style="width: 70%;"/>
				<?php _e( 'Only digits.', self::DOMAIN ); ?>
			</label>
		</p>
		<?php
	}


	/**
	 * Get the settings option array and print one of its values
	 */
	public function app_secret_callback() {
		if ( isset( $this->options['app_secret'] ) ) {
			$app_secret = $this->options['app_secret'];
		} else {
			$app_secret = '';
		}
		?>
		<p>
			<label for="fsc_app_secret" style="width: 100%;">
				<input type="text" id="fsc_app_secret" name="fsc_options[app_secret]" value="<?php echo $app_secret; ?>"
				       style="width: 70%;"/>
				<?php _e( 'Numbers and alphabets.', self::DOMAIN ); ?>
			</label>
		</p>
		<?php
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 *
	 * @return array
	 */
	public function sanitize( $input ) {
		$new_input = array();

		foreach ( $input as $key => $value ) {
			$new_input[ $key ] = $value;
		}

		// boolean sanitize
//		if ( isset( $input['aggressive'] ) ) { // boolean
//			$new_input['aggressive'] = $input['aggressive'];
//		} else {
//			$new_input['aggressive'] = false;
//		}

//		if ( isset( $input['title'] ) ) {
//			$new_input['title'] = sanitize_text_field( $input['title'] );
//		}


		return $new_input;
	}
}
