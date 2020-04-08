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
        echo '<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">';
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
        register_setting($option_group, 'mchat_phone_number');
        register_setting($option_group, 'mchat_contact_message');
        register_setting($option_group, 'mchat_screen_position');
        register_setting($option_group, 'mchat_custom_icon');
        register_setting($option_group, 'mchat_tooltip');
        register_setting($option_group, 'mchat_popup_message');
        register_setting($option_group, 'mchat_text_button');

    }

    /**
     * Formulário de configurações administrativo
     */
    public function mchat_form_setup()
    {
        if (!current_user_can('administrator'))
            wp_die('Boa tentativa! Agora chame o administrador.');

        # DADOS ATUAIS
        $curr_phone_number      = get_option('mchat_phone_number');
        $curr_contact_message   = get_option('mchat_contact_message');
        $curr_screen_position   = get_option('mchat_screen_position');
        $curr_custom_icon       = get_option('mchat_custom_icon');
        $curr_tooltip           = get_option('mchat_tooltip');
        $curr_popup_message     = get_option('mchat_popup_message');
        $curr_text_button       = get_option('mchat_text_button');
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
                            <label for="mchat_phone_number"><?php _e('Número WhatsApp', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="phone" id="mchat_phone_number" name="mchat_phone_number" class="" placeholder="Ex: 00 00000-0000" value="<?php echo (!empty($curr_phone_number) ? $curr_phone_number : ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_contact_message"><?php _e('Mensagem de contato', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mchat_contact_message" name="mchat_contact_message" class="" placeholder="Olá, estou ancioso(a) pelo atendimento." value="<?php echo (!empty($curr_contact_message) ? $curr_contact_message : ''); ?>">
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
                            <input type="text" id="mchat_tooltip" name="mchat_tooltip" class="" placeholder="Precisa de ajuda?" value="<?php echo (!empty($curr_tooltip) ? $curr_tooltip : ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_popup_message"><?php _e('Texto inicial da conversa', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mchat_popup_message" name="mchat_popup_message" class="" placeholder="Ex: Olá, estamos aqui para te ajudar. O que você precisa?" value="<?php echo (!empty($curr_popup_message) ? $curr_popup_message : ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mchat_text_button"><?php _e('Texto do botão', 'mchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mchat_text_button" name="mchat_text_button" class="" placeholder="Ex: Iniciar conversa" value="<?php echo (!empty($curr_text_button) ? $curr_text_button : ''); ?>">
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
        $mchat_phone_number      = get_option('mchat_phone_number');
        $mchat_contact_message   = get_option('mchat_contact_message');
        $mchat_screen_position   = get_option('mchat_screen_position');
        $mchat_custom_icon       = get_option('mchat_custom_icon');
        $mchat_tooltip           = get_option('mchat_tooltip');
        $mchat_popup_message     = get_option('mchat_popup_message');
        $mchat_text_button       = get_option('mchat_text_button');

        ?>
        <!-- Wrapper chat -->
        <div class="mchatMessenger mchatMessenger--disabled">
            <header class="mchatMessenger__header" role="">
                <h3 class="mchatMessenger__title">Bem vindo!</h3>
                <p class="mchatMessenger__subtitle">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
            </header>

            <section class="mchatMessenger__body">
                <div class="mchatMessenger__messageContainer">
                    <span class="mchatMessenger__messageText"><?php echo $mchat_popup_message; ?></span>
                </div>

                <div class="mchatMessenger__messageContainer">
                    <span class="mchatMessenger__messageText">Ver Catálogo/Produtos</span>
                </div>

                <div class="mchatMessenger__messageContainer">
                    <span class="mchatMessenger__messageText">Atendimento Comercial</span>
                </div>

                <div class="mchatMessenger__messageContainer">
                    <span class="mchatMessenger__messageText">Suporte</span>
                </div>
            </section>

            <footer class="mchatMessenger__footer" role="">
                <div class="mchatMessenger__wrapperInput">
                    <input type="text" id="" class="mchatMessenger__inputText" placeholder="Digite e pressione enter para enviar">
                    <button type="button" id="" class="mchatBtn mchatBtn__send">
                        <span class="mchatIcon mchatIcon__send"></span>
                    </button>
                </div>
            </footer>

        </div>
        <!-- Container elements -->
        <div class="mchatContainer mchatContainer--right">
            <!-- Wrapper tooltips -->
            <span class="mchatTooltip__text"><?php echo $mchat_tooltip ?></span>
            
            <!-- Wrapper buttons open/close -->
            <button type="button" id="" class="mchatBtn mchatBtn__openAndClose">
                <span class="mchatIcon mchatIcon__messenger mchatIcon--enabled"></span>
                <span class="mchatIcon mchatIcon__close mchatIcon--disabled"></span>
            </button>
        </div>
        <?php
    }


    /**
     * Instanciando a função
     */
    public static function instance() {
        if (!isset(self::$instance)) {
          self::$instance = new self();
        }
        return self::$instance;
    }
}
MercuriusChat::instance();