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


        // Render chat front-end
        add_action('wp_footer', array($this, 'mchat_render_plugin'));
    }

    /**
     * Enfileirar scripts e estilos
     */
    public function mchat_enqueue_scripts()
    {
        // Add Enfileiramento de scripts
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
        require_once(plugin_dir_path(__FILE__) . '../Views/admin/home-page.php');
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
                            <input type="url" id="mchat_tooltip" name="mchat_tooltip" class="" placeholder="Precisa de ajuda?" value="<?php echo (!empty($curr_tooltip) ? $curr_tooltip : ''); ?>">
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
        <!-- Container app -->
        <div class="">
            <!-- Wrapper chat -->
            <div class="">
                <header class="" role="">
                    <h3 class="">Bem vindo!</h3>
                    <p class="">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
                </header>

                <section class="">
                    <div class="">
                        <span class=""><?php echo $mchat_contact_message; ?></span>
                    </div>

                    <div class="">
                        <span class="">Ver Catálogo/Produtos</span>
                    </div>

                    <div class="">
                        <span class="">Atendimento Comercial</span>
                    </div>

                    <div class="">
                        <span class="">Suporte</span>
                    </div>
                </section>

                <footer class="" role="">
                    <div class="">
                        <input type="text" id="" class="" placeholder="Digite e pressione enter para enviar">
                        <button type="button" id="" class="mchat__button">
                            <span clas="mchat__icon mchat__icon--send"></span>
                            <span class=""><?php echo $mchat_text_button ?></span>
                        </button>
                    </div>
                </footer>

            </div>

            <!-- Wrapper tooltips -->
            <div class="">
                <span class="mchat__tooltip"><?php echo $mchat_tooltip ?></span>
            </div>

            <!-- Wrapper buttons open/close -->
            <div class="">
                <button type="button" id="" class="mchat__button">
                    <span class="mchat__icon mchat__icon--messenger"></span>
                    <span class="mchat__icon mchat__icon--close"></span>
                </button>
            </div>
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