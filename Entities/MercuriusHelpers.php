<?php 

class MercuriusHelpers
{


    /**
     * Sanitize URL avatar from user
     * 
     * @param $user_id int
     * 
     * @return $url string
     */
    public function mchat_sanitize_url_avatar($user_id)
    {
        $element            = get_wp_user_avatar($user_id);
        $to_remove          = array("<", ">", "img", "src=", "alt=", "class=", "avatar avatar-", "wp-user-avatar", "wp-user-avatar-", "alignnone");

        $sanitized_avatar   = str_replace($to_remove, "", $element);
        $pieces_avatar      = explode(" ", $sanitized_avatar);
        $url                = $pieces_avatar[1]; # URL POSITION

        $url                = str_replace('"', '', $url);
        return $url;
    }


    /**
     * Sanitize URL from woocomerce gallery
     * 
     * @param $ids array
     * 
     * @return string
     */
    public function mchat_gallery_from_wc_gallery($ids)
    {
        $imgs_url   = array();
        $imgs       = '';
        foreach ($ids as $id) :
            $element        = wc_get_gallery_image_html( $id );
            $element        = explode('"', $element);
            $extract_url    = str_replace("-100x100", "", $element[1]);

            $imgs_url = $extract_url;
            $imgs .= '<img src="'. $extract_url .'" alt="" class="mchatBody__thumb mchatBody__thumb--128px">';
        endforeach;

        return $imgs;
    }


    /**
     * Get product or service by id
     * 
     * @return WP_post object
     */
    public function get_product_by_id()
    {
        $ajax_id    = esc_attr($_POST['the_id']);

        $the_post   = wc_get_product($ajax_id);

        $title          = $the_post->name;
        $description    = $the_post->description;
        $gallery        = $the_post->gallery_image_ids;
        $arr_images_url = MercuriusHelpers::mchat_gallery_from_wc_gallery($gallery);

        $output_html = '<span class="btnSimple btnBack" onclick="backButton(jQuery(this))">
                            <i class="btnSimple btnSimple__icon btnSimple__icon--24px fas fa-arrow-circle-left"></i>
                        </span>
                        <div class="mchat__wrapperTitle">
                            <h2 class="mchat__title mchat__title--small">'. $title .'</h2>
                        </div>                        
                        <!-- Inicial -->
                        <div class="mCard__container">
                            <div class="mchatBody__gallery">'. $arr_images_url .'</div>
                            <p class="mchatBody__desc">'. wp_trim_words($description, 22, '...') .'</p>
                            <a href="" class="btnPrimary btnPrimary--ghost">Página produto</a>
                            <a href="" class="btnPrimary">Adicionar ao carrinho</a>
                        </div>';

        // echo $the_post;
        echo $output_html;

        die();
    }

    /**
     * The ajax request for the acton "get_product_by_id"
     */
    public function ajax_id_product()
    {
        ?>
        <script type="text/javascript">
        function show_product(el)
        {
            jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'post',
                data: { 
                    action: 'get_product_by_id', 
                    the_id: el.attr('data-id'),
                },
                success: function(data)
                {
                    var contentParents  = jQuery('.mchat__content');
                    contentParents.each(function()
                    {
                        if (jQuery(this).attr('data-content') == 'service-page')
                        {
                            jQuery(this).html(data);
                            jQuery(this).show();
                        }
                        else
                        {
                            jQuery(this).hide();
                        }
                    });
                }
            });

        }
        </script>
        <?php
    }
}