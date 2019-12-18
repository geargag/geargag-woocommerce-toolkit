<?php

namespace GearGag_WooCommerce_Toolkit;

use GearGag_WooCommerce_Toolkit\tools\contracts\Bootable;
use WC_Order_Item;

class Change_Paypal_Item_Name implements Bootable {
	public function boot() {
		add_filter('woocommerce_paypal_get_order_item_name', [$this, 'change_paypal_item_name'], 10, 3);
	}

	/**
	 * Change paypal item name to order ID.
	 * @param $item_name
	 * @param $order
	 * @param WC_Order_Item $item
	 *
	 * @return string
	 */
	public function change_paypal_item_name($item_name, $order, $item) {
		$new_item_name = sprintf('#%s', $item->get_order_id());
		$item_meta = wp_strip_all_tags(
			wc_display_item_meta($item, [
				'before' => '',
				'separator' => ', ',
				'after' => '',
				'echo' => false,
				'autop' => false,
			])
		);

		if ($item_meta) {
			$new_item_name .= ' (' . $item_meta . ')';
		}

		return $new_item_name;
	}
}
