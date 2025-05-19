<?php
    $badge_title = !empty( $badge['badge_title'] ) ? $badge['badge_title'] : esc_html__('Badge One','woolentor');
    $badge_translate_name = str_replace(' ', '', trim(preg_replace('/\(.+\)/', '', $badge_title)));

    $badge_text   = !empty( $badge['badge_text'] ) ? woolentor_translator('woolentor_badge_text_'.$badge_translate_name, $badge['badge_text']) : "";
    $badges_class = (!empty( $badge['has_ind'] ) && $badge['has_ind'] === true) ? 'ht-product-label ht-product-label-left' : '';
    $badge_postion= !empty( $badge['badge_position'] ) ? $badge['badge_position'] : "";

    if( (!empty( $badge['has_ind'] ) && $badge['has_ind'] === true) ){
        $badges_class = 'ht-product-label ht-product-label-left';
        $classes .= ' ht-product-badges';
    }else{
        $badges_class = '';
    }

    $badge_custom_position_css = $badge_css = "";
    if($badge_postion === 'custom_position' && woolentor_is_pro()){
        $custom_position = woolentor_css_position( 'badge_custom_position','woolentor_badges_settings','',$badge['badge_custom_position'] );
        $badge_custom_position_css .= $custom_position;
    }

    // Custom CSS
    $text_color = woolentor_generate_css('badge_text_color','woolentor_badges_settings','color','',$badge['badge_text_color']);
    $text_bg_color = woolentor_generate_css('badge_bg_color','woolentor_badges_settings','background-color','',$badge['badge_bg_color']);
    $text_font_size = woolentor_generate_css('badge_font_size','woolentor_badges_settings','font-size','px',$badge['badge_font_size']);
    $padding = woolentor_dimensions('badge_padding','woolentor_badges_settings','padding',$badge['badge_padding']);
    $border_radius = woolentor_dimensions('badge_border_radius','woolentor_badges_settings','border-radius',$badge['badge_border_radius']);
    $badge_css .= $text_color.$text_bg_color.$text_font_size.$padding.$border_radius;

?>
<div class="woolentor-product-badge-area <?php echo esc_attr($classes); ?>" style="<?php echo esc_attr($badge_custom_position_css); ?>">
    <span class="woolentor-product-badge <?php echo esc_attr($badges_class);?>" style="<?php echo esc_attr($badge_css); ?>">
        <?php echo esc_html( $badge_text ); ?>
    </span>
</div>