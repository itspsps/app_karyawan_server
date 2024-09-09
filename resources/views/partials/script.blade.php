<!-- jQuery -->
<script src="{{ url('public/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ url('public/adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ url('public/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ url('public/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ url('public/adminlte/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ url('public/adminlte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ url('public/adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ url('public/adminlte/plugins/moment/moment.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ url('public/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ url('public/adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ url('public/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('public/adminlte/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ url('public/adminlte/dist/js/demo.js') }}"></script> --}}
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ url('public/adminlte/dist/js/pages/dashboard.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ url('public/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ url('public/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ url('https://cdn.jsdelivr.net/npm/flatpickr') }}"></script>
<script src="{{ url('adminlte/plugins/fullcalendar/main.js') }}"></script>

<script>
  $(function() {

    /* initialize the external
  $(function () {

    /* initialize the external events
    -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (https://fullcalendar.io/docs/event-object)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    ini_events($('#external-events div.external-event'))

    /* initialize the calendar
    -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
    m    = date.getMonth(),
    y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');

    // initialize the external events
    // -----------------------------------------------------------------

    new Draggable(containerEl, {
      itemSelector: '.external-event',
      eventData: function(eventEl) {
        return {
          title: eventEl.innerText,
          backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
        };
      }
    });



    var calendar = new Calendar(calendarEl, {
      headerToolbar: {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      themeSystem: 'bootstrap',
      //Random default events
      events: [
        @php
          $tahun_skrg = date('Y');
          $bulan_skrg = date('m');
          $jmlh_bulan = cal_days_in_month(CAL_GREGORIAN,$bulan_skrg,$tahun_skrg);
          $tgl_mulai = date('Y-m-01');
          $tgl_akhir = date('Y-m-'.$jmlh_bulan);
          $data_user = App\Models\User::select('name', 'tgl_lahir')->whereBetween('tgl_lahir', [$tgl_mulai, $tgl_akhir])->get();
        @endphp
        @foreach($data_user as $du)
          @php
            $pecah = explode("-", $du->tgl_lahir)
          @endphp
          {
            title          : 'Ulang Tahun: {{ $du->name }}',
            start          : new Date(y, {{ $pecah[1]-1 }}, {{ $pecah[2] }}),
            backgroundColor: '#00a65a',
            borderColor    : '#00a65a',
            allDay         : true
          },
        @endforeach
        @php
          $data_cuti = App\Models\Cuti::select('user_id', 'tanggal')->where('status_cuti', 'Diterima')->whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->get();
        @endphp
        @foreach($data_cuti as $dc)
          @php
            $pecah2 = explode("-", $dc->tanggal)
          @endphp
          {
            title          : 'Cuti: {{ $dc->User->name }}',
            start          : new Date({{ $pecah2[0] }}, {{ $pecah2[1]-1 }}, {{ $pecah2[2] }}),
            backgroundColor: '#f39c12',
            borderColor    : '#f39c12',
            allDay         : true
          },
        @endforeach
      ],
      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function(info) {
        // is the "remove after drop" checkbox checked?
        if (checkbox.checked) {
          // if so, remove the element from the "Draggable Events" list
          info.draggedEl.parentNode.removeChild(info.draggedEl);
        }
      }
    });


    calendar.render();
    // $('#calendar').fullCalendar()

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    // Color chooser button
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      // Save color
      currColor = $(this).css('color')
      // Add color effect to button
      $('#add-new-event').css({
        'background-color': currColor,
        'border-color'    : currColor
      })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      // Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      // Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.text(val)
      $('#external-events').prepend(event)

      // Add draggable funtionality
      ini_events(event)

      // Remove event from text input
      $('#new-event').val('')
    })
  })
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
<script type="text/javascript">
   
    var i = 0;
       
    $("#add").click(function(){
   
        ++i;
        let id_departemen = $('#id_departemen').val();
        // console.log(id_departemen);
        $.ajax({
              type: 'GET',
              url: "{{url('karyawan/get_divisi')}}",
                data: {
                    id_departemen: id_departemen
                },
                cache: false,

                success: function(msg) {
                  // $('#id_divisi').html(msg);
                  $('#dynamicTable').append('<tr><td><select name="addmore['+i+'][divisi_id]" id="addmore['+i+']id_divisi" class="select_divisi'+i+' form-control" data-live-search="true">'+msg+'</select></td><td><select name="addmore['+i+'][jabatan_id]" id="addmore['+i+']id_jabatan" class="select_jabatan'+i+' form-control" data-live-search="true"></select></td><td><button type="button" class="btn btn-sm btn-danger remove-tr">Remove</button></td></tr>');
                  var get = 'addmore['+i+']id_divisi';
                },
                error: function(data) {
                  console.log('error:', data)
                },
                
              })
              
              // return get;
           
            // console.log('addmore[1]id_divisi');
            $(document).on('change', '.select_divisi'+i+'', function() {
              console.log('OK');
            let id_divisi = $(this).val();
            console.log(id_divisi);
            $.ajax({
              type: 'GET',
              url: "{{url('karyawan/get_jabatan')}}",
              data: {
                id_divisi: id_divisi
              },
              cache: false,
              
              success: function(msg) {
                // console.log(msg);
                $('.select_jabatan'+i+'').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
    $(document).on('click', '.remove-tr', function(){  
         $(this).parents('tr').remove();
    });  
  });
   
</script>
<script>
  $(function () {

    $("#tableprint").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      // "buttons": ["excel", "pdf", "print"]
      // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#tableprint_wrapper .col-md-6:eq(0)');

    $("#tableprintrekap").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["excel", "print"]
      // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#tableprintrekap_wrapper .col-md-6:eq(0)');



  });

        $(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        })

        $(function(){
          $('#id_departemen').on('change', function() {
            let id_departemen = $('#id_departemen').val();
            // console.log(id_departemen);
            $.ajax({
              type: 'GET',
              url: "{{url('karyawan/get_divisi')}}",
                data: {
                    id_departemen: id_departemen
                },
                cache: false,

                success: function(msg) {
                  // console.log(msg);
                  // $('#id_divisi').html(msg);
                  $('#id_divisi').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },
                
              })
        })
          $('#id_divisi').on('change', function() {
            let id_divisi = $('#id_divisi').val();
            // console.log(id_divisi);
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_jabatan')}}",
                data: {
                    id_divisi: id_divisi
                },
                cache: false,

                success: function(msg) {
                    $('#id_jabatan').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        })
        $(function(){
          $('form').on('submit', function(){
            $(':input[type="submit"]').prop('disabled', true);
          })
        })

        $(function(){
            $('#user_id_ajax').on('change', function(){
                let user_id = $('#user_id_ajax').val();

                $.ajax({
                    type : 'POST',
                    url : "{{ url('/data-cuti/getuserid') }}",
                    data :  {id:user_id},
                    cache : false,
                    success: function(msg){
                        $('#nama_cuti_ajax').html(msg);
                    },
                    error: function(data){
                        console.log('error:' ,data);
                    }
                })
            })
        })
</script>
<script type='text/javascript'>
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
    </script>
