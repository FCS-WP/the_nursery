<?php
namespace Woolentor\Modules\EmailReports;

/**
 * Email Template class
 */
class Email_Template {

    /**
     * Get template style
     */
    public function get_style() {
        return '
            <style type="text/css">
                /* Reset styles */
                body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important; }
                body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
                
                /* Basic styles */
                .container { width: 96%; max-width: 600px; margin: 0 auto; }
                .header { background: #6E42D3; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .header h1 { margin: 0; font-size: 24px; }
                .content { background: #ffffff; padding: 20px; }
                
                /* Card styles */
                .card {
                    background: #f8f9fa;
                    border-radius: 8px;
                    padding: 20px;
                    margin-bottom: 20px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                
                /* Table styles */
                .data-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                .data-table th {
                    background: #f1f3f5;
                    padding: 12px;
                    text-align: left;
                    font-weight: 600;
                    border-bottom: 2px solid #dee2e6;
                }
                .data-table td {
                    padding: 12px;
                    border-bottom: 1px solid #dee2e6;
                }
                
                /* Stats box styles */
                .stats-box {
                    background: #ffffff;
                    border-radius: 6px;
                    padding: 15px;
                    margin-bottom: 15px;
                    border-left: 4px solid #6E42D3;
                }
                .stats-label {
                    font-size: 14px;
                    color: #666;
                    margin-bottom: 5px;
                }
                .stats-value {
                    font-size: 24px;
                    font-weight: bold;
                    color: #333;
                }
                
                /* Trend indicators */
                .trend-up { color: #28a745; }
                .trend-down { color: #dc3545; }
                
                /* Footer styles */
                .footer {
                    background: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    color: #666;
                    border-radius: 0 0 8px 8px;
                }
                
                /* Responsive design */
                @media screen and (max-width: 600px) {
                    .container { width: 100% !important; }
                    .content { padding: 10px !important; }
                    .stats-value { font-size: 20px !important; }
                }
            </style>
        ';
    }

    /**
     * Get template header
     */
    public function get_header($report_data) {
        $site_name = get_bloginfo('name');
        $report_period = sprintf('%s to %s', 
            date('M j, Y', strtotime($report_data['period_start'])),
            date('M j, Y', strtotime($report_data['period_end']))
        );

        return '
        <div class="header">
            <h1>Sales Report - ' . esc_html($site_name) . '</h1>
            <p>Report Period: ' . esc_html($report_period) . '</p>
        </div>';
    }

    /**
     * Get sales summary section
     */
    public function get_sales_summary($report_data) {
        $sales = isset($report_data['sales']) ? $report_data['sales'] : 0;
        $orders = isset($report_data['orders']) ? $report_data['orders'] : 0;
        $average_order = $orders > 0 ? ($sales / $orders) : 0;

        return '
        <div class="card">
            <h2>Sales Summary</h2>
            <div class="stats-container">
                <div class="stats-box">
                    <div class="stats-label">Total Sales</div>
                    <div class="stats-value">' . wc_price($sales) . '</div>
                </div>
                <div class="stats-box">
                    <div class="stats-label">Total Orders</div>
                    <div class="stats-value">' . number_format($orders) . '</div>
                </div>
                <div class="stats-box">
                    <div class="stats-label">Average Order Value</div>
                    <div class="stats-value">' . wc_price($average_order) . '</div>
                </div>
            </div>
        </div>';
    }

    /**
     * Get top products section
     */
    public function get_top_products($products) {
        if(empty($products)) {
            return '';
        }

        $html = '
        <div class="card">
            <h2>Top Selling Products</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Units Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>';

        foreach($products as $product) {
            $html .= '
                <tr>
                    <td>' . esc_html($product->post_title) . '</td>
                    <td>' . number_format($product->quantity) . '</td>
                    <td>' . wc_price($product->revenue) . '</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </div>';

        return $html;
    }

    /**
     * Get footer section
     */
    public function get_footer() {
        $site_url = get_bloginfo('url');
        $site_name = get_bloginfo('name');

        return '
        <div class="footer">
            <p>This report was automatically generated for ' . esc_html($site_name) . '</p>
            <p><a href="' . esc_url($site_url) . '">' . esc_html($site_name) . '</a></p>
        </div>';
    }
}