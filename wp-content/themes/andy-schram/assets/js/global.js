function PrevFounder(){
    jQuery(".founder-slider .owl-nav .owl-prev").click()
}

function NextFounder(){
    jQuery(".founder-slider .owl-nav .owl-next").click()
}
jQuery(document).ready(function($) { 
        var header = $('.sticky-header');
        var sticky = header.offset().top;
       
        $(window).scroll(function() {
            if (window.pageYOffset > sticky) {
                header.addClass('fixed');
            } else {
                 header.removeClass('fixed');
            }
        });
    

    $(document).on('click', '.view-bio', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var urls = $(this).attr('href') ? $(this).attr('href').split('/') : $(this).find('a').attr('href').split('/');
        var post_slug = urls[urls.length - 2];

        $.ajax({
            url: frontendajax.ajaxurl,
            type: 'POST',
            data: {
                action: 'fetch_founder_data',
                post_slug: post_slug
            },
            success: function(response) {
                if (response.success) {
                    $('#founder-content').html(response.data);
                    var positionNumber = $('#founder-content input[name="sliderPosition"]').val() || 0;
                    $('#founder-popup').modal('show');
                        $('.founder-slider').owlCarousel({
                            items:1,
                            startPosition: positionNumber,
                            loop:false,
                            animateOut: 'fadeOut',
                            animateIn: 'fadeIn'
                        });
                } else {
                    console.log('Error:', response.data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('AJAX error:', textStatus, errorThrown);
            }
        });
    });
});
