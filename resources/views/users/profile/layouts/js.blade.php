<script src="{{ asset('assets/assets_users/index.js') }}"></script>
<script src="{{ asset('assets/assets_users/js/jquery.js') }}"></script>
<script src="{{ asset('assets/assets_users/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/assets_users/js/settings.js') }}"></script>
<script src="{{ asset('assets/assets_users/js/custom.js') }}"></script>
<script src="{{ asset('assets/assets_users/js/dz.carousel.js') }}"></script><!-- Swiper -->
<script src="{{ asset('assets/assets_users/vendor/swiper/swiper-bundle.min.js') }}"></script><!-- Swiper -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<!-- Datatable -->


<script>
  $(function() {

    var today = moment().format('YYYY-MM-DD');
    var month = moment().format('MM');
    var day = moment().format('D');
    var year = moment().format('YYYY');

    $('.month').val(month);
    // $('.month option:lt(' + month + ')').prop('disabled', true);

  });
</script>