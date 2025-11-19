<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $("input[type=text]").keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });
    $(document).ready(function() {
        // simpan posisi sebelum reload
        window.addEventListener("beforeunload", function() {
            localStorage.setItem("scrollPos", window.scrollY);
        });

        // balikin posisi setelah reload
        window.addEventListener("load", function() {
            let scrollPos = localStorage.getItem("scrollPos");
            if (scrollPos) {
                window.scrollTo(0, scrollPos);
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            // simpan tab terakhir yang dibuka
            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function(tab) {
                tab.addEventListener("shown.bs.tab", function(e) {
                    localStorage.setItem("activeTab", e.target.getAttribute(
                        "data-bs-target"));
                });
            });

            // balikin tab terakhir setelah reload
            let activeTab = localStorage.getItem("activeTab");
            if (activeTab) {
                let tabTriggerEl = document.querySelector(`button[data-bs-target="${activeTab}"]`);
                if (tabTriggerEl) {
                    new bootstrap.Tab(tabTriggerEl).show();

                    // kalau di tab restore ada input, baru kasih fokus di sini
                    setTimeout(() => {
                        let input = document.querySelector(`${activeTab} input`);
                        if (input) input.focus();
                    }, 300);
                }
            }
        });
        $('#id_provinsi').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Provinsi",
            allowClear: true
        });

        $('#id_kabupaten').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Kabupaten",
            allowClear: true
        });
        $('#id_kecamatan').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Kecamatan",
            allowClear: true
        });
        $('#id_desa').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Desa",
            allowClear: true
        });
        $('#id_provinsi_domisili').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Provinsi Domisili",
            allowClear: true
        });
        $('#id_kabupaten_domisili').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Kabupaten Domisili",
            allowClear: true
        });
        $('#id_kecamatan_domisili').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Kecamatan Domisili",
            allowClear: true
        });
        $('#id_desa_domisili').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Desa Domisili",
            allowClear: true
        });
        $('#penempatan_kerja').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Penempatan Kerja",
            allowClear: true
        });
        $('#approval_site').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Approval Job",
            allowClear: true
        });
        $('#id_departemen').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Departemen",
            allowClear: true
        });
        $('#id_divisi').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Divisi",
            allowClear: true
        });
        $('#id_jabatan').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Jabatan",
            allowClear: true
        });
        $('#id_bagian').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Bagian",
            allowClear: true
        });




        // BUTTON FOTO
        var foto_karyawan = '{{ $karyawan->foto_karyawan }}';
        if (foto_karyawan == null) {
            $('#group-button-foto').empty();
            $('#group-button-foto').append(
                '<button type="button" id="btn_edit_foto" class="btn btn-sm me-2 mb-3"><i class="mdi mdi-pencil-outline text-primary"></i><span class="text-primary">&nbsp;Edit Foto</span></button>'
            );
        } else {
            $('#group-button-foto').empty();
            $('#group-button-foto').append(
                '<button type="button" id="btn_edit_foto" class="btn btn-sm me-2 mb-3"><i class="mdi mdi-pencil-outline text-primary"></i><span class="text-primary">&nbsp;Edit Foto</span></button>'
            );

        }
        $(document).on('click', '#btn_edit_foto', function() {
            $('#foto_karyawan').click();
        });
        $('#foto_karyawan').change(function() {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#template_foto_karyawan').attr('src', e.target.result);
            };
            // $('#group-button-foto').empty();
            $('#group-button-foto')
                .empty()
                .append(
                    '<button type="button" id="btn_edit_foto" class="btn btn-sm me-2 mb-3"><i class="mdi mdi-pencil-outline text-primary"></i><span class="text-primary">&nbsp;Edit Foto</span></button>'
                )
                .append(
                    '<button type="button" id="btn_reset_foto" class="btn btn-sm me-2 mb-3"><i class="mdi mdi-reload text-danger"></i><span class="text-danger">&nbsp;Reset</span></button>'
                );
            reader.readAsDataURL(file);
        });
        $(document).on('click', '#btn_reset_foto', function() {
            $('#foto_karyawan').val('');
            $('#template_foto_karyawan').attr('src',
                '{{ asset('storage/foto_karyawan/default_profil.jpg') }}');
            $('#group-button-foto')
                .empty()
                .append(
                    '<button type="button" id="btn_edit_foto" class="btn btn-sm me-2 mb-3"><i class="mdi mdi-pencil-outline text-primary"></i><span class="text-primary">&nbsp;Edit Foto</span></button>'
                );
        });
        // BUTTON PENDIDIKAN
        $('#btn_add_pendidikan').click(function() {
            $(this).find(':focus').blur(); // lepas focus dari elemen apapun di modal
            $('#nama_instansi').val('');
            $('#jurusan').val('');
            $('#jenjang').val('');
            $('#tahun_masuk').val('');
            $('#tahun_keluar').val('');
            $('#modal_add_pendidikan').modal('show');
        });
        $('#btn_add_keahlian').click(function() {
            $(this).find(':focus').blur(); // lepas focus dari elemen apapun di modal
            $('#nama_keahlian').val('');
            $('#file_keahlian').val('');
            $('.group-button-keahlian').empty();
            $('#modal_add_keahlian').modal('show');
        });
        var table = $('#table_pendidikan').DataTable({
            pageLength: 50,
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "@if (Auth::user()->is_admin == 'hrd') {{ url('hrd/karyawan/pendidikan/' . $karyawan->id) }}@else{{ url('karyawan/pendidikan/' . $karyawan->id) }}@endif",
            },
            columns: [{
                    data: "aksi",
                    name: "aksi"
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'institusi',
                    name: 'institusi'
                },
                {
                    data: 'jenjang',
                    name: 'jenjang'
                },
                {
                    data: 'jurusan',
                    name: 'jurusan'
                },
                {
                    data: 'tanggal_masuk',
                    name: 'tanggal_masuk'
                },
                {
                    data: 'tanggal_keluar',
                    name: 'tanggal_keluar'
                }
            ],
            order: [
                [2, 'asc']
            ]
        });
        var table1 = $('#table_keahlian').DataTable({
            pageLength: 50,
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "@if (Auth::user()->is_admin == 'hrd') {{ url('hrd/karyawan/keahlian/' . $karyawan->id) }}@else{{ url('karyawan/keahlian/' . $karyawan->id) }}@endif",
            },
            columns: [{
                    data: "aksi",
                    name: "aksi"
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'keahlian',
                    name: 'keahlian'
                },
                {
                    data: 'file',
                    name: 'file'
                }
            ],
            order: [
                [2, 'asc']
            ]
        });
        // riwayat
        $('#pesan_disabled').hide();
        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "{{ url('karyawan/button_riwayat/' . $karyawan->id) }}",
                error: function(error) {
                    Swal.fire({
                        title: 'error',
                        text: error.responseJSON.message,
                        icon: 'error',
                        timer: 4500
                    })

                },
                success: function(data_riwayat) {
                    // console.log(data_riwayat);
                    if (data_riwayat.data_riwayat >= 3) {
                        $('#pesan_disabled').show();
                        $('#btn_tambah_riwayat').attr("disabled", true);

                    }
                }
            });
        });
        var table2 = $('#tabel_riwayat').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('karyawan/riwayat/' . $karyawan->id) }}",
            },
            columns: [{
                    data: 'option',
                    name: 'option'
                },
                {
                    data: 'nama_perusahaan',
                    name: 'nama_perusahaan'
                },
                {
                    data: 'alamat_perusahaan',
                    name: 'alamat_perusahaan'
                },
                {
                    data: 'posisi',
                    name: 'posisi'
                }, {
                    data: 'gaji',
                    name: 'gaji'
                },
                {
                    data: 'tanggal_masuk',
                    name: 'tanggal_masuk'
                },
                {
                    data: 'tanggal_keluar',
                    name: 'tanggal_keluar'
                },
                {
                    data: 'alasan_keluar',
                    name: 'alasan_keluar'
                },
                {
                    data: 'surat_keterangan',
                    name: 'surat_keterangan'
                },
                {
                    data: 'nomor_referensi',
                    name: 'nomor_referensi'
                },
                {
                    data: 'jabatan_referensi',
                    name: 'jabatan_referensi'
                }

            ],
        });
        $('#progres-tab').on('shown.bs.tab', function(e) {
            table2.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        $('#btn_tambah_riwayat').click(function() {
            $('#modal_tambah_riwayat').modal('show');
        });
        $(document).on('keyup', '#gaji_add', function(e) {
            var data = $(this).val();
            var hasil = formatRupiah(data, "Rp. ");
            $(this).val(hasil);
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }

        function replace_titik(x) {
            return ((x.replace('.', '')).replace('.', '')).replace('.', '');
        }
        $('#btn_save_riwayat').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            //ambil data dari form
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('id_karyawan', $('#id_karyawan_add').val());
            formData.append('nama_perusahaan', $('#nama_perusahaan_add').val());
            formData.append('alamat_perusahaan', $('#alamat_perusahaan_add').val());
            formData.append('posisi', $('#posisi_add').val());
            formData.append('gaji', replace_titik($('#gaji_add').val()));
            formData.append('tanggal_masuk', $('#tanggal_masuk_add').val());
            formData.append('tanggal_keluar', $('#tanggal_keluar_add').val());
            formData.append('alasan_keluar', $('#alasan_keluar_add').val());
            formData.append('nomor_referensi', $('#nomor_referensi_add').val());
            formData.append('jabatan_referensi', $('#jabatan_referensi_add').val());

            var fileInput = $('#surat_keterangan_add')[0];
            if (fileInput.files.length > 0) {
                formData.append('surat_keterangan', fileInput.files[0]);
            }
            // post
            $.ajax({
                type: "POST",

                url: "{{ url('karyawan/riwayat_post') }}",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Memuat Data...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                error: function() {
                    Swal.close();
                    alert('Something is wrong!');
                    // console.log(formData);
                },
                success: function(data) {
                    Swal.close();
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_tambah_riwayat').modal('hide');
                        $('#nama_perusahaan_add').val('');
                        $('#alamat_perusahaan_add').val('');
                        $('#posisi_add').val('');
                        $('#gaji_add').val('');
                        $('#tanggal_masuk_add').val('');
                        $('#tanggal_keluar_add').val('');
                        $('#alasan_keluar_add').val('');
                        $('#surat_keterangan_add').val('');
                        $('#nomor_referensi_add').val('');
                        $('#jabatan_referensi_add').val('');
                        $('#tabel_riwayat').DataTable().ajax.reload();
                        if (data.data_riwayat >= 3) {
                            $('#btn_tambah_riwayat').attr("disabled", true);
                            $('#pesan_disabled').show();
                        }
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 4500
                        })

                    }
                }
            });
        });
        $(document).on('click', '#btn_edit_riwayat', function() {
            var id_riwayat = $(this).data('id_riwayat');
            var nama_perusahaan = $(this).data('nama_perusahaan');
            var alamat_perusahaan = $(this).data('alamat_perusahaan');
            var posisi = $(this).data('posisi');
            var gaji = $(this).data('gaji');
            var tanggal_masuk = $(this).data('tanggal_masuk');
            var tanggal_keluar = $(this).data('tanggal_keluar');
            var alasan_keluar = $(this).data('alasan_keluar');
            var nomor_referensi = $(this).data('nomor_referensi');
            var jabatan_referensi = $(this).data('jabatan_referensi');
            var old_file = $(this).data('old_file');
            $('#id_riwayat_update').val(id_riwayat);
            $('#nama_perusahaan_update').val(nama_perusahaan);
            $('#alamat_perusahaan_update').val(alamat_perusahaan);
            $('#posisi_update').val(posisi);
            $('#gaji_update').val(gaji);
            $('#tanggal_masuk_update').val(tanggal_masuk);
            $('#tanggal_keluar_update').val(tanggal_keluar);
            $('#alasan_keluar_update').val(alasan_keluar);
            $('#nomor_referensi_update').val(nomor_referensi);
            $('#jabatan_referensi_update').val(jabatan_referensi);
            $('#old_file_update').val(old_file);
            $('#modal_edit_riwayat').modal('show');

        });
        $(document).on('keyup', '#gaji_update', function(e) {
            var data = $(this).val();
            var hasil = formatRupiah(data, "Rp. ");
            $(this).val(hasil);
        });
        $('#btn_update_riwayat').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('id_riwayat', $('#id_riwayat_update').val());
            formData.append('nama_perusahaan', $('#nama_perusahaan_update').val());
            formData.append('alamat_perusahaan', $('#alamat_perusahaan_update').val());
            formData.append('posisi', $('#posisi_update').val());
            formData.append('gaji', replace_titik($('#gaji_update').val()));
            formData.append('tanggal_masuk', $('#tanggal_masuk_update').val());
            formData.append('tanggal_keluar', $('#tanggal_keluar_update').val());
            formData.append('alasan_keluar', $('#alasan_keluar_update').val());
            formData.append('nomor_referensi', $('#nomor_referensi_update').val());
            formData.append('jabatan_referensi', $('#jabatan_referensi_update').val());
            formData.append('old_file', $('#old_file_update').val());
            var fileInput = $('#surat_keterangan_update')[0];
            if (fileInput.files.length > 0) {
                formData.append('surat_keterangan', fileInput.files[0]);
            }
            $.ajax({
                type: "POST",

                url: "{{ url('karyawan/riwayat_update') }}",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Memuat Data...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                error: function() {
                    Swal.close();
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    Swal.close();
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#nama_perusahaan_update').val('');
                        $('#alamat_perusahaan_update').val('');
                        $('#posisi_update').val('');
                        $('#gaji_update').val('');
                        $('#tanggal_masuk_update').val('');
                        $('#tanggal_keluar_update').val('');
                        $('#alasan_keluar_update').val('');
                        $('#surat_keterangan_update').val('');
                        $('#nomor_referensi_update').val('');
                        $('#jabatan_referensi_update').val('');
                        $('#modal_edit_riwayat').modal('hide');
                        $('#tabel_riwayat').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 10000
                        })

                    }
                }

            });
        });
        $(document).on('click', '#btn_delete_riwayat', function() {
            // $('#modal_delete_riwayat').modal('show');
            var id_riwayat = $(this).data('id_riwayat');
            var surat_keterangan = $(this).data('surat_keterangan');
            Swal.fire({
                title: 'Konfirmasi',
                icon: 'warning',
                text: "Apakah benar-benar ingin menghapus data ini?",
                showCancelButton: true,
                inputValue: 0,
                confirmButtonText: 'Yes',
            }).then(function(result) {
                if (result.value) {
                    // console.log(id_riwayat);
                    $.ajax({
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id_riwayat: id_riwayat,
                            surat_keterangan: surat_keterangan,
                        },
                        url: "{{ url('karyawan/delete_riwayat/' . $karyawan->id) }}",
                        type: "POST",
                        dataType: 'json',
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Memuat Data...',
                                html: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal
                                        .showLoading();
                                }
                            });
                        },
                        success: function(data) {
                            Swal.close();
                            if (data.code == 200) {
                                $('#tabel_riwayat').DataTable()
                                    .ajax
                                    .reload();
                                Swal.fire({
                                    title: 'success',
                                    text: 'Data Berhasil dihapus',
                                    icon: 'success',
                                    timer: 1500
                                })
                                // console.log(data.data_riwayat);
                                if (data.data_riwayat <= 3) {
                                    $('#btn_tambah_riwayat')
                                        .attr(
                                            "disabled", false);
                                    $('#pesan_disabled').hide();
                                }
                            } else {
                                $('#tabel_riwayat').DataTable()
                                    .ajax
                                    .reload();
                                Swal.fire({
                                    title: 'error',
                                    text: data.error,
                                    icon: 'error',
                                    timer: 6500
                                })
                            }
                        },
                        error: function(data) {
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Data Gagal dihapus',
                                icon: 'error',
                                timer: 1500
                            })
                        }
                    });

                } else {
                    Swal.fire({
                        title: 'Gagal !',
                        text: 'Data gagal dihapus',
                        icon: 'warning',
                        timer: 1500
                    })
                }

            });
        });
        // end riwayat
        $('#btn_simpan_pendidikan').click(function() {

            var id_karyawan = $('#id_karyawan').val();
            var jenjang = $('#jenjang').val();
            var jurusan = $('#jurusan').val();
            var tahun_masuk = $('#tahun_masuk').val();
            var tahun_lulus = $('#tahun_lulus').val();
            var nama_instansi = $('#nama_instansi').val();
            // console.log(id_karyawan, jenjang, jurusan, tahun_masuk, tahun_lulus, nama_instansi);
            $.ajax({
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/AddPendidikan') }}@else{{ url('karyawan/AddPendidikan') }}@endif",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id_karyawan: id_karyawan,
                    jenjang: jenjang,
                    jurusan: jurusan,
                    tahun_masuk: tahun_masuk,
                    tahun_lulus: tahun_lulus,
                    nama_instansi: nama_instansi
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon tunggu...',
                        text: 'Sedang memproses data',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    if (response.code == 200) {
                        $('#modal_add_pendidikan').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        })
                    } else {
                        let errors = '';
                        $.each(response.message, function(key, value) {
                            errors += value.join('<br>') + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errors,
                        })
                    }
                    $('#form_add_pendidikan').trigger('reset');
                    table.ajax.reload();
                },
                error: function(data) {
                    Swal.close();
                    console.log(data);
                    $('#form_add_pendidikan').trigger('reset');
                    $('#modal_add_pendidikan').modal('hide');
                    let errors = '';
                    $.each(data.responseJSON.message, function(key, value) {
                        errors += value.join('<br>') + '<br>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errors,
                    });

                }
            });

        });
        $(document).on('click', '.btn_edit_pendidikan', function() {
            console.log($(this).data());
            var id_pendidikan = $(this).data('id_pendidikan');
            var jenjang = $(this).data('jenjang');
            var nama_instansi = $(this).data('nama_instansi');
            var jurusan = $(this).data('jurusan');
            var tahun_masuk = $(this).data('tahun_masuk');
            var tahun_lulus = $(this).data('tahun_lulus');
            $('#id_pendidikan').val(id_pendidikan);
            $('#jenjang_update').val(jenjang);
            $('#nama_instansi_update').val(nama_instansi);
            $('#jurusan_update').val(jurusan);
            $('#tahun_masuk_update').val(tahun_masuk);
            $('#tahun_lulus_update').val(tahun_lulus);
            $('#modal_edit_pendidikan').modal('show');
        });
        $('#btn_simpan_edit_pendidikan').click(function() {
            var id_pendidikan = $('#id_pendidikan').val();
            var id_karyawan = $('#id_karyawan').val();
            var jenjang = $('#jenjang_update').val();
            var jurusan = $('#jurusan_update').val();
            var tahun_masuk = $('#tahun_masuk_update').val();
            var tahun_lulus = $('#tahun_lulus_update').val();
            var nama_instansi = $('#nama_instansi_update').val();
            // console.log(id_karyawan, jenjang, jurusan, tahun_masuk, tahun_lulus, nama_instansi);
            $.ajax({
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/UpdatePendidikan') }}@else{{ url('karyawan/UpdatePendidikan') }}@endif",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id_pendidikan: id_pendidikan,
                    id_karyawan: id_karyawan,
                    jenjang: jenjang,
                    jurusan: jurusan,
                    tahun_masuk: tahun_masuk,
                    tahun_lulus: tahun_lulus,
                    nama_instansi: nama_instansi
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon tunggu...',
                        text: 'Sedang memproses data',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    $('#form_add_pendidikan').trigger('reset');
                    $('#modal_edit_pendidikan').modal('hide');
                    if (response.code == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 4000,
                        })
                    } else {
                        let errors = '';
                        $.each(response.message, function(key, value) {
                            errors += value.join('<br>') + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errors,
                            timer: 4000,
                        })
                    }
                    table.ajax.reload();
                },
                error: function(data) {
                    Swal.close();
                    console.log(data);
                    $('#form_edit_pendidikan').trigger('reset');
                    $('#modal_edit_pendidikan').modal('hide');
                    let errors = '';
                    $.each(data.message, function(key, value) {
                        errors += value.join('<br>') + '<br>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errors,
                        timer: 4000,
                    });

                }
            });

        });
        $(document).on('click', '#btn_delete_pendidikan', function() {
            let id = $(this).data('id'); // ambil id dari tombol

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data pendidikan akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) { // v9 pakai result.value, bukan result.isConfirmed
                    $.ajax({
                        url: "{{ url('karyawan/DeletePendidikan') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_pendidikan: id
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Mohon tunggu...',
                                text: 'Sedang menghapus data',
                                allowOutsideClick: false,
                                onBeforeOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            Swal.close();
                            if (response.code == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                table.ajax.reload(); // reload datatable
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server'
                            });
                        }
                    });
                }
            });
        });
        $('#btn_upload_keahlian').click(function() {
            $('#file_keahlian').click();
        });
        $('#file_keahlian').change(function() {
            var file = this.files[0];
            if (!file) return;
            var fileURL = URL.createObjectURL(file);
            // $(this).val(file);
            $('.group-button-keahlian').empty();
            $('.group-button-keahlian').append('<a href="' + fileURL +
                    '" target="_blank" class="btn btn-sm btn-primary"><i class="mdi mdi-eye"></i></a>')
                .append(
                    '<button type="button" id="btn_reset_keahlian" class="btn btn-sm btn-danger"><i class="mdi mdi-refresh"></i></button>'
                );
        });
        $(document).on('click', '#btn_reset_keahlian', function() {
            // console.log('reset');
            $('#file_keahlian').val('');
            $('.group-button-keahlian').empty();
        });
        $(document).on('click', '#btn_reset_keahlian_update', function() {
            // console.log('reset');
            $('#file_keahlian_update').val('');
            $('.group-update-keahlian').empty();
            $('.group-update-keahlian').append(
                '<button type="button" id="btn_upload_keahlian_update" class="btn btn-sm btn-secondary"><i class="mdi mdi-upload"></i> Upload</button><p class="text-primary">format: PDF</p>'
            );

        });
        $(document).on('click', '#btn_upload_keahlian_update', function() {
            // console.log('upload');
            $('#file_keahlian_update').click();
        });
        $(document).on('click', '.btn_edit_keahlian', function() {
            console.log($(this).data());
            var id_keahlian = $(this).data('id_keahlian');
            var keahlian = $(this).data('keahlian');
            var file_keahlian = $(this).data('file_keahlian');
            var file_url = $(this).data('file_url');
            $('#id_keahlian').val(id_keahlian);
            $('#nama_keahlian_update').val(keahlian);
            console.log(file_keahlian.length);
            if (file_keahlian.length != 0) {
                console.log('ya');
                $('#file_keahlian_old_update').val(file_keahlian);
                $('.group-update-keahlian').empty();
                $('.group-update-keahlian').append('<a href="' + file_url +
                        '" target="_blank" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i></a>')
                    .append(
                        '<button type="button" id="btn_reset_keahlian_update" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>'
                    );
            } else {
                console.log('ok');
                $('.group-update-keahlian').empty();
                $('.group-update-keahlian').append(
                    '<button type="button" id="btn_upload_keahlian_update" class="btn btn-sm btn-secondary"><i class="mdi mdi-upload"></i> Upload</button>'
                );

            }
            $('#modal_edit_keahlian').modal('show');
        });
        $('#file_keahlian_update').change(function() {
            var file = this.files[0];
            if (!file) return;
            var fileURL = URL.createObjectURL(file);
            // $(this).val(file);
            $('.group-update-keahlian').empty();
            $('.group-update-keahlian').append('<a href="' + fileURL +
                    '" target="_blank" class="btn btn-sm btn-primary"><i class="mdi mdi-eye"></i></a>')
                .append(
                    '<button type="button" id="btn_reset_keahlian" class="btn btn-sm btn-danger"><i class="mdi mdi-refresh"></i></button>'
                );
        });
        $('#btn_simpan_keahlian').click(function() {
            var id_karyawan = $('#id_karyawan').val();
            var nama_keahlian = $('#nama_keahlian').val();
            var file_keahlian = $('#file_keahlian')[0].files[0];

            var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('id_karyawan', id_karyawan);
            formData.append('nama_keahlian', nama_keahlian);
            if (file_keahlian) {
                formData.append('file_keahlian', file_keahlian);
            } else {
                formData.append('file_keahlian', null);
            }
            // console.log(id_karyawan, nama_keahlian, file_keahlian);
            $.ajax({
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/AddKeahlian') }}@else{{ url('karyawan/AddKeahlian') }}@endif",
                type: 'POST',
                data: formData,
                processData: false, // WAJIB
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon tunggu...',
                        text: 'Sedang memproses data',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    if (response.code == 200) {
                        $('#modal_add_keahlian').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        })
                    } else {
                        let errors = '';
                        $.each(response.message, function(key, value) {
                            errors += value.join('<br>') + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errors,
                        })
                    }

                    $('#form_add_keahlian').trigger('reset');

                    table1.ajax.reload();
                },
                error: function(data) {
                    Swal.close();
                    console.log(data);
                    $('#form_add_keahlian').trigger('reset');
                    $('#modal_add_keahlian').modal('hide');
                    let errors = '';
                    $.each(data.responseJSON.message, function(key, value) {
                        errors += value.join('<br>') + '<br>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errors,
                    });

                }
            });
        });
        $('#btn_simpan_edit_keahlian').click(function() {
            var id_karyawan = $('#id_karyawan').val();
            var id_keahlian = $('#id_keahlian').val();
            var nama_keahlian = $('#nama_keahlian_update').val();
            var file_keahlian = $('#file_keahlian_update')[0].files[0];
            var file_keahlian_old = $('#file_keahlian_old_update').val();

            var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('id_karyawan', id_karyawan);
            formData.append('id_keahlian', id_keahlian);
            formData.append('nama_keahlian', nama_keahlian);
            formData.append('file_keahlian_old', file_keahlian_old);
            console.log(file_keahlian);
            if (file_keahlian) {
                formData.append('file_keahlian', file_keahlian);
            } else {
                formData.append('file_keahlian', null);
            }
            // console.log(id_karyawan, nama_keahlian, file_keahlian);
            $.ajax({
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/UpdateKeahlian') }}@else{{ url('karyawan/UpdateKeahlian') }}@endif",
                type: 'POST',
                data: formData,
                processData: false, // WAJIB
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon tunggu...',
                        text: 'Sedang memproses data',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    if (response.code == 200) {
                        $('#modal_edit_keahlian').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        })
                    } else {
                        let errors = '';
                        $.each(response.message, function(key, value) {
                            errors += value.join('<br>') + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errors,
                        })
                    }

                    $('#form_edit_keahlian').trigger('reset');

                    table1.ajax.reload();
                },
                error: function(data) {
                    Swal.close();
                    console.log(data);
                    $('#form_edit_keahlian').trigger('reset');
                    $('#modal_edit_keahlian').modal('hide');
                    let errors = '';
                    $.each(data.responseJSON.message, function(key, value) {
                        errors += value.join('<br>') + '<br>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errors,
                    });

                }
            });
        });
        $(document).on('click', '#btn_delete_keahlian', function() {
            let id = $(this).data('id'); // ambil id dari tombol

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data keahlian akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) { // v9 pakai result.value, bukan result.isConfirmed
                    $.ajax({
                        url: "{{ url('karyawan/DeleteKeahlian') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_keahlian: id
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Mohon tunggu...',
                                text: 'Sedang menghapus data',
                                allowOutsideClick: false,
                                onBeforeOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            Swal.close();
                            if (response.code == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                table1.ajax.reload(); // reload datatable
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server'
                            });
                        }
                    });
                }
            });
        });

        // IJAZAH
        var file_ijazah = '{{ $karyawan->ijazah }}';
        var url_ijazah = "{{ asset('storage/ijazah') }}";
        $(document).on('click', '#btn_change_ijazah', function() {
            $('#file_ijazah').click();
            console.log('change ijazah');
        });
        $(document).on('click', '#btn_upload_ijazah', function() {
            $('#file_ijazah').click();
            // console.log('upload ijazah');
        });
        $('#file_ijazah').change(function() {
            var file = this.files[0];
            if (!file) return;
            var fileURL = URL.createObjectURL(file);
            // $(this).val(file);
            $('.group-button-ijazah').empty();
            $('.group-button-ijazah').append('<a href="' + fileURL +
                    '" target="_blank" class="btn btn-sm bottom-0"><i class="mdi mdi-eye"></i><span class="text-primary">&nbsp;Lihat File</span></a>'
                )
                .append(
                    '<button type="button" id="btn_reset_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-refresh"></i><span class="text-primary">&nbsp;Reset</span></button>'
                );
        });
        $(document).on('click', '#btn_reset_ijazah', function() {
            $('#file_ijazah').val('');
            if (file_ijazah == '') {
                $('.group-button-ijazah').empty();
                $('.group-button-ijazah').append(
                    '<button type="button" id="btn_upload_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-upload"></i><span class="text-primary">&nbsp;Ganti</span></button>'
                );
            } else {
                $('.group-button-ijazah').empty();
                $('.group-button-ijazah').append('<a href="' + url_ijazah + '/' + file_ijazah +
                        '" target="_blank" class="btn btn-sm bottom-0"><i class="mdi mdi-eye"></i><span class="text-primary">&nbsp;Lihat File</span></a>'
                    )
                    .append(
                        '<button type="button" id="btn_change_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-pencil"></i><span class="text-primary">&nbsp;Ganti</span></button>'
                    )
                    .append(
                        '<button type="button" id="btn_delete_file_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-delete"></i><span class="text-primary">&nbsp;Hapus</span></button>'
                    );
            }
        });
        $(document).on('click', '#btn_delete_file_ijazah', function() {
            $('#file_ijazah').val('');
            $('.group-button-ijazah').empty();
            $('.group-button-ijazah').append(
                '<button type="button" id="btn_upload_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-upload"></i><span class="text-primary">&nbsp;Upload</span></button>'
            );
        });

        // TRANSKRIP NILAI
        var file_transkrip_nilai = '{{ $karyawan->transkrip_nilai }}';
        var url_transkrip_nilai = "{{ asset('storage/transkrip_nilai') }}";
        $(document).on('click', '#btn_change_transkrip_nilai', function() {
            $('#file_transkrip_nilai').click();
            // console.log('change transkrip nilai');
        });
        $(document).on('click', '#btn_upload_transkrip_nilai', function() {
            $('#file_transkrip_nilai').click();
            // console.log('upload transkrip nilai');
        });
        $('#file_transkrip_nilai').change(function() {
            var file = this.files[0];
            if (!file) return;
            var fileURL = URL.createObjectURL(file);
            // $(this).val(file);
            $('.group-button-transkrip_nilai').empty();
            $('.group-button-transkrip_nilai').append('<a href="' + fileURL +
                    '" target="_blank" class="btn btn-sm bottom-0"><i class="mdi mdi-eye"></i><span class="text-primary">&nbsp;Lihat File</span></a>'
                )
                .append(
                    '<button type="button" id="btn_reset_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-refresh"></i><span class="text-primary">&nbsp;Reset</span></button>'
                );
        });
        $(document).on('click', '#btn_reset_transkrip_nilai', function() {
            $('#file_transkrip_nilai').val('');
            if (file_transkrip_nilai == '') {
                $('.group-button-transkrip_nilai').empty();
                $('.group-button-transkrip_nilai').append(
                    '<button type="button" id="btn_upload_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-upload"></i><span class="text-primary">&nbsp;Ganti</span></button>'
                );
            } else {
                $('.group-button-transkrip_nilai').empty();
                $('.group-button-transkrip_nilai').append('<a href="' + url_transkrip_nilai + '/' +
                        file_transkrip_nilai +
                        '" target="_blank" class="btn btn-sm bottom-0"><i class="mdi mdi-eye"></i><span class="text-primary">&nbsp;Lihat File</span></a>'
                    )
                    .append(
                        '<button type="button" id="btn_change_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-pencil"></i><span class="text-primary">&nbsp;Ganti</span></button>'
                    )
                    .append(
                        '<button type="button" id="btn_delete_file_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-delete"></i><span class="text-primary">&nbsp;Hapus</span></button>'
                    );
            }
        });
        $(document).on('click', '#btn_delete_file_transkrip_nilai', function() {
            $('#file_transkrip_nilai').val('');
            console.log('File transkrip nilai dihapus');
            $('.group-button-transkrip_nilai').empty();
            $('.group-button-transkrip_nilai').append(
                '<button type="button" id="btn_upload_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-upload"></i><span class="text-primary">&nbsp;Upload</span></button>'
            );
        });
    });


    function bankCheck(that) {
        if (that.value == "BBRI") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank BRI?',
                showConfirmButton: true
            });
            bankdigit = 15;
            // document.getElementById("ifBRI").style.display = "block";
            // document.getElementById("ifBCA").style.display = "none";
            // document.getElementById("ifMANDIRI").style.display = "none";
        } else if (that.value == "BBCA") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank BCA?',
                showConfirmButton: true
            });
            bankdigit = 10;
            // document.getElementById("ifMANDIRI").style.display = "block";
            // document.getElementById("ifBCA").style.display = "none";
            // document.getElementById("ifBRI").style.display = "none";
        } else if (that.value == "BOCBC") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank OCBC?',
                showConfirmButton: true
            });
            bankdigit = 12;
            // document.getElementById("ifBCA").style.display = "block";
            // document.getElementById("ifMANDIRI").style.display = "none";
            // document.getElementById("ifBRI").style.display = "none";
        }
    }
    $(function() {
        var kategori = '{{ $karyawan->kategori }}';
        if (kategori == 'Karyawan Harian') {
            $('#form_departemen').hide();
            $('#form_divisi').hide();
            $('#form_jabatan_more').hide();
            $('#form_jabatan').hide();
            $('#form_lama_kotrak').hide();
            $('#form_bagian').hide();
            $('#form_kontrak').hide();
            $('#form_tgl_kontrak_kerja').hide();
            // $('#form_level').hide();
            $('#form_tgl_mulai_kontrak').hide();
            $('#form_tgl_selesai_kontrak').hide();
            $('#form_site').hide();
            $('#form_lama_kontrak').hide();
        } else {

            $('#form_departemen').show();
            $('#form_divisi').show();
            $('#form_jabatan_more').show();
            $('#form_jabatan').show();
            $('#form_lama_kotrak').show();
            $('#form_bagian').show();
            $('#form_kontrak').show();
            $('#form_tgl_kontrak_kerja').show();
            // $('#form_level').show();
            $('#form_lama_kontrak').show();
            $('#form_tgl_mulai_kontrak').show();
            $('#form_tgl_selesai_kontrak').show();
            $('#form_site').show();
        }
        $('#kategori').on('change', function() {
            var id = $(this).val();
            if (id == 'Karyawan Harian') {
                $('#form_departemen').hide();
                $('#form_divisi').hide();
                $('#form_jabatan_more').hide();
                $('#form_jabatan').hide();
                $('#form_lama_kotrak').hide();
                $('#form_bagian').hide();
                $('#form_kontrak').hide();
                $('#form_tgl_kontrak_kerja').hide();
                // $('#form_level').hide();
                $('#form_tgl_mulai_kontrak').hide();
                $('#form_tgl_selesai_kontrak').hide();
                $('#form_site').hide();
                $('#form_lama_kontrak').hide();
                $('#form_kuota_cuti').hide();
            } else if (id == 'Karyawan Bulanan') {
                let lama = $('#lama_kontrak_kerja').val();
                // console.log(lama);
                if (lama == 'tetap') {
                    $('#form_tgl_mulai_kontrak').show();
                    $('#form_kuota_cuti').show();
                } else if (lama == '') {
                    $('#form_tgl_mulai_kontrak').hide();
                    $('#form_tgl_selesai_kontrak').hide();
                    $('#form_kuota_cuti').hide();
                } else {
                    $('#form_tgl_mulai_kontrak').show();
                    $('#form_tgl_selesai_kontrak').show();
                    $('#form_kuota_cuti').show();
                }
                $('#form_departemen').show();
                $('#form_divisi').show();
                $('#form_jabatan_more').show();
                $('#form_jabatan').show();
                $('#form_lama_kotrak').show();
                $('#form_bagian').show();
                $('#form_kontrak').show();
                $('#form_tgl_kontrak_kerja').show();
                $('#form_level').show();
                $('#form_lama_kontrak').show();
                // $('#form_tgl_mulai_kontrak').hide();
                // $('#form_tgl_selesai_kontrak').hide();
                $('#form_site').show();
                // $('#form_kuota_cuti').hide();
            }
        });
        $('#btn_upload_ktp').on('click', function() {
            $('#ktp').click();
        });
        $('#ktp').on('change', function() {
            let file = $(this).val();
            if (file) {
                $('#btn_upload_ktp').hide();
            }
        });
        $('#lama_kontrak_kerja').on('change', function() {
            let iya = $(this).val();
            if (iya == 'tetap') {
                $('#form_tgl_selesai_kontrak').hide();
                $('#form_tgl_mulai_kontrak').show();
                $('#form_kuota_cuti').show();
            } else {
                $('#form_tgl_selesai_kontrak').show();
                $('#form_tgl_mulai_kontrak').show();
                $('#form_kuota_cuti').show();
            }
        })

    });
    $(function() {

        $('#atasan').on('change', function() {
            let id = $('#id_jabatan').val();
            let divisi = $('#id_divisi').val();
            let id_karyawan = $('#id_karyawan').val();
            let holding = '{{ $holding }}';
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/atasan2/get_jabatan') }}@else{{ url('karyawan/atasan2/get_jabatan') }}@endif" +
                "/" + holding;
            // console.log(divisi);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: true,
                data: {
                    id: id,
                    id_karyawan: id_karyawan,
                    holding: holding,
                    id_divisi: divisi
                },
                success: function(response) {
                    // console.log(response);
                    $('#atasan2').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        });

    })
    $(function() {
        $('#nik').keyup(function(e) {
            if ($(this).val().length >= 16) {
                $(this).val($(this).val().substr(0, 16));
                document.getElementById("nik").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor NIK harus ' + 16 + ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });
        $('#npwp').keyup(function(e) {
            if ($(this).val().length >= 16) {
                $(this).val($(this).val().substr(0, 16));
                document.getElementById("npwp").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor NPWP harus ' + 16 + ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });
        $('#nomor_rekening').keyup(function(e) {
            if ($(this).val().length >= bankdigit) {
                $(this).val($(this).val().substr(0, bankdigit));
                document.getElementById("nomor_rekening").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor Rekening harus ' + bankdigit +
                        ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });


        $('#id_provinsi').on('change', function() {
            let id_provinsi = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_kabupaten') }}@else{{ url('/karyawan/get_kabupaten') }}@endif" +
                "/" + id_provinsi;
            // console.log(id_provinsi);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_provinsi: id_provinsi
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kabupaten').html(response);
                    $('#id_kecamatan').html('<option value="">Pilih Kecamatan</option>');
                    $('#id_desa').html('<option value="">Pilih Desa</option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kabupaten').on('change', function() {
            let id_kabupaten = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_kecamatan') }}@else{{ url('/karyawan/get_kecamatan') }}@endif" +
                "/" + id_kabupaten;
            // console.log(id_kabupaten);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_kabupaten: id_kabupaten
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kecamatan').html(response);
                    $('#id_desa').html('<option value="">Pilih Desa</option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kecamatan').on('change', function() {
            let id_kecamatan = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_desa') }}@else{{ url('/karyawan/get_desa') }}@endif" +
                "/" + id_kecamatan;
            // console.log(id_kecamatan);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_kecamatan: id_kecamatan
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_desa').html(response);

                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_provinsi_domisili').on('change', function() {
            let id_provinsi = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_kabupaten') }}@else{{ url('/karyawan/get_kabupaten') }}@endif" +
                "/" + id_provinsi;
            // console.log(id_provinsi);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_provinsi: id_provinsi
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kabupaten_domisili').html(response);
                    $('#id_kecamatan_domisili').html(
                        '<option value="">Pilih Kecamatan</option>');
                    $('#id_desa_domisili').html('<option value="">Pilih Desa</option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kabupaten_domisili').on('change', function() {
            let id_kabupaten = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_kecamatan') }}@else{{ url('/karyawan/get_kecamatan') }}@endif" +
                "/" + id_kabupaten;
            // console.log(id_kabupaten);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_kabupaten: id_kabupaten
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kecamatan_domisili').html(response);
                    $('#id_desa_domisili').html('<option value="">Pilih Desa</option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kecamatan_domisili').on('change', function() {
            let id_kecamatan = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_desa') }}@else{{ url('/karyawan/get_desa') }}@endif" +
                "/" + id_kecamatan;
            // console.log(id_kecamatan);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_kecamatan: id_kecamatan
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_desa_domisili').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
    });
</script>
<script>
    $('#row_bpjs_ketenagakerjaan').hide();
    $('#row_bpjs_kesehatan').hide();
    $('#row_kelas_bpjs').show();
    var status_nomor = "{{ old('status_nomor', $karyawan->status_nomor) }}";
    var status_bpjs_ketenagakerjaan = "{{ old('bpjs_ketenagakerjaan', $karyawan->bpjs_ketenagakerjaan) }}";
    var status_bpjs_kesehatan = "{{ old('bpjs_kesehatan', $karyawan->bpjs_kesehatan) }}";
    var pilih_domisili_alamat = "{{ old('pilihan_alamat_domisili', $karyawan->status_alamat) }}";
    var status_npwp = "{{ old('status_npwp', $karyawan->status_npwp) }}";
    // console.log(status_bpjs_ketenagakerjaan);
    if (status_nomor == 'ya') {
        $('#content_nomor_wa').hide();
    } else if (status_nomor == 'tidak') {
        $('#content_nomor_wa').show();
    } else {
        $('#content_nomor_wa').hide();
    }
    if (pilih_domisili_alamat == 'ya') {
        $('#content_alamat_domisili').hide();
    } else if (pilih_domisili_alamat == 'tidak') {
        $('#content_alamat_domisili').show();
    } else {
        $('#content_alamat_domisili').hide();
    }
    if (status_bpjs_ketenagakerjaan == 'on') {
        $('#row_bpjs_ketenagakerjaan').show();
    } else if (status_bpjs_ketenagakerjaan == 'off') {
        $('#row_bpjs_ketenagakerjaan').hide();
    } else {
        $('#row_bpjs_ketenagakerjaan').hide();

    }
    if (status_bpjs_kesehatan == 'on') {
        $('#row_bpjs_kesehatan').show();
        $('#row_kelas_bpjs').show();
    } else if (status_bpjs_kesehatan == 'off') {
        $('#row_kelas_bpjs').hide();
        $('#row_bpjs_kesehatan').hide();
    } else {
        $('#row_kelas_bpjs').hide();
        $('#row_bpjs_kesehatan').hide();

    }

    if (status_npwp == 'on') {
        $('#row_npwp').show();
    } else if (status_npwp == 'off') {
        $('#row_npwp').hide();
    } else {
        $('#row_npwp').hide();
    }
    $(document).on("click", "#btn_status_no_ya", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_nomor_wa').hide();

        }
    });
    $(document).on("click", "#btn_status_no_tidak", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_nomor_wa').show();
        }
    });
    $(document).on("click", "#status_npwp_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_npwp').show();
        } else {
            $('#row_npwp').hide();

        }
    });
    $(document).on("click", "#btnradio_ya", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_alamat_domisili').hide();

        }
    });
    $(document).on("click", "#btnradio_tidak", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_alamat_domisili').show();
        }
    });
    $(document).on("click", "#status_npwp_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_npwp').hide();
        } else {
            $('#row_npwp').show();

        }
    });
    $(document).on("click", "#bpjs_ketenagakerjaan_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_bpjs_ketenagakerjaan').show();
        } else {
            $('#row_bpjs_ketenagakerjaan').hide();

        }
    });
    $(document).on("click", "#bpjs_ketenagakerjaan_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_bpjs_ketenagakerjaan').hide();
        } else {
            $('#row_bpjs_ketenagakerjaan').show();

        }
    });
    $(document).on("click", "#bpjs_kesehatan_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_bpjs_kesehatan').show();
            $('#row_kelas_bpjs').show();
        } else {
            $('#row_bpjs_kesehatan').hide();
            $('#row_kelas_bpjs').hide();

        }
    });
    $(document).on("click", "#bpjs_kesehatan_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_bpjs_kesehatan').hide();
            $('#row_kelas_bpjs').hide();
        } else {
            $('#row_bpjs_kesehatan').show();
            $('#row_kelas_bpjs').show();

        }
    });
    var file_cv = '{{ $karyawan->file_cv }}';
    if (file_cv == '') {
        // console.log('ok');
        $('#btn_modal_lihat').hide();
    } else {
        // console.log('ok1');
        $('#btn_modal_lihat').show();
    }
    $('#file_cv').change(function() {


        let reader = new FileReader();
        // console.log(reader);
        reader.onload = (e) => {
            $('#lihat_file_cv').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);

    });
    $('#row_kategori_jabatan').hide();
    if ($('#site_job').val() == 'ALL SITES (SP, SPS, SIP)') {
        $('#row_kategori_jabatan').show();
    }
    $(document).on("change", "#site_job", function() {
        var id = $(this).val();
        if (id == 'ALL SITES (SP, SPS, SIP)') {
            $('#row_kategori_jabatan').show();
            var holding = $('#kategori_jabatan').val();
            // console.log(holding);
        } else if (id == 'ALL SITES (SP)') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'ALL SITES (SPS)') {
            $('#row_kategori_jabatan').hide();
            $('#kategori_jabatan').val('sps');
            var holding = 'sps';
            $('#row_kategori_jabatan').hide();
        } else if (id == 'ALL SITES (SIP)') {
            $('#kategori_jabatan').val('sip');
            var holding = 'sip';
        } else if (id == 'CV. SUMBER PANGAN - KEDIRI') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'CV. SUMBER PANGAN - TUBAN') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - KEDIRI') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - NGAWI') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - SUBANG') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else {
            $('#row_kategori_jabatan').hide();
            var holding = '{{ $holding }}';
        }
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
            data: {
                holding: holding,
            },
            cache: false,

            success: function(msg) {
                console.log(msg);
                // $('#id_divisi').html(msg);
                $('#id_departemen').html(msg);
                $('#id_departemen1').html(msg);
                $('#id_departemen2').html(msg);
                $('#id_departemen3').html(msg);
                $('#id_departemen4').html(msg);
                $('#id_divisi').html('<option value=""></option>');
                $('#id_bagian').html('<option value=""></option>');
                $('#id_jabatan').html('<option value=""></option>');
                $('#id_divisi1').html('<option value=""></option>');
                $('#id_bagian1').html('<option value=""></option>');
                $('#id_jabatan1').html('<option value=""></option>');
                $('#id_divisi2').html('<option value=""></option>');
                $('#id_bagian2').html('<option value=""></option>');
                $('#id_jabatan2').html('<option value=""></option>');
                $('#id_divisi3').html('<option value=""></option>');
                $('#id_bagian3').html('<option value=""></option>');
                $('#id_jabatan3').html('<option value=""></option>');
                $('#id_divisi4').html('<option value=""></option>');
                $('#id_bagian4').html('<option value=""></option>');
                $('#id_jabatan4').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
        // console.log($(this).val());
    });
    $(document).on("click", "#kategori_jabatan_sp", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sp') {
            $('#kategori_jabatan').val(holding);
            // console.log(id_departemen);
            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $(document).on("click", "#kategori_jabatan_sps", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sps') {
            $('#kategori_jabatan').val(holding);
            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $(document).on("click", "#kategori_jabatan_sip", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sip') {
            $('#kategori_jabatan').val(holding);
            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $('#id_departemen').on('change', function() {
        let id_departemen = $('#id_departemen').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_divisi') }}@else{{ url('karyawan/get_divisi') }}@endif" +
                '/' + id_departemen,
            cache: false,

            success: function(msg) {
                $('#id_divisi').html(msg);
                $('#id_bagian').html('<option value=""></option>');
                $('#id_jabatan').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_departemen1').on('change', function() {
        let id_departemen = $('#id_departemen1').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_divisi') }}@else{{ url('karyawan/get_divisi') }}@endif" +
                '/' + id_departemen,
            cache: false,

            success: function(msg) {

                $('#id_divisi1').html(msg);
                $('#id_bagian1').html('<option value=""></option>');
                $('#id_jabatan1').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_departemen2').on('change', function() {
        let id_departemen = $('#id_departemen2').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_divisi') }}@else{{ url('karyawan/get_divisi') }}@endif" +
                '/' + id_departemen,
            cache: false,

            success: function(msg) {

                $('#id_divisi2').html(msg);
                $('#id_bagian2').html('<option value=""></option>');
                $('#id_jabatan2').html('<option value=""></option>');

            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi').on('change', function() {
        let id_divisi = $('#id_divisi').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_bagian') }}@else{{ url('karyawan/get_bagian') }}@endif" +
                '/' + id_divisi,
            cache: false,

            success: function(msg) {
                $('#id_bagian').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi1').on('change', function() {
        let id_divisi = $('#id_divisi1').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_bagian') }}@else{{ url('karyawan/get_bagian') }}@endif" +
                '/' + id_divisi,
            cache: false,

            success: function(msg) {
                $('#id_bagian1').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi2').on('change', function() {
        let id_divisi = $('#id_divisi2').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_bagian') }}@else{{ url('karyawan/get_bagian') }}@endif" +
                '/' + id_divisi,
            cache: false,

            success: function(msg) {
                $('#id_bagian2').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian').on('change', function() {
        let id_bagian = $('#id_bagian').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_jabatan') }}@else{{ url('karyawan/get_jabatan') }}@endif" +
                '/' + id_bagian,
            cache: false,

            success: function(msg) {
                $('#id_jabatan').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian1').on('change', function() {
        let id_bagian = $('#id_bagian1').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_jabatan') }}@else{{ url('karyawan/get_jabatan') }}@endif" +
                '/' + id_bagian,
            cache: false,

            success: function(msg) {
                $('#id_jabatan1').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian2').on('change', function() {
        let id_bagian = $('#id_bagian2').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_jabatan') }}@else{{ url('karyawan/get_jabatan') }}@endif" +
                '/' + id_bagian,
            cache: false,

            success: function(msg) {
                $('#id_jabatan2').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_jabatan').on('change', function() {
        let id = $(this).val();
        let id_karyawan = $('#id_karyawan').val();
        let divisi = $('#id_divisi').val();
        let holding = '{{ $holding }}';
        let url =
            "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/atasan/get_jabatan') }}@else{{ url('karyawan/atasan/get_jabatan') }}@endif" +
            "/" + holding + "/" + divisi;
        // console.log(divisi);
        // console.log(holding);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: true,
            data: {
                id: id,
                id_karyawan: id_karyawan,
                holding: holding,
                id_divisi: divisi
            },
            success: function(response) {
                // console.log(response);
                $('#atasan').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    });
</script>
<script>
    $('#row_bpjs_ketenagakerjaan').hide();
    $('#row_bpjs_kesehatan').hide();
    $('#row_kelas_bpjs').show();
    var status_nomor = "{{ old('status_nomor', $karyawan->status_nomor) }}";
    var status_bpjs_ketenagakerjaan = "{{ old('bpjs_ketenagakerjaan', $karyawan->bpjs_ketenagakerjaan) }}";
    var status_bpjs_kesehatan = "{{ old('bpjs_kesehatan', $karyawan->bpjs_kesehatan) }}";
    var pilih_domisili_alamat = "{{ old('pilihan_alamat_domisili', $karyawan->status_alamat) }}";
    var status_npwp = "{{ old('status_npwp', $karyawan->status_npwp) }}";
    // console.log(status_bpjs_ketenagakerjaan);
    if (status_nomor == 'ya') {
        $('#content_nomor_wa').hide();
    } else if (status_nomor == 'tidak') {
        $('#content_nomor_wa').show();
    } else {
        $('#content_nomor_wa').hide();
    }
    if (pilih_domisili_alamat == 'ya') {
        $('#content_alamat_domisili').hide();
    } else if (pilih_domisili_alamat == 'tidak') {
        $('#content_alamat_domisili').show();


    } else {
        $('#content_alamat_domisili').hide();
    }
    if (status_bpjs_ketenagakerjaan == 'on') {
        $('#row_bpjs_ketenagakerjaan').show();
    } else if (status_bpjs_ketenagakerjaan == 'off') {
        $('#row_bpjs_ketenagakerjaan').hide();
    } else {
        $('#row_bpjs_ketenagakerjaan').hide();

    }
    if (status_bpjs_kesehatan == 'on') {
        $('#row_bpjs_kesehatan').show();
        $('#row_kelas_bpjs').show();
    } else if (status_bpjs_kesehatan == 'off') {
        $('#row_kelas_bpjs').hide();
        $('#row_bpjs_kesehatan').hide();
    } else {
        $('#row_kelas_bpjs').hide();
        $('#row_bpjs_kesehatan').hide();

    }

    if (status_npwp == 'on') {
        $('#row_npwp').show();
    } else if (status_npwp == 'off') {
        $('#row_npwp').hide();
    } else {
        $('#row_npwp').hide();
    }
    $(document).on("click", "#btn_status_no_ya", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_nomor_wa').hide();

        }
    });
    $(document).on("click", "#btn_status_no_tidak", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_nomor_wa').show();
        }
    });
    $(document).on("click", "#status_npwp_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_npwp').show();
        } else {
            $('#row_npwp').hide();

        }
    });
    $(document).on("click", "#btnradio_ya", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_alamat_domisili').hide();
        }
    });
    $(document).on("click", "#btnradio_tidak", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_alamat_domisili').show();
            console.log('ok');
            $('#id_provinsi_domisili option:selected').prop('selected', false);
        }
    });
    $(document).on("click", "#status_npwp_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_npwp').hide();
        } else {
            $('#row_npwp').show();

        }
    });
    $(document).on("click", "#bpjs_ketenagakerjaan_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_bpjs_ketenagakerjaan').show();
        } else {
            $('#row_bpjs_ketenagakerjaan').hide();

        }
    });
    $(document).on("click", "#bpjs_ketenagakerjaan_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_bpjs_ketenagakerjaan').hide();
        } else {
            $('#row_bpjs_ketenagakerjaan').show();

        }
    });
    $(document).on("click", "#bpjs_kesehatan_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_bpjs_kesehatan').show();
            $('#row_kelas_bpjs').show();
        } else {
            $('#row_bpjs_kesehatan').hide();
            $('#row_kelas_bpjs').hide();

        }
    });
    $(document).on("click", "#bpjs_kesehatan_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_bpjs_kesehatan').hide();
            $('#row_kelas_bpjs').hide();
        } else {
            $('#row_bpjs_kesehatan').show();
            $('#row_kelas_bpjs').show();

        }
    });
    var file_cv = '{{ $karyawan->file_cv }}';
    if (file_cv == '') {
        // console.log('ok');
        $('#btn_modal_lihat').hide();
    } else {
        // console.log('ok1');
        $('#btn_modal_lihat').show();
    }
    $('#file_cv').change(function() {


        let reader = new FileReader();
        // console.log(reader);
        reader.onload = (e) => {
            $('#lihat_file_cv').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);

    });
    $('#row_kategori_jabatan').hide();
    if ($('#site_job').val() == 'ALL SITES (SP, SPS, SIP)') {
        $('#row_kategori_jabatan').show();
    }
    $(document).on("change", "#site_job", function() {
        var id = $(this).val();
        if (id == 'ALL SITES (SP, SPS, SIP)') {
            $('#row_kategori_jabatan').show();
            var holding = $('#kategori_jabatan').val();
            // console.log(holding);
        } else if (id == 'ALL SITES (SP)') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'ALL SITES (SPS)') {
            $('#row_kategori_jabatan').hide();
            $('#kategori_jabatan').val('sps');
            var holding = 'sps';
            $('#row_kategori_jabatan').hide();
        } else if (id == 'ALL SITES (SIP)') {
            $('#kategori_jabatan').val('sip');
            var holding = 'sip';
        } else if (id == 'CV. SUMBER PANGAN - KEDIRI') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'CV. SUMBER PANGAN - TUBAN') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - KEDIRI') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - NGAWI') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - SUBANG') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else {
            $('#row_kategori_jabatan').hide();
            var holding = '{{ $holding }}';
        }
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
            data: {
                holding: holding,
            },
            cache: false,

            success: function(msg) {
                console.log(msg);
                // $('#id_divisi').html(msg);
                $('#id_departemen').html(msg);
                $('#id_departemen1').html(msg);
                $('#id_departemen2').html(msg);
                $('#id_departemen3').html(msg);
                $('#id_departemen4').html(msg);
                $('#id_divisi').html('<option value=""></option>');
                $('#id_bagian').html('<option value=""></option>');
                $('#id_jabatan').html('<option value=""></option>');
                $('#id_divisi1').html('<option value=""></option>');
                $('#id_bagian1').html('<option value=""></option>');
                $('#id_jabatan1').html('<option value=""></option>');
                $('#id_divisi2').html('<option value=""></option>');
                $('#id_bagian2').html('<option value=""></option>');
                $('#id_jabatan2').html('<option value=""></option>');
                $('#id_divisi3').html('<option value=""></option>');
                $('#id_bagian3').html('<option value=""></option>');
                $('#id_jabatan3').html('<option value=""></option>');
                $('#id_divisi4').html('<option value=""></option>');
                $('#id_bagian4').html('<option value=""></option>');
                $('#id_jabatan4').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
        // console.log($(this).val());
    });
    $(document).on("click", "#kategori_jabatan_sp", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sp') {
            $('#kategori_jabatan').val(holding);
            // console.log(id_departemen);
            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $(document).on("click", "#kategori_jabatan_sps", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sps') {
            $('#kategori_jabatan').val(holding);
            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $(document).on("click", "#kategori_jabatan_sip", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sip') {
            $('#kategori_jabatan').val(holding);
            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
</script>
<script>
    $(document).on("click", "#btndetail_karyawan", function() {
        let id = $(this).data('id');
        let holding = $(this).data("holding");
        // console.log(id);
        let url =
            "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/detail/') }}@else{{ url('/karyawan/detail/') }}@endif" +
            '/' + id + '/' + holding;
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: false,
            // data: {
            //     id_kecamatan: id_kecamatan
            // },
            success: function(response) {
                // console.log(response);
                window.location.assign(url);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    });
</script>
{{-- riwayat pekerjaan --}}
<script></script>

{{-- end riwayat pekerjaan --}}
