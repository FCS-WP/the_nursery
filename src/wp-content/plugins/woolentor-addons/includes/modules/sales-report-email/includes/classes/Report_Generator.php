<?php
namespace Woolentor\Modules\EmailReports;

/**
 * Report Generator class
 */
class Report_Generator {

    /**
     * Generate report data
     */
    public function generate() {
        $metrics = woolentor_get_option('report_metrics', 'woolentor_email_reports_settings', array());
        $data = array(
            'period_start' => $this->get_period_start(),
            'period_end'   => $this->get_period_end(),
            'schedule_type' => woolentor_get_option('schedule_type', 'woolentor_email_reports_settings', 'daily')
        );

        // Get sales data
        if(in_array('sales', $metrics)) {
            $data['sales'] = $this->get_sales_data();
            $data['previous_sales'] = $this->get_sales_data(true); // For comparison
        }

        // Get orders data
        if(in_array('orders', $metrics)) {
            $data['orders'] = $this->get_orders_data();
            $data['previous_orders'] = $this->get_orders_data(true); // For comparison
        }

        // Get top products
        if(in_array('top_products', $metrics)) {
            $data['top_products'] = $this->get_top_products();
        }

        return $data;
    }

    /**
     * Get period start date
     */
    private function get_period_start() {
        $schedule = woolentor_get_option('schedule_type', 'woolentor_email_reports_settings', 'daily');
        $current_time = current_time('timestamp');

        switch($schedule) {
            case 'custom':
                $minutes = (int) woolentor_get_option('custom_minutes', 'woolentor_email_reports_settings', 30);
                return date('Y-m-d H:i:s', strtotime("-{$minutes} minutes", $current_time));

            case 'hourly':
                return date('Y-m-d H:i:s', strtotime('-1 hour', $current_time));

            case 'daily':
                return date('Y-m-d H:i:s', strtotime('-1 day', $current_time));

            case 'weekly':
                return date('Y-m-d H:i:s', strtotime('-7 days', $current_time));

            case 'monthly':
                return date('Y-m-d H:i:s', strtotime('-30 days', $current_time));

            default:
                return date('Y-m-d H:i:s', strtotime('-1 day', $current_time));
        }
    }

    /**
     * Get period end date
     */
    private function get_period_end() {
        return current_time('Y-m-d H:i:s');
    }

    /**
     * Get previous period start date (for comparison)
     */
    private function get_previous_period_start() {
        $schedule = woolentor_get_option('schedule_type', 'woolentor_email_reports_settings', 'daily');
        $current_start = strtotime($this->get_period_start());

        switch($schedule) {
            case 'custom':
                $minutes = (int) woolentor_get_option('custom_minutes', 'woolentor_email_reports_settings', 30);
                return date('Y-m-d H:i:s', strtotime("-{$minutes} minutes", $current_start));

            case 'hourly':
                return date('Y-m-d H:i:s', strtotime('-1 hour', $current_start));

            case 'daily':
                return date('Y-m-d H:i:s', strtotime('-1 day', $current_start));

            case 'weekly':
                return date('Y-m-d H:i:s', strtotime('-7 days', $current_start));

            case 'monthly':
                return date('Y-m-d H:i:s', strtotime('-30 days', $current_start));

            default:
                return date('Y-m-d H:i:s', strtotime('-1 day', $current_start));
        }
    }

    /**
     * Get sales data
     */
    private function get_sales_data($previous_period = false) {
        global $wpdb;
        
        $start_date = $previous_period ? $this->get_previous_period_start() : $this->get_period_start();
        $end_date = $previous_period ? $this->get_period_start() : $this->get_period_end();

        $query = $wpdb->prepare(
            "SELECT SUM(meta_value) as total_sales 
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON p.ID = pm.post_id
            WHERE meta_key = '_order_total'
            AND p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND p.post_date >= %s
            AND p.post_date < %s",
            $start_date,
            $end_date
        );

        return (float) $wpdb->get_var($query);
    }

    /**
     * Get orders data
     */
    private function get_orders_data($previous_period = false) {
        global $wpdb;
        
        $start_date = $previous_period ? $this->get_previous_period_start() : $this->get_period_start();
        $end_date = $previous_period ? $this->get_period_start() : $this->get_period_end();

        $query = $wpdb->prepare(
            "SELECT COUNT(*) as total_orders 
            FROM {$wpdb->posts}
            WHERE post_type = 'shop_order'
            AND post_status IN ('wc-completed', 'wc-processing')
            AND post_date >= %s
            AND post_date < %s",
            $start_date,
            $end_date
        );

        return (int) $wpdb->get_var($query);
    }

    /**
     * Get top products
     */
    private function get_top_products() {
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT 
                p.ID,
                p.post_title,
                SUM(oim.meta_value) as quantity,
                SUM(oim_total.meta_value) as revenue
            FROM {$wpdb->prefix}woocommerce_order_items oi
            JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim 
                ON oi.order_item_id = oim.order_item_id
            JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim_total 
                ON oi.order_item_id = oim_total.order_item_id
            JOIN {$wpdb->posts} o 
                ON oi.order_id = o.ID
            JOIN {$wpdb->posts} p 
                ON oim.meta_value = p.ID
            WHERE oim.meta_key = '_product_id'
            AND oim_total.meta_key = '_line_total'
            AND o.post_type = 'shop_order'
            AND o.post_status IN ('wc-completed', 'wc-processing')
            AND o.post_date >= %s
            AND o.post_date < %s
            GROUP BY p.ID
            ORDER BY quantity DESC
            LIMIT 5",
            $this->get_period_start(),
            $this->get_period_end()
        );

        $results = $wpdb->get_results($query);

        // Calculate percentage changes
        foreach($results as $product) {
            // Get previous period data for this product
            $previous_data = $this->get_product_previous_data($product->ID);
            $product->quantity_change = $this->calculate_percentage_change(
                $previous_data->quantity ?? 0,
                $product->quantity
            );
            $product->revenue_change = $this->calculate_percentage_change(
                $previous_data->revenue ?? 0,
                $product->revenue
            );
        }

        return $results;
    }

    /**
     * Get product data for previous period
     */
    private function get_product_previous_data($product_id) {
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT 
                SUM(oim.meta_value) as quantity,
                SUM(oim_total.meta_value) as revenue
            FROM {$wpdb->prefix}woocommerce_order_items oi
            JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim 
                ON oi.order_item_id = oim.order_item_id
            JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim_total 
                ON oi.order_item_id = oim_total.order_item_id
            JOIN {$wpdb->posts} o 
                ON oi.order_id = o.ID
            WHERE oim.meta_key = '_product_id'
            AND oim.meta_value = %d
            AND oim_total.meta_key = '_line_total'
            AND o.post_type = 'shop_order'
            AND o.post_status IN ('wc-completed', 'wc-processing')
            AND o.post_date >= %s
            AND o.post_date < %s",
            $product_id,
            $this->get_previous_period_start(),
            $this->get_period_start()
        );

        return $wpdb->get_row($query);
    }

    /**
     * Calculate percentage change
     */
    private function calculate_percentage_change($old_value, $new_value) {
        if($old_value == 0) {
            return $new_value > 0 ? 100 : 0;
        }
        return (($new_value - $old_value) / $old_value) * 100;
    }
}