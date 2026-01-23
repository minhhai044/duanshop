@extends('admin.layouts.master')
@section('title')
    Color
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span style="font-size: 25px" class="m-0 font-weight-bold text-primary">List Color</span>
                <a class="btn btn-primary" style="float: right" href="{{ route('colors.create') }}" role="button">Thêm
                    mới</a>

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
                                <th>Slug</th>
                                <th>Color Preview</th>
                                <th>Color Code</th>
                                <th>Text Color</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->color_name }}</td>
                                    <td>{{ $item->slug }}</td>
                                    <td>
                                        <div style="background-color: {{ $item->color_code }}; color: {{ $item->color_text }}; padding: 8px 12px; border-radius: 4px; text-align: center; font-weight: bold; min-width: 80px;">
                                            {{ $item->color_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <span style="background-color: {{ $item->color_code }}; color: white; padding: 2px 6px; border-radius: 3px; font-family: monospace;">
                                            {{ $item->color_code }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="background-color: {{ $item->color_text }}; color: white; padding: 2px 6px; border-radius: 3px; font-family: monospace;">
                                            {{ $item->color_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item->is_active ? 'success' : 'danger' }}">
                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td> {{ $item->created_at->format('d/m/Y H:i:s') }} </td>
                                    <td> {{ $item->updated_at->format('d/m/Y H:i:s') }} </td>
                                    <td>
                                        <a class="btn btn-dark" href="{{ route('colors.edit', $item) }}"
                                            role="button">Edit</a>
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
