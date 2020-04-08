'use-strict'
// When document load
jQuery(document).ready(function()
{
    // console.log("Olá mundo");
})

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
    jQuery('#mchatMessenger').toggleClass('mchatMessenger--enabled');
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