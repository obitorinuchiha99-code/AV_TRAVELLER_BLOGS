(function ($) {
  'use strict';

  let unavailableDates = [];

  const dateDiffDays = (start, end) => {
    if (!start || !end) return 0;
    const startDate = new Date(start);
    const endDate = new Date(end);
    const diff = Math.ceil((endDate - startDate) / 86400000) + 1;
    return Number.isFinite(diff) && diff > 0 ? diff : 0;
  };

  const updateCost = () => {
    const selected = $('#carId option:selected');
    const price = Number(selected.data('price') || 0);
    const days = dateDiffDays($('#pickupDate').val(), $('#returnDate').val());
    const driver = $('#driverRequired').val() === '1' ? 800 * Math.max(days, 1) : 0;
    const total = price && days ? (price * days) + driver : 0;
    $('#costEstimate').text(total ? `₹${total.toLocaleString('en-IN')} for ${days} day${days > 1 ? 's' : ''}` : 'Select car and dates');
  };

  const loadUnavailableDates = () => {
    const carId = $('#carId').val();
    if (!carId) {
      unavailableDates = [];
      return;
    }
    $.getJSON('api/availability.php', { car_id: carId })
      .done((response) => {
        unavailableDates = response.dates || [];
        if (window.pickupPicker) window.pickupPicker.set('disable', unavailableDates);
        if (window.returnPicker) window.returnPicker.set('disable', unavailableDates);
      });
  };

  const initCalendars = () => {
    if (!window.flatpickr) return;
    const common = {
      minDate: 'today',
      dateFormat: 'Y-m-d',
      disable: unavailableDates,
      onChange: updateCost
    };
    window.pickupPicker = flatpickr('#pickupDate', common);
    window.returnPicker = flatpickr('#returnDate', common);
  };

  const openRazorpay = (booking) => {
    if (!window.Razorpay || !booking.razorpay_order_id || booking.razorpay_order_id === 'demo_order') {
      window.location.href = `payment-success.php?booking=${encodeURIComponent(booking.booking_code)}`;
      return;
    }

    const rzp = new Razorpay({
      key: window.AV_CONFIG.razorpayKey,
      amount: booking.amount_paise,
      currency: 'INR',
      name: 'AV Traveller Vlogs',
      description: `Booking ${booking.booking_code}`,
      order_id: booking.razorpay_order_id,
      handler: function (response) {
        $.post('api/payment-verify.php', {
          csrf_token: window.AV_CONFIG.csrf,
          booking_code: booking.booking_code,
          razorpay_payment_id: response.razorpay_payment_id,
          razorpay_order_id: response.razorpay_order_id,
          razorpay_signature: response.razorpay_signature
        }).always(() => {
          window.location.href = `payment-success.php?booking=${encodeURIComponent(booking.booking_code)}`;
        });
      },
      prefill: {
        name: booking.customer_name,
        contact: booking.phone
      },
      theme: { color: '#2f7dff' }
    });
    rzp.open();
  };

  const initBookingForm = () => {
    const form = $('.ajax-booking');
    if (!form.length) return;

    $('#carId').on('change', function () {
      loadUnavailableDates();
      updateCost();
    });
    $('#pickupDate, #returnDate, #driverRequired').on('change input', updateCost);
    $('#paymentMethod').on('change', function () {
      $('#upiPanel').prop('hidden', $(this).val() !== 'upi');
    });

    initCalendars();
    loadUnavailableDates();
    updateCost();

    form.on('submit', function (event) {
      event.preventDefault();
      const status = $('.booking-status');
      const button = form.find('button[type="submit"]');
      status.text('Creating your booking...');
      button.prop('disabled', true);

      $.post(form.attr('action'), form.serialize())
        .done((response) => {
          status.text(response.message || `Booking created: ${response.booking_code}`);
          if (response.whatsapp_url) {
            window.open(response.whatsapp_url, '_blank', 'noopener');
          }
          if ($('#paymentMethod').val() === 'razorpay' && response.payment) {
            openRazorpay(response.payment);
          }
        })
        .fail((xhr) => {
          status.text(xhr.responseJSON?.message || 'Could not create booking. Please call or WhatsApp us.');
        })
        .always(() => button.prop('disabled', false));
    });
  };

  $(initBookingForm);
})(jQuery);
