

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
                <span>Chọn loại phòng</span>
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
                    <!-- Thẻ card là LABEL, trỏ tới radio ở trong form -->
                    <label class="payment-method selected" for="pm_vnpay" data-method="vnpay">
                        <div class="method-icon vnpay-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="method-info">
                            <h3>Thanh toán VNPay</h3>
                            <p>Thanh toán online nhanh chóng và bảo mật</p>
                            <div class="supported-banks">
                                <small>Hỗ trợ: Visa, Mastercard, ATM nội địa</small>
                            </div>
                        </div>
                        <div class="method-radio">
                            <i class="fas fa-check-circle" style="color:#003580;font-size:20px;"></i>
                        </div>
                    </label>

                    <label class="payment-method" for="pm_cash" data-method="cash">
                        <div class="method-icon cash-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="method-info">
                            <h3>Thanh toán tiền mặt</h3>
                            <p>Thanh toán tại khách sạn khi nhận phòng</p>
                            <div class="supported-banks">
                                <small>Đặt trước, thanh toán khi check-in</small>
                            </div>
                        </div>
                        <div class="method-radio">
                            <i class="far fa-circle" style="color:#ccc;font-size:20px;"></i>
                        </div>
                    </label>
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
                    <label class="form-label">Họ tên</label>
                    <input type="text" class="form-input" value="<?php echo e($user->name ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-input" value="<?php echo e($user->phone ?? ''); ?>" placeholder="Nhập số điện thoại của bạn">
                </div>
                <div class="form-group">
                    <label class="form-label">Email xác nhận</label>
                    <input type="email" class="form-input" value="<?php echo e($user->email ?? ''); ?>">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:end;">
                    <div class="form-group">
                        <label class="form-label">Ngày check-in</label>
                        <input type="date" class="detail-form-input" id="checkin-date" value="<?php echo e($checkin ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ngày check-out</label>
                        <input type="date" class="detail-form-input" id="checkout-date" value="<?php echo e($checkout ?? ''); ?>" required>
                    </div>
                </div>
            </div>

            <form id="payment-form" action="<?php echo e(route('process.payment')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <input type="radio" name="payment_method" id="pm_vnpay" value="vnpay" class="hidden" checked>
                <input type="radio" name="payment_method" id="pm_cash"  value="cash"  class="hidden">

                <input type="hidden" name="rt_id" value="<?php echo e($roomType->rt_id ?? ''); ?>">
                <input type="hidden" name="checkin" value="<?php echo e($checkin); ?>">
                <input type="hidden" name="checkout" value="<?php echo e($checkout); ?>">
                <input type="hidden" name="guests" value="<?php echo e($guests); ?>">
                
                <input type="hidden" name="total_price" value="<?php echo e((int)($final_total ?? 0)); ?>">
                <input type="hidden" name="discount_code" value="<?php echo e($discount_code); ?>">
                <input type="hidden" name="discount_amount" value="<?php echo e((int)($discount_amount ?? 0)); ?>">

                
                <?php
                    // $selectedServices có thể được controller cung cấp (nếu bạn muốn giữ nguyên)
                    $selectedServices = $selectedServices ?? request('services', []);
                    $services_total = (int)($services_total ?? 0);
                ?>
                <input type="hidden" name="services_total" value="<?php echo e($services_total); ?>">
                <?php if(!empty($selectedServices)): ?>
                    <?php $__currentLoopData = $selectedServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sid => $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="services[<?php echo e((int)($svc['s_id'] ?? $sid)); ?>][s_id]" value="<?php echo e((int)($svc['s_id'] ?? $sid)); ?>">
                        <input type="hidden" name="services[<?php echo e((int)($svc['s_id'] ?? $sid)); ?>][quantity]" value="<?php echo e((int)($svc['quantity'] ?? 0)); ?>">
                        <input type="hidden" name="services[<?php echo e((int)($svc['s_id'] ?? $sid)); ?>][unit_price]" value="<?php echo e((int)($svc['unit_price'] ?? 0)); ?>">
                        <input type="hidden" name="services[<?php echo e((int)($svc['s_id'] ?? $sid)); ?>][pricing_model]" value="<?php echo e((int)($svc['pricing_model'] ?? 0)); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

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
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="item-info">
                        <h4><?php echo e($roomType->type_name ?? 'Tên loại phòng'); ?></h4>
                        <p><?php echo e($roomType->description ?? 'Mô tả loại phòng'); ?></p>
                        <div class="room-specs">
                            <?php if($roomType): ?>
                                <span class="spec-item"><i class="fas fa-users"></i> Tối đa <?php echo e($roomType->max_guests); ?> người</span>
                                <span class="spec-item"><i class="fas fa-bed"></i> <?php echo e($roomType->number_beds); ?> giường</span>
                                <span class="spec-item"><i class="fas fa-expand-arrows-alt"></i> <?php echo e($roomType->room_size); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="item-details" id="order-item-details">
                    <p><i class="fas fa-calendar-alt"></i> <span id="order-checkin"><?php echo e($checkin ?? '...'); ?></span> - <span id="order-checkout"><?php echo e($checkout ?? '...'); ?></span></p>
                    <p><i class="fas fa-moon"></i> <span id="order-nights"><?php echo e((int)($nights ?? 0)); ?></span> đêm</p>
                    <p><i class="fas fa-users"></i> <?php echo e($guests ?? '...'); ?> người lớn</p>
                </div>
            </div>

            <?php
                $nightsVal      = (int)($nights ?? 0);
                $roomTotalVal   = (int)($roomTotal ?? 0);
                $discountAmount = (int)($discount_amount ?? 0);
                $servicesTotal  = (int)($services_total ?? 0);
                $finalTotal     = max(0, $roomTotalVal - $discountAmount + $servicesTotal);
                $pricePerNight  = (int)($roomType->base_price ?? 0);
            ?>

            <div class="price-breakdown">
                <div class="price-row">
                    <span>Giá loại phòng (<span id="order-nights2"><?php echo e($nightsVal); ?></span> đêm)</span>
                    <span id="order-room-price"><?php echo e(number_format($roomTotalVal, 0, ',', '.')); ?>₫</span>
                </div>

                <?php if($servicesTotal > 0): ?>
                    <div class="price-row services-total">
                        <span>Dịch vụ kèm</span>
                        <span id="order-services-price"><?php echo e(number_format($servicesTotal, 0, ',', '.')); ?>₫</span>
                    </div>
                <?php endif; ?>

                <?php if($discountAmount > 0): ?>
                    <div class="price-row discount">
                        <span>Giảm giá (<?php echo e((int)($discount_percent ?? 0)); ?>%)</span>
                        <span>-<?php echo e(number_format($discountAmount, 0, ',', '.')); ?>₫</span>
                    </div>
                <?php endif; ?>

                <div class="price-row total">
                    <span>Tổng cộng</span>
                    <span id="order-total"><?php echo e(number_format($finalTotal, 0, ',', '.')); ?>₫</span>
                </div>
            </div>

            <?php if(request('discount_code') && !$voucher): ?>
                <div class="warning-inline">
                    <i class="fas fa-exclamation-circle"></i>
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

            <div class="note-box">
                <i class="fas fa-info-circle"></i>
                <strong>Lưu ý:</strong> Bạn sẽ được admin assign phòng cụ thể sau khi hoàn tất đặt loại phòng.
            </div>
        </div>
    </div>
</div>

<style>
    :root{
        --brand:#003580;
        --brand-d:#002147;
        --brand-mid:#1e3c72;
        --accent:#2a5298;
        --ok:#1a7f37;
        --bg:#f6f8ff;
        --card:#ffffff;
        --border:#e5eaf5;
        --muted:#66728c;
        --danger:#d7263d;
        --shadow:0 10px 30px rgba(0,53,128,.08);
    }
    .hidden{position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;}
    .payment{max-width:1200px;margin:0 auto;padding:24px}
    .payment-header{background:linear-gradient(120deg, #f8fbff, #eef4ff);border:1px solid var(--border);border-radius:18px;padding:20px 24px;margin-bottom:22px;box-shadow:var(--shadow)}
    .header-top{display:flex;align-items:center;justify-content:space-between}
    .logo-section{display:flex;align-items:center;gap:12px}
    .logo-icon{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--brand-mid),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;box-shadow:0 6px 18px rgba(0,53,128,.25)}
    .logo-text{font-weight:800;font-size:20px;color:var(--brand)}
    .security-badge{display:flex;align-items:center;gap:8px;color:var(--muted);font-weight:600}
    .progress-bar{height:6px;background:#eaf1ff;border-radius:999px;margin:16px 0 10px;overflow:hidden}
    .progress-fill{height:100%;width:75%;background:linear-gradient(90deg,var(--brand-mid),var(--accent));border-radius:999px}
    .step-indicator{display:grid;grid-template-columns:repeat(4,1fr);gap:8px}
    .step{display:flex;align-items:center;gap:8px;padding:10px;border:1px dashed #dbe5ff;border-radius:10px;color:var(--muted);background:#fafcff}
    .step.active,.step.completed{border-style:solid;border-color:#cfe0ff;background:#f5f9ff;color:var(--brand)}
    .payment-content{display:grid;grid-template-columns:1.3fr .8fr;gap:24px}
    .payment-form{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:20px;box-shadow:var(--shadow)}
    .form-section{border:1px solid #edf2ff;border-radius:14px;padding:16px 16px 8px;margin-bottom:16px;background:#fbfdff}
    .section-title{display:flex;align-items:center;gap:10px;margin-bottom:12px}
    .section-icon{width:36px;height:36px;border-radius:10px;background:#eaf1ff;display:flex;align-items:center;justify-content:center;color:var(--brand)}
    .section-title span{font-weight:800;color:#1f2b48}
    .payment-methods{display:flex;flex-direction:column;gap:12px}
    .payment-method{border:2px solid #e0e6f5;border-radius:14px;padding:18px;cursor:pointer;transition:all .25s ease;display:flex;align-items:center;gap:16px;background:#fff}
    .payment-method:hover{border-color:var(--brand);box-shadow:0 8px 20px rgba(0,53,128,.12);transform:translateY(-1px)}
    .payment-method.selected{border-color:var(--brand);background:#f7faff;box-shadow:0 8px 20px rgba(0,53,128,.15)}
    .method-icon{width:52px;height:52px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:24px;color:#fff;flex-shrink:0}
    .vnpay-icon{background:linear-gradient(135deg,var(--brand-mid) 0%,var(--accent) 100%)}
    .cash-icon{background:linear-gradient(135deg,#16a085 0%,#27ae60 100%)}
    .method-info{flex:1}
    .method-info h3{margin:0 0 6px 0;font-size:16px;font-weight:800;color:#21325b}
    .method-info p{margin:0 0 4px 0;color:#51618a;font-size:14px}
    .supported-banks small{color:#7a88a8;font-size:12px}
    .form-group{margin-bottom:14px}
    .form-label{display:block;font-size:13px;font-weight:700;color:#344366;margin-bottom:6px}
    .form-input,.detail-form-input{width:100%;padding:12px 12px;border:1px solid #d9e4ff;border-radius:10px;font-size:14px;transition:border-color .2s, box-shadow .2s;background:#fff}
    .form-input:focus,.detail-form-input:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 4px rgba(0,53,128,.08)}
    .checkout-button{width:100%;padding:16px 22px;background:linear-gradient(135deg,var(--brand-mid) 0%,var(--accent) 100%);color:#fff;border:none;border-radius:12px;font-size:16px;font-weight:800;cursor:pointer;transition:transform .15s, box-shadow .15s;display:flex;align-items:center;justify-content:center;gap:10px;margin-top:18px;box-shadow:0 12px 26px rgba(0,53,128,.25)}
    .checkout-button:hover{transform:translateY(-1px);box-shadow:0 14px 30px rgba(0,53,128,.3)}
    .checkout-button:active{transform:translateY(0)}
    .order-summary{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:20px;height:max-content;position:sticky;top:20px;box-shadow:var(--shadow)}
    .summary-title{display:flex;align-items:center;gap:10px;margin-bottom:12px}
    .booking-item{border:1px solid #eef2ff;background:#fbfdff;border-radius:12px;padding:12px;margin-bottom:12px}
    .item-header{display:flex;gap:12px}
    .item-icon{width:36px;height:36px;border-radius:10px;background:#eaf1ff;display:flex;align-items:center;justify-content:center;color:var(--brand)}
    .item-info h4{margin:0 0 4px 0;font-size:16px;color:#1f2b48;font-weight:800}
    .item-info p{margin:0;color:#6c7aa6;font-size:13px}
    .room-specs{display:flex;flex-wrap:wrap;gap:10px;margin-top:8px}
    .spec-item{font-size:12px;color:#6b7796;display:flex;align-items:center;gap:6px}
    .price-breakdown{border-top:1px solid #eef2ff;margin-top:10px;padding-top:12px}
    .price-row{display:flex;align-items:center;justify-content:space-between;padding:8px 0;color:#2a3553}
    .price-row.services-total{border-top:1px dashed #d9e4ff;margin-top:6px;padding-top:10px;color:var(--brand);font-weight:800}
    .price-row.discount{color:#e53935;font-weight:800}
    .price-row.total{font-size:18px;font-weight:900;color:#122041;border-top:2px solid #eaf0ff;margin-top:6px;padding-top:10px}
    .payment-security-info{margin-top:16px;padding:12px;background:#f7faff;border-radius:10px;border:1px solid #eaf1ff}
    .security-item{display:flex;align-items:center;gap:8px;margin-bottom:6px;font-size:13px;color:#536289}
    .security-item i{color:var(--brand)}
    .warning-inline{color:#e53935;font-size:14px;margin:10px 0;font-weight:800;display:flex;align-items:center;gap:8px}
    .note-box{margin-top:16px;padding:14px;background:#f0f6ff;border-radius:10px;font-size:14px;color:#566487;border-left:4px solid var(--brand);display:flex;align-items:flex-start;gap:10px}
    .note-box i{color:var(--brand);margin-top:2px}
    .custom-alert{padding:14px 20px;border-radius:12px;margin-bottom:16px;font-size:15px;font-weight:600;display:flex;align-items:center;gap:10px;background:#eaf4ff;color:#1863b8;border:1.5px solid #b3d8fd;box-shadow:var(--shadow)}
    .custom-alert-success{background:#eafaf1;color:var(--ok);border-color:#b7e4c7}
    .custom-alert-error{background:#fff0f0;color:var(--danger);border-color:#ffd6d6}
    @media (max-width: 992px){
        .payment-content{grid-template-columns:1fr}
        .order-summary{position:relative;top:auto}
    }
</style>

<script>
    function parseDateStr(s){ const [y,m,d]=(s||'').split('-').map(Number); return (y&&m&&d)?new Date(y,m-1,d):null; }
    function calcNights(ci,co){ if(!ci||!co) return 0; const ms=co-ci; let n=Math.floor(ms/86400000); if(n<1) n=1; return n; }

    function updateOrderSummary(){
        const ci = parseDateStr(document.getElementById('checkin-date').value);
        const co = parseDateStr(document.getElementById('checkout-date').value);
        const nights = calcNights(ci,co);

        const pricePerNight = <?php echo e((int)($roomType->base_price ?? 0)); ?>;
        const servicesTotal = <?php echo e((int)($services_total ?? 0)); ?>;
        const discountAmount = <?php echo e((int)($discount_amount ?? 0)); ?>;

        const roomPrice = pricePerNight * (nights||0);
        const finalTotal = Math.max(0, roomPrice - discountAmount + servicesTotal);

        document.getElementById('order-checkin').textContent = document.getElementById('checkin-date').value || '...';
        document.getElementById('order-checkout').textContent = document.getElementById('checkout-date').value || '...';
        document.getElementById('order-nights').textContent = nights||0;
        document.getElementById('order-nights2').textContent = nights||0;
        document.getElementById('order-room-price').textContent = roomPrice.toLocaleString('vi-VN')+'₫';
        document.getElementById('order-total').textContent = finalTotal.toLocaleString('vi-VN')+'₫';

        // đồng bộ hidden input gửi đi
        document.querySelector('input[name="checkin"]').value = document.getElementById('checkin-date').value || '<?php echo e($checkin ?? ""); ?>';
        document.querySelector('input[name="checkout"]').value = document.getElementById('checkout-date').value || '<?php echo e($checkout ?? ""); ?>';
        document.querySelector('input[name="total_price"]').value = finalTotal;
    }

    function selectPaymentMethodByRadio(){
        const vnpChecked=document.getElementById('pm_vnpay').checked;
        const vnpCard=document.querySelector('[for="pm_vnpay"]');
        const cashCard=document.querySelector('[for="pm_cash"]');

        vnpCard.classList.toggle('selected',vnpChecked);
        cashCard.classList.toggle('selected',!vnpChecked);

        vnpCard.querySelector('.method-radio i').className= vnpChecked?'fas fa-check-circle':'far fa-circle';
        vnpCard.querySelector('.method-radio i').style.color= vnpChecked?'#003580':'#ccc';

        cashCard.querySelector('.method-radio i').className= vnpChecked?'far fa-circle':'fas fa-check-circle';
        cashCard.querySelector('.method-radio i').style.color= vnpChecked?'#ccc':'#003580';

        const checkoutText=document.getElementById('checkout-text');
        const checkoutBtn=document.getElementById('checkout-btn');
        if(vnpChecked){
            checkoutText.textContent='Thanh toán VNPay';
            checkoutBtn.style.background='linear-gradient(135deg,#1e3c72 0%,#2a5298 100%)';
        }else{
            checkoutText.textContent='Đặt phòng - Trả tiền mặt';
            checkoutBtn.style.background='linear-gradient(135deg,#16a085 0%,#27ae60 100%)';
        }
    }

    window.addEventListener('DOMContentLoaded',function(){
        // set mặc định theo old()
        const defaultMethod='<?php echo e(old("payment_method","vnpay")); ?>';
        document.getElementById(defaultMethod==='cash'?'pm_cash':'pm_vnpay').checked=true;
        selectPaymentMethodByRadio();

        document.getElementById('pm_vnpay').addEventListener('change',selectPaymentMethodByRadio);
        document.getElementById('pm_cash').addEventListener('change',selectPaymentMethodByRadio);

        document.getElementById('checkin-date').addEventListener('change',updateOrderSummary);
        document.getElementById('checkout-date').addEventListener('change',updateOrderSummary);

        // Khởi tạo lại tổng (trường hợp controller đã tính sẵn)
        updateOrderSummary();

        document.getElementById('payment-form').addEventListener('submit',function(){
            // đảm bảo hidden cập nhật cuối
            updateOrderSummary();
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/user/payment.blade.php ENDPATH**/ ?>