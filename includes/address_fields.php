<?php



// Add fields to billing address
function avawc_add_billing_address_fields()
{
    add_action('woocommerce_billing_fields', function($fields, $country_code) {
        $fields['billing_sex'] = array(
            'id'    => 'billing_sex',
            'type' => 'text', // add field type
            'label' => __('Sex', 'woocommerce'), // Add custom field label
            'placeholder' => _x('Your sex ....', 'placeholder', 'woocommerce'), // Add custom field placeholder
            'required' => true, // if field is required or not
            'description' => 'Enter `Mr` or `Ms`',
            'maxlength' => 6,
            'clear' => false, // add clear or not
            'class' => array('my-class'),    // add class name
            'label_class'       => array('label-class'),
            'input_class'       => array('input-class'),
            'custom_attributes' => array('data-f' => 'data-f-value'),
            'priority'          => 1,
        );
        return $fields;
    }, PHP_INT_MAX, 2);

    add_action('woocommerce_customer_meta_fields', function($fields) {
        $fields['billing']['fields']['billing_sex'] = array(
            'label' => 'Sex',
            'description' => 'Enter `Mr` or `Ms`'
        );
        return $fields;
    }, PHP_INT_MAX);
}
avawc_add_billing_address_fields();

// Add fields to shipping address
function avawc_add_shipping_address_fields()
{
    add_action('woocommerce_shipping_fields', function($fields, $country_code) {
        $fields['shipping_sex'] = array(
            'id'    => 'shipping_sex',
            'type' => 'text', // add field type
            'label' => __('Sex', 'woocommerce'), // Add custom field label
            'placeholder' => _x('Your sex ....', 'placeholder', 'woocommerce'), // Add custom field placeholder
            'required' => true, // if field is required or not
            'description' => 'Enter `Mr` or `Ms`',
            'maxlength' => 6,
            'clear' => false, // add clear or not
            'class' => array('my-class'),    // add class name
            'label_class'       => array('label-class'),
            'input_class'       => array('input-class'),
            'custom_attributes' => array('data-f' => 'data-f-value'),
            'priority'          => 1,
        );
        return $fields;
    }, PHP_INT_MAX, 2);

    add_action('woocommerce_customer_meta_fields', function($fields) {
        $fields['shipping']['fields']['shipping_sex'] = array(
            'label' => 'Sex',
            'description' => 'Enter `Mr` or `Ms`'
        );
        return $fields;
    }, PHP_INT_MAX);
}
avawc_add_shipping_address_fields();


add_action('woocommerce_formatted_address_replacements', function($replacements, $values) {
    if ( !empty($values['sex']) ) $replacements['{sex}'] = $values['sex'];
    return $replacements;
}, PHP_INT_MAX, 2);

add_action('woocommerce_localisation_address_formats', function($address_formats) {
    $address_formats['default'] .= "\n{sex}";
    return $address_formats;
}, PHP_INT_MAX, 1);


// Add formatted address
function avawc_formated_address($key, $value) {

}
add_action('woocommerce_my_account_my_address_formatted_address', function($address, $customer_id, $address_type) {
    $address['sex'] = get_user_meta($customer_id, $address_type . '_sex', true);
    return $address;
}, PHP_INT_MAX, 3);

add_action('woocommerce_order_formatted_billing_address', function($address, $WC_Order) {
    $customer_id = $WC_Order->get_customer_id();
    $address['sex'] = get_user_meta($customer_id, 'billing_sex', true);
    return $address;
}, PHP_INT_MAX, 2);

add_action('woocommerce_order_formatted_shipping_address', function($address, $WC_Order) {
    $customer_id = $WC_Order->get_customer_id();
    $address['sex'] = get_user_meta($customer_id, 'shipping_sex', true);
    return $address;
}, PHP_INT_MAX, 2);


//woocommerce_order_formatted_billing_address
//woocommerce_order_formatted_shipping_address


