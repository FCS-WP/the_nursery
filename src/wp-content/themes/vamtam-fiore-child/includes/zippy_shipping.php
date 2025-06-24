<?php
add_action('admin_menu', 'custom_delivery_settings_menu');
function custom_delivery_settings_menu()
{
    add_submenu_page(
        'woocommerce',
        'Delivery Settings',
        'Delivery Settings',
        'manage_options',
        'custom-delivery-settings',
        'custom_delivery_settings_page'
    );
}

function custom_delivery_settings_page()
{
?>
    <div class="wrap">
        <h1>Delivery Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('delivery_settings_group');
            do_settings_sections('custom-delivery-settings');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

add_action('admin_init', 'custom_delivery_settings_init');
function custom_delivery_settings_init()
{
    register_setting('delivery_settings_group', 'delivery_cutoff_time');
    register_setting('delivery_settings_group', 'delivery_disabled_days');
    register_setting('delivery_settings_group', 'delivery_time_slot');

    add_settings_section('section_main', 'Main Settings', null, 'custom-delivery-settings');

    add_settings_field(
        'cutoff_time',
        'Next Day Cutoff Time (24h format)',
        'cutoff_time_callback',
        'custom-delivery-settings',
        'section_main'
    );

    add_settings_field(
        'disabled_days',
        'Disable Delivery On (choose)',
        'disabled_days_callback',
        'custom-delivery-settings',
        'section_main'
    );

    add_settings_field(
        'time_slot',
        'Default Time Slot',
        'time_slot_callback',
        'custom-delivery-settings',
        'section_main'
    );
}

function cutoff_time_callback()
{
    $value = esc_attr(get_option('delivery_cutoff_time', '14:00'));
    echo "<input type='time' name='delivery_cutoff_time' value='$value' />";
}

function disabled_days_callback()
{
    $days = [
        '0' => 'Sunday',
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday'
    ];
    $selected = (array)get_option('delivery_disabled_days', ['2']);

    foreach ($days as $key => $label) {
        $checked = in_array($key, $selected) ? 'checked' : '';
        echo "<label><input type='checkbox' name='delivery_disabled_days[]' value='$key' $checked> $label</label><br/>";
    }
}

function time_slot_callback()
{
    $start = esc_attr(get_option('delivery_time_start', '09:00'));
    $end   = esc_attr(get_option('delivery_time_end', '22:00'));
    echo "<div class='delivery_custom_time_slot'>";
    echo "<label>Start Time: </label>
          <input type='time' name='delivery_time_start' value='$start' />
          <br/><br/>
          <label>End Time: </label>
          <input type='time' name='delivery_time_end' value='$end' />";
    echo "</div>";
}
function custom_admin_css_file($hook)
{
    if ($hook !== 'woocommerce_page_custom-delivery-settings') return;
    echo '<style>
        .delivery_custom_time_slot {
            display: flex;
            align-items: center;
            gap: 20px;
        }
    </style>';
}
add_action('admin_enqueue_scripts', 'custom_admin_css_file');
//

add_action('woocommerce_after_shipping_rate', 'custom_checkout_delivery_fields_per_method', 10, 2);

function custom_checkout_delivery_fields_per_method($method, $index)
{
    $method_id = $method->id;

    $disabled_days = get_option('delivery_disabled_days', ['2']);
    $start_time    = get_option('delivery_time_start', '09:00');
    $end_time      = get_option('delivery_time_end', '22:00');
    $slot_label    = date('g:i A', strtotime($start_time)) . ' - ' . date('g:i A', strtotime($end_time));

    $current_time = current_time('H:i');
    $cutoff_time  = get_option('delivery_cutoff_time', '14:00');
    $today        = current_time('w');


    $show_date = false;
    $show_time = false;

    if (strpos($method_id, 'flat_rate:5') !== false) {
        if ($disabled_days == $today || $current_time >= $cutoff_time) {
            return;
        }
        $show_date = true;
    } elseif (strpos($method_id, 'flat_rate:6') !== false) {
        $show_date = true;
        $show_time = true;
    }


    if (!$show_date && !$show_time) {
        return;
    }

?>
    <div class="custom_delivery_wrapper"
        data-method="<?php echo esc_attr($method_id); ?>"
        data-disabled-days="<?php echo esc_attr(json_encode(array_map('intval', $disabled_days))); ?>"
        style="display: none; margin-top: 15px; border: 1px dashed #aaa; padding: 10px;">

        <strong>Delivery Options for <?php echo esc_html($method->get_label()); ?>:</strong><br><br>

        <?php if ($show_date): ?>
            <p class="form-row form-row-wide">
                <label for="delivery_date_<?php echo esc_attr($index); ?>">Delivery Date <span class="required">*</span></label>
                <input type="date" name="delivery_date_<?php echo esc_attr($index); ?>" class="input-text delivery_date">
            </p>
        <?php endif; ?>

        <?php if ($show_time): ?>
            <p class="form-row form-row-wide">
                <label for="delivery_time_<?php echo esc_attr($index); ?>">Delivery Time Slot <span class="required">*</span></label>
                <select name="delivery_time_<?php echo esc_attr($index); ?>" class="delivery_time">
                    <option value="">Select a time</option>
                    <option value="<?php echo esc_attr($slot_label); ?>"><?php echo esc_html($slot_label); ?></option>
                </select>
            </p>
        <?php endif; ?>
    </div>

    <?php if ($index === 0):
    ?>
        <script>
            jQuery(function($) {
                function updateDeliveryForms() {
                    var selected = $('input[name="shipping_method[0]"]:checked').val();
                    $(".custom_delivery_wrapper").hide();

                    var $wrapper = $('.custom_delivery_wrapper[data-method="' + selected + '"]');
                    $wrapper.show();

                    var disabledDays = [];
                    try {
                        disabledDays = JSON.parse($wrapper.attr("data-disabled-days") || "[]");
                    } catch (e) {
                        console.warn("Could not parse disabledDays:", e);
                    }

                    $wrapper.find(".delivery_date").each(function() {
                        if (this._flatpickr) {
                            this._flatpickr.destroy();
                        }

                        flatpickr(this, {
                            minDate: "today",
                            dateFormat: "Y-m-d",
                            disable: [
                                function(date) {
                                    return disabledDays.includes(date.getDay());
                                },
                            ],
                        });
                    });
                }

                $(document).on("change", 'input[name="shipping_method[0]"]', updateDeliveryForms);
                $(document).ready(updateDeliveryForms);

                $(document.body).on("updated_shipping_method", updateDeliveryForms);
            });
        </script>

<?php endif;
}
add_filter('woocommerce_package_rates', 'custom_filter_shipping_methods', 10, 2);
function convert_label_shipping_methods($label)
{
    $slug = str_replace(' ', '-', $label);
    return strtolower($slug);
}
function custom_filter_shipping_methods($rates, $package)
{
    $cutoff_time   = get_option('delivery_cutoff_time', '14:00');
    $current_time = current_time('H:i');
    $cutoff_passed = ($current_time >= $cutoff_time);

    foreach ($rates as $rate_id => $rate) {
        $rate_slug = convert_label_shipping_methods($rate->get_label());
        if ($rate_slug === 'next-day-delivery') {
            $cutoff_passed  ? null : ($allowed_rates[$rate_id] = $rate);
            continue;
        }

        $allowed_rates[$rate_id] = $rate;
    }

    return $allowed_rates;
}
