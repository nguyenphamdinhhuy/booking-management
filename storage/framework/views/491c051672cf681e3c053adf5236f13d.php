

<?php $__env->startSection('content'); ?>


<?php if(session('success')): ?>
<div class="custom-alert custom-alert-success" id="alert-success">
    <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="custom-alert custom-alert-error" id="alert-error">
    <i class="fas fa-times-circle"></i> <?php echo e(session('error')); ?>

</div>
<?php endif; ?>


<?php if($errors->any()): ?>
<div class="custom-alert custom-alert-error" id="alert-validate">
    <i class="fas fa-exclamation-triangle"></i>
    <ul style="margin: 0; padding-left: 20px;">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>
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
                    <!-- VNPay Option -->
                    <div class="payment-method selected" onclick="selectPaymentMethod(this, 'vnpay')">
                        <div class="method-icon vnpay-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="method-info">
                            <h3>Thanh toán VNPay</h3>
                            <p>Thanh toán nhanh chóng và bảo mật qua VNPay</p>
                            <div class="supported-banks">
                                <small>Hỗ trợ: Visa, Mastercard, ATM nội địa</small>
                            </div>
                        </div>
                        <div class="method-radio">
                            <i class="fas fa-check-circle" style="color: #003580; font-size: 20px;"></i>
                        </div>
                    </div>

                    <!-- MoMo Option -->
                    <div class="payment-method" onclick="selectPaymentMethod(this, 'momo')">
                        <div class="method-icon momo-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="method-info">
                            <h3>Ví điện tử MoMo</h3>
                            <p>Thanh toán nhanh chóng với ví MoMo</p>
                            <div class="supported-banks">
                                <small>Quét QR hoặc liên kết ví MoMo</small>
                            </div>
                        </div>
                        <div class="method-radio">
                            <i class="far fa-circle" style="color: #ccc; font-size: 20px;"></i>
                        </div>
                    </div>

                    <!-- Bank Transfer Option -->
                    <div class="payment-method" onclick="selectPaymentMethod(this, 'bank')">
                        <div class="method-icon bank-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="method-info">
                            <h3>Chuyển khoản ngân hàng</h3>
                            <p>Chuyển khoản trực tiếp qua ngân hàng</p>
                            <div class="supported-banks">
                                <small>Vietcombank, BIDV, VietinBank, Techcombank...</small>
                            </div>
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
                    <input type="text" class="form-input" value="<?php echo e($user->id ?? ''); ?>" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Họ tên</label>
                    <input type="text" class="form-input" value="<?php echo e($user->name ?? ''); ?>" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-input" value="<?php echo e($user->phone ?? ''); ?>" placeholder="Nhập số điện thoại của bạn">
                </div>
                <div class="form-group">
                    <label class="form-label">Email xác nhận</label>
                    <input type="email" class="form-input" value="<?php echo e($user->email ?? ''); ?>" readonly>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: end;">
                    <div class="form-group">
                        <label class="form-label">Ngày check-in</label>
                        <input type="date" class="detail-form-input" id="checkin-date" value="<?php echo e($checkin ?? ''); ?>" required readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ngày check-out</label>
                        <input type="date" class="detail-form-input" id="checkout-date" value="<?php echo e($checkout ?? ''); ?>" required readonly>
                    </div>
                </div>
            </div>

            <form id="payment-form" action="<?php echo e(route('process.payment')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="r_id" value="<?php echo e($room->r_id); ?>">
                <input type="hidden" name="checkin" value="<?php echo e($checkin); ?>">
                <input type="hidden" name="checkout" value="<?php echo e($checkout); ?>">
                <input type="hidden" name="guests" value="<?php echo e($guests); ?>">
                <input type="hidden" name="total_price" value="<?php echo e($total_price); ?>">
                <input type="hidden" name="discount_code" value="<?php echo e($discount_code); ?>">
                <input type="hidden" name="discount_amount" value="<?php echo e($discount_amount); ?>">
                <input type="hidden" name="payment_method" id="payment_method" value="vnpay">

                <button type="submit" class="checkout-button" id="checkout-btn">
                    <i class="fas fa-shield-alt"></i>
                    <span id="checkout-text">Thanh toán VNPay</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
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
                        <h4><?php echo e($room->name ?? 'Tên phòng'); ?></h4>
                        <p><?php echo e($room->description ?? 'Mô tả phòng'); ?></p>
                    </div>
                </div>
                <div class="item-details" id="order-item-details">
                    <p><i class="fas fa-calendar-alt"></i> <span id="order-checkin"><?php echo e($checkin ?? '...'); ?></span> - <span id="order-checkout"><?php echo e($checkout ?? '...'); ?></span></p>
                    <p><i class="fas fa-moon"></i> <span id="order-nights"><?php echo e(isset($checkin, $checkout) ? ((strtotime($checkout) - strtotime($checkin))/86400) : '...'); ?></span> đêm</p>
                    <p><i class="fas fa-users"></i> <?php echo e($guests ?? '...'); ?> người lớn</p>
                </div>
            </div>

            <?php
            // Tính số đêm
            $nights = 0;
            if (!empty($checkin) && !empty($checkout)) {
            $nights = (strtotime($checkout) - strtotime($checkin)) / 86400;
            if ($nights < 1) $nights=1;
                }
                // Giá phòng/đêm
                $pricePerNight=$room->price_per_night ?? 0;
                // Tổng tiền
                $total = $nights * $pricePerNight;
                $discount_percent = $discount_percent ?? 0;
                $discount_amount = $discount_amount ?? 0;
                $final_total = $total - $discount_amount;
                ?>

                <div class="price-breakdown">
                    <div class="price-row">
                        <span>Giá phòng (<span id="order-nights2"><?php echo e($nights); ?></span> đêm)</span>
                        <span id="order-room-price"><?php echo e(number_format($pricePerNight * $nights, 0, ',', '.')); ?>₫</span>
                    </div>
                    <?php if($discount_amount > 0): ?>
                    <div class="price-row discount" style="color: #e53935; font-weight: bold;">
                        <span>Giảm giá (<?php echo e($discount_percent); ?>%)</span>
                        <span>-<?php echo e(number_format($discount_amount, 0, ',', '.')); ?>₫</span>
                    </div>
                    <?php endif; ?>
                    <div class="price-row total" style="font-size: 18px; font-weight: bold;">
                        <span>Tổng cộng</span>
                        <span id="order-total"><?php echo e(number_format($final_total, 0, ',', '.')); ?>₫</span>
                    </div>
                </div>

                <?php if(request('discount_code') && !$voucher): ?>
                <div style="color: #e53935; font-size: 15px; margin: 10px 0; font-weight: bold;">
                    Mã giảm giá không hợp lệ hoặc đã hết hạn!
                </div>
                <?php endif; ?>

                <div class="payment-security-info">
                    <div class="security-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Bảo mật 256-bit SSL</span>
                    </div>
                    <div class="security-item">
                        <i class="fas fa-lock"></i>
                        <span>Thông tin được mã hóa</span>
                    </div>
                    <div class="security-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Thanh toán an toàn</span>
                    </div>
                </div>

                <div style="margin-top: 20px; padding: 16px; background: #f0f6ff; border-radius: 8px; font-size: 14px; color: #666; border-left: 4px solid #003580;">
                    <i class="fas fa-info-circle" style="color: #003580;"></i>
                    <strong>Lưu ý:</strong> Bạn sẽ không bị tính phí cho đến khi xác nhận đặt phòng thành công.
                </div>


        </div>
    </div>
</div>

<style>
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .payment-method {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 16px;
        background: white;
    }

    .payment-method:hover {
        border-color: #003580;
        box-shadow: 0 4px 12px rgba(0, 53, 128, 0.1);
    }

    .payment-method.selected {
        border-color: #003580;
        background: #f8fafe;
        box-shadow: 0 4px 12px rgba(0, 53, 128, 0.15);
    }

    .method-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        flex-shrink: 0;
    }

    .vnpay-icon {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }

    .momo-icon {
        background: linear-gradient(135deg, #a21caf 0%, #ec4899 100%);
    }

    .bank-icon {
        background: linear-gradient(135deg, #047857 0%, #10b981 100%);
    }

    .method-info {
        flex: 1;
    }

    .method-info h3 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .method-info p {
        margin: 0 0 4px 0;
        color: #666;
        font-size: 14px;
    }

    .supported-banks {
        margin-top: 4px;
    }

    .supported-banks small {
        color: #888;
        font-size: 12px;
    }

    .payment-security-info {
        margin-top: 20px;
        padding: 16px;
        background: #f8fafe;
        border-radius: 8px;
        border: 1px solid #e3f2fd;
    }

    .security-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 14px;
        color: #666;
    }

    .security-item:last-child {
        margin-bottom: 0;
    }

    .security-item i {
        color: #003580;
        width: 16px;
    }

    .checkout-button {
        width: 100%;
        padding: 18px 24px;
        background: linear-gradient(135deg, #003580 0%, #0057b8 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 24px;
    }

    .checkout-button:hover {
        background: linear-gradient(135deg, #002147 0%, #003d82 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 53, 128, 0.3);
    }

    .checkout-button:active {
        transform: translateY(0);
    }

    .custom-alert {
        padding: 14px 20px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 16px;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        gap: 10px;
        animation: fadeInDown 0.5s;
        position: relative;
        background: #eaf4ff;
        color: #1863b8;
        border: 1.5px solid #b3d8fd;
    }

    .custom-alert-success {
        background: #eafaf1;
        color: #1a7f37;
        border-color: #b7e4c7;
    }

    .custom-alert-error {
        background: #fff0f0;
        color: #d7263d;
        border-color: #ffd6d6;
    }

    .custom-alert i {
        font-size: 20px;
        margin-right: 6px;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    function selectPaymentMethod(element, method) {
        // Remove selected class from all methods
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
            el.querySelector('.method-radio i').className = 'far fa-circle';
            el.querySelector('.method-radio i').style.color = '#ccc';
        });

        // Add selected class to clicked method
        element.classList.add('selected');
        element.querySelector('.method-radio i').className = 'fas fa-check-circle';
        element.querySelector('.method-radio i').style.color = '#003580';

        // Update hidden input and button text
        document.getElementById('payment_method').value = method;
        const checkoutText = document.getElementById('checkout-text');
        const checkoutBtn = document.getElementById('checkout-btn');

        switch (method) {
            case 'vnpay':
                checkoutText.textContent = 'Thanh toán VNPay';
                checkoutBtn.style.background = 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)';
                break;
            case 'momo':
                checkoutText.textContent = 'Thanh toán MoMo';
                checkoutBtn.style.background = 'linear-gradient(135deg, #a21caf 0%, #ec4899 100%)';
                break;
            case 'bank':
                checkoutText.textContent = 'Chuyển khoản ngân hàng';
                checkoutBtn.style.background = 'linear-gradient(135deg, #047857 0%, #10b981 100%)';
                break;
        }
    }

    function updateOrderSummary() {
        const checkin = document.getElementById('checkin-date').value;
        const checkout = document.getElementById('checkout-date').value;
        const pricePerNight = {
            {
                $room - > price_per_night ?? 0
            }
        };

        let nights = 0;
        if (checkin && checkout) {
            nights = (new Date(checkout) - new Date(checkin)) / (1000 * 60 * 60 * 24);
            if (nights < 1) nights = 1;
        }

        document.getElementById('order-checkin').textContent = checkin;
        document.getElementById('order-checkout').textContent = checkout;
        document.getElementById('order-nights').textContent = nights;
        document.getElementById('order-nights2').textContent = nights;
        document.getElementById('order-room-price').textContent = (pricePerNight * nights).toLocaleString('vi-VN') + '₫';
        document.getElementById('order-total').textContent = (pricePerNight * nights).toLocaleString('vi-VN') + '₫';
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        updateOrderSummary();
        // Set default payment method
        selectPaymentMethod(document.querySelector('.payment-method.selected'), 'vnpay');
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/user/payment.blade.php ENDPATH**/ ?>