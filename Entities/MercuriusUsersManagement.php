<?php
/**
 *  Class MercuriusUsersMagement
 */
class MercuriusUsersManagement
{
    /**
     * O campo para edição nas telas
     * 
     * @param $user WP_user object
     */
    public function mchat_usermeta_form_fields($user)
    {
        $curr_phone_user = get_user_option('mchat_phone_user', $user->ID);
        ?>
        <table class="form-table">
            <tr>
                <th>
                    <label for="mchat_phone_user">Telefone/Whatsapp</label> <span class="description">(obrigatório)</span>
                </th>
                <td>
                    <input type="text" id="id_mchat_phone_user" class="" name="mchat_phone_user" value="<?php echo (!empty($curr_phone_user) ? $curr_phone_user : '') ?>" required>
                    <p class="description">
                        Insira o número do telefone do usuário
                    </p>
                </td>
            </tr>
        </table>

        <?php
    }

    /**
     * Salvamento dos custom meta-campos de usuário
     * 
     * @param $user_id Int user id
     */
    public function mchat_usermeta_save_fields($user_id)
    {
        if (!current_user_can('manage_options'))
            return false;

        # save custom field
        $arr_custom_fields = array(
            'mchat_phone_user',
        );

        foreach ($arr_custom_fields as $field):
            update_user_meta($user_id, $field, $_POST[$field]);
        endforeach;
    }
}