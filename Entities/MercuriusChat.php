<?php 
/**
 * MercuriusChat plugin file.
 */

 /**
  * Classe Inicial do plugin
  */
class MercuriusChat{

    protected static $instance;

    /**
     * Construtor e inicializador
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'mchat_add_menu'));
        add_action('admin_init', array($this, 'mchat_register_settings'));

        // Enfileiramento de scripts
        add_action('wp_enqueue_scripts', array($this, 'mchat_enqueue_scripts'));
        add_action('wp_head', array($this, 'mchat_enqueue_gfonts'));

        // Render chat front-end
        add_action('wp_footer', array($this, 'mchat_render_plugin'));
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
    public function mchat_register_options()
    {
        // add opções e configurações
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
     * Página de configurações
     */
    public function mchat_register_settings()
    {
        $option_group = 'mchat-settings';        
        # Header
        register_setting($option_group, 'mchat_title');
        register_setting($option_group, 'mchat_description');
        
        # Opções
        register_setting($option_group, 'mchat_option_message');
        register_setting($option_group, 'mchat_option_service_1');
        register_setting($option_group, 'mchat_service_1_endpoint');
        register_setting($option_group, 'mchat_option_service_2');
        register_setting($option_group, 'mchat_service_2_endpoint');
        register_setting($option_group, 'mchat_option_service_3');
        register_setting($option_group, 'mchat_service_3_endpoint');

        # Contact
        register_setting($option_group, 'mchat_phone_number');
        register_setting($option_group, 'mchat_contact_message');

        # Another settings
        register_setting($option_group, 'mchat_screen_position');
        register_setting($option_group, 'mchat_custom_icon');
        register_setting($option_group, 'mchat_tooltip');
        register_setting($option_group, 'mchat_text_button');
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

        # OPÇÕES CHAT
        $curr_option_message        = get_option('mchat_option_message');
        $curr_option_service_1      = get_option('mchat_option_service_1');
        $curr_service_1_endpoint    = get_option('mchat_service_1_endpoint');
        $curr_option_service_2      = get_option('mchat_option_service_2');
        $curr_service_2_endpoint    = get_option('mchat_service_2_endpoint');
        $curr_option_service_3      = get_option('mchat_option_service_3');
        $curr_service_3_endpoint    = get_option('mchat_service_3_endpoint');

        # CONTACT
        $curr_phone_number          = get_option('mchat_phone_number');
        $curr_contact_message       = get_option('mchat_contact_message');

        # ANOTHERS SETTINGS
        $curr_screen_position   = get_option('mchat_screen_position');
        $curr_custom_icon       = get_option('mchat_custom_icon');
        $curr_tooltip           = get_option('mchat_tooltip');

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
                            <input type="text" id="id_mchat_title" name="mchat_title" class="" placeholder="Ex: Nome da empresa" value="<?php echo (!empty($curr_title) ? $curr_title : ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mchat_description"><?php _e('Descrição rápida', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_description" name="mchat_description" class="" placeholder="Ex: Bem vindo ao nosso chat de atendimento" value="<?php echo (!empty($curr_description) ? $curr_description : ''); ?>">
                        </td>
                    </tr>
                    <!-- /Header -->

                    <tr>
                        <th scope="row">
                            <label for="mchat_option_message"><?php _e('Mensagem inicial do chat:', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_option_message" name="mchat_option_message" class="" placeholder="Ex: O que você precisa?" value="<?php echo (!empty($curr_option_message) ? $curr_option_message : ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_option_service_1"><?php _e('Opção 1', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_option_service_1" name="mchat_option_service_1" class="" placeholder="Ex: Ver produtos" value="<?php echo (!empty($curr_option_service_1) ? $curr_option_service_1 : ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mchat_service_1_endpoint"><?php _e('Endpoint opção 1', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_service_1_endpoint" name="mchat_service_1_endpoint" class="" placeholder="Endpoint da opção 1" value="<?php echo (!empty($curr_service_1_endpoint) ? $curr_service_1_endpoint : ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_option_service_2"><?php _e('Opção 2', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_option_service_2" name="mchat_option_service_2" class="" placeholder="Ex: Ver produtos" value="<?php echo (!empty($curr_option_service_2) ? $curr_option_service_2 : ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mchat_service_1_endpoint"><?php _e('Endpoint opção 2', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_service_2_endpoint" name="mchat_service_2_endpoint" class="" placeholder="Endpoint da opção 2" value="<?php echo (!empty($curr_service_2_endpoint) ? $curr_service_2_endpoint : ''); ?>">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mchat_option_service_3"><?php _e('Opção 3', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_option_service_3" name="mchat_option_service_3" class="" placeholder="Ex: Ver produtos" value="<?php echo (!empty($curr_option_service_3) ? $curr_option_service_3 : ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mchat_service_3_endpoint"><?php _e('Endpoint opção 3', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_service_3_endpoint" name="mchat_service_3_endpoint" class="" placeholder="Endpoint da opção 3" value="<?php echo (!empty($curr_service_3_endpoint) ? $curr_service_3_endpoint : ''); ?>">
                        </td>
                    </tr>
                    <!-- /Options -->

                    <tr>
                        <th scope="row">
                            <label for="mchat_phone_number"><?php _e('Número WhatsApp', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="phone" id="id_mchat_phone_number" name="mchat_phone_number" class="" placeholder="Ex: 00 00000-0000" value="<?php echo (!empty($curr_phone_number) ? $curr_phone_number : ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_contact_message"><?php _e('Mensagem de contato', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="id_mchat_contact_message" name="mchat_contact_message" class="" placeholder="Ex: Contato via site" value="<?php echo (!empty($curr_contact_message) ? $curr_contact_message : ''); ?>">
                        </td>
                    </tr>
                    <!-- /Contact settings -->

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
                            <input type="text" id="mchat_tooltip" name="mchat_tooltip" class="" placeholder="Precisa de ajuda?" value="<?php echo (!empty($curr_tooltip) ? $curr_tooltip : ''); ?>">
                        </td>
                    </tr>
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