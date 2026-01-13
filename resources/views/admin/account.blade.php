@extends('admin.layouts.master')
@section('title')
    Account Management
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">User Account Management</h6>
            </div>
            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Account Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                                <tr class="{{ $item->deleted_at ? 'table-secondary' : '' }}">
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->avatar)
                                                <img src="{{ getImageStorage($item->avatar) }}" 
                                                     class="rounded-circle me-2" width="32" height="32" 
                                                     alt="{{ $item->name }}">
                                            @else
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 32px; height: 32px; color: white; font-size: 14px;">
                                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span>{{ $item->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone ?: 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $item->type === 'admin' ? 'danger' : 'primary' }}">
                                            {{ ucfirst($item->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item->is_active ? 'success' : 'warning' }}">
                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->deleted_at)
                                            <span class="badge badge-secondary">Suspended</span>
                                        @else
                                            <span class="badge badge-success">Normal</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if ($item->type === 'member')
                                                @if ($item->deleted_at)
                                                    {{-- Suspended member actions --}}
                                                    <a href="{{ route('account.restore', $item->id) }}" class="btn btn-sm btn-success" 
                                                       onclick="return confirm('Bạn có muốn khôi phục tài khoản không?')" 
                                                       title="Restore Account">
                                                        <i class="fas fa-undo"></i> Restore
                                                    </a>
                                                @else
                                                    {{-- Active member actions --}}
                                                    <a href="{{ route('account.setrole', $item->id) }}" class="btn btn-sm btn-primary" 
                                                       onclick="return confirm('Bạn có chắc chắn cấp quyền admin không?')" 
                                                       title="Promote to Admin">
                                                        <i class="fas fa-user-shield"></i> Set Admin
                                                    </a>
                                                    <a href="{{ route('account.toggleStatus', $item->id) }}" 
                                                       class="btn btn-sm btn-{{ $item->is_active ? 'warning' : 'success' }}" 
                                                       onclick="return confirm('Bạn có chắc chắn {{ $item->is_active ? 'vô hiệu hóa' : 'kích hoạt' }} tài khoản không?')" 
                                                       title="{{ $item->is_active ? 'Deactivate' : 'Activate' }} Account">
                                                        <i class="fas fa-{{ $item->is_active ? 'ban' : 'check' }}"></i> 
                                                        {{ $item->is_active ? 'Deactivate' : 'Activate' }}
                                                    </a>
                                                @endif
                                            @else
                                                {{-- Admin actions --}}
                                                <a href="{{ route('account.downgrade', $item->id) }}" class="btn btn-sm btn-secondary" 
                                                   onclick="return confirm('Bạn có chắc chắn chuyển về quyền member không?')" 
                                                   title="Downgrade to Member">
                                                    <i class="fas fa-user-minus"></i> Downgrade
                                                </a>
                                                <a href="{{ route('account.toggleStatus', $item->id) }}" 
                                                   class="btn btn-sm btn-{{ $item->is_active ? 'warning' : 'success' }}" 
                                                   onclick="return confirm('Bạn có chắc chắn {{ $item->is_active ? 'vô hiệu hóa' : 'kích hoạt' }} tài khoản không?')" 
                                                   title="{{ $item->is_active ? 'Deactivate' : 'Activate' }} Account">
                                                    <i class="fas fa-{{ $item->is_active ? 'ban' : 'check' }}"></i> 
                                                    {{ $item->is_active ? 'Deactivate' : 'Activate' }}
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
