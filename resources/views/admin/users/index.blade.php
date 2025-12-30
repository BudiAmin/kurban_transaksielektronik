<!DOCTYPE html>
<html lang="id">

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
                <div class="container-fluid">
                    <!-- Page Header -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h1 class="h3 mb-0 text-gray-800">
                                Pengguna Aplikasi
                            </h1>
                            <p class="text-muted mb-0">Kelola semua pengguna aplikasi</p>
                        </div>
                    </div>

                    <!-- Messages -->
                    @include('components.admin.message')

                    <!-- Data Table -->
                    <div class="card custom-card fade-in">
                        <div class="card-body">

                            <!-- Desktop Table View -->

                            <!-- Mobile Card View -->
                            <div class="d-md-none mobile-card-view">
                                @forelse($users as $item)
                                    <div class="card mb-3 shadow-sm">
                                        <div class="card-body">
                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Nama Pengguna</div>
                                                    <div class="mobile-value">
                                                        <span class="custom-badge badge-weight">
                                                            {{ $item->name }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Email</div>
                                                    <div class="mobile-value animal-type">
                                                        {{ $item->email }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Alamat</div>
                                                    <div class="mobile-value">
                                                        <span class="custom-badge badge-quantity">
                                                            {{ $item->alamat }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">No Handphone</div>
                                                    <div class="mobile-value price-tag">{{ $item->no_hp }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Role Pengguna</div>
                                                    <div class="mobile-value price-tag">{{ $item->role }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center pt-2">
                                                <button type="button" class="action-btn btn-primary edit-role-btn"
                                                    title="Edit Role" data-toggle="tooltip"
                                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                    data-email="{{ $item->email }}" data-role="{{ $item->role }}">
                                                    <i class="fas fa-user-cog fa-xs text-white"></i>
                                                </button>

                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('admin.users.destroy', $item->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn btn-danger border-0 ml-2"
                                                        title="Hapus" data-toggle="tooltip">
                                                        <i class="fas fa-trash fa-xs text-white"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-database"></i>
                                        </div>
                                        <h4 class="text-gray-700 mb-3">Data Belum Tersedia</h4>
                                        <p class="text-gray-500 mb-4">Belum ada data hewan kurban yang tersedia.</p>
                                        <a href="{{ route('admin.ketersediaan-hewan.create') }}"
                                            class="btn btn-custom">
                                            <i class="fas fa-plus-circle mr-2"></i>Tambah Data Hewan
                                        </a>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
                                <div class="d-flex justify-content-center mt-5">
                                    <nav aria-label="Page navigation">
                                        {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
                                    </nav>
                                </div>
                            @endif
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
                        <span>Copyright &copy; Sistem Manajemen Kurban {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Modal Edit Role -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editRoleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editUserId">

                        <div class="form-group">
                            <label for="editUserName">Nama Pengguna</label>
                            <input type="text" class="form-control" id="editUserName" readonly>
                        </div>

                        <div class="form-group">
                            <label for="editUserEmail">Email</label>
                            <input type="email" class="form-control" id="editUserEmail" readonly>
                        </div>

                        <div class="form-group">
                            <label for="editUserRole">Role <span class="text-danger">*</span></label>
                            <select class="form-control" id="editUserRole" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin_kurban">Admin Kurban</option>
                                <!-- Tambahkan role lainnya sesuai kebutuhan -->
                            </select>
                            <small class="form-text text-muted">Pilih "admin_kurban" untuk memberikan akses admin
                                khusus kurban</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Admin Kurban -->
    <div class="modal fade" id="confirmAdminKurbanModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmAdminKurbanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmAdminKurbanModalLabel">Konfirmasi Admin Kurban</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengubah role pengguna ini menjadi <strong>Admin Kurban</strong>?</p>
                    <p><strong>Hak Akses Admin Kurban:</strong></p>
                    <ul>
                        <li>Mengelola data hewan kurban</li>
                        <li>Mengelola jadwal penyembelihan</li>
                        <li>Mengelola pendaftaran kurban</li>
                        <li>Tidak dapat mengakses pengguna lain</li>
                    </ul>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Pastikan pengguna ini benar-benar membutuhkan akses
                        admin kurban.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmAdminKurbanBtn">Ya, Jadikan Admin
                        Kurban</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap & jQuery Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        // Edit Role Modal
        $(document).on('click', '.edit-role-btn', function() {
            const userId = $(this).data('id');
            const userName = $(this).data('name');
            const userEmail = $(this).data('email');
            const userRole = $(this).data('role');

            // Isi form modal
            $('#editUserId').val(userId);
            $('#editUserName').val(userName);
            $('#editUserEmail').val(userEmail);
            $('#editUserRole').val(userRole);

            // Set action form
            $('#editRoleForm').attr('action', '{{ route('admin.users.index', '') }}/' + userId);

            // Tampilkan modal
            $('#editRoleModal').modal('show');

            // Jika memilih admin_kurban, tampilkan konfirmasi
            $('#editUserRole').off('change').on('change', function() {
                if ($(this).val() === 'admin_kurban') {
                    $('#editRoleModal').modal('hide');
                    setTimeout(() => {
                        $('#confirmAdminKurbanModal').modal('show');
                    }, 300);
                }
            });
        });

        // Konfirmasi Admin Kurban
        $('#confirmAdminKurbanBtn').click(function() {
            // Submit form setelah konfirmasi
            $('#editRoleForm').submit();
            $('#confirmAdminKurbanModal').modal('hide');
        });

        // Handle form submission
        $('#editRoleForm').submit(function(e) {
            e.preventDefault();

            const form = $(this);
            const formData = form.serialize();
            const url = form.attr('action');

            // Tampilkan loading
            const submitBtn = form.find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Tutup modal
                    $('#editRoleModal').modal('hide');

                    // Tampilkan pesan sukses
                    if (response.success) {
                        showAlert('success', response.message);

                        // Reload halaman setelah 1.5 detik
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert('error', response.message || 'Terjadi kesalahan');
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat menyimpan';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                    }

                    showAlert('error', errorMessage);
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
        });

        // Fungsi tampilkan alert
        function showAlert(type, message) {
            const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;

            // Hapus alert sebelumnya
            $('.alert').remove();

            // Tambahkan alert di atas table
            $('.card.custom-card').prepend(alertHtml);

            // Auto dismiss setelah 5 detik
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
        }
    </script>

</body>

</html>
