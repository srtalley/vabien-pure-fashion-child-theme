<?php

namespace Vabien\Theme;

class WooCommerce_Shop {

    public function __construct() {

        add_action( 'init' , array($this, 'vabien_shop_setup'), 15 );
        add_action( 'woocommerce_before_shop_loop_item_title', array($this, 'vabien_create_flipper_images'), 10);

        // add_filter('wp_get_attachment_image_attributes', array($this, 'set_product_image_alt_tags'), 20, 2);
    }
    /**
     * Add alt tags to product images
     */
    // function set_product_image_alt_tags( $attr, $attachment ) {
    //     // Get post parent
    //     $parent = get_post_field( 'post_parent', $attachment);
        
    //     // Get post type to check if it's product
    //     $type = get_post_field( 'post_type', $parent);
    //     if( $type != 'product' ){
    //         return $attr;
    //     }
        
    //     /// Get title
    //     $title = get_post_field( 'post_title', $parent);
        
    //     if( $attr['alt'] == ''){
    //         $attr['alt'] = $title;
    //         // $attr['title'] = $title;
    //     }
        
    //     return $attr;
    // }

      
    /**
     * Create images that flip in the shop
     */
    function vabien_create_flipper_images() {
        $product = wc_get_product();
        $get_gallery_image_ids = $product->get_gallery_image_ids();
        $get_image_id  = $product->get_image_id();
        $image_url_top = get_the_post_thumbnail_url($product->get_id(), 'woocommerce_thumbnail');
        // $placeholder_img = wc_placeholder_img_src('woocommerce_thumbnail');
        //wp_dbug($placeholder_img);
        // if($get_image_id){

            $image_top_alt = get_post_meta($get_image_id, '_wp_attachment_image_alt', TRUE);
            if(!$image_top_alt){
                $image_top_alt = $product->get_name();
            }

            if($get_gallery_image_ids){

                $image_bottom_alt = get_post_meta($get_gallery_image_ids[0], '_wp_attachment_image_alt', TRUE);
                if(!$image_bottom_alt){
                    $image_bottom_alt = $image_top_alt;
                }

                $output = '<div class="vabien-image-wrapper">';
                    //$post->post_title;
                    //$image_url_top = get_the_post_thumbnail_url($post->ID, 'woocommerce_thumbnail');
                    $image_url_bottom = wp_get_attachment_image_src($get_gallery_image_ids[0], 'woocommerce_thumbnail' );
                    $output .= '<img class="vabien-image" src="'.$image_url_top.'" alt="'.$image_top_alt.'" />';
                    // $output .= woocommerce_get_product_thumbnail();
                    //$output .= '<img class="bottom" width="300" height="300" src="'.$image_url_bottom[0].'" />';
                    $output .= '<img class="vabien-image-hover" src="'.$image_url_bottom[0].'" alt="'.$image_bottom_alt.'" />';
                            
                $output .= '</div>';
                
            }
            else{
                $output = '<div class="vabien-image-wrapper"><img class="image" src="'.$image_url_top.'" alt="'.$image_top_alt.'" /></div>';
            }

        // }
        // else{
        //     $output = '<div class="vabien-image-wrapper"><img class="image" src="'.$placeholder_img.'" /></div>';
        // }

        echo $output;
    }


    /**
     * Add and remove actions in the shop
     */
    public function vabien_shop_setup() {
        remove_action( 'woocommerce_before_main_content', 'thb_filter_bar', 10 );
        // add_action( 'woocommerce_before_shop_loop', 'thb_filter_bar', 10 );
        add_action( 'woocommerce_before_shop_loop', array($this, 'vabien_thb_filter_bar'), 10 );

        // add_action( 'woocommerce_before_shop_loop', 'woocommerce_breadcrumb', 11 );
                // add_action( 'woocommerce_before_shop_loop', 'thb_shop_filters', 11 );

        // Fix an issue with the sidebar not working
        // because of its placement in the parent theme
        remove_action( 'thb_shop_filters', 'thb_shop_filters' );
        add_action( 'woocommerce_after_main_content', array($this, 'vabien_thb_shop_filters') );

    }

    /**
     * Customized version of the filter bar
     * Copied from inc/woocommerce/woocommerce-filterbar.php
     */
    public function vabien_thb_filter_bar() {
        if ( is_product() ) {
            return;
        }

        $classes[] = 'thb-filter-bar';
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="row align-middle">
                <div class="small-6 medium-6 columns category_bar">
                    <a href="#" id="thb-shop-filters"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="11px" viewBox="299 372.046 14 11" enable-background="new 299 372.046 14 11" xml:space="preserve">
                        <path d="M302.254,377.166h-2.876c-0.208,0-0.378,0.17-0.378,0.378c0,0.208,0.17,0.378,0.378,0.378h2.876
                            c0.17,0.757,0.851,1.325,1.665,1.325s1.495-0.568,1.665-1.325c0.019,0,0.019,0,0.038,0h7c0.208,0,0.378-0.17,0.378-0.378
                            c0-0.208-0.17-0.378-0.378-0.378h-7c-0.019,0-0.038,0-0.038,0c-0.17-0.757-0.852-1.325-1.665-1.325
                            S302.425,376.409,302.254,377.166z M304.865,377.543c0,0.53-0.417,0.946-0.946,0.946c-0.529,0-0.946-0.417-0.946-0.946
                            c0-0.529,0.417-0.946,0.946-0.946C304.449,376.598,304.865,377.014,304.865,377.543z"></path>
                        <path d="M309.179,374.17c0.019,0,0.019,0,0.038,0h3.405c0.208,0,0.378-0.17,0.378-0.378s-0.17-0.378-0.378-0.378h-3.405
                            c-0.02,0-0.038,0-0.038,0c-0.17-0.757-0.852-1.324-1.665-1.324s-1.495,0.567-1.665,1.324h-6.47c-0.208,0-0.378,0.17-0.378,0.378
                            s0.17,0.378,0.378,0.378h6.47c0.17,0.757,0.852,1.324,1.665,1.324S309.009,374.927,309.179,374.17z M306.567,373.792
                            c0-0.53,0.417-0.946,0.946-0.946s0.946,0.416,0.946,0.946s-0.417,0.946-0.946,0.946S306.567,374.322,306.567,373.792z"></path>
                        <path d="M312.622,380.917h-3.405c-0.02,0-0.038,0-0.038,0c-0.17-0.757-0.852-1.324-1.665-1.324s-1.495,0.567-1.665,1.324h-6.47
                            c-0.208,0-0.378,0.17-0.378,0.378s0.17,0.378,0.378,0.378h6.47c0.17,0.757,0.852,1.324,1.665,1.324s1.495-0.567,1.665-1.324
                            c0.019,0,0.019,0,0.038,0h3.405c0.208,0,0.378-0.17,0.378-0.378S312.83,380.917,312.622,380.917z M307.514,382.241
                            c-0.529,0-0.946-0.416-0.946-0.946s0.417-0.946,0.946-0.946s0.946,0.417,0.946,0.946S308.043,382.241,307.514,382.241z"></path>
                    </svg>
                    <?php esc_html_e( 'Filter', 'pure-fashion' ); ?></a>
                    <?php woocommerce_breadcrumb(); ?>

                </div>
                
                <div class="small-6 medium-6 columns ordering text-right">
                    <?php woocommerce_catalog_ordering(); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Customized version of the side filter
     */
    public function vabien_thb_shop_filters() {
        if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
            ?>
            <div id="side-filters" class="side-panel thb-side-filters">
                <header>
                    <h6><?php esc_html_e( 'Filter', 'pure-fashion' ); ?></h6>
                    <a href="#" class="thb-close" title="<?php esc_attr_e( 'Close', 'pure-fashion' ); ?>"><?php get_template_part( 'assets/img/svg/close.svg' ); ?></a>
                </header>
                <div class="side-panel-content custom_scroll">
                    <?php
                    if ( is_active_sidebar( 'thb-shop-filters' ) ) {
                        dynamic_sidebar( 'thb-shop-filters' );
                    }
                    ?>
                </div>
            </div>
            <?php
        }
}
}


$vabien_woocommerce_shop = new WooCommerce_Shop();