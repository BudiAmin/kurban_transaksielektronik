<!DOCTYPE html>
<html lang="en">

@include('components.admin.head')

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('components.admin.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('components.admin.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid px  -0 px-md-3">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h1 class="h3 mb-0 text-gray-800">
                                Data Keuangan Kurban
                            </h1>
                            <p class="text-muted mb-0">Kelola kas pelaksanaan kurban</p>
                        </div>
                        <div class="btn-group-responsive">
                            <a href="{{ route('admin.dana-operasional.create') }}" class="btn btn-custom shadow-sm">
                                <i class="fas fa-plus-circle fa-sm mr-2"></i>Tambah Pengeluaran
                            </a>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Overview Keuangan</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                   @include('components.admin.message')

                                    <!-- Table Container -->
                                    <div class="table-responsive">

                                        <!-- Mobile Table View -->
                                        <div class="d-md-none">
                                            @forelse ($dana_operasional as $item)
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <small class="text-muted d-block">No</small>
                                                                <strong>{{ $loop->iteration }}</strong>
                                                            </div>
                                                            <div class="col-6 text-right">
                                                                <small class="text-muted d-block">Keperluan</small>
                                                                <strong>{{ $item->keperluan ?? '-'}}</strong>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block">Jumlah Pengeluaran</small>
                                                            <strong>{{ number_format($item->Jumlah_Pengeluaran ?? 0, 0, ',', '.') }}</strong>
                                                        </div>
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block">Sumber Dana</small>
                                                            <strong>{{ $item->danaDkm?->sumber_dana ?? '-' }}</strong>
                                                        </div>
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block">Keterangan</small>
                                                            <strong class="text-primary">{{ $item->Keterangan ?? '-' }}</strong>
                                                        </div>
                                                        <div class="mb-0">
                                                            <small class="text-muted d-block">Penanggung Jawab</small>
                                                            <strong>{{ $item->user?->name ?? '-' }}</strong>
                                                        </div>
                                                        <div class="mb-0">
                                                            <small class="text-muted d-block">Tanggal Pengeluaran</small>
                                                            <strong>{{ $item->created_at->format('d/m/Y H:i') }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="empty-state">
                                                    <i class="fas fa-database"></i>
                                                    <p class="mt-2 mb-0">Tidak ada data yang tersedia</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- Total Keuangan -->
                                    <div class="total-card">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">TOTAL PENGELUARAN</h6>
                                            </div>
                                            <div>
                                                <h4 class="mb-0">Rp {{ number_format($jumlahDana, 0, ',', '.') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    @include('components.admin.logout')

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>

    <script>
        // Script untuk responsivitas tambahan
        $(document).ready(function() {
            // Tambahkan label untuk responsive table pada layar sangat kecil
            function setupResponsiveTable() {
                if ($(window).width() <= 576) {
                    $('.table-custom td').each(function() {
                        var header = $(this).closest('table').find('th').eq($(this).index());
                        $(this).attr('data-label', header.text());
                    });
                }
            }

            // Panggil fungsi saat resize window
            $(window).resize(setupResponsiveTable);
            setupResponsiveTable();
        });
    </script>
</body>

</html>