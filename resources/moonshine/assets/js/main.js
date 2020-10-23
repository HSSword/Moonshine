/*
	Radiate by Pixelarity
	pixelarity.com | hello@pixelarity.com
	License: pixelarity.com/license
*/

(function ($) {

    var $window = $(window),
        $body = $('body'),
        $wrapper = $('#wrapper'),
        $banner = $('#banner'),
        $header = $('#header');

    // Breakpoints.
    breakpoints({
        default: ['1681px', null],
        xlarge: ['1281px', '1680px'],
        large: ['981px', '1280px'],
        medium: ['737px', '980px'],
        small: ['481px', '736px'],
        xsmall: ['361px', '480px'],
        xxsmall: [null, '360px']
    });

    // Play initial animations on page load.
    $window.on('load', function () {
        window.setTimeout(function () {
            $body.removeClass('is-preload');
        }, 100);
    });

    // Update scrolly links.
    $('.scrolly').scrolly({
        speed: 1000,
        offset: $header.outerHeight() + 20
    });

    // Header.
    if ($banner.length > 0
        && $header.hasClass('alt')) {

        $window.on('resize', function () {
            $window.trigger('scroll');
        });

        $banner.scrollex({
            bottom: $header.outerHeight(),
            terminate: function () {
                $header.removeClass('alt');
            },
            enter: function () {
                $header.addClass('alt');
                $header.removeClass('reveal');
            },
            leave: function () {
                $header.removeClass('alt');
                $header.addClass('reveal');
            }
        });

    }

    // Menu.
    $('#menu')
        .append('<a href="#menu" class="close"></a>')
        .appendTo($body)
        .panel({
            target: $body,
            visibleClass: 'is-menu-visible',
            delay: 500,
            hideOnClick: true,
            hideOnSwipe: true,
            resetScroll: false,
            resetForms: true,
            side: 'right'
        });

    // Gallery.
    $('.gallery')
        .on('click', 'a', function (event) {

            var $a = $(this),
                $gallery = $a.parents('.gallery'),
                $modal = $gallery.children('.modal'),
                $modalImg = $modal.find('img'),
                href = $a.attr('href');

            // Not an image? Bail.
            if (!href.match(/\.(jpg|gif|png|mp4)$/))
                return;

            // Prevent default.
            event.preventDefault();
            event.stopPropagation();

            // Locked? Bail.
            if ($modal[0]._locked)
                return;

            // Lock.
            $modal[0]._locked = true;

            // Set src.
            $modalImg.attr('src', href);

            // Set visible.
            $modal.addClass('visible');

            // Focus.
            $modal.focus();

            // Delay.
            setTimeout(function () {

                // Unlock.
                $modal[0]._locked = false;

            }, 600);

        })
        .on('click', '.modal', function (event) {

            var $modal = $(this),
                $modalImg = $modal.find('img');

            // Locked? Bail.
            if ($modal[0]._locked)
                return;

            // Already hidden? Bail.
            if (!$modal.hasClass('visible'))
                return;

            // Stop propagation.
            event.stopPropagation();

            // Lock.
            $modal[0]._locked = true;

            // Clear visible, loaded.
            $modal
                .removeClass('loaded');

            // Delay.
            setTimeout(function () {

                $modal
                    .removeClass('visible');

                // Pause scroll zone.
                $wrapper.triggerHandler('---pauseScrollZone');

                setTimeout(function () {

                    // Clear src.
                    $modalImg.attr('src', '');

                    // Unlock.
                    $modal[0]._locked = false;

                    // Focus.
                    $body.focus();

                }, 475);

            }, 125);

        })
        .on('keypress', '.modal', function (event) {

            var $modal = $(this);

            // Escape? Hide modal.
            if (event.keyCode == 27)
                $modal.trigger('click');

        })
        .on('mouseup mousedown mousemove', '.modal', function (event) {

            // Stop propagation.
            event.stopPropagation();

        })
        .prepend('<div class="modal" tabIndex="-1"><div class="inner"><img src="" /></div></div>')
        .find('img')
        .on('load', function (event) {

            var $modalImg = $(this),
                $modal = $modalImg.parents('.modal');

            setTimeout(function () {

                // No longer visible? Bail.
                if (!$modal.hasClass('visible'))
                    return;

                // Set loaded.
                $modal.addClass('loaded');

            }, 275);

        });

})(jQuery);