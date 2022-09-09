(function ($, Drupal) {
   Drupal.behaviors.productdetail = {
    attach: function (context, settings) {
       
        jQuery(".qr-code-left").find(".field--name-field-product-image-up img").removeAttr("width").removeAttr("height");

    }
  };

})(jQuery, Drupal);