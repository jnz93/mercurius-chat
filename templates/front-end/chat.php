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

// Funções
function mchat_attendants_card()
{
    $args = array(
        'role'  => 'contributor'
    );
    $attendants = get_users($args); 

    if($attendants) :
        echo '<div class="mchatCard">
            <h3 class="mchatCard__title">Atendimento Online</h3>';
            foreach($attendants as $user) :
                $user_id        = $user->ID;
                $user_name      = $user->display_name;
                $user_position  = $user->roles[0];
                $user_phone_number = get_user_option('mchat_phone_user', $user_id);
                $user_phone_number = str_replace(' ', '', $user_phone_number);

                echo '<div class="mchatPerson">
                        <div class="mchatPerson__thumbContainer mchatPerson__thumbContainer--48px">
                            <img src="" alt="" class="mchatPerson__thumb mchatPerson__thumb--48px">
                            <span class="mchatPerson__status mchatPerson__status--online"></span>
                        </div>
                        <div class="mchatPerson__infoContainer">
                            <span class="mchatPerson__name">'. $user_name .'</span>
                            <span class="mchatPerson__role">'. $user_position .'</span>
                        </div>
                    </div>
                    <a href="https://wa.me/55'. $user_phone_number .'" target="_blank" class="btnPrimary"><span class="btnPrimary__text">iniciar conversa</span><i class="btnPrimary__icon btnPrimary__icon--s21 fab fa-whatsapp"></i></a>';
            endforeach;
        echo '</div>';
    endif;
    // print_r($attendants);
}

/**
 * header
 * @param $button = bool, $title = string, $description = string
 */
function mchat_header($show_button = null, $title, $description)
{
    ?>
    <header class="mchatHeader">
        <?php if ($show_button) : ?>
            <div id="" class="mchatHeader__content">
                <span class="btnSimple btnBack">
                    <i class="btnSimple btnSimple__icon btnSimple__icon--24px fas fa-arrow-circle-left"></i>
                    <span class="btnSimple__label">Voltar</span>
                </span>
            </div>
        <?php endif; ?>
        <div class="mchatHeader__content">
            <h1 class="mchatHeader__title"><?php echo $title; ?></h1>
            <p class="mchatHeader__description"><?php echo $description; ?></p>
        </div>
    </header>
    <?php 
}

/**
 * Menu
 */
function mchat_menu()
{
    $menu_title         = get_option('mchat_menu_title');
    $menu_option_1      = get_option('mchat_menu_option_1');
    $menu_endpoint_1    = get_option('mchat_menu_endpoint_1');
    $menu_option_2      = get_option('mchat_menu_option_2');
    $menu_endpoint_2    = get_option('mchat_menu_endpoint_2');
    $menu_option_3      = get_option('mchat_menu_option_3');
    $menu_endpoint_3    = get_option('mchat_menu_endpoint_3');

    ?>
    <h3 class="mchatBody__title"><?php echo (!empty($menu_title) ? $menu_title : 'Menu rápido:'); ?></h3>
    <ul class="mchatNav">
        <li class="mchatNav__item"><a href="<?php echo $menu_endpoint_1 ?>" class="mchatNav__link"><i class="mchatNav__icon mchatNav__icon--21 fas fa-angle-double-right"></i><?php echo $menu_option_1 ?></a></li>
        <li class="mchatNav__item"><a href="<?php echo $menu_endpoint_2 ?>" class="mchatNav__link"><i class="mchatNav__icon mchatNav__icon--21 fas fa-angle-double-right"></i><?php echo $menu_option_2 ?></a></li>
    </ul>
    <?php
}

/**
 * Get and render products cards
 */
function mchat_get_wc_products()
{
    $args = array(
        'limit' => 3,
    );
    $products = wc_get_products( $args );

    // echo '<pre>';
    // print_r($products);
    // echo '</pre>';

    if ($products):
        echo '<p class="mchatBody__label">Encontramos <b>'. count($products) .'</b> produtos:</p>';
        foreach($products as $product):
            $product_id         = $product->id;
            $product_title      = $product->name;
            $short_description  = $product->short_description;
            $regular_price      = $product->regular_price;
            $thumb_id           = $product->image_id;
            $gallery_image_ids  = $product->gallery_images_id;

            $thumb_product      = wp_get_attachment_image_url($thumb_id);

            echo '<div class="mchatCardProduct">
                    <div class="mchatCardProduct__container">
                        <!-- Thumb -->
                        <div class="mchatCardProduct__wrapperThumb">
                            <img src="'. $thumb_product .'" alt="" class="mchatCardProduct__thumb">
                        </div>
                
                        <!-- Title -->
                        <div class="mchatCardProduct__wrapperTitle">
                            <a href="#open-product-'. $product_id .'" class="mchatCardProduct__link">
                                <h3 class="mchatCardProduct__title">'. wp_trim_words($product_title, 8, '...') .'</h3>
                            </a> 
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
                    <span class="mchatCardProduct__separator"></span>
                    <div class="mchatCardProduct__container">
                        <!-- Descrição -->
                        <div class="mchatCardProduct__wrapperDesc">
                            <p class="mchatCardProduct__desc">'. wp_trim_words($short_description, 8, '...') .'</p>
                        </div>
                
                        <div class="mchatCardProduct__boxPrice">
                            <!-- Preço -->
                            <h3 name="product-price" class="mchatCardProduct__price">R$'. $regular_price .'</h3>
                            <!-- Label preço -->
                            <label for="product-price" class="mchatCardProduct__label">Até 3x no cartão</label>
                        </div>
                    </div>
                </div>';

        endforeach;
    endif;
}


/**
 * Get and render faq posts
 */
function mchat_get_faq_posts()
{
    $args = array(
        'post_type'     => 'mchat-faq',
        'post_status'   => 'publish',
    );
    $faq_posts = new WP_Query($args);

    if ($faq_posts->have_posts()) :
        while ($faq_posts->have_posts()) :
            $faq_posts->the_post();
            $faq_id     = $faq_posts->ID;
            $title      = get_the_title($faq_id);
            $content    = get_the_content();

            echo '<div class="mchatCardFaq mchatCardFaq--expand">
                    <h3 class="mchatCardFaq__title">'. $title .' <i class="fas fa-caret-down"></i></h3>
                    <div class="mchatCardFaq__content mchatCardFaq__content--hide">
                        <p class="mchatCardFaq__text">'.  $content .'</p>
                    </div>
                </div>';
        endwhile;
    endif;

    
}

// HELPERS FUNCTIONS

?>

<div class="mchatContainerButtons mchatContainerButtons--right">
    <span class="mchatTooltip mchatTooltip__text"><?php echo $chat_tooltip ?></span>

    <!-- Wrapper buttons open/close -->
    <span class="mchatBtn mchatBtn__openAndClose" onclick="openAndCloseChat(jQuery(this))">
        <i class="mchatIcon mchatIcon__messenger mchatIcon--enabled"></i>
        <i class="mchatIcon mchatIcon__close mchatIcon--disabled"></i>
    </span>
</div>



<!-- Tela inicial -->
<div id="page-home" class="mchatContainer mchatContainer--right">
    
    <?php 
    mchat_header(false, $title, $description);
    ?>
    <section class="mchatBody">

        <!-- Inicial -->
        <div class="mchatBody__content">
            <?php mchat_attendants_card(); ?>
            <!-- /End Card Atendente -->

            <?php mchat_menu(); ?>
            <!-- /End Menu -->
        </div>

        <!-- Footer -->
        <footer class="mchatBody__footer">
            <span class="mchatBody__label">Horário de atendimento: <b><?php echo $business_hours ?></b></span>
        </footer>
    </section>
</div>

<!-- Tela serviços -->
<div id="page-services" class="mchatContainer mchatContainer--right">
    <?php
    mchat_header(true, $page_services_title, $page_services_desc);
    ?>
    <section class="mchatBody">

        <!-- Pages -->
        <div class="mchatBody__containerResults">
            <div id="page-products" class="">
                <!-- Add produtos -->
                <?php mchat_get_wc_products(); ?>
            </div>

            <div id="page-details-product" class="" style="display: none;">
                <!-- Add detalhes produto selecionado -->
            </div>
        </div>
    </section>
</div>

<!-- Tela FAQ -->
<div id="page-faq" class="mchatContainer mchatContainer--right">
    
    <?php 
    // mchat_header(false, $title, $description);
    mchat_header(true, $page_faq_title, $page_faq_desc);
    ?>
    <section class="mchatBody">

        <!-- Pages -->
        <div class="mchatBody__containerResults">
            <div id="page-faq" class="">
                <!-- FAQ page -->
                <?php mchat_get_faq_posts(); ?>
            </div>
        </div>

    </section>
</div>