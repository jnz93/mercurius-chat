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
        
        # MENU
        register_setting($option_group, 'mchat_menu_title');
        register_setting($option_group, 'mchat_menu_option_1');
        register_setting($option_group, 'mchat_menu_endpoint_1');
        register_setting($option_group, 'mchat_menu_option_2');
        register_setting($option_group, 'mchat_menu_endpoint_2');
        register_setting($option_group, 'mchat_menu_option_3');
        register_setting($option_group, 'mchat_menu_endpoint_3');

        # PAGES
        register_setting($option_group, 'mchat_page_services_title');
        register_setting($option_group, 'mchat_page_services_description');
        register_setting($option_group, 'mchat_page_faq_title');
        register_setting($option_group, 'mchat_page_faq_description');

        # Contact
        register_setting($option_group, 'mchat_contact_message');

        # Another settings
        register_setting($option_group, 'mchat_business_hours');
        register_setting($option_group, 'mchat_screen_position');
        register_setting($option_group, 'mchat_custom_icon');
        register_setting($option_group, 'mchat_tooltip');
        register_setting($option_group, 'mchat_text_button');
    }
}
