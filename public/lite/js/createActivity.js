var CreateActivity = {
    addToCollection: function () {
        $('.add-to-collection').on('click', function (e) {
            e.preventDefault();
            var source = $(this).attr('data-collection');
            var collection = $('.' + source + '-container');
            var parentContainer = $('.' + source);
            var count = $('.' + source + '> div.form-group').length;
            var proto = collection.data('prototype').replace(/__NAME__/g, count);

            if (source == "funding_organisations" || source == "implementing_organisations") {
                $(parentContainer).append(proto);
            } else {
                proto = $(proto).addClass('added-new-block');
                $(parentContainer).append(proto);
            }

            $('form select').select2();
            $("[type='date']").attr('type', 'text').addClass('datepicker').datetimepicker({timepicker: false, format: 'Y-m-d'});
        });
    },
    scroll: function () {
        $(document).ready(function () {
            /**
             * This part does the "fixed navigation after scroll" functionality
             * We use the jQuery function scroll() to recalculate our variables as the
             * page is scrolled/
             */
            $(window).scroll(function () {
                var window_top = $(window).scrollTop() + 20; // the "12" should equal the margin-top value for nav.stick
                var div_top = $('#nav-anchor').offset().top;
                if (window_top > div_top) {
                    $('.panel__nav nav').addClass('stick');
                } else {
                    $('.panel__nav nav').removeClass('stick');
                }
            });

            /**
             * This part causes smooth scrolling using scrollto.js
             * We target all a tags inside the nav, and apply the scrollto.js to it.
             */
            $(".panel__nav nav a").click(function (evn) {
                evn.preventDefault();
                $('html,body').scrollTo(this.hash, this.hash);
            });

            /**
             * This part handles the highlighting functionality.
             * We use the scroll functionality again, some array creation and
             * manipulation, class adding and class removing, and conditional testing
             */
            var aChildren = $(".panel__nav nav li").children(); // find the a children of the list items
            var aArray = []; // create the empty aArray
            for (var i = 0; i < aChildren.length; i++) {
                var aChild = aChildren[i];
                var ahref = $(aChild).attr('href');
                aArray.push(ahref);
            } // this for loop fills the aArray with attribute href values

            $(window).scroll(function () {
                var windowPos = $(window).scrollTop(); // get the offset of the window from the top of page
                var windowHeight = $(window).height(); // get the height of the window
                var docHeight = $(document).height();

                for (var i = 0; i < aArray.length; i++) {
                    var theID = aArray[i];
                    var divPos = $(theID).offset().top - 54; // get the offset of the div from the top of page
                    var divHeight = $(theID).height(); // get the height of the div in question
                    if (windowPos >= divPos && windowPos < (divPos + divHeight)) {
                        $("a[href='" + theID + "']").addClass("active");
                    } else {
                        $("a[href='" + theID + "']").removeClass("active");
                    }
                }

                if (windowPos + windowHeight == docHeight) {
                    if (!$(".panel__nav nav li:last-child a").hasClass("active")) {
                        var navActiveCurrent = $(".active").attr("href");
                        $("a[href='" + navActiveCurrent + "']").removeClass("active");
                        $(".panel__nav nav li:last-child a").addClass("active");
                    }
                }
            });
        });
    },
    formCollection: function () {
        $(document).ready(function () {
            var separator = $('.collection_form.separator');
            separator.each(function () {
                $(this).children('div:not(:first)').addClass('added-new-block');
            });
        });
    },
    editTextArea: function (model) {
        if (model) {
            $("textarea").css('height', '79px');
        }
    },
    changeRegionAndDistrict: function () {
        $('.region').on('change', function () {
            var region = $(this).val();

            console.log($("[aria-label='Arusha']"));
        })
    }
};

CreateActivity.formCollection();
