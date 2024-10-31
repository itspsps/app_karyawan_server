<script src="{{ asset('assets/assets_users/index.js') }}"></script>
<script src="{{ asset('assets/assets_users/js/jquery.js') }}"></script>
<script src="{{ asset('assets/assets_users/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/assets_users/js/settings.js') }}"></script>
<script src="{{ asset('assets/assets_users/js/custom.js') }}"></script>
<script src="{{ asset('assets/assets_users/js/dz.carousel.js') }}"></script><!-- Swiper -->
<script src="{{ asset('assets/assets_users/vendor/swiper/swiper-bundle.min.js') }}"></script><!-- Swiper -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<!-- Datatable -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>


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
<script type="text/javascript">
  $(document).ready(function() {
    load_data();

    function load_data(filter_month = '') {
      console.log(filter_month);
      var table1 = $('#datatableHome').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        "bPaginate": false,
        searching: false,
        ajax: {
          url: "{{ route('datatableHome') }}",
          data: {
            filter_month: filter_month,
          }
        },
        columns: [{
            data: 'id',
            render: function(data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
            }
          },
          {
            data: 'tanggal_masuk',
            name: 'tanggal_masuk'
          },
          {
            data: 'jam_absen',
            name: 'jam_absen'
          },
          {
            data: 'jam_pulang',
            name: 'jam_pulang'
          },
        ],
        order: [
          [1, 'desc']
        ]
      });
    }

    function load_absensi(filter_month = '') {
      $.ajax({
        url: "{{route('get_count_absensi_home')}}",
        data: {
          filter_month: filter_month,
        },
        type: "GET",
        error: function() {
          alert('Something is wrong');
        },
        success: function(data) {
          $('#count_absen_hadir').html(data);
          console.log(data)
        }
      });
    }
    $('#month').change(function() {
      filter_month = $(this).val();
      console.log(filter_month);
      $('#datatableHome').DataTable().destroy();
      load_data(filter_month);
      load_absensi(filter_month);


    })
  });
</script>





{{-- selectpicker --}}
<script src="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js') }}"></script>

<script>
  getLocation();

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      x.innerHTML = "Geolocation is not supported by this browser.";
    }
  }

  function showPosition(position) {
    //   x.innerHTML = "Latitude: " + position.coords.latitude +
    //   "<br>Longitude: " + position.coords.longitude;
    $('#lat').val(position.coords.latitude);
    $('#lat2').val(position.coords.latitude);
    $('#long').val(position.coords.longitude);
    $('#long2').val(position.coords.longitude);
  }
</script>

<script>
  config = {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
  }

  flatpickr("input[type=datetime-local]", config)
  flatpickr("input[type=datetime]", {})
</script>

<script>
  $(function() {

    $("#tableprint").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      // "buttons": ["excel", "pdf", "print"]
      // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#tableprint_wrapper .col-md-6:eq(0)');

    $("#tableprintrekap").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["excel", "print"]
      // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#tableprintrekap_wrapper .col-md-6:eq(0)');



  });

  $(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  })

  $(function() {
    $('form').on('submit', function() {
      $(':input[type="submit"]').prop('disabled', true);
    })
  })

  $(function() {
    $('#user_id_ajax').on('change', function() {
      let user_id = $('#user_id_ajax').val();

      $.ajax({
        type: 'POST',
        url: "{{ url('/data-cuti/getuserid') }}",
        data: {
          id: user_id
        },
        cache: false,
        success: function(msg) {
          $('#nama_cuti_ajax').html(msg);
        },
        error: function(data) {
          console.log('error:', data);
        }
      })
    })
  })
</script>
{{-- <script type='text/javascript'>
      var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
          'November', 'Desember'
      ];
      var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
      var date = new Date();
      var day = date.getDate();
      var month = date.getMonth();
      var thisDay = date.getDay(),
          thisDay = myDays[thisDay];
      var yy = date.getYear();
      var year = (yy < 1000) ? yy + 1900 : yy;
      document.getElementById("tanggal").innerHTML = thisDay + ', ' + day + ' ' + months[month] + ' ' + year;
      </script>
      <script type="text/javascript">
      // 1 detik = 1000
      window.setTimeout("waktu()", 1000);

      function waktu() {
          var tanggal = new Date();
          var minutes = tanggal.getMinutes() < 10 ? '0' + tanggal.getMinutes() : tanggal.getMinutes();
          var seconds = tanggal.getSeconds() < 10 ? '0' + tanggal.getSeconds() : tanggal.getSeconds();
          setTimeout("waktu()", 1000);
          document.getElementById("jam").innerHTML = tanggal.getHours() + ":" + minutes + ":" + seconds + " ";
      }
      </script> --}}