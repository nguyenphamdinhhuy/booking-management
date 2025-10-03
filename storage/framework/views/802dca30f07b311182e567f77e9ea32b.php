<?php $__env->startSection('title', 'Đặt phòng thành công'); ?>

<?php $__env->startSection('content'); ?>
<div class="bks"> 
    <div class="bks-header">
        <div class="bks-header__top">
            <div class="bks-brand">
                <div class="bks-brand__icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="bks-brand__text">BookingPay</div>
            </div>
            <div class="bks-secure">
                <i class="fas fa-shield-alt"></i>
                <span>Bảo mật SSL</span>
            </div>
        </div>

        <div class="bks-progress">
            <div class="bks-progress__fill" style="width:100%"></div>
        </div>

        <div class="bks-steps">
            <div class="bks-step bks-step--done">
                <i class="fas fa-check-circle"></i><span>Chọn phòng</span>
            </div>
            <div class="bks-step bks-step--done">
                <i class="fas fa-check-circle"></i><span>Thông tin</span>
            </div>
            <div class="bks-step bks-step--done">
                <i class="fas fa-check-circle"></i><span>Thanh toán</span>
            </div>
            <div class="bks-step bks-step--active">
                <i class="fas fa-check-circle"></i><span>Hoàn tất</span>
            </div>
        </div>
    </div>

    <div class="bks-container">
        <div class="bks-grid">
            <div class="bks-card">
                <div class="bks-success">
                    <div class="bks-success__icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="bks-success__title">Đặt phòng thành công!</h2>
                    <p class="bks-success__msg">
                        Cảm ơn bạn đã đặt phòng tại hệ thống của chúng tôi.<br>
                        Thông tin đặt phòng đã được gửi về email của bạn.
                    </p>
                </div>

                <div class="bks-section">
                    <h3 class="bks-section__title"><i class="fas fa-receipt"></i> Chi tiết đặt phòng</h3>

                    <div class="bks-two-col">
                        <div class="bks-info">
                            <h4 class="bks-info__title">Thông tin đơn hàng</h4>
                            <div class="bks-info__list">
                                <div class="bks-info__row">
                                    <span>Mã đơn:</span>
                                    <strong>#<?php echo e($booking->b_id); ?></strong>
                                </div>
                                <div class="bks-info__row">
                                    <span>Khách hàng:</span>
                                    <?php echo e($user->name ?? 'N/A'); ?>

                                </div>
                                <div class="bks-info__row">
                                    <span>Email:</span>
                                    <?php echo e($user->email ?? 'N/A'); ?>

                                </div>
                                <div class="bks-info__row">
                                    <span>Trạng thái:</span>
                                    <?php $paid = isset($isPaid) ? $isPaid : ((int)($booking->payment_status ?? 0) === 1); ?>
                                    <?php if($paid): ?>
                                    <span class="bks-badge bks-badge--success">Đã thanh toán</span>
                                    <?php else: ?>
                                    <span class="bks-badge bks-badge--danger">Chưa thanh toán</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="bks-info">
                            <h4 class="bks-info__title">Thông tin phòng</h4>
                            <div class="bks-info__list">
                                <div class="bks-info__row">
                                    <span>Tên loại phòng:</span>
                                    <?php echo e($roomType->type_name ?? 'N/A'); ?>

                                </div>
                                <div class="bks-info__row">
                                    <span>Giá/đêm:</span>
                                    <?php echo e($roomType->formatted_price ?? 'N/A'); ?>

                                </div>
                                <div class="bks-info__row">
                                    <span>Số khách:</span>
                                    <?php echo e($details->guests ?? 'N/A'); ?> người
                                </div>
                                <div class="bks-info__row">
                                    <span>Số đêm:</span>
                                    <?php echo e($booking->nights ?? 1); ?> đêm
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bks-dates">
                        <div class="bks-date">
                            <i class="fas fa-calendar-check bks-date__icon"></i>
                            <div>
                                <div class="bks-date__label">Ngày nhận phòng</div>
                                <div class="bks-date__value"><?php echo e($booking->formatted_check_in ?? 'N/A'); ?></div>
                            </div>
                        </div>
                        <div class="bks-date">
                            <i class="fas fa-calendar-times bks-date__icon"></i>
                            <div>
                                <div class="bks-date__label">Ngày trả phòng</div>
                                <div class="bks-date__value"><?php echo e($booking->formatted_check_out ?? 'N/A'); ?></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bks-services">
                        <div class="bks-services__head">
                            <div class="bks-ico"><i class="fas fa-concierge-bell"></i></div>
                            <h4>Dịch vụ kèm theo</h4>
                        </div>

                        <?php if($services->count() > 0): ?>
                        <div class="bks-services__list">
                            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bks-svc-row">
                                <div class="bks-svc-row__left">
                                    <div class="bks-svc__name"><?php echo e($svc->service_name); ?></div>
                                    <div class="bks-svc__meta">
                                        <span class="bks-chip"><?php echo e($svc->pricing_label); ?></span>
                                        <?php if($svc->scheduled_fmt): ?>
                                        <span class="bks-dot">•</span>
                                        <span class="bks-muted">Lịch: <?php echo e($svc->scheduled_fmt); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="bks-svc-row__qty">x<?php echo e($svc->quantity_int); ?></div>
                                <div class="bks-svc-row__right">
                                    <div class="bks-svc__unit"><?php echo e($svc->unit_price_fmt); ?></div>
                                    <div class="bks-svc__sum"><?php echo e($svc->subtotal_fmt); ?></div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="bks-services__total">
                            <span>Tổng dịch vụ</span>
                            <span class="bks-strong"><?php echo e($services_total_fmt); ?></span>
                        </div>
                        <?php else: ?>
                        <div class="bks-services__empty">
                            <i class="far fa-smile"></i> Không có dịch vụ kèm theo.
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="bks-summary">
                        <h4 class="bks-info__title">Tổng kết thanh toán</h4>
                        <div class="bks-info__list">
                            <div class="bks-info__row">
                                <span>Giá phòng (<?php echo e($booking->nights ?? 1); ?> đêm):</span>
                                <?php echo e($room_only_total_fmt); ?>

                            </div>

                            <?php if($services_total > 0): ?>
                            <div class="bks-info__row">
                                <span>Dịch vụ kèm theo:</span>
                                <?php echo e($services_total_fmt); ?>

                            </div>
                            <?php endif; ?>

                            <?php if(!empty($voucher)): ?>
                            <div class="bks-info__row bks-info__row--discount">
                                <span>Mã giảm giá (<?php echo e($voucher->v_code ?? ''); ?>):</span>
                                -<?php echo e($discount_amount_fmt); ?>

                            </div>
                            <?php endif; ?>

                            <div class="bks-info__row bks-info__row--total">
                                <span>Tổng thanh toán:</span>
                                <strong><?php echo e($booking->formatted_total_price); ?></strong>
                            </div>
                        </div>
                    </div>



                    <div class="bks-actions">
                        <a href="<?php echo e(route('index')); ?>" class="bks-btn bks-btn--primary">
                            <i class="fas fa-home"></i> Về trang chủ
                        </a>
                        <a href="<?php echo e(route('booking.history', ['userId' => Auth::id()])); ?>" class="bks-btn bks-btn--ghost">
                            <i class="fas fa-history"></i> Lịch sử đặt phòng
                        </a>
                    </div>

                    <div class="bks-help">
                        <h4 class="bks-info__title">Cần hỗ trợ?</h4>
                        <p><i class="fas fa-phone"></i> Hotline: <strong>1900 1234</strong></p>
                        <p><i class="fas fa-envelope"></i> Email: <strong>support@hotel.com</strong></p>
                        <p><i class="fas fa-info-circle"></i> Chúng tôi sẽ liên hệ trước ngày nhận phòng để xác nhận thông tin.</p>
                    </div>
                </div>
            </div>

            <aside class="bks-side">
                <div class="bks-side__title">
                    <div class="bks-ico"><i class="fas fa-file-invoice-dollar"></i></div>
                    <span>Tóm tắt</span>
                </div>

                <div class="bks-side__row">
                    <span>Giá phòng (<?php echo e($booking->nights ?? 1); ?> đêm)</span>
                    <span><?php echo e($room_only_total_fmt); ?></span>
                </div>
                <div class="bks-side__row">
                    <span>Dịch vụ</span>
                    <span><?php echo e($services_total_fmt); ?></span>
                </div>
                <?php if(!empty($voucher)): ?>
                <div class="bks-side__row bks-side__row--discount">
                    <span>Giảm giá</span>
                    <span>-<?php echo e($discount_amount_fmt); ?></span>
                </div>
                <?php endif; ?>
                <div class="bks-side__total">
                    <span>Tổng cộng</span>
                    <span><?php echo e($booking->formatted_total_price); ?></span>
                </div>

                <div class="bks-side__badge">
                    <?php if($paid): ?>
                    <span class="bks-paid"><i class="fas fa-shield-check"></i> ĐÃ THANH TOÁN</span>
                    <?php else: ?>
                    <span class="bks-unpaid"><i class="fas fa-exclamation-circle"></i> CHƯA THANH TOÁN</span>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</div>

<style>
    /* ==== booking.com-ish palette & scoped styles ==== */
    .bks {
        --bks-blue: #003580;
        --bks-blue-d: #00224f;
        --bks-ink: #1a1a1a;
        --bks-text: #1c1c1c;
        --bks-muted: #5f6a7a;
        --bks-line: #e7ecf5;
        --bks-card: #fff;
        --bks-bg: #f7f9fc;
        --bks-accent: #2a6ae9;
        --bks-ok: #1a7f37;
        --bks-danger: #d7263d;
        --bks-shadow: 0 12px 30px rgba(0, 27, 80, .08);
    }

    .bks {
        max-width: 1200px;
        margin: 0 auto;
        padding: 22px;
        font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, 'Helvetica Neue', Arial
    }

    /* Header */
    .bks-header {
        background: linear-gradient(120deg, #f4f7ff, #eef3ff);
        border: 1px solid var(--bks-line);
        border-radius: 16px;
        padding: 18px 20px;
        margin-bottom: 20px;
        box-shadow: var(--bks-shadow)
    }

    .bks-header__top {
        display: flex;
        align-items: center;
        justify-content: space-between
    }

    .bks-brand {
        display: flex;
        align-items: center;
        gap: 10px
    }

    .bks-brand__icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--bks-blue), var(--bks-blue-d));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff
    }

    .bks-brand__text {
        font-weight: 800;
        color: var(--bks-blue)
    }

    .bks-secure {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--bks-muted);
        font-weight: 600
    }

    .bks-progress {
        height: 6px;
        background: #eaf0ff;
        border-radius: 999px;
        margin: 14px 0 10px;
        overflow: hidden
    }

    .bks-progress__fill {
        height: 100%;
        background: linear-gradient(90deg, var(--bks-blue), #1952a3)
    }

    .bks-steps {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px
    }

    .bks-step {
        display: flex;
        gap: 8px;
        align-items: center;
        padding: 10px;
        border-radius: 10px;
        border: 1px dashed #d7e3ff;
        color: #2c3e66;
        background: #fafcff
    }

    .bks-step--active,
    .bks-step--done {
        border-style: solid;
        border-color: #cfe0ff;
        background: #f5f9ff;
        color: var(--bks-blue)
    }

    /* Layout */
    .bks-container {
        margin-top: 16px
    }

    .bks-grid {
        display: grid;
        grid-template-columns: 1.4fr .8fr;
        gap: 20px
    }

    .bks-card {
        background: var(--bks-card);
        border: 1px solid var(--bks-line);
        border-radius: 16px;
        padding: 18px;
        box-shadow: var(--bks-shadow)
    }

    .bks-side {
        background: var(--bks-card);
        border: 1px solid var(--bks-line);
        border-radius: 16px;
        padding: 16px;
        position: sticky;
        top: 84px;
        height: max-content;
        box-shadow: var(--bks-shadow)
    }

    /* Success */
    .bks-success {
        text-align: center;
        margin-bottom: 10px
    }

    .bks-success__icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        margin: 0 auto 10px;
        background: linear-gradient(135deg, #18a05a, #1fbc6b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 26px;
        box-shadow: 0 10px 24px rgba(24, 160, 90, .25)
    }

    .bks-success__title {
        margin: 0 0 6px 0;
        color: #10224f
    }

    .bks-success__msg {
        color: #5c6a8c
    }

    /* Sections */
    .bks-section__title {
        display: flex;
        gap: 10px;
        align-items: center;
        color: #163060;
        margin: 4px 0 10px 0
    }

    .bks-two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 8px
    }

    .bks-info {
        border: 1px solid #edf2ff;
        background: #fbfdff;
        border-radius: 12px;
        padding: 12px
    }

    .bks-info__title {
        margin: 0 0 8px 0;
        color: #122a57;
        font-weight: 800
    }

    .bks-info__list {
        display: flex;
        flex-direction: column;
        gap: 8px
    }

    .bks-info__row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #1d2d57
    }

    .bks-info__row--discount {
        color: #d03030;
        font-weight: 700
    }

    .bks-info__row--total {
        font-size: 18px;
        font-weight: 900;
        color: #0f224a;
        border-top: 2px solid #eaf0ff;
        margin-top: 6px;
        padding-top: 8px
    }

    .bks-badge {
        padding: 6px 10px;
        border-radius: 999px;
        color: #fff;
        font-weight: 800
    }

    .bks-badge--success {
        background: #1a7f37
    }

    .bks-badge--danger {
        background: #d7263d
    }

    /* Dates */
    .bks-dates {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin: 14px 0
    }

    .bks-date {
        display: flex;
        gap: 10px;
        align-items: center;
        border: 1px solid #e7edff;
        background: #f7faff;
        padding: 12px;
        border-radius: 12px
    }

    .bks-date__icon {
        color: var(--bks-blue)
    }

    .bks-date__label {
        color: #6b7796;
        font-size: 13px
    }

    .bks-date__value {
        font-weight: 800;
        color: #11214b
    }

    /* Services */
    .bks-services {
        border: 1px solid #edf2ff;
        border-radius: 14px;
        padding: 14px;
        background: #fbfdff;
        margin-bottom: 12px
    }

    .bks-services__head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px
    }

    .bks-ico {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #eaf1ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--bks-blue)
    }

    .bks-services__list {
        display: flex;
        flex-direction: column;
        gap: 10px
    }

    .bks-svc-row {
        display: grid;
        grid-template-columns: 1fr 80px 180px;
        gap: 12px;
        align-items: center;
        border: 1px solid #e9eef6;
        background: #fff;
        border-radius: 12px;
        padding: 12px
    }

    .bks-svc-row__left {
        min-width: 0
    }

    .bks-svc__name {
        font-weight: 800;
        color: #152956
    }

    .bks-svc__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 4px
    }

    .bks-chip {
        background: #e9f2ff;
        color: #003580;
        border: 1px solid #cfe0ff;
        border-radius: 999px;
        padding: 4px 8px;
        font-size: 12px;
        font-weight: 700
    }

    .bks-dot {
        color: #9aa8c7
    }

    .bks-muted {
        color: #6c7aa6
    }

    .bks-svc-row__qty {
        text-align: center;
        color: #122a57;
        font-weight: 700
    }

    .bks-svc-row__right {
        text-align: right
    }

    .bks-svc__unit {
        color: #6c7aa6;
        font-size: 13px
    }

    .bks-svc__sum {
        font-weight: 900;
        color: #0e1e46
    }

    .bks-services__total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px dashed #d9e4ff;
        margin-top: 8px;
        padding-top: 10px;
        color: #003580
    }

    .bks-services__total .bks-strong {
        font-weight: 900
    }

    .bks-services__empty {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6c7aa6;
        background: #f7faff;
        border: 1px dashed #d9e4ff;
        padding: 12px;
        border-radius: 12px
    }

    /* Room preview */
    .bks-room {
        margin-top: 10px
    }

    .bks-room__box {
        display: flex;
        gap: 12px;
        border: 1px solid #eef2ff;
        border-radius: 12px;
        padding: 12px;
        background: #fbfdff
    }

    .bks-room__img {
        width: 160px;
        height: 110px;
        object-fit: cover;
        border-radius: 10px
    }

    .bks-room__info h5 {
        margin: 0 0 6px 0
    }

    .bks-room__tags {
        display: flex;
        gap: 10px;
        margin-top: 6px;
        color: #6b7796
    }

    /* Side summary */
    .bks-side__title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px
    }

    .bks-side__row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        color: #1b2b55
    }

    .bks-side__row--discount {
        color: #d03030;
        font-weight: 800
    }

    .bks-side__total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 18px;
        font-weight: 900;
        color: #0f224a;
        border-top: 2px solid #eaf0ff;
        margin-top: 6px;
        padding-top: 10px
    }

    .bks-side__badge {
        margin-top: 10px;
        text-align: center
    }

    .bks-paid {
        background: #eafaf1;
        color: #1a7f37;
        border: 1px solid #b7e4c7;
        padding: 8px 10px;
        border-radius: 999px;
        display: inline-flex;
        gap: 8px;
        align-items: center;
        font-weight: 800
    }

    .bks-unpaid {
        background: #fff0f0;
        color: #d7263d;
        border: 1px solid #ffd6d6;
        padding: 8px 10px;
        border-radius: 999px;
        display: inline-flex;
        gap: 8px;
        align-items: center;
        font-weight: 800
    }

    /* Actions + help */
    .bks-actions {
        display: flex;
        gap: 12px;
        margin-top: 14px
    }

    .bks-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        border-radius: 12px;
        font-weight: 800;
        text-decoration: none
    }

    .bks-btn--primary {
        background: linear-gradient(135deg, var(--bks-blue), #1952a3);
        color: #fff
    }

    .bks-btn--ghost {
        background: #f7faff;
        border: 1px solid #dbe5ff;
        color: #18356e
    }

    .bks-help {
        margin-top: 14px;
        padding: 12px;
        background: #f7faff;
        border: 1px solid #eaf1ff;
        border-radius: 12px;
        color: #4f5f86
    }

    /* Responsive */
    @media (max-width: 992px) {
        .bks-grid {
            grid-template-columns: 1fr
        }

        .bks-side {
            position: relative;
            top: auto
        }

        .bks-svc-row {
            grid-template-columns: 1fr 70px 140px
        }
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/user/booking-success.blade.php ENDPATH**/ ?>