<?php
namespace Woolentor\Modules\CurrencySwitcher\Frontend;
use WooLentor\Traits\Singleton;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Shortcode{
    use Singleton;

    public function __construct(){
        add_shortcode( 'woolentor_currency_switcher', [ $this, 'currency_switcher' ] );
    }

    /**
     * [currency_switcher] Currency Switcher Shortcode callable function
     * @param  [type] $atts 
     * @param  string $content
     * @return [HTML] 
     */
    public function currency_switcher( $atts, $content = '' ){
       
        // Fetch option data
        $currency_list = woolentor_currency_list();
        $current_currency_code = woolentor_current_currency_code();
        $current_currency = woolentor_current_currency( $current_currency_code );

        if( empty( $current_currency ) ){
            return;
        }
       
        // Shortcode atts
        $default_atts = array(
            'style' => 'dropdown',
            'flags' => 'yes',
            'flag_style' => 'circle' // square || circle
        );

        $atts = shortcode_atts( $default_atts, $atts, $content );

        // Missing flag Style
        if( !in_array( $atts['flag_style'],['square','circle'] ) ){
            $atts['flag_style'] = 'circle';
        }

        if( !woolentor_is_pro() ){
            $atts['flags'] = 'no';
        }


        $wc_currencie_list = get_woocommerce_currencies();
        $current_currency_symbol = woolentor_currency_symbol( $current_currency );

        $custom_flags = array_column( $currency_list, 'custom_flag', 'currency' );

        $current_currency_flag = ( $atts['flags'] == 'yes' ) 
            ? ( !empty($custom_flags[$current_currency['currency']]) 
                ? '<img src="'.esc_url($custom_flags[$current_currency['currency']]).'" alt="'.$wc_currencie_list[$current_currency['currency']].'"/>' 
                : '<img src="'.$this->get_flag_url( $atts['flag_style'], $current_currency['currency'] ).'" alt="'.$wc_currencie_list[$current_currency['currency']].'"/>'
            ) 
            : '';

        ob_start();
        ?>
            <div class="woolentor-currency-switcher">

                <?php if( $atts['style'] !== 'dropdown' ): ?>
                    <div class="woolentor-currency-dropdown list-style">
                        <ul>
                            <?php
                                foreach ( $currency_list as $currency ) {
                                    
                                    $currency_symbol = woolentor_currency_symbol( $currency );
                                    $active_currency = ( $current_currency_code === $currency['currency'] ) ? "class='active-currency'" : '';

                                    $flag = ( $atts['flags'] === 'yes' ) 
                                        ? (!empty($currency['custom_flag']) 
                                            ? '<img src="' . esc_url($currency['custom_flag']) . '" alt="' . $wc_currencie_list[$currency['currency']] . '"/>' 
                                            : '<img src="' . $this->get_flag_url( $atts['flag_style'], $currency['currency'] ) . '" alt="' . $wc_currencie_list[$currency['currency']] . '"/>' 
                                        ) 
                                        : '';

                                    echo sprintf('<li %4$s data-value="%1$s">%5$s %2$s (%3$s)</li>', $currency['currency'], $wc_currencie_list[$currency['currency']], $currency_symbol, $active_currency, $flag ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                }
                            ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="woolentor-selected-currency-wrap">
                        <span class="woolentor-selected-currency">
                            <?php echo sprintf('%3$s %1$s (%2$s)', $wc_currencie_list[$current_currency_code], $current_currency_symbol, $current_currency_flag); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </span>
                        <span class="woolentor-currency-dropdown-arrow"></span>
                    </div>
                    <div class="woolentor-currency-dropdown" style="display:none;">
                        <ul>
                            <?php
                                foreach ( $currency_list as $currency ) {
                                    $hide_currency = ( $current_currency_code === $currency['currency'] ) ? "class='hide-currency'" : '';
                                    $currency_symbol = woolentor_currency_symbol( $currency );

                                    $flag = ( $atts['flags'] === 'yes' ) 
                                        ? (!empty($currency['custom_flag']) 
                                            ? '<img src="' . esc_url($currency['custom_flag']) . '" alt="' . $wc_currencie_list[$currency['currency']] . '"/>' 
                                            : '<img src="' . $this->get_flag_url( $atts['flag_style'], $currency['currency'] ) . '" alt="' . $wc_currencie_list[$currency['currency']] . '"/>' 
                                        ) 
                                        : '';

                                    echo sprintf('<li %4$s data-value="%1$s">%5$s %2$s (%3$s)</li>', $currency['currency'], $wc_currencie_list[$currency['currency']], $currency_symbol, $hide_currency, $flag); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                }
                            ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </div>
        <?php
        return ob_get_clean();


    }

    /**
     * Get Flag URL
     *
     * @param [type] $flag_style
     * @param [type] $country_code
     * @return string
     */
    public function get_flag_url( $flag_style, $country_code ){
        $url = 'https://raw.githubusercontent.com/HasThemes/public_assets/main/country_flags/'.$flag_style.'/'.strtoupper( $country_code ).'.png';
        return $url;
    }

}