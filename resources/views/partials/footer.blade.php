<footer class="main-footer">
            {{-- To the right --}}
            {{-- <div class="float-right d-none d-sm-block">
                <b>Version</b> 8.0.0
            </div> --}}
            {{-- Default to the left --}}

            <div class="float-left credits d-md-block d-lg-block d-xl-block d-none d-sm-none mb-2">
                <strong>Copyright &copy; 2024-2025 <a href="/">MY-SP</a>.</strong> All rights reserved.
            </div>
            <div class="credits d-sm-block d-md-none text-sm text-center mb-2">
                <strong>Copyright &copy; 2024-2025 <a href="/">MY-SP</a>.</strong> All rights reserved.
            </div>
        </footer>


<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <form action="{{ url('/logout') }}" method="post">
                    @csrf
                    <button class="btn btn-primary" type="submit">logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
