<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Dashboard Manajemen Kurban">
    <meta name="author" content="">

    <title>Dashboard Pengelolaan Kurban</title>

    <!-- Custom fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/admin/ketersediaan/index.css') }}" rel="stylesheet">
</head>

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
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Total Peserta -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Peserta Kurban</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_peserta }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Daging -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Daging Terdistribusi</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $total_berat_daging ? number_format($total_berat_daging, 1) . ' kg' : '-' }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Penerima -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total
                                                Penerima Manfaat
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Pendapatan Kurban</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                Rp.{{ number_format($total_pendapatan, 0, ',', '.') ?? '-' }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        {{-- <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Total Pendapatan Kurban</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div> --}}


                        <!-- Project Card Example -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Status Pembayaran</h6>
                                    <span class="badge">
                                        Total: {{ $orders['total'] }} Order
                                    </span>
                                </div>
                                <div class="card-body">
                                    <!-- Ditolak -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="small font-weight-bold mb-0">
                                                <i class="fas fa-times-circle text-danger me-1"></i> Ditolak
                                            </h6>
                                            <div>
                                                <span
                                                    class="text-muted small me-2">{{ $orders['ditolak']['count'] ?? 0 }}
                                                    order</span>
                                                <span
                                                    class="font-weight-bold">{{ $paymentStats['ditolak']['percent'] ?? 0 }}%</span>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-danger" role="progressbar"
                                                style="width: {{ $paymentStats['ditolak']['percent'] ?? 0 }}%"
                                                aria-valuenow="{{ $paymentStats['ditolak']['percent'] ?? 0 }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Menunggu Verifikasi -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="small font-weight-bold mb-0">
                                                <i class="fas fa-clock text-warning me-1"></i> Menunggu Verifikasi
                                            </h6>
                                            <div>
                                                <span
                                                    class="text-muted small me-2">{{ $paymentStats['menunggu verifikasi']['count'] ?? 0 }}
                                                    order</span>
                                                <span
                                                    class="font-weight-bold">{{ $paymentStats['menunggu verifikasi']['percent'] ?? 0 }}%</span>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-warning" role="progressbar"
                                                style="width: {{ $paymentStats['menunggu verifikasi']['percent'] ?? 0 }}%"
                                                aria-valuenow="{{ $paymentStats['menunggu verifikasi']['percent'] ?? 0 }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Disetujui -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="small font-weight-bold mb-0">
                                                <i class="fas fa-check-circle text-success me-1"></i> Disetujui
                                            </h6>
                                            <div>
                                                <span
                                                    class="text-muted small me-2">{{ $paymentStats['disetujui']['count'] ?? 0 }}
                                                    order</span>
                                                <span
                                                    class="font-weight-bold">{{ $paymentStats['disetujui']['percent'] ?? 0 }}%</span>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $paymentStats['disetujui']['percent'] ?? 0 }}%"
                                                aria-valuenow="{{ $paymentStats['disetujui']['percent'] ?? 0 }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer dengan summary -->
                                <div class="card-footer bg-light py-2">
                                    <div class="row text-center">
                                        <div class="col-3 border-end">
                                            <small class="text-muted d-block">Ditolak</small>
                                            <span
                                                class="fw-bold text-danger">{{ $orders['ditolak']['count'] ?? 0 }}</span>
                                        </div>
                                        <div class="col-3 border-end">
                                            <small class="text-muted d-block">Menunggu</small>
                                            <span
                                                class="fw-bold text-warning">{{ $orders['menunggu_verifikasi']['count'] ?? 0 }}</span>
                                        </div>
                                        <div class="col-3 border-end">
                                            <small class="text-muted d-block">Disetujui</small>
                                            <span
                                                class="fw-bold text-success">{{ $orders['disetujui']['count'] ?? 0 }}</span>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted d-block">Total</small>
                                            <span class="fw-bold text-primary">{{ $orders['total'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Direct
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Social
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Referral
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">


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
</body>

</html>
