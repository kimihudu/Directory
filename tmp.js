jQuery('.list > li a').click(function ($) {
    jQuery(this).parent().children('ul').toggle()
});

jQuery('.animated-icon1').click(function () {
    var self = jQuery(this);
    self.toggleClass('open');
    jQuery('#mega-hamburger').toggleClass('open');
    jQuery('.tab-bar').toggleClass('open');
    if (self.hasClass('open')) {
        jQuery('html, body')
            .css('overflow','hidden');
            // Needed to remove previously bound handlers
            // .off('touchstart touchmove')
            // .on('touchstart touchmove', function (e) {
            //     e.preventDefault();
            // });
    } else {
        jQuery('html, body')
            .css('overflow','auto');
    //         .off('touchstart touchmove')
    //         .on('touchstart touchmove', function (e) { });
    }
});

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        /* Toggle between adding and removing the "active" class,
        to highlight the button that controls the panel */
        this.classList.toggle("active");

        /* Toggle between hiding and showing the active panel */
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}