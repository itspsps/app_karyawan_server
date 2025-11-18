@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
<style type="text/css">
    .swal2-container {
        z-index: 9999 !important;
    }

    .dataTables_wrapper {
        width: 100% !important;
    }


    /* Card + dark mode toggle */
    body.dark-mode {
        background: #0f172a;
        color: #e6eef8;
    }

    .card.dark {
        background: #0b1220;
        color: #e6eef8;
        border-color: #142032;
    }

    /* Modern tree styles */
    .tree {
        list-style: none;
        padding-left: 1rem;
        font-size: 15px;
    }

    .tree li {
        padding: 6px 0;
        position: relative;
    }

    .tree .label {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px;
        border-radius: 8px;
        transition: background .12s;
    }

    .tree .label:hover {
        background: rgba(99, 102, 241, 0.06);
    }

    .toggle-icon {
        width: 20px;
        text-align: center;
        color: #64748b;
        transition: transform .12s;
    }

    .tree li.open>.label .toggle-icon {
        transform: rotate(90deg);
    }

    .tree li i.mdi {
        font-size: 18px;
        color: #6366f1;
    }

    /* nested ul */
    .tree ul {
        margin-left: 1rem;
        border-left: 1px dashed rgba(148, 163, 184, 0.25);
        padding-left: 0.8rem;
        display: none;
    }

    .tree ul.collapsed {
        display: none;
    }

    /* action buttons */
    .node-actions {
        margin-left: auto;
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .node-actions .btn {
        padding: 0.22rem .5rem;
        font-size: 12px;
    }

    /* drag handle */
    .drag-handle {
        cursor: grab;
        color: #94a3b8;
        margin-right: 6px;
    }

    .drag-over {
        outline: 2px dashed #6366f1;
    }

    /* Saat parent LI punya class "open" → tampilkan */
    .tree li.open>ul {
        display: block !important;
    }

    /* Panah rotate saat open */
    .tree li.open>.label .toggle-icon {
        transform: rotate(90deg);
    }

    /* small helper */
    .small-muted {
        font-size: 12px;
        color: #94a3b8;
        margin-left: 6px;
    }
</style>
@endsection
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row gy-4">
        <!-- Transactions -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">DATA MASTER MENU</h5>
                        <div>
                            <!-- <button id="toggleDark" class="btn btn-sm btn-outline-secondary">Toggle Dark</button> -->
                            <a href="#" class="btn btn-sm btn-primary ms-2" id="btnAddRoot">+ Tambah Menu</a>
                            <button id="btnSaveOrder" class="btn btn-sm btn-success ms-2">Save Order</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="modal fade" id="modal_tambah_access_karyawan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" id="form_add_access_karyawan" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="" id="id" name="id">
                                <input type="hidden" name="id_karyawan" id="id_karyawan" value="">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Access Karyawan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-md-12">
                                        <div class="card mb-4">
                                            <h4 class="card-header">&nbsp;Profil</h4>
                                            <!-- Account -->
                                            <div class="card-body">
                                                <div class="d-flex align-items-start align-items-sm-center gap-4">

                                                    <img src="{{asset('admin/assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />

                                                    <table>
                                                        <tr>
                                                            <th>Nama</th>
                                                            <td>&nbsp;</td>
                                                            <td>:</td>
                                                            <td id="td_name"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Departemen</th>
                                                            <td>&nbsp;</td>
                                                            <td>:</td>
                                                            <td id="td_departemen"> </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Divisi</th>
                                                            <td>&nbsp;</td>
                                                            <td>:</td>
                                                            <td id="td_divisi"> </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Jabatan</th>
                                                            <td>&nbsp;</td>
                                                            <td>:</td>
                                                            <td id="td_jabatan"></td>
                                                        <tr>
                                                            <th>Kontrak Kerja</th>
                                                            <td>&nbsp;</td>
                                                            <td>:</td>
                                                            <td id="td_kontrak_kerja"> </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <table class="table table-hover w-100" id="table-roles">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Hak&nbsp;Akses</th>
                                                <th>Deskripsi</th>
                                                <th>List&nbsp;Menu</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="button" id="btn_save_add_access" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Tree View</h5>
                            <div class="modal fade" id="menuModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <form id="menuForm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Menu</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="menu_id">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Menu</label>
                                                    <input type="text" class="form-control" name="nama" id="menu_name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Route (kosongi jika sebagai Parent)</label>
                                                    <input type="text" class="form-control" name="route" id="route">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Icon (mdi class)</label>
                                                    <input type="text" class="form-control" name="icon" id="icon" placeholder="mdi-folder">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Parent(Kosongi Jika Tidak Ada )</label>
                                                    <select class="form-select" name="parent_id" id="parent_id">
                                                        <option value="">-- Pilih Parent --
                                                            @foreach($parenPts as $p)
                                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button class="btn btn-primary" id="menuSaveBtn" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="menuTreeArea">
                                <ul class="tree" id="menuTree">
                                    @foreach($menus as $menu)
                                    @include('admin.menu.tree_item', ['item' => $menu])
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Help & Sample Icons</h5>
                            <h5 class="small-muted">Cari Icon di Web MDI Icon</h5>
                            <div class="">
                                <a type="button" class="btn btn-outline-primary" href="https://pictogrammers.com/library/mdi/" target="_blank">
                                    <i class="mdi mdi-material-design"></i><span>&nbsp;MDI ICON</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Transactions -->
            <!--/ Data Tables -->
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<!-- For convenience use CDN version that attaches Sortable to window -->
<script src="https://unpkg.com/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    $(document).on("click", ".label", function(e) {
        e.stopPropagation();

        let li = $(this).closest("li");

        // Toggle open class
        li.toggleClass("open");
    });

    function buildTreePayload($ul) {
        let arr = [];
        $ul.children('li').each(function() {
            const $li = $(this);
            const id = $li.data('id');
            const childrenUl = $li.children('ul').first();
            let children = [];
            if (childrenUl.length) {
                children = buildTreePayload(childrenUl);
            }
            arr.push({
                id: id,
                children: children
            });
        });
        return arr;
    }
    /* ----------------------------
   Init Sortable for all lists
   ---------------------------- */
    function initSortables() {
        // destroy existing if needed (not implemented here) then:
        $('#menuTree').find('ul, #menuTree').each(function() {
            new Sortable(this, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                handle: '.drag-handle',
                onAdd: function() {},
                onEnd: function( /**Event*/ evt) {}
            });
        });
    }
    $(document).ready(function() {

        // console.log('test');
        initSortables();


        // Add Root
        $('#btnAddRoot').on('click', function(e) {
            e.preventDefault();
            $('#menuForm')[0].reset();
            $('#menu_id').val('');
            $('#parent_id').val('');
            $('#menuModal .modal-title').text('Tambah Menu');
            new bootstrap.Modal($('#menuModal')).show();
        });

        // Add child from node action
        $(document).on('click', '.btn-add-child', function(e) {
            e.stopPropagation();
            const parentId = $(this).closest('li').data('id');
            $('#menuForm')[0].reset();
            $('#menu_id').val('');
            $('#parent_id').val(parentId);
            $('#menuModal .modal-title').text('Tambah Sub Menu');
            new bootstrap.Modal($('#menuModal')).show();
        });
        // Edit node
        $(document).on('click', '.btn-edit', function(e) {
            e.stopPropagation();
            const $li = $(this).closest('li');
            const id = $li.data('id');
            const name = $li.find('.tree-text').text().trim();
            const icon = $li.data('icon') || '';
            const route = $li.data('route') || '';

            $('#menu_id').val(id);
            $('#menu_name').val(name);
            $('#icon').val(icon);
            $('#route').val(route);
            $('#parent_id').val($li.data('parent') || '');
            $('#menuModal .modal-title').text('Edit Menu');
            new bootstrap.Modal($('#menuModal')).show();
        });
        $(document).on('click', '.btn-delete', function(e) {
            e.stopPropagation();
            const $li = $(this).closest('li');
            const id = $li.data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Delete menu?',
                text: 'This will remove the menu (children will be orphaned).',
                showCancelButton: true
            }).then(res => {
                if (res.isConfirmed) {
                    $.post("{{ url('menu/delete', ['id'=>'']) }}".replace(/\/$/, '') + '/' + id, {}, function(resp) {
                        if (resp.status == 'ok') {
                            $li.remove();
                            Swal.fire('Deleted', 'Menu removed', 'success');
                        }
                    });
                }
            });
        });
        // Save Order
        $('#btnSaveOrder').on('click', function() {
            console.log('test');
            const tree = buildTreePayload($('#menuTree'));

            Swal.fire({
                title: 'Saving order...',
                didOpen() {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: "{{ url('menu/save_all_change') }}",
                data: {
                    tree: tree,
                },
                method: 'post',
                success: function(resp) {
                    console.log(resp);
                    Swal.close();
                    if (resp.status == 'ok') {
                        Swal.fire('Saved', 'Order updated', 'success');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Failed to save', 'error');
                }
            })

        });

        // Toggle Dark mode
        $('#toggleDark').on('click', function() {
            $('body').toggleClass('dark-mode');
            $('#menuCard').toggleClass('dark');
        });
    });
</script>

@endsection