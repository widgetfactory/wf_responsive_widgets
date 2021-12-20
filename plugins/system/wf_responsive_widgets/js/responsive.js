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

    var button = '<div role="presentation" aria-label="Click to play" class="wf-responsive-iframe-poster-play"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="39" height="39" viewBox="0 0 512 512"><title>Click to play</title><path d="M256 0c-141.385 0-256 114.615-256 256s114.615 256 256 256 256-114.615 256-256-114.615-256-256-256zM256 464c-114.875 0-208-93.125-208-208s93.125-208 208-208 208 93.125 208 208-93.125 208-208 208zM192 144l192 112-192 112z"></path></svg></div>';

    $(document).ready(function () {
        $('iframe, video, object, embed', 'span.wf-responsive-container').each(function () {
            if ($(this.parentNode).hasClass('wf-responsive-full')) {
                return true;
            }

            $(this).addClass('wf-responsive');

            if (this.nodeName == 'IFRAME') {
                $(this).filter(function () {
                    if (/(dai\.?ly(motion)?|youtu(\.)?be|vimeo\.com|google\.com\/maps\/embed)/i.test(this.src)) {
                        return true;
                    }
                }).addClass('wf-responsive-iframe');
            }

            if (this.nodeName == 'VIDEO') {
                $(this).on('loadedmetadata', function (e) {
                    if (this.videoWidth && this.videoHeight) {
                        var ratio = this.videoWidth / this.videoHeight;

                        if (ratio !== 16 / 9) {
                            $(this).parent().css('--aspect-ratio', Math.floor(ratio));
                        }
                    }
                });
            }
        });

        $('.wf-responsive-iframe-poster > iframe[data-poster]').each(function () {
            var ifr = this, html = $(this).parent().html();

            $(this).parent().css({
                'background-image': 'url("' + $(ifr).data('poster') + '")'
            }).on('click', function () {
                $(this).prepend(html);
                $(this).css('background-image', 'none').removeClass('wf-responsive-iframe-poster');
            }).append(button);

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
