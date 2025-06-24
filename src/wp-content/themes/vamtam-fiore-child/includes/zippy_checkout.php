<?php
add_filter('woocommerce_states', 'custom_singapore_regions');
function custom_singapore_regions($states)
{
    $states['SG'] = array(
        'SG-01' => 'Central Singapore',
        'SG-02' => 'North East',
        'SG-03' => 'North West',
        'SG-04' => 'South East',
        'SG-05' => 'South West',
    );
    return $states;
}

add_filter('woocommerce_default_address_fields', 'change_state_label_to_region');
function change_state_label_to_region($fields)
{
    if (isset($fields['state'])) {
        $fields['state']['label'] = 'Region';
        $fields['state']['placeholder'] = 'Select a Region';
    }
    return $fields;
}

add_filter( 'woocommerce_default_address_fields', 'change_postcode_label' );
function change_postcode_label( $fields ) {
    $fields['postcode']['label'] = 'Postal Code';
    $fields['postcode']['placeholder'] = 'Postal Code';
    return $fields;
}
