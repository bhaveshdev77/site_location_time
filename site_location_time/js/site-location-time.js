 
(function ($, Drupal) {

    'use strict';
  
    Drupal.behaviors.site_location_time = {
      attach: function (context, settings) {   
        setInterval(resetDateTimeAjaxRequest, 5000);
      }
    };

    var resetDateTimeAjaxRequest = function() {
        $.ajax({
            url: Drupal.url('site-location-time'),
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                $('p.site-location-time-refresh strong').text(response.datetime);
                $('p.site-location-container strong').text(response.city+", "+response.country);
            }
          });
    } 
  
})(jQuery, Drupal);
  