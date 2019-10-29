(function ($) {
    function debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this,
                args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    $(document).ready(function () {
        $('iframe').not('.wf-responsive-no-container, .wf-responsive-off').filter(function() {
            if (/(dai\.?ly(motion)?|youtu(\.)?be|vimeo\.com)/i.test(this.src)) {
                return true;
            }
        }).css('max-width', function () {
            var mw = this.width;

            if (mw) {
                if (/[\D]/.test(mw)) {
                    return mw;
                }

                return mw + 'px';
            }

            return '';
        }).css('height', function() {
            return Math.floor(this.height * this.offsetWidth / this.width)  + 'px';
        }).addClass('wf-responsive-iframe');

        $('video, audio, object, embed').not('.wf-responsive-no-container, .wf-responsive-off').addClass('wf-responsive');

        var resize = debounce(function () {
            $('iframe').css('height', function () {
                return Math.floor(this.height * this.offsetWidth / this.width)  + 'px';
            });
        }, 64);

        $(window).on('resize.wf, orientationchange.wf', resize);
    });

})(jQuery);