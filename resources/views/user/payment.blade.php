@extends('layout.app')

@section('content')
<div class="payment">
    <!-- Header -->
    <div class="payment-header">
        <div class="header-top">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="logo-text">BookingPay</div>
            </div>
            <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                <span>Bảo mật SSL</span>
            </div>
        </div>

        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>

        <div class="step-indicator">
            <div class="step completed">
                <i class="fas fa-check-circle"></i>
                <span>Chọn phòng</span>
            </div>
            <div class="step completed">
                <i class="fas fa-check-circle"></i>
                <span>Thông tin</span>
            </div>
            <div class="step active">
                <i class="fas fa-credit-card"></i>
                <span>Thanh toán</span>
            </div>
            <div class="step">
                <i class="fas fa-check-circle"></i>
                <span>Hoàn tất</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="payment-content">
        <!-- Payment Form -->
        <div class="payment-form">
            <div class="form-section">
                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <span>Chọn phương thức thanh toán</span>
                </div>

                <div class="payment-methods">
                    <div class="payment-method selected" onclick="selectPaymentMethod(this)">
                        <div class="method-icon momo-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="method-info">
                            <h3>Ví điện tử MoMo</h3>
                            <p>Thanh toán nhanh chóng và bảo mật với MoMo</p>
                        </div>
                        <div class="method-radio">
                            <i class="fas fa-check-circle" style="color: #003580; font-size: 20px;"></i>
                        </div>
                    </div>

                    <div class="payment-method" onclick="selectPaymentMethod(this)">
                        <div class="method-icon bank-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="method-info">
                            <h3>Chuyển khoản ngân hàng</h3>
                            <p>Chuyển khoản trực tiếp qua ngân hàng</p>
                        </div>
                        <div class="method-radio">
                            <i class="far fa-circle" style="color: #ccc; font-size: 20px;"></i>
                        </div>
                    </div>

                    <div class="payment-method" onclick="selectPaymentMethod(this)">
                        <div class="method-icon card-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="method-info">
                            <h3>Thẻ tín dụng/ghi nợ</h3>
                            <p>Visa, Mastercard, JCB được hỗ trợ</p>
                        </div>
                        <div class="method-radio">
                            <i class="far fa-circle" style="color: #ccc; font-size: 20px;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <span>Thông tin thanh toán</span>
                </div>

                <div class="form-group">
                    <label class="form-label">ID khách hàng</label>
                    <input type="number" class="form-input" placeholder="Nhập ID khách hàng" value="12345">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Ngày check-in</label>
                        <input type="date" class="detail-form-input" id="checkin-date" required>

                    </div>

                    <div class="form-group">
                        <label class="form-label">Ngày check-out</label>
                        <input type="date" class="detail-form-input" id="checkout-date" required>

                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại MoMo</label>
                    <input type="tel" class="form-input" placeholder="Nhập số điện thoại MoMo của bạn" value="0987654321">
                </div>

                <div class="form-group">
                    <label class="form-label">Email xác nhận</label>
                    <input type="email" class="form-input" placeholder="Nhập email để nhận hóa đơn" value="user@example.com">
                </div>
            </div>

            <button class="checkout-button" onclick="processPayment()">
                <i class="fas fa-shield-alt"></i>
                <span>Thanh toán bảo mật</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <div class="summary-title">
                <div class="section-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <span>Chi tiết đơn hàng</span>
            </div>

            <div class="booking-item">
                <div class="item-header">
                    <div class="item-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div class="item-info">
                        <h4>Deluxe Ocean View Room</h4>
                        <p>Sunrise Hotel & Resort</p>
                    </div>
                </div>
                <div class="item-details">
                    <p><i class="fas fa-calendar-alt"></i> 25/06/2025 - 27/06/2025</p>
                    <p><i class="fas fa-moon"></i> 2 đêm</p>
                    <p><i class="fas fa-users"></i> 2 người lớn</p>
                </div>
            </div>

            <div class="price-breakdown">
                <div class="price-row">
                    <span>Giá phòng (2 đêm)</span>
                    <span>4.800.000₫</span>
                </div>
                <div class="price-row">
                    <span>Thuế và phí dịch vụ</span>
                    <span>240.000₫</span>
                </div>
                <div class="price-row discount">
                    <span>Giảm giá thành viên</span>
                    <span>-240.000₫</span>
                </div>
                <div class="price-row total">
                    <span>Tổng cộng</span>
                    <span>4.800.000₫</span>
                </div>
            </div>

            <div style="margin-top: 20px; padding: 16px; background: #f0f6ff; border-radius: 4px; font-size: 14px; color: #666; border-left: 4px solid #003580;">
                <i class="fas fa-info-circle" style="color: #003580;"></i>
                <strong>Lưu ý:</strong> Bạn sẽ không bị tính phí cho đến khi xác nhận đặt phòng.
            </div>
        </div>
    </div>
</div>
@endsection