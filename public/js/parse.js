var applocation = {
    run: function () {
        this.checkArrs.init();
    },
    checkArrs:{
        content: null,
        init: function () {
            var form = jQuery('#post');
            var _this = this;

            form.on('submit', function(event){

                _this.content = jQuery("textarea#content").val();
                var counter = 0;
                var validated_host_urls= [];
                var min_quatity_of_links=parseInt(parse_vars.min_quatity_of_links);
                var urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;

                var urls = _this.content.match(urlRegex);

                if (Array.isArray(urls)) {
                    urls.forEach(function (url) {
                        if (parse_vars.domain.localeCompare(_this.parse_url(url).hostname) == 0) {
                            validated_host_urls.push(url);
                        }
                    });

                    counter = validated_host_urls.length;

                    if (counter>=min_quatity_of_links) {

                        jQuery.ajax({
                            type: "POST",
                            async: false,
                            url: parse_vars.admin_url,
                            data: {
                                validated_host_urls: validated_host_urls,
                                action: "check_parse",
                                nonce: parse_vars.nonce
                            },
                            timeout: 3000,
                            success: function(count){
                                if (parseInt(count)<min_quatity_of_links){
                                    event.preventDefault();
                                }
                            },
                            error: function () {
                                alert('failed check');
                            }
                        });
                    } else {
                        event.preventDefault();
                        _this.render_message_block(min_quatity_of_links);
                    }
                } else {
                    event.preventDefault();
                    _this.render_message_block(min_quatity_of_links);
                }

            });
        },
        parse_url: function (url) {
            var parser = document.createElement('a');
            parser.href = url;
            return parser;
        },
        render_message_block: function (number) {
            var suff_text='';

            if (number == 1){
               suff_text = ' internal11 link is required to publish this page - Add meaningful and useful link to other page on this website to publish the page';
            } else {
                suff_text = ' internal links are required to publish this page - Add meaningful and useful links to other pages on this website to publish the page';
            }
            var text_info = number + suff_text;
            if(jQuery("#IL_error_info").length == 0) {
                jQuery("<p id='IL_error_info'>"+ text_info +"</p>").insertAfter("#submitdiv");
            }
        }
    }
};

jQuery(document).ready(function () {
    applocation.run();
});