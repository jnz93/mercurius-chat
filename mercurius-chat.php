<?php 
/**
 * Plugin name: Mercurius Chat
 * Plugin URI: 
 * Description: Entregamos mensagens via whatsapp e lhe ajudamos no atendimento, vendas e engajamento.
 * Version: Alfa
 * Author: JA93
 * Author URI: http://unitycode.tech
 * Text domain: mchat
 * License: 
*/

if (!defined('ABSPATH')) :
    exit;
endif;

CONST TEXT_DOMAIN   = 'mchat';
CONST PREFIX        = 'mchat_';
// CONST PLUGIN_PATH   = $path; 

include_once( plugin_dir_path(__FILE__) . 'entities/MercuriusChat.php');