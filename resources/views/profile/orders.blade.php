@extends('layouts.app')

@section('content')
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-header-content">
                <h1>Đơn đặt phòng</h1>
                <p>Xem lại lịch sử đặt phòng và quản lý các đơn hàng của bạn</p>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-shopping-bag"></i> Lịch sử đặt phòng</h2>
                </div>

                <div class="orders-content">
                    @if(empty($orders))
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h3>Chưa có đơn đặt phòng nào</h3>
                            <p>Bạn chưa có đơn đặt phòng nào. Hãy khám phá các phòng khách sạn tuyệt vời!</p>
                            <a href="{{ route('index') }}" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm phòng ngay
                            </a>
                        </div>
                    @else
                        <div class="orders-list">
                            @foreach($orders as $order)
                                <div class="order-item">
                                    <div class="order-header">
                                        <div class="order-info">
                                            <h4>Đơn hàng #{{ $order->id }}</h4>
                                            <p class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="order-status {{ $order->status }}">
                                            <span>{{ ucfirst($order->status) }}</span>
                                        </div>
                                    </div>

                                    <div class="order-details">
                                        <div class="room-info">
                                            <img src="{{ $order->room->image }}" alt="{{ $order->room->name }}" class="room-image">
                                            <div class="room-details">
                                                <h5>{{ $order->room->name }}</h5>
                                                <p>{{ $order->room->description }}</p>
                                                <div class="room-specs">
                                                    <span><i class="fas fa-calendar"></i> {{ $order->check_in }} -
                                                        {{ $order->check_out }}</span>
                                                    <span><i class="fas fa-users"></i> {{ $order->guests }} khách</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="order-price">
                                            <div class="price-amount">
                                                <span class="currency">₫</span>
                                                <span class="amount">{{ number_format($order->total_price) }}</span>
                                            </div>
                                            <div class="order-actions">
                                                <a href="#" class="btn btn-outline">Xem chi tiết</a>
                                                @if($order->status === 'pending')
                                                    <button class="btn btn-danger">Hủy đơn</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-header {
            background: linear-gradient(135deg, #003580 0%, #004a9e 100%);
            color: white;
            padding: 40px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
        }

        .profile-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .profile-header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.25rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header i {
            color: #003580;
        }

        .orders-content {
            padding: 30px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 30px;
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .order-item {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }

        .order-item:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            background: #f8f9fa;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e9ecef;
        }

        .order-info h4 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 1.1rem;
        }

        .order-date {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .order-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .order-status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .order-status.confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .order-status.completed {
            background: #d4edda;
            color: #155724;
        }

        .order-status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-details {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .room-info {
            display: flex;
            gap: 15px;
            flex: 1;
        }

        .room-image {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .room-details h5 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 1rem;
        }

        .room-details p {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 0.9rem;
        }

        .room-specs {
            display: flex;
            gap: 15px;
            font-size: 0.8rem;
            color: #888;
        }

        .room-specs span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .order-price {
            text-align: right;
            min-width: 150px;
        }

        .price-amount {
            margin-bottom: 15px;
        }

        .currency {
            font-size: 0.9rem;
            color: #666;
        }

        .amount {
            font-size: 1.5rem;
            font-weight: 600;
            color: #003580;
        }

        .order-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #003580;
            color: white;
        }

        .btn-primary:hover {
            background: #002855;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: #003580;
            border: 1px solid #003580;
        }

        .btn-outline:hover {
            background: #003580;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 15px;
            }

            .profile-header {
                padding: 30px 15px;
            }

            .profile-header h1 {
                font-size: 1.5rem;
            }

            .orders-content {
                padding: 20px;
            }

            .order-details {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .order-price {
                text-align: left;
                width: 100%;
            }

            .order-actions {
                flex-direction: row;
                gap: 10px;
            }

            .room-info {
                flex-direction: column;
                gap: 10px;
            }

            .room-image {
                width: 100%;
                height: 120px;
            }
        }
    </style>
@endsection