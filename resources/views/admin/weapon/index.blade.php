@extends('admin.layout.main')
@section('content')
    @include('admin/layout/header')
    <div class="clearfix"></div>
    <div class="page-container">
        @include('admin/layout/sidebar')
        <div class="page-content-wrapper">
            <div class="page-content">
                @include('admin/layout/message')
                
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="{{ route('admin.weapon.index') }}">Quản lý thay đổi vũ khí</a>
                        </li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-settings font-dark"></i>
                                    <span class="caption-subject font-dark sbold uppercase">Quản lý thay đổi vũ khí</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <!-- Tabs -->
                                <ul class="nav nav-tabs" id="weaponTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">Cấu hình chung</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="weapon-types-tab" data-toggle="tab" href="#weapon-types" role="tab" aria-controls="weapon-types" aria-selected="false">Loại vũ khí</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="log-tab" data-toggle="tab" href="#log" role="tab" aria-controls="log" aria-selected="false">Lịch sử thay đổi</a>
                                    </li>
                                </ul>

                                <!-- Tab content -->
                                <div class="tab-content" id="weaponTabContent">
                                    <!-- Tab Cấu hình chung -->
                                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title">Cấu hình chung</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <form action="{{ route('admin.weapon.config.updateGeneral') }}" method="POST">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="change_fee">Phí thay đổi vũ khí (Xu)</label>
                                                                <input type="number" class="form-control" id="change_fee" name="change_fee" value="{{ $generalConfig ? $generalConfig->change_fee : 0 }}" min="0">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="min_level_required">Cấp độ tối thiểu</label>
                                                                <input type="number" class="form-control" id="min_level_required" name="min_level_required" value="{{ $generalConfig ? $generalConfig->min_level_required : 1 }}" min="1">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="is_enabled">Trạng thái</label>
                                                                <select class="form-control" id="is_enabled" name="is_enabled">
                                                                    <option value="1" {{ $generalConfig && $generalConfig->is_enabled ? 'selected' : '' }}>Kích hoạt</option>
                                                                    <option value="0" {{ $generalConfig && !$generalConfig->is_enabled ? 'selected' : '' }}>Tắt</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="maintenance_message">Thông báo bảo trì (nếu tắt)</label>
                                                                <textarea class="form-control" id="maintenance_message" name="maintenance_message" rows="3">{{ $generalConfig ? $generalConfig->maintenance_message : 'Tính năng đang được bảo trì, vui lòng quay lại sau.' }}</textarea>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Lưu cấu hình</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title">Hướng dẫn</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="alert alert-info">
                                                            <p><strong>Phí thay đổi vũ khí:</strong> Số Xu người chơi phải trả để thay đổi vũ khí.</p>
                                                            <p><strong>Cấp độ tối thiểu:</strong> Cấp độ nhân vật tối thiểu để sử dụng tính năng thay đổi vũ khí.</p>
                                                            <p><strong>Trạng thái:</strong> Bật/tắt tính năng thay đổi vũ khí.</p>
                                                            <p><strong>Thông báo bảo trì:</strong> Nội dung hiển thị khi tính năng bị tắt.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab Loại vũ khí -->
                                    <div class="tab-pane fade" id="weapon-types" role="tabpanel" aria-labelledby="weapon-types-tab">
                                        <div class="row my-3">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addWeaponTypeModal">
                                                    <i class="fa fa-plus"></i> Thêm loại vũ khí mới
                                                </button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Tên loại vũ khí</th>
                                                        <th>Phạm vi ID (từ-đến)</th>
                                                        <th>Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($weaponTypes as $type)
                                                    <tr>
                                                        <td>{{ $type->id }}</td>
                                                        <td>{{ $type->name }}</td>
                                                        <td>{{ $type->min_id }} - {{ $type->max_id }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-info edit-weapon-type" 
                                                                data-toggle="modal" 
                                                                data-target="#editWeaponTypeModal"
                                                                data-id="{{ $type->id }}"
                                                                data-name="{{ $type->name }}"
                                                                data-min-id="{{ $type->min_id }}"
                                                                data-max-id="{{ $type->max_id }}">
                                                                <i class="fa fa-edit"></i> Sửa
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger delete-weapon-type" data-id="{{ $type->id }}">
                                                                <i class="fa fa-trash"></i> Xóa
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Chưa có loại vũ khí nào được cấu hình</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Tab lịch sử thay đổi -->
                                    <div class="tab-pane fade" id="log" role="tabpanel" aria-labelledby="log-tab">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="logTable">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Người chơi</th>
                                                        <th>Nhân vật</th>
                                                        <th>Vũ khí cũ</th>
                                                        <th>Vũ khí mới</th>
                                                        <th>Phí (Xu)</th>
                                                        <th>Thời gian</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Data sẽ được load bằng DataTables -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal thêm loại vũ khí -->
                <div class="modal fade" id="addWeaponTypeModal" tabindex="-1" role="dialog" aria-labelledby="addWeaponTypeModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('admin.weapon.config.addType') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addWeaponTypeModalLabel">Thêm loại vũ khí mới</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="name">Tên loại vũ khí</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="min_id">ID nhỏ nhất</label>
                                        <input type="number" class="form-control" id="min_id" name="min_id" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="max_id">ID lớn nhất</label>
                                        <input type="number" class="form-control" id="max_id" name="max_id" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal chỉnh sửa loại vũ khí -->
                <div class="modal fade" id="editWeaponTypeModal" tabindex="-1" role="dialog" aria-labelledby="editWeaponTypeModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('admin.weapon.config.updateType') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" id="edit_id">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editWeaponTypeModalLabel">Chỉnh sửa loại vũ khí</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="edit_name">Tên loại vũ khí</label>
                                        <input type="text" class="form-control" id="edit_name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_min_id">ID nhỏ nhất</label>
                                        <input type="number" class="form-control" id="edit_min_id" name="min_id" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_max_id">ID lớn nhất</label>
                                        <input type="number" class="form-control" id="edit_max_id" name="max_id" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-footer">
        <div class="page-footer-inner">
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // DataTable cho lịch sử thay đổi
        $('#logTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.weapon.config.logs") }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'user_id', name: 'user_id' },
                { data: 'character_name', name: 'character_name' },
                { data: 'old_weapon', name: 'old_weapon' },
                { data: 'new_weapon', name: 'new_weapon' },
                { data: 'cost', name: 'cost' },
                { data: 'created_at', name: 'created_at' }
            ],
            order: [[0, 'desc']]
        });

        // Điền thông tin vào modal chỉnh sửa loại vũ khí
        $('.edit-weapon-type').click(function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_name').val($(this).data('name'));
            $('#edit_min_id').val($(this).data('min-id'));
            $('#edit_max_id').val($(this).data('max-id'));
        });

        // Xóa loại vũ khí
        $('.delete-weapon-type').click(function() {
            const id = $(this).data('id');
            if (confirm('Bạn có chắc chắn muốn xóa loại vũ khí này?')) {
                $.ajax({
                    url: "{{ route('admin.weapon.config.deleteType') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Xóa thành công!');
                            location.reload();
                        } else {
                            alert('Lỗi: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Đã xảy ra lỗi khi xóa loại vũ khí');
                    }
                });
            }
        });
    });
</script>
@endsection