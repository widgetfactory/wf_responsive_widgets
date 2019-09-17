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
        $('.wf-responsive-container').css('max-width', function () {
            var mw = this.firstChild.width;

            if (mw) {
                if (/[\D]/.test(mw)) {
                    return mw;
                }

                return mw + 'px';
            }

            return '';
        });

        $('.wf-responsive-container iframe').css('height', function() {
            return Math.floor(this.height * this.parentNode.offsetWidth / this.width)  + 'px';
        });

        var resize = debounce(function () {
            $('.wf-responsive-container iframe').css('height', function () {
                return Math.floor(this.height * this.parentNode.offsetWidth / this.width)  + 'px';
            });
        }, 64);

        $(window).on('resize.wf, orientationchange.wf', resize);
    });

})(jQuery);