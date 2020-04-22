<?php 
$title              = get_option('mchat_title');
$description        = get_option('mchat_description');

$page_services_title    = get_option('mchat_page_services_title');
$page_services_desc     = get_option('mchat_page_services_description');
$page_faq_title         = get_option('mchat_page_faq_title');
$page_faq_desc          = get_option('mchat_page_faq_description');

$contact_message        = get_option('mchat_contact_message');
$business_hours         = get_option('mchat_business_hours');
$chat_position          = get_option('mchat_screen_position');
$chat_icon              = get_option('mchat_custom_icon');
$chat_tooltip           = get_option('mchat_tooltip');
$text_button            = get_option('mchat_text_button');

function mchat_card_support()
{
    $args = array(
        'role'  => 'contributor'
    );
    $attendants = get_users($args); 

    if($attendants) :
        echo '<div class="mCard mCard__container mCard__container--scroll">
            <h3 class="mchat__title mchat__title--smaller mchat__title--cDark0">Atendimento Online</h3><span class="mchat__separator"></span>';
            foreach($attendants as $user) :
                $user_id            = $user->ID;
                $user_name          = $user->display_name;
                $user_position      = $user->roles[0];
                $user_phone_number  = get_user_option('mchat_phone_user', $user_id);
                $user_phone_number  = str_replace(' ', '', $user_phone_number);
                $avatar_url         = MercuriusHelpers::mchat_sanitize_url_avatar($user_id);

                echo '<div class="mCard__support">
                        <div class="mCard__thumbContainer mCard__thumbContainer--64px">
                            <img src="'. $avatar_url .'" alt="'. $user_name .'" class="mCard__thumb mCard__thumb--radius100">
                            <span class="mCard__status mCard__status--online"></span>
                        </div>
                        <div class="mCard__infoContainer">
                            <span class="mCard__name">'. $user_name .'</span>
                            <span class="mCard__role">'. $user_position .'</span>
                        </div>
                    </div>
                    <a href="https://wa.me/55'. $user_phone_number .'" target="_blank" class="btnPrimary"><span class="btnPrimary__text">iniciar conversa</span><i class="btnPrimary__icon btnPrimary__icon--s21 fab fa-whatsapp"></i></a>';
            endforeach;
        echo '</div>';
    endif;
    // print_r($attendants);
}

function mchat_nav()
{
    $menu_title         = get_option('mchat_menu_title');
    $menu_option_1      = get_option('mchat_menu_option_1');
    $menu_endpoint_1    = get_option('mchat_menu_endpoint_1');
    $menu_option_2      = get_option('mchat_menu_option_2');
    $menu_endpoint_2    = get_option('mchat_menu_endpoint_2');
    $menu_option_3      = get_option('mchat_menu_option_3');
    $menu_endpoint_3    = get_option('mchat_menu_endpoint_3');

    ?>
    <ul class="mchat__nav mchat__nav--posBottom">
        <h4 class="mchat__title mchat__title--small mchat__title--cDark0"><?php echo (!empty($menu_title) ? $menu_title : 'Menu rápido:'); ?></h4>
        <li class="mchat__menuItem" data-endpoint="<?php echo $menu_endpoint_1 ?>"><i class="mchat__icon mchat__icon--21 mchat__icon--cDark mchat__icon--rightMargin fas fa-angle-double-right"></i><?php echo $menu_option_1 ?></li>
        <li class="mchat__menuItem" data-endpoint="<?php echo $menu_endpoint_2 ?>"><i class="mchat__icon mchat__icon--21 mchat__icon--cDark mchat__icon--rightMargin fas fa-angle-double-right"></i><?php echo $menu_option_2 ?></li>
    </ul>
    <?php
}

function mchat_page_services()
{
    $args = array(
        'limit' => 3,
    );
    $products = wc_get_products( $args );

    echo ' <div class="mCard__container mCard__container--scroll">';
    if ($products):
        echo '<p class="mchat__text mchat__text--normal mchat__text--cDark">Encontramos <b>'. count($products) .'</b> produtos:</p>';
        foreach($products as $product):
            $product_id         = $product->id;
            $product_title      = $product->name;
            $short_description  = $product->short_description;
            $regular_price      = $product->regular_price;
            $thumb_id           = $product->image_id;
            $gallery_image_ids  = $product->gallery_images_id;

            $thumb_product      = wp_get_attachment_image_url($thumb_id);

            echo '<div class="mCard__services">
                    <div class="mCard__wrapper">
                        <!-- Thumb -->
                        <div class="mCard__wrapperThumb">
                            <img src="'. $thumb_product .'" alt="" class="mCard__thumb">
                        </div>
                
                        <!-- Title -->
                        <div class="mCard__wrapperTitle">
                            <h4 class="mchat__title mchat__text--normal mchat__text--cDark" data-id="'. $product_id .'" onclick="show_product(jQuery(this))">'. wp_trim_words($product_title, 8, '...') .'</h4>
                            <div class="mchatRating">
                                <i class="mchatRating__icon mchatRating__icon--s16 fas fa-star"></i>
                                <i class="mchatRating__icon mchatRating__icon--s16 fas fa-star"></i>
                                <i class="mchatRating__icon mchatRating__icon--s16 fas fa-star"></i>
                                <i class="mchatRating__icon mchatRating__icon--s16 fas fa-star"></i>
                                <i class="mchatRating__icon mchatRating__icon--s16 fas fa-star-half"></i>
                            </div>
                            <!-- Avaliação -->
                        </div>
                    </div>
                    <span class="mCard__separator"></span>
                    <div class="mCard__wrapper">
                        <!-- Descrição -->
                        <div class="mCard__wrapperDesc">
                            <p class="mCard__desc">'. wp_trim_words($short_description, 8, '...') .'</p>
                        </div>
                
                        <div class="mCard__boxPrice">
                            <!-- Preço -->
                            <h3 name="product-price" class="mCard__price">R$'. $regular_price .'</h3>
                            <!-- Label preço -->
                            <label for="product-price" class="mCard__label">Até 3x no cartão</label>
                        </div>
                    </div>
                </div>';

        endforeach;
    endif;
    echo '</div> ';
}


function machat_page_faq()
{
    $args = array(
        'post_type'     => 'mchat-faq',
        'post_status'   => 'publish',
    );
    $faq_posts = new WP_Query($args);
    echo '<div class="mCard__container mCard__container--scroll">';
    if ($faq_posts->have_posts()) :
        while ($faq_posts->have_posts()) :
            $faq_posts->the_post();
            $faq_id     = $faq_posts->ID;
            $title      = get_the_title($faq_id);
            $content    = get_the_content();

            echo '<div class="mchatCardFaq">
                    <h3 class="mchatCardFaq__title">'. $title .' <i class="mchatCardFaq__icon mchatCardFaq__icon--s21 fas fa-caret-down"></i></h3>
                    <div class="mchatCardFaq__content mchatCardFaq__content--hide">
                        <p class="mchatCardFaq__text">'.  $content .'</p>
                    </div>
                </div>';
        endwhile;
    endif;
    echo '</div>';

    
}

?>

<div class="mchatContainerButtons mchatContainerButtons--right">
    <span class="mchatTooltip mchatTooltip__text"><?php echo $chat_tooltip ?></span>

    <!-- Wrapper buttons open/close -->
    <span class="mchatBtn mchatBtn__openAndClose" onclick="openAndCloseChat(jQuery(this))">
        <i class="mchatIcon mchatIcon__messenger mchatIcon--enabled"></i>
        <i class="mchatIcon mchatIcon__close mchatIcon--disabled"></i>
    </span>
</div>


<div class="mchat mchat--right">
    <div class="mchat__container">
        <div class="mchat--topMainBackground"></div>
        <!-- /End top background -->
        <div data-content="home" class="mchat__content">
            <h2 class="mchat__title mchat__title--small"><?php echo $title ?></h2>
            <p class="mchat__text mchat__text--normal mchat__text--cWhite"><?php echo $description ?></p>

            <?php mchat_card_support(); ?>

            <?php mchat_nav(); ?>

            <span class="mchat__text mchat__text--small mchat__text--cDark0 mchat__text--posBottom"><span class="mchat__separator"></span>Horário de atendimento: 09:00 - 18:00 PM</span>
        </div>
        <!-- /End tela inicial -->

        <div data-content="services" class="mchat__content mchat__content--flexStart" style="display: none">
            <span class="btnSimple btnBack">
                <i class="btnSimple btnSimple__icon btnSimple__icon--24px fas fa-arrow-circle-left"></i>
            </span>
            <div class="mchat__wrapperTitle">
                <h2 class="mchat__title mchat__title--small"><?php echo $page_services_title; ?></h2>
                <p class="mchat__text mchat__text--normal mchat__text--cWhite"><?php echo $page_services_desc; ?></p>
            </div>

            <?php mchat_page_services(); ?>

        </div>
        <!-- /End servicos -->
         
        <div data-content="service-page" id="insert-html" class="mchat__content mchat__content--flexStart" style="display: none"></div>
        <!-- /End FAQ page -->

        <div data-content="faq" class="mchat__content mchat__content--flexStart" style="display: none">
            <span class="btnSimple btnBack">
                <i class="btnSimple btnSimple__icon btnSimple__icon--24px fas fa-arrow-circle-left"></i>
            </span>
            <div class="mchat__wrapperTitle">
                <h2 class="mchat__title mchat__title--small"><?php echo $page_faq_title; ?></h2>
                <p class="mchat__text mchat__text--normal mchat__text--cWhite"><?php echo $page_faq_desc; ?></p>
            </div>

            <?php machat_page_faq(); ?>

        </div>
        <!-- /End FAQ page -->

        <!-- /End App -->
        <div class="mchat--bottomMainBackground"></div>
        <!-- /End bottom background -->
    </div>
</div>
<!-- /End Mchat -->