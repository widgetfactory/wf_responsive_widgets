/* eslint-disable consistent-this */
/* global jQuery */
(function ($) {
    function debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this,
                args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) { 
                    func.apply(context, args); 
                }
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) { 
                func.apply(context, args);
            }
        };
    }

    function isIframeMedia(url) {
        var match = false;
        
        // youtube
        if (/youtu(\.)?be(.+)?\/(.+)/.test(url)) {
            match = 'youtube';
        }
        // vimeo
        if (/vimeo(.+)?\/(.+)/.test(url)) {
            match = 'vimeo';
        }
        // Dailymotion
        if (/dai\.?ly(motion)?(\.com)?/.test(url)) {
            match = 'dailymotion';
        }
        // Scribd
        if (/scribd\.com\/(.+)/.test(url)) {
            match = 'scribd';
        }
        // Slideshare
        if (/slideshare\.net\/(.+)\/(.+)/.test(url)) {
            match = 'slideshare';
        }
        // Soundcloud
        if (/soundcloud\.com\/(.+)/.test(url)) {
            match = 'soundcloud';
        }
        // Spotify
        if (/spotify\.com\/(.+)/.test(url)) {
            match = 'spotify';
        }
        // TED
        if (/ted\.com\/talks\/(.+)/.test(url)) {
            match = 'ted';
        }
        // Twitch
        if (/twitch\.tv\/(.+)/.test(url)) {
            match = 'twitch';
        }
        // Google Maps
        if (/google\.com\/maps\/(.+)/.test(url)) {
            match = 'google';
        }

        return match;
    }

    var button = '<div role="presentation" aria-label="Click to play" class="wf-responsive-iframe-poster-play"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="39" height="39" viewBox="0 0 512 512"><title>Click to play</title><path d="M256 0c-141.385 0-256 114.615-256 256s114.615 256 256 256 256-114.615 256-256-114.615-256-256-256zM256 464c-114.875 0-208-93.125-208-208s93.125-208 208-208 208 93.125 208 208-93.125 208-208 208zM192 144l192 112-192 112z"></path></svg></div>';

    $(document).ready(function () {
        $('iframe, object, embed', 'span.wf-responsive-container').each(function () {
            if ($(this.parentNode).add(this).hasClass('wf-responsive-full')) {
                return true;
            }

            $(this).addClass('wf-responsive');

            if (this.nodeName == 'IFRAME') {
                $(this).filter(function () {
                    
                    if (isIframeMedia(this.src) !== false) {
                        return true;
                    }

                }).addClass('wf-responsive-iframe');
            }
        });

        $('.wf-responsive-iframe-poster > iframe').each(function () {
            var ifr = this, html = $(this).parent().html();

            var poster = $(ifr).data('poster') || 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
            var text = $(this).parent().attr('aria-label') || '';
            
            $(this).parent().css({
                'background-image': 'url("' + poster + '")'
            }).on('click', function () {
                $(this).css('background-image', 'none').removeClass('wf-responsive-iframe-poster').empty().append(html);
            }).append(button).find('.wf-responsive-iframe-poster-play').attr('aria-label', function () {
                return text || this.value;
            });

            $(this).parent().not('.wf-responsive-container-full').css({
                'max-width': $(ifr).attr('width') + 'px',
                'height': $(ifr).attr('height')
            });

            $(ifr).remove();
        });

        var resize = debounce(function () {
            $('iframe.wf-responsive-iframe').parent('.wf-responsive-container').not('.wf-responsive-container-full').css('height', function () {
                var ifr = this.firstChild;
                return Math.floor(ifr.height * ifr.offsetWidth / ifr.width) + 'px';
            });
        }, 64);

        $(window).on('resize.wf, orientationchange.wf', resize);

        resize();
    });

})(jQuery);