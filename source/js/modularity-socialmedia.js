var ModularitySocialMedia = {};

ModularitySocialMedia = ModularitySocialMedia || {};

ModularitySocialMedia.ToggleVisiblility = (function ($) {

    function ToggleVisiblility() {
        this.handleEvents();
    }

    /**
     * Handle events
     * @return {void}
     */
    ToggleVisiblility.prototype.handleEvents = function() {
        $(document).on('click', '.js-mod-socialmedia-toggle-visibility', function (e) {
            e.preventDefault();

            $(this).toggleClass("is-hidden");

            var data = {
                'action': 'mod_socialmedia_toggle_inlay_visibility',
                'inlay_id': $(this).attr('data-inlay-id'),
                'module_id': $(this).attr('data-module-id'),
            };

            $.post(ajaxurl, data, function(response) {
            });
        });
    };

    return new ToggleVisiblility();

}(jQuery));
