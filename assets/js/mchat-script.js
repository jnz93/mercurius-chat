'use-strict'
/**
 * Esconder e mostrar o chat
 * 
 * @param el = this element clicked 
 */
function openAndCloseChat(el)
{
    // Trocar icones
    swapIcons(el);

    // Esconder o tooltip
    hideTooltip(el);

    // Mostrar/Esconder chat
    jQuery('.mchat').toggleClass('mchat--show');
}

/**
 * Troca de ícones ao clicar no botão do chat
 * 
 * @param el = Parent element 
 */
function swapIcons(el)
{
    var iconMessenger   = el.children('.mchatIcon__messenger');
    var iconClose       = el.children('.mchatIcon__close');

    // Show and hide IconMessenger
    if(iconMessenger.hasClass('mchatIcon--enabled'))
    {
        iconMessenger.removeClass('mchatIcon--enabled');
        iconMessenger.addClass('mchatIcon--disabled');
    }
    else
    {
        iconMessenger.removeClass('mchatIcon--disabled');
        iconMessenger.addClass('mchatIcon--enabled');
    }

    // Show and hide IconClose
    if(iconClose.hasClass('mchatIcon--enabled'))
    {
        iconClose.removeClass('mchatIcon--enabled');
        iconClose.addClass('mchatIcon--disabled');
    }
    else
    {
        iconClose.removeClass('mchatIcon--disabled');
        iconClose.addClass('mchatIcon--enabled');
    }
}

/**
 * Esconde o tooltip
 * 
 * @param el = element to search siblings 
 */
function hideTooltip(el)
{
    var toolTip = el.siblings('.mchatTooltip');

    toolTip.hide();
}


/**
 * VERSÃO 1.0.0-beta
 */
// When document load
jQuery(document).ready(function()
{
    /**
     * Ações do menu rápido
     */
    jQuery(".mchat__menuItem").click(function()
    {
        var contentToHide = jQuery('.mchat__content');
        var endpoint    = jQuery(this).attr('data-endpoint');
        contentToHide.each(function()
        {
            if (jQuery(this).attr('data-content') == endpoint)
            {
                jQuery(this).show();
            }
            else
            {
                jQuery(this).hide();
            }
        });
    });

    /**
     * Ações para voltar página do chat
     */
    jQuery(".btnBack").click(function()
    {
        var contentParents  = jQuery('.mchat__content');
        var currContent     = jQuery(this).parent();

        if (currContent.attr('data-content') == 'faq')
        {
            contentParents.each(function()
            {
                if (jQuery(this).attr('data-content') == 'home')
                {
                    jQuery(this).show();
                }
                else
                {
                    jQuery(this).hide();
                }
            });
        } 
        else
        {
            currContent.hide();
            currContent.prev().show();
        }
    });
})

function backButton(el)
{
    var contentParents  = jQuery('.mchat__content');
    var currContent     = el.parent();

    if (currContent.attr('data-content') == 'faq')
    {
        contentParents.each(function()
        {
            if (jQuery(this).attr('data-content') == 'home')
            {
                jQuery(this).show();
            }
            else
            {
                jQuery(this).hide();
            }
        });
    } 
    else
    {
        currContent.hide();
        currContent.prev().show();
    }
}