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
}