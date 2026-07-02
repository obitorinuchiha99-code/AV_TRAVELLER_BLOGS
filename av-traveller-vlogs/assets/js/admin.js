(function ($) {
  'use strict';

  $(function () {
    $('#adminMenu').on('click', function () {
      $('.admin-sidebar').toggleClass('open');
    });

    $('[data-confirm]').on('click', function (event) {
      if (!confirm($(this).data('confirm'))) {
        event.preventDefault();
      }
    });

    $('.auto-slug-source').on('input', function () {
      const target = $($(this).data('slugTarget'));
      if (!target.length || target.data('dirty')) return;
      const slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
      target.val(slug);
    });

    $('.auto-slug-target').on('input', function () {
      $(this).data('dirty', true);
    });
  });
})(jQuery);
