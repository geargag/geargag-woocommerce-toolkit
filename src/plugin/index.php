<?php
/**
 * Plugin Name: vnh_name
 * Description: vnh_description
 * Version: vnh_version
 * Tags: vnh_tags
 * Author: vnh_author
 * Author URI: vnh_author_uri
 * License: vnh_license
 * License URI: vnh_license_uri
 * Document URI: vnh_document_uri
 * Text Domain: vnh_textdomain
 * Tested up to: WordPress vnh_tested_up_to
 * WC requires at least: vnh_wc_requires
 * WC tested up to: vnh_wc_tested_up_to
 */

namespace GearGag_WooCommerce_Toolkit;

defined('WPINC') || die();

use GearGag_WooCommerce_Toolkit\admin\Admin;
use GearGag_WooCommerce_Toolkit\tools\KSES;

const PLUGIN_FILE = __FILE__;
const PLUGIN_DIR = __DIR__;

final class Plugin {
	public $admin_notices;
	public $batch_delete;
	public $change_paypal_item_name;

	public function __construct() {
		$this->load();
		$this->init();
		$this->core();
		$this->boot();
	}

	public function load() {
		require_once PLUGIN_DIR . '/vendor/autoload.php';
		require_once PLUGIN_DIR . '/constants.php';
		require_once PLUGIN_DIR . '/helpers.php';
	}

	public function init() {
		new KSES();

		if (is_admin()) {
			$this->admin_notices = new Admin();
			$this->admin_notices->init();
			$this->admin_notices->boot();
		}
	}

	public function core() {
		if (!is_woocommerce_active()) {
			return;
		}

		$this->change_paypal_item_name = new Change_Paypal_Item_Name();
		$this->change_paypal_item_name->boot();

		if (!class_exists('GearGag_Toolkit\Batch_Delete_Products')) {
			$this->batch_delete = new Batch_Delete_Products();
			$this->batch_delete->boot();
		}
	}

	public function boot() {
		add_action('plugin_loaded', [$this, 'load_plugin_textdomain']);
	}

	public function load_plugin_textdomain() {
		load_plugin_textdomain('vnh_textdomain');
	}
}

new Plugin();
