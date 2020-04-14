<?php 
 /**
  * Class MercuriusChat
  */
include 'MercuriusSettings.php';
class MercuriusChat{

    protected static $instance;

    /**
     * Construtor e inicializador
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'mchat_add_menu'));
        add_action('admin_init', 'MercuriusSettings::mchat_register_settings');
        add_action('init', array($this, 'mchat_register_cpt'));

        // Enfileiramento de scripts
        add_action('wp_enqueue_scripts', array($this, 'mchat_enqueue_scripts'));
        add_action('wp_head', array($this, 'mchat_enqueue_gfonts'));

        // Render chat front-end
        // add_action('wp_footer', array($this, 'mchat_render_plugin'));
        add_action('wp_footer', array($this, 'mchat_front_end_chat'));
    }

    /**
     * Enfileirar scripts e estilos
     */
    public function mchat_enqueue_scripts()
    {
        // Registro de arquivos CSS
        wp_register_style('mchat-css', plugins_url() . '/mercurius-chat/assets/css/mchat.css', array(), 'all');

        // Registro de arquivos JS
        wp_register_script('jquery-3.4.1', plugins_url('../assets/js/jquery-3.4.1.min.js', __FILE__), array(), false);
        wp_register_script('mchat-js', plugins_url('../assets/js/mchat-script.js', __FILE__), array('jquery-3.4.1'), false);
        
        // Enfileiramento de scripts e css
        wp_enqueue_style('mchat-css');
        wp_enqueue_script('jquery-3.4.1');
        wp_enqueue_script('mchat-js');
    }

    public function mchat_enqueue_gfonts()
    {
        echo '<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans:400,700&display=swap" rel="stylesheet">';
    }
    /**
     * Registrar options de configurações do plugin
     */
    public function mchat_register_cpt()
    {
        // CPT: FAQ

        $labels = array(
            'name'                  => _x( 'Mchat FAQ', 'Post type general name', 'mchat' ),
            'singular_name'         => _x( 'FAQ', 'Post type singular name', 'mchat' ),
            'menu_name'             => _x( 'Mchat FAQ', 'Admin Menu text', 'mchat' ),
            'name_admin_bar'        => _x( 'FAQ', 'Add New on Toolbar', 'mchat' ),
            'add_new'               => __( 'Add New', 'mchat' ),
            'add_new_item'          => __( 'Add New FAQ', 'mchat' ),
            'new_item'              => __( 'New FAQ', 'mchat' ),
            'edit_item'             => __( 'Edit FAQ', 'mchat' ),
            'view_item'             => __( 'View FAQ', 'mchat' ),
            'all_items'             => __( 'All Mchat FAQ', 'mchat' ),
            'search_items'          => __( 'Search Mchat FAQ', 'mchat' ),
            'parent_item_colon'     => __( 'Parent Mchat FAQ:', 'mchat' ),
            'not_found'             => __( 'No Mchat FAQ found.', 'mchat' ),
            'not_found_in_trash'    => __( 'No Mchat FAQ found in Trash.', 'mchat' ),
            'featured_image'        => _x( 'FAQ Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'mchat' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'mchat' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'mchat' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'mchat' ),
            'archives'              => _x( 'FAQ archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'mchat' ),
            'insert_into_item'      => _x( 'Insert into FAQ', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'mchat' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this FAQ', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'mchat' ),
            'filter_items_list'     => _x( 'Filter Mchat FAQ list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'mchat' ),
            'items_list_navigation' => _x( 'Mchat FAQ list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'mchat' ),
            'items_list'            => _x( 'Mchat FAQ list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'mchat' ),
        );
     
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'faq' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-sos',
            'supports'           => array( 'title', 'editor', 'author'),
        );
        register_post_type('machat-faq', $args);
    }

    /**
     * Adicionar menu no painel admin do wordpress
     */
    public function mchat_add_menu()
    {
        add_menu_page('Mercurius Chat', 'MChat', 'administrator', 'm-chat', 'MercuriusChat::mchat_admin_page', 'dashicons-format-status', 65);
    }

    
    /**
     * Chamada da página da inicial do plugin
     */
    public function mchat_admin_page()
    {
        require_once(plugin_dir_path(__FILE__) . '../templates/admin/home-page.php');
    }

    /**
     * Formulário de configurações administrativo
     */
    public function mchat_form_setup()
    {
        if (!current_user_can('administrator'))
            wp_die('Boa tentativa! Agora chame o administrador.');

        # HEADER
        $curr_title             = get_option('mchat_title');
        $curr_description       = get_option('mchat_description');

        # MENU
        $curr_title_menu        = get_option('mchat_menu_title');
        $curr_menu_option_1     = get_option('mchat_menu_option_1');
        $curr_menu_endpoint_1   = get_option('mchat_menu_endpoint_1');
        $curr_menu_option_2     = get_option('mchat_menu_option_2');
        $curr_menu_endpoint_2   = get_option('mchat_menu_endpoint_2');
        $curr_menu_option_3     = get_option('mchat_menu_option_3');
        $curr_menu_endpoint_3   = get_option('mchat_menu_endpoint_3');
        
        # CONTACT
        $curr_contact_message       = get_option('mchat_contact_message');
        
        # ANOTHERS SETTINGS
        $curr_screen_position       = get_option('mchat_screen_position');
        $curr_custom_icon           = get_option('mchat_custom_icon');
        $curr_tooltip               = get_option('mchat_tooltip');
        $curr_business_hours        = get_option('mchat_business_hours');
        
        # PAGES/SCREENS
        $curr_title_page_services           = get_option('mchat_page_services_title');
        $curr_page_services_description     = get_option('mchat_page_services_description');
        $curr_title_page_faq                = get_option('mchat_page_faq_title');
        $curr_description_page_faq          = get_option('mchat_page_faq_description');
        ?>

        <h3>Configurações Gerais</h3>
        <form method="post" action="options.php" class="row col-lg-8">
            <?php 
            settings_fields('mchat-settings');
            do_settings_sections('mchat-settings');
            ?>

            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="mchat_title"><?php _e('Título do chat', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_title" name="mchat_title" class="" placeholder="" value="<?php echo (!empty($curr_title) ? $curr_title : ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mchat_description"><?php _e('Descrição rápida', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_description" name="mchat_description" class="" placeholder="" value="<?php echo (!empty($curr_description) ? $curr_description : ''); ?>">
                        </td>
                    </tr>
                    <!-- /Header -->
                    
                    <tr>
                        <td><h3>Configurações Menu:</h3></td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_menu_title"><?php _e('Título do menu rápido:', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_menu_title" name="mchat_menu_title" class="" placeholder="" value="<?php echo (!empty($curr_title_menu) ? $curr_title_menu : ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_menu_option_1"><?php _e('Menu opção 1', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_menu_option_1" name="mchat_menu_option_1" class="" placeholder="" value="<?php echo (!empty($curr_menu_option_1) ? $curr_menu_option_1 : ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mchat_menu_endpoint_1"><?php _e('Endpoint opção 1', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_menu_endpoint_1" name="mchat_menu_endpoint_1" class="" placeholder="" value="<?php echo (!empty($curr_menu_endpoint_1) ? $curr_menu_endpoint_1 : ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_menu_option_2"><?php _e('Menu opção 2', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_menu_option_2" name="mchat_menu_option_2" class="" placeholder="" value="<?php echo (!empty($curr_menu_option_2) ? $curr_menu_option_2 : ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mchat_menu_endpoint_2"><?php _e('Endpoint opção 2', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_menu_endpoint_2" name="mchat_menu_endpoint_2" class="" placeholder="" value="<?php echo (!empty($curr_service_2_endpoint) ? $curr_service_2_endpoint : ''); ?>">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mchat_menu_option_3"><?php _e('Menu opção 3', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_menu_option_3" name="mchat_menu_option_3" class="" placeholder="" value="<?php echo (!empty($curr_menu_option_3) ? $curr_menu_option_3 : ''); ?>">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mchat_menu_endpoint_3"><?php _e('Endpoint opção 3', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_menu_endpoint_3" name="mchat_menu_endpoint_3" class="" placeholder="" value="<?php echo (!empty($curr_service_3_endpoint) ? $curr_service_3_endpoint : ''); ?>">
                        </td>
                    </tr>
                    <!-- /Menu -->

                    <tr>
                        <td><h3>Configurações Telas:</h3></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mchat_page_services_title"><?php _e('Título página de serviços', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_page_services_title" name="mchat_page_services_title" class="" placeholder="" value="<?php echo (!empty($curr_title_page_services) ? $curr_title_page_services : ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mchat_page_services_description"><?php _e('Descrição página de serviços', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_page_services_description" name="mchat_page_services_description" class="" placeholder="" value="<?php echo (!empty($curr_title_page_services) ? $curr_title_page_services : ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_page_faq_title"><?php _e('Título página FAQ', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_page_faq_title" name="mchat_page_faq_title" class="" placeholder="" value="<?php echo (!empty($curr_title_page_faq) ? $curr_title_page_faq : ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mchat_page_faq_description"><?php _e('Descrição página FAQ', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_page_faq_description" name="mchat_page_faq_description" class="" placeholder="" value="<?php echo (!empty($curr_description_page_faq) ? $curr_description_page_faq : ''); ?>">
                        </td>
                    </tr>



                    <tr>
                        <td><h3>Configurações gerais:</h3></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mchat_contact_message"><?php _e('Mensagem de contato', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_contact_message" name="mchat_contact_message" class="" placeholder="" value="<?php echo (!empty($curr_contact_message) ? $curr_contact_message : ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for=""><?php _e('Posicionamento do botão', 'mchat'); ?></label>
                        </th>
                        <td>
                            <div>
                                <input type="radio" id="mchat_screen_position_left" name="mchat_screen_position" class="" value="pos-left" <?php echo ($curr_screen_position == 'pos-left' ? 'checked' : ''); ?>>
                                <label for="mchat_screen_position_left">Canto esquerdo da tela</label>
                            </div>
                            <div>
                                <input type="radio" id="mchat_screen_position_right" name="mchat_screen_position" class="" value="pos-right" <?php echo ($curr_screen_position == 'pos-right' ? 'checked' : ''); ?>>
                                <label for="mchat_screen_position_right">Canto direito da tela</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_custom_icon"><?php _e('Ícone do chat', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="url" id="mchat_custom_icon" name="mchat_custom_icon" class="" placeholder="" value="<?php echo (!empty($curr_custom_icon) ? $curr_custom_icon : ''); ?>">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mchat_tooltip"><?php _e('Dica do chat', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mchat_tooltip" name="mchat_tooltip" class="" placeholder="" value="<?php echo (!empty($curr_tooltip) ? $curr_tooltip : ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_business_hours"><?php _e('Horário de funcionamento', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_business_hours" name="mchat_business_hours" class="" placeholder="" value="<?php echo (!empty($curr_business_hours) ? $curr_business_hours : ''); ?>">
                        </td>
                    </tr>
                    <!-- / General settings -->
                </tbody>  
            </table>
            <?php submit_button(); ?>
        </form>
        <?php
    }

    /**
     * html plugin
     */
    public function mchat_render_plugin()
    {
        # HEADER
        $mchat_title            = get_option('mchat_title');
        $mchat_description      = get_option('mchat_description');

        # OPÇÕES CHAT
        $option_message         = get_option('mchat_option_message');
        $option_service_1       = get_option('mchat_option_service_1');
        $service_1_endpoint     = get_option('mchat_service_1_endpoint');
        $option_service_2       = get_option('mchat_option_service_2');
        $service_2_endpoint     = get_option('mchat_service_2_endpoint');
        $option_service_3       = get_option('mchat_option_service_3');
        $service_3_endpoint     = get_option('mchat_service_3_endpoint');

        # CONTACT
        $phone_number           = get_option('mchat_phone_number');
        $contact_message        = get_option('mchat_contact_message');
        $link_open_whatsapp     = 'https://wa.me/55' . str_replace(' ', '', $phone_number);

        # ANOTHERS SETTINGS
        $screen_position        = get_option('mchat_screen_position');
        $custom_icon            = get_option('mchat_custom_icon');
        $tooltip                = get_option('mchat_tooltip');

        ?>
        <!-- Wrapper chat -->
        <div id="mchatMessenger" class="mchatMessenger mchatMessenger--disabled">
            <header class="mchatMessenger__header" role="">
                <h3 class="mchatMessenger__title"><?php echo ( !empty($mchat_title) ? $mchat_title : _e('Configure o título do chat', 'mchat') ) ?></h3>
                <p class="mchatMessenger__subtitle"><?php echo ( !empty($mchat_description) ? $mchat_description : _e('Configure a descrição do chat', 'mchat') ) ?></p>
            </header>

            <section class="mchatMessenger__body">
                <div class="mchatMessenger__messageContainer">
                    <span class="mchatMessenger__messageText"><?php echo ( !empty($option_message) ? $option_message : _e('Bem vindo. Nós do(a) <b>'. get_bloginfo('site_title') . '</b> estamos a sua disposição. Digite o que você precisa e pressione enter :)' , 'mchat') ) ?></span>
                </div>

                <?php if (!empty($option_service_1)) : ?>
                    <div class="mchatMessenger__messageContainer">
                        <span class="mchatMessenger__messageText"><a href="<?php echo get_site_url() . $service_1_endpoint ?>"><?php echo $option_service_1 ?></a></span>
                    </div>
                <?php endif; ?>
                    
                <?php if (!empty($option_service_1)) : ?>
                    <div class="mchatMessenger__messageContainer">
                        <span class="mchatMessenger__messageText"><a href="<?php echo get_site_url() . $service_2_endpoint ?>"><?php echo $option_service_2 ?></a></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($option_service_1)) : ?>
                    <div class="mchatMessenger__messageContainer">
                        <span class="mchatMessenger__messageText"><a href="<?php echo get_site_url() . $service_3_endpoint ?>"><?php echo $option_service_3 ?></a></span>
                    </div>
                <?php endif; ?>
            </section>

            <footer class="mchatMessenger__footer" role="">
                <div class="mchatMessenger__wrapperInput">
                    <input type="text" id="" class="mchatMessenger__inputText" placeholder="Digite e pressione enter para enviar">
                    <button style="display: none;" type="button" id="" class="mchatBtn mchatBtn__send">
                        <span class="mchatIcon mchatIcon__send"></span>
                    </button>
                    <a href="<?php echo $link_open_whatsapp ?>" id="" class="mchatBtn mchatBtn__send">
                        <span class="mchatIcon mchatIcon__send"></span>
                    </a>
                </div>
            </footer>

        </div>
        <!-- Container elements -->
        <div class="mchatContainer mchatContainer--right">
            <!-- Wrapper tooltips -->
            <span class="mchatTooltip mchatTooltip__text"><?php echo $tooltip ?></span>
            
            <!-- Wrapper buttons open/close -->
            <button type="button" id="" class="mchatBtn mchatBtn__openAndClose" onclick="openAndCloseChat(jQuery(this))">
                <span class="mchatIcon mchatIcon__messenger mchatIcon--enabled"></span>
                <span class="mchatIcon mchatIcon__close mchatIcon--disabled"></span>
            </button>
        </div>
        <?php
    }

    /**
     * Front-end plugin
     */
    public function mchat_front_end_chat()
    {
        $header = '<header class="mchatHeader">
                        <div class="mchatHeader__">
                            <span class=""><!-- Botão voltar --></span>
                        </div>
                        <h1 class="mchatHeader__title">Bem vindo</h1>
                        <p class="mchatHeader__description">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                    </header>';

        $body = '<section class="mchatBody">
                    <div class="mchatBody__content">
                        <h3 class="mchatBody__title">Menu rápido:</h3>
                        <ul class="mchatNav">
                            <li class="mchatNav__item"><a href="#" class="mchatNav__link"><i class="icon__arrowRight icon--16px"></i>Compra rápida</a></li>
                            <li class="mchatNav__item"><a href="#" class="mchatNav__link"><i class="icon__arrowRight icon--16px"></i>Perguntas Frequentes(FAQ)</a></li>
                        </ul>
                    </div>
                    <footer class="mchatBody__footer">
                        <span class="mchatBody__label">Horário de atendimento: <b>09:00 AM - 18:00 PM</b></span>
                    </footer>
                </section>';
    

        $cardPerson = '<div class="mchatCard">
                            <h3 class="mchatCard__title">Atendimento Online</h3>
                            <div class="mchatPerson">
                                <div class="mchatPerson__thumbContainer mchatPerson__thumbContainer--48px">
                                    <img src="" alt="" class="mchatPerson__thumb mchatPerson__thumb--48px">
                                    <span class="mchatPerson__status mchatPerson__status--online"></span>
                                </div>
                                <div class="mchatPerson__infoContainer">
                                    <span class="mchatPerson__name">Janice M. Gleen</span>
                                    <span class="mchatPerson__role">Suporte</span>
                                </div>
                            </div>
                            <button type="button" class="btnPrimary"><span class="btnPrimary__text">iniciar conversa</span><i class="icon__whatsapp icon--16px"></i></button>
                        </div>';
        
        

        // Templates parts
        $cardProduct = '<div class="mchatCardProduct">
                        <div class="mchatCardProduct__container">
                            <!-- Thumb -->
                            <div class="mchatCardProduct__wrapperThumb">
                                <img src="" alt="" class="mchatCardProduct__thumb">
                            </div>
                    
                            <!-- Title -->
                            <div class="mchatCardProduct__wrapperTitle">
                                <h3 class="mchatCardProduct__title">Monitor Gamer Acer Kg241q 23.6 Full Hd 144hz 1ms</h3>
                                <div class="mchatRating">
                                    <i class="mchatRating__star"></i>
                                    <i class="mchatRating__star"></i>
                                    <i class="mchatRating__star"></i>
                                    <i class="mchatRating__star"></i>
                                    <i class="mchatRating__halfStar"></i>
                                </div>
                                <!-- Avaliação -->
                            </div>
                        </div>
                        <span class="mchatCardProduct__separator"></span>
                        <div class="mchatCardProduct__container">
                            <!-- Descrição -->
                            <div class="mchatCardProduct__wrapperDesc">
                                <p class="mchatCardProduct__desc">Lorem ipsum dolor sit amet consectetur adipisicing elit...</p>
                            </div>
                    
                            <div class="mchatCardProduct__boxPrice">
                                <!-- Preço -->
                                <h3 name="product-price" class="mchatCardProduct__price">R$1.349</h3>
                                <!-- Label preço -->
                                <label for="product-price" class="mchatCardProduct__label">Até 3x no cartão</label>
                            </div>
                        </div>
                    </div>';

        $detailsProduct = '<div class="mchatCardDetails">
                            <div class="mchatCardDetails__container">
                                <ul class="mchatCardDetails__list">
                                    <li class="mchatCardDetails__item">
                                        <img src="" alt="" class="mchatCardDetails__thumb">
                                    </li>
                                    <li class="mchatCardDetails__item">
                                        <img src="" alt="" class="mchatCardDetails__thumb">
                                    </li>
                                    <li class="mchatCardDetails__item">
                                        <img src="" alt="" class="mchatCardDetails__thumb">
                                    </li>
                                </ul>
                            </div>
                        
                            <div class="mchatCardDetails__container">
                                <p class="mchatCardDetails__desc">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla, veritatis! Dolorum iste, tempora eligendi ad ratione eveniet ut voluptatibus</p>
                            </div>
                        
                            <button type="button" class="btnPrimary btnPrimary--ghost">Página do produto <i class="icon__whatsapp icon--16px"></i></button>
                            <button type="button" class="btnPrimary">Adicionar ao carrinho <i class="icon__whatsapp icon--16px"></i></button>
                        </div>';



        $faqPost = '<div class="mchatCardFaq mchatCardFaq--expand">
                    <h3 class="mchatCardFaq__title">Quais as opções de pagamento? <span class="icon__carretDown icon--16px"></span></h3>
                    <div class="mchatCardFaq__content mchatCardFaq__content--hide">
                        <p class="mchatCardFaq__text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minus aperiam quod cumque? Corrupti laboriosam neque eaque perferendis. Voluptatem at minus.</p>
                    </div>
                </div>';
        // End

        $bodyEmpty = '<section class="mchatBody">
                    <div class="mchatBody__containerResults">
                        <div id="page-products" class="">
                        '. $cardProduct .'
                        '. $cardProduct .'
                        </div>
                        <div id="page-details-product" class="" style="display: none;">
                        '. $detailsProduct .'
                        </div>
                        <div id="page-faq" class="" style="display: none;">
                        '. $faqPost .'
                        </div>
                    </div>
            </section>';
        
        include plugin_dir_path(__FILE__) . '../templates/front-end/chat.php';
            
    }
    

    /**
     * Instanciando a classe
     */
    public static function instance() {
        if (!isset(self::$instance)) {
          self::$instance = new self();
        }
        return self::$instance;
    }
}
MercuriusChat::instance();