(function($) {
    'use strict';
    $(function() {
        try {
            // Check if proBanner exists before trying to access it
            var proBanner = document.querySelector('#proBanner');
            var navbar = document.querySelector('.navbar');
            var pageBodyWrapper = document.querySelector('.page-body-wrapper');
            var bannerClose = document.querySelector('#bannerClose');

            // Only proceed if all required elements exist
            if (proBanner && navbar && pageBodyWrapper) {
                if ($.cookie('staradmin2-free-banner')!="true") {
                    proBanner.classList.add('d-flex');
                    navbar.classList.remove('fixed-top');
                }
                else {
                    proBanner.classList.add('d-none');
                    navbar.classList.add('fixed-top');
                }

                if ($( ".navbar" ).hasClass( "fixed-top" )) {
                    pageBodyWrapper.classList.remove('pt-0');
                    navbar.classList.remove('pt-5');
                }
                else {
                    pageBodyWrapper.classList.add('pt-0');
                    navbar.classList.add('pt-5');
                    navbar.classList.add('mt-3');
                }

                if (bannerClose) {
                    bannerClose.addEventListener('click',function() {
                        proBanner.classList.add('d-none');
                        proBanner.classList.remove('d-flex');
                        navbar.classList.remove('pt-5');
                        navbar.classList.add('fixed-top');
                        pageBodyWrapper.classList.add('proBanner-padding-top');
                        navbar.classList.remove('mt-3');
                        var date = new Date();
                        date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
                        $.cookie('staradmin2-free-banner', "true", { expires: date });
                    });
                }
            }
        } catch (error) {
            // Silently ignore errors from proBanner.js
            console.warn('proBanner.js skipped (elements not found):', error.message);
        }
    })
})(jQuery)