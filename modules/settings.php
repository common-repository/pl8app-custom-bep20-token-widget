<?php


class wtwp_vooSettingsClassV3
{
	/* V1.0.1 */
	var $settings_parameters;
	var $settings_prefix;
	var $message;

	function __construct($prefix)
	{
		$this->settings_prefix = $prefix;

		if (isset($_POST[$this->settings_prefix . 'save_settings_field'])) {
			if (wp_verify_nonce($_POST[$this->settings_prefix . 'save_settings_field'], $this->settings_prefix . 'save_settings_action')) {
				$options = array();
				foreach ($_POST as $key => $value) {
					$options[$key] = sanitize_text_field($value);
				}
				update_option($this->settings_prefix . '_options', $options);
			}
		}


	}

	function get_setting($setting_name)
	{
		$inner_option = get_option($this->settings_prefix . '_options');
		return $inner_option[$setting_name];
	}

	function create_menu($parameters)
	{
		$this->settings_parameters = $parameters;
		$this->message = '<div class="alert alert-success">' . esc_html($this->settings_parameters['save_message']) . '</div>';

		add_action('admin_menu', array($this, 'add_menu_item'));

	}

	function add_menu_item()
	{

		$default_array = [
			'type' => '',
			'parent_slug' => '',
			'form_title' => '',
			'is_form' => '',
			'page_title' => '',
			'menu_title' => '',
			'capability' => '',
			'menu_slug' => '',
			'icon' => ''
		];
		
		$this->settings_parameters = array_merge($default_array, $this->settings_parameters);

		$block_type = $this->settings_parameters['type'];
		$single_option = $this->settings_parameters;

		if ($block_type == 'menu') {
			add_menu_page(
				$single_option['page_title'],
				$single_option['menu_title'],
				$single_option['capability'],
				$this->settings_prefix . $single_option['menu_slug'],
				array($this, 'show_settings'),
				$single_option['icon']
			);
		}
		if ($block_type == 'submenu') {
			add_submenu_page(
				$single_option['parent_slug'],
				$single_option['page_title'],
				$single_option['menu_title'],
				$single_option['capability'],
				$this->settings_prefix . $single_option['menu_slug'],
				array($this, 'show_settings')
			);
		}
		if ($block_type == 'option') {

			add_options_page(
				$single_option['page_title'],
				$single_option['menu_title'],
				$single_option['capability'],
				$this->settings_prefix . $single_option['menu_slug'],
				array($this, 'show_settings')
			);
		}


	}

	function show_settings()
	{
		// hide output if its parent menu
		if (count($this->settings_parameters['parameters']) == 0) {
			return false;
		}

		?>
		<div class="wrap tw-bs4">
			<h2>
				<?php echo esc_html($this->settings_parameters['form_title']); ?>
			</h2>
			<hr />
			<?php
			echo $this->message;
			?>

			<?php if ($this->settings_parameters['is_form']): ?>
				<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
				<?php endif; ?>

				<?php
				wp_nonce_field($this->settings_prefix . 'save_settings_action', $this->settings_prefix . 'save_settings_field');
				$config = get_option($this->settings_prefix . '_options');

				?>
				<fieldset>

					<?php

					foreach ($this->settings_parameters['parameters'] as $key => $value) {

						$interface_element_value = '';
						if (isset($value['name'])) {
							if (isset($config[$value['name']])) {
								$interface_element_value = $config[$value['name']];
							}
						}


						$interface_element = new wtwp_formElementsClass($value['type'], $value, $interface_element_value);
						echo $interface_element->get_code();
					}

					?>
				</fieldset>

				<?php if ($this->settings_parameters['is_form']): ?>
				</form>
			<?php endif; ?>

		</div>
		<?php
	}
}




add_Action('init', function () {

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (defined('DOING_AJAX') && DOING_AJAX) {
		return;
	}

	$locale = 'wtw';
	$config_big =

		array(
			'type' => 'submenu',
			'parent_slug' => 'edit.php?post_type=contract_address',
			'form_title' => __('Settings', $locale),
			'is_form' => true,
			'page_title' => __('Settings', $locale),
			'save_message' => __('Settings Saved', $locale),
			'menu_title' => __('Settings', $locale),
			'capability' => 'edit_published_posts',
			'menu_slug' => 'main_settings',
			'parameters' => array(
				array(
					'type' => 'checkbox',
					'title' => __('Show Widget Credentials', $locale),
					'name' => 'show_credentials',
					'text' => '',
					'value' => 'on',
					'id' => '',
					'style' => '',
					'class' => ''
				),

				array(
					'type' => 'save',
					'title' => __('Save', $locale),
				),


			)
		)
	;
	global $settings;

	$settings = new wtwp_vooSettingsClassV3($locale);
	$settings->create_menu($config_big);

});



?>