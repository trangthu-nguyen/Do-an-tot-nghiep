@extends('admin.layout')

@section('title', 'Quản lý khách hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold" style="color:#7b5554;">👤 Danh sách khách hàng</h3>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card shadow-sm border-0" style="border-radius:18px;">
    <div class="card-body p-3">

        <table class="table table-hover align-middle mb-0">
            <thead style="background:#7b5554; color:white;">
                <tr>
                    <th style="border-top-left-radius:14px;">ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Địa chỉ</th>
                    <th style="border-top-right-radius:14px;">Ngày tạo</th>
                </tr>
            </thead>

            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td class="fw-semibold">{{ $customer->customer_id }}</td>
                        <td>{{ $customer->full_name }}</td>
                        <td class="text-muted">{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td class="text-muted">{{ $customer->address }}</td>
                        <td class="text-muted">{{ $customer->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Chưa có khách hàng nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
@endsection