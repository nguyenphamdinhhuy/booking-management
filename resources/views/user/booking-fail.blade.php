@extends('layouts.app')
@section('title', 'Thanh toán thất bại')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-lg p-4">
                <div class="mb-4">
                    <i class="fas fa-times-circle fa-4x text-danger"></i>
                </div>
                <h2 class="mb-3 text-danger">Thanh toán thất bại!</h2>
                <p class="lead mb-4">Rất tiếc, giao dịch của bạn chưa được thực hiện thành công.<br>
                    Vui lòng kiểm tra lại thông tin hoặc thử lại sau.</p>
                <a href="{{ route('payment') }}" class="btn btn-warning mt-3">Thử lại thanh toán</a>
                <a href="{{ route('index') }}" class="btn btn-outline-secondary mt-3">Về trang chủ</a>
            </div>
        </div>
    </div>
</div>
@endsection