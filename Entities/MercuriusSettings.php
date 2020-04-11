<?php
/**
 * Class MercuriusSettings
 * -> registro das configurações/options
 * -> 
 */
class MercuriusSettings{


    /**
     * Configurações do plugin
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
}
