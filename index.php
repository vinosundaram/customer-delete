<?php

/**
 * Plugin Name: Customer Delete
 * Description: FH Testing
 * Plugin URI:
 * Author: Elementor.com
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


function delete_customer_menu()
{
    add_menu_page(
        __('Delete Customer', 'FH'),
        __('Delete Customer', 'FH'),
        'manage_options',
        'custompage',
        'delete_customer_function',
        '',
        6
    );
}
add_action('admin_menu', 'delete_customer_menu');


function delete_customer_function()
{

    $args = array(
        'post_status' => 'any',
        'post_type'         => 'shop_order',
        'posts_per_page'    => -1,
        'fields' => 'ids'
    );

    $orders = get_posts($args);

    if (empty($orders)) {
        return 'No Orders';
    }

    foreach ($orders as $each_id) {
        $order = wc_get_order($each_id);
        $order_user_ids[] = $order->get_customer_id();
    }
    echo '<br>';
    echo ('<h2>Order Count - ' . count($order_user_ids) . '</h2>');

    $order_unique_users = array_unique($order_user_ids);

    update_option('order_unique_users_meta',  $order_unique_users);
    echo '<br>';
    echo ('<h2>Order Unique User Count - ' . count($order_unique_users) . '</h2>');

    $users = get_users(array('role__in' => array('customer'), 'fields' => 'ids'));


    if (empty($users)) {
        return 'No Users';
    }
    echo '<br>';
    echo ('<h2>Site User Count - ' . count($users) . '</h2>');

    $delete_users = array_diff($users,  get_option('order_unique_users_meta'));

    if (empty($delete_users)) {
        return 'No Non Order Users';
    }
    echo '<br>';
    echo ('<h2>Delete User Count -' . count($delete_users) . ' </h2>');
    echo '<br>';
    echo ('<h2>Delete Mail Ids</h2>');

    foreach ($delete_users as $each_users_id) {

        $numorders = wc_get_customer_order_count($each_users_id);

        $user = get_userdata($each_users_id);

        if (!empty($user)) {
            var_dump('yes');
            // wp_delete_user($each_users_id);
        } else {
            var_dump('no');
        }
    }
}
