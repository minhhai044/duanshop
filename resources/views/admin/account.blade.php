@extends('admin.layouts.master')
@section('title')
    Account
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Account</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                                <tr>
                                    <td> {{ $item->id }} </td>
                                    <td> {{ $item->name }} </td>
                                    <td> {{ $item->email }} </td>
                                    <td>{{ $item->type }}</td>
                                    <td style="width:23%">

                                        @if ($item->type === 'member')
                                            @if ($item->deleted_at) 
                                                <a href="{{ route('account.setrole', $item->id) }}"><button onclick="return confirm('Bạn có chắc chắn cấp quyền admin không !!!')" class="btn btn-success" @disabled(true)>Set role</button></a>
                                                <a href="{{ route('account.restore', $item->id) }}"><button onclick="return confirm('Bạn có muốn khôi phục tài khoản không !!!')" class="btn btn-primary">Restore</button></a>
                                            @else
                                                <a href="{{ route('account.setrole', $item->id) }}"><button onclick="return confirm('Bạn có chắc chắn cấp quyền admin không !!!')" class="btn btn-success">Set role</button></a>
                                                <a href="{{ route('account.destroy', $item->id) }}"><button onclick="return confirm('Bạn có chắc chắn ban không !!!')" class="btn btn-warning">Ban</button></a>
                                            @endif

                                            <a href="{{ route('account.delete', $item->id) }}"><button onclick="return confirm('Bạn có chắc chắn xóa vĩnh viễn tài khoản này không !!!')" class="btn btn-danger">Delete</button></a>
                                        @else
                                            <a href="{{ route('account.downgrade', $item->id) }}"><button onclick="return confirm('Bạn có chắc chắn downgrade không !!!')" class="btn btn-primary">Downgrade</button></a>
                                        @endif

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
