


<?php $__env->startSection('title', $roomType->type_name . ' - Chi tiết loại phòng'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .room-detail-container{max-width:1200px;margin:0 auto;padding:0 20px;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif}
    .thumbnail.is-active{outline:2px solid #0071c2;outline-offset:2px}
    .breadcrumb{background:none;padding:20px 0 10px;margin:0;font-size:14px;color:#0071c2}
    .breadcrumb a{color:#0071c2;text-decoration:none}
    .breadcrumb a:hover{text-decoration:underline}
    .room-header{margin-bottom:24px}
    .room-title{font-size:32px;font-weight:700;color:#262626;margin:0 0 8px}
    .room-subtitle{font-size:16px;color:#595959;margin-bottom:16px}
    .rating-section{display:flex;align-items:center;gap:12px;margin-bottom:16px}
    .rating-badge{background:#003b95;color:#fff;padding:6px 12px;border-radius:6px;font-weight:600;font-size:14px}
    .rating-text{color:#262626;font-weight:500}
    .review-count{color:#595959;font-size:14px}
    .main-content{display:grid;grid-template-columns:2fr 400px;gap:40px;margin-bottom:40px}
    .gallery-section{margin-bottom:32px}
    .main-image{width:100%;height:400px;object-fit:cover;border-radius:12px;margin-bottom:16px}
    .image-thumbnails{display:grid;grid-template-columns:repeat(4,1fr);gap:8px}
    .thumbnail{width:100%;height:80px;object-fit:cover;border-radius:6px;cursor:pointer;transition:opacity .2s}
    .thumbnail:hover{opacity:.8}
    .room-info{background:#fff}
    .info-section{margin-bottom:32px}
    .info-title{font-size:22px;font-weight:600;color:#262626;margin:0 0 16px}
    .features-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:24px}
    .feature-item{display:flex;align-items:center;gap:12px;padding:12px;background:#f5f5f5;border-radius:8px}
    .feature-icon{font-size:20px;color:#0071c2}
    .feature-text{font-size:14px;color:#262626}
    .amenities-list{display:grid;grid-template-columns:repeat(2,1fr);gap:8px;margin-top:16px}
    .amenity-item{display:flex;align-items:center;gap:8px;padding:8px 0;font-size:14px;color:#262626}
    .amenity-icon{color:#00c851;font-size:16px}
    .description-text{line-height:1.6;color:#595959;margin-bottom:24px}
    .booking-card{background:#fff;border:1px solid #e6e6e6;border-radius:12px;padding:24px;position:sticky;top:100px;height:fit-content}
    .price-section{margin-bottom:12px}
    .price{font-size:28px;font-weight:700;color:#262626}
    .price-unit{font-size:16px;color:#595959;font-weight:normal}
    .availability-status{padding:12px;border-radius:8px;margin-bottom:24px;font-size:14px;font-weight:500;display:none}
    .available{background:#e8f5e8;color:#2e7d32}
    .limited{background:#fff3e0;color:#ef6c00}
    .unavailable{background:#ffebee;color:#c62828}
    .booking-form{margin-bottom:24px}
    .form-group{margin-bottom:16px}
    .form-label{display:block;font-size:14px;font-weight:500;color:#262626;margin-bottom:6px}
    .form-input{width:100%;padding:12px;border:1px solid #ccc;border-radius:6px;font-size:14px;transition:border-color .2s}
    .form-input:focus{outline:none;border-color:#0071c2}
    .date-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .btn{padding:14px 24px;border-radius:6px;font-weight:600;text-decoration:none;text-align:center;border:none;cursor:pointer;font-size:16px;transition:all .2s;width:100%}
    .btn-primary{background:#0071c2;color:#fff}
    .btn-primary:hover{background:#005999}
    .btn:disabled{background:#ccc;cursor:not-allowed;color:#666}
    .detail-form-group{margin-bottom:16px}
    .detail-form-label{display:block;font-size:14px;font-weight:500;color:#262626;margin-bottom:6px}
    .detail-form-input{width:100%;padding:12px;border:1px solid #ccc;border-radius:6px;font-size:14px;transition:border-color .2s}

    .reviews-section{margin-top:40px;border-top:1px solid #e6e6e6;padding-top:40px}
    .review-item{background:#f8f9fa;border-radius:8px;padding:20px;margin-bottom:16px}
    .review-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
    .reviewer-name{font-weight:600;color:#262626}
    .review-date{font-size:14px;color:#595959}
    .review-rating{display:flex;gap:2px;margin-bottom:8px}
    .star{color:#ffb400;font-size:14px}

    @media (max-width: 768px){
        .main-content{grid-template-columns:1fr;gap:24px}
        .features-grid{grid-template-columns:1fr}
        .amenities-list{grid-template-columns:1fr}
        .date-grid{grid-template-columns:1fr}
        .room-title{font-size:24px}
    }

    /* Services (Booking.com vibes) */
    .services-section{margin-top:24px;border:1px solid #e6e6e6;border-radius:12px;padding:16px;background:#f8fafe;}
    .services-title{display:flex;align-items:center;gap:8px;color:#003580;font-weight:700;font-size:18px;margin:0 0 12px}
    .service-item{display:grid;grid-template-columns:1fr auto 90px;gap:12px;align-items:center;padding:10px 12px;border-radius:10px;border:1px solid #e9eef6;background:#fff;margin-bottom:10px}
    .service-name{font-weight:600;color:#1a1a1a}
    .service-price{color:#003580;font-weight:700}
    .service-qty{display:flex;align-items:center;gap:6px}
    .service-qty input{width:54px;padding:10px 8px;border:1px solid #cbd6ee;border-radius:8px;font-size:14px;text-align:center}
    .service-checkbox{display:flex;align-items:center;gap:10px}
    .service-checkbox input[type="checkbox"]{width:18px;height:18px;accent-color:#003580}

    .price-row.services-total{border-top:1px dashed #cdd9f0;margin-top:6px;padding-top:6px;color:#003580;font-weight:600}
</style>

<div class="room-detail-container">
    <nav class="breadcrumb">
        <a href="<?php echo e(url('/')); ?>">Trang chủ</a> &gt;
        <a href="<?php echo e(route('all_rooms')); ?>">Loại phòng</a> &gt;
        <?php echo e($roomType->type_name); ?>

    </nav>

    <?php if(session('error')): ?>
    <div class="custom-alert-error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if(session('success')): ?>
    <div class="custom-alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="room-header">
        <h1 class="room-title"><?php echo e($roomType->type_name); ?></h1>
        <p class="room-subtitle"><?php echo e($roomType->room_size ? 'Diện tích: ' . $roomType->room_size : 'Phòng cao cấp'); ?></p>

        <?php if($totalReviews > 0): ?>
        <div class="rating-section">
            <div class="rating-badge"><?php echo e($averageRating); ?>/5</div>
            <span class="rating-text">Tuyệt vời</span>
            <span class="review-count">(<?php echo e($totalReviews); ?> đánh giá)</span>
        </div>
        <?php endif; ?>
    </div>

    <div class="main-content">
        <!-- Left -->
        <div class="room-info">
            
            <div class="gallery-section">
                <?php
                    $raw = $roomType->images ?? [];
                    $images = is_array($raw) ? $raw : (is_string($raw) && $raw !== '' ? (json_decode($raw, true) ?? (strpos($raw, ',') !== false ? array_map('trim', explode(',', $raw)) : [trim($raw)])) : []);
                    $imageUrls = [];
                    foreach ($images as $img) {
                        $img = trim((string)$img);
                        if ($img === '') continue;
                        $imageUrls[] = preg_match('#^https?://#i', $img) ? $img : asset($img);
                    }
                    if (count($imageUrls) === 0) $imageUrls[] = asset('assets/images/default-room.jpg');
                ?>

                <img src="<?php echo e($imageUrls[0]); ?>" alt="<?php echo e($roomType->type_name); ?>" class="main-image" id="mainImage"
                     onerror="this.onerror=null;this.src='<?php echo e(asset('assets/images/default-room.jpg')); ?>';">

                <?php if(count($imageUrls) > 1): ?>
                <div class="image-thumbnails">
                    <?php $__currentLoopData = $imageUrls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img src="<?php echo e($url); ?>" alt="Ảnh <?php echo e($i+1); ?>" class="thumbnail <?php echo e($i===0 ? 'is-active' : ''); ?>"
                         data-src="<?php echo e($url); ?>"
                         onerror="this.onerror=null;this.src='<?php echo e(asset('assets/images/default-room.jpg')); ?>';">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="info-section">
                <h2 class="info-title">Thông tin phòng</h2>
                <div class="features-grid">
                    <div class="feature-item"><span class="feature-icon">👥</span><span class="feature-text">Tối đa <?php echo e($roomType->max_guests); ?> khách</span></div>
                    <div class="feature-item"><span class="feature-icon">🛏️</span><span class="feature-text"><?php echo e($roomType->number_beds); ?> giường</span></div>
                    <?php if($roomType->room_size): ?>
                    <div class="feature-item"><span class="feature-icon">📏</span><span class="feature-text"><?php echo e($roomType->room_size); ?></span></div>
                    <?php endif; ?>
                    <div class="feature-item"><span class="feature-icon">🏨</span><span class="feature-text"><?php echo e($availableRooms); ?> phòng có sẵn</span></div>
                </div>
            </div>

            
            <?php if($roomType->description): ?>
            <div class="info-section">
                <h2 class="info-title">Mô tả</h2>
                <p class="description-text"><?php echo e($roomType->description); ?></p>
            </div>
            <?php endif; ?>

            
            <?php if(!empty($roomType->amenities)): ?>
            <div class="info-section">
                <h2 class="info-title">Tiện nghi</h2>
                <div class="amenities-list">
                    <?php $__currentLoopData = $roomType->amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="amenity-item"><span class="amenity-icon">✓</span><span><?php echo e($amenity); ?></span></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if(isset($services) && count($services) > 0): ?>
            <div class="info-section services-section">
                <div class="services-title">
                    <span style="font-size:18px;">🛎️</span><span>Dịch vụ kèm theo</span>
                </div>

                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="service-item" data-sid="<?php echo e($svc->s_id); ?>" data-price="<?php echo e((int)$svc->price); ?>">
                    <div class="service-checkbox">
                        <input type="checkbox" class="svc-check" id="svc_<?php echo e($svc->s_id); ?>">
                        <label for="svc_<?php echo e($svc->s_id); ?>" class="service-name"><?php echo e($svc->name); ?></label>
                    </div>
                    <div class="service-price"><?php echo e(number_format((int)$svc->price, 0, ',', '.')); ?>₫</div>
                    <div class="service-qty">
                        <label for="qty_<?php echo e($svc->s_id); ?>" style="font-size:12px;color:#666;">Số lượng</label>
                        <input type="number" id="qty_<?php echo e($svc->s_id); ?>" class="svc-qty" value="1" min="1" step="1" disabled>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div style="font-size:12px;color:#666;margin-top:6px;">
                    * Giá dịch vụ hiện tính theo “mỗi lần”. Nếu cần theo đêm/đầu khách, sẽ nâng cấp sau.
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right (card) -->
        <div class="booking-card">
            <div class="price-section">
                <span class="price"><?php echo e(number_format($roomType->base_price, 0, ',', '.')); ?> VNĐ</span>
                <span class="price-unit">/ đêm</span>
            </div>

            
            <div class="price-row services-total" id="services-total-row" style="display:none;justify-content:space-between;margin:8px 0;">
                <span>Dịch vụ kèm</span>
                <span id="order-services-price">0₫</span>
            </div>
            <div class="price-row" id="mini-total-row" style="display:flex;justify-content:space-between;margin:4px 0 16px;font-weight:700;">
                <span>Tạm tính</span>
                <span id="mini-order-total"><?php echo e(number_format($roomType->base_price, 0, ',', '.')); ?>₫</span>
            </div>

            <div class="availability-status" id="availabilityStatus"></div>

            <form class="booking-form" method="GET" action="<?php echo e(route('room-type.validate-booking')); ?>" onsubmit="return validateBookingForm();">
                <input type="hidden" name="room_type_id" value="<?php echo e($roomType->rt_id); ?>">

                
                <div id="service-hidden-bag"></div>

                <div class="date-grid">
                    <div class="form-group">
                        <label class="form-label">Nhận phòng</label>
                        <input type="date" name="check_in" class="form-input" id="checkin-date" min="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Trả phòng</label>
                        <input type="date" name="check_out" class="form-input" id="checkout-date" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Số khách</label>
                    <select name="guests" class="form-input" required>
                        <?php for($i = 1; $i <= $roomType->max_guests; $i++): ?>
                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?> khách</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="detail-form-group">
                    <label class="detail-form-label">Mã giảm giá</label>
                    <input type="text" class="detail-form-input" name="discount_code" id="discount-code" placeholder="Nhập mã giảm giá nếu có">
                    <span id="voucher-error" style="color:#e53935;font-size:13px;display:none;"></span>
                </div>

                
                <input type="hidden" name="services_total" value="0">

                <button type="submit" class="btn btn-primary" id="bookingBtn" style="text-align:center;justify-content:center;">
                    Đặt phòng ngay
                </button>
            </form>

            <div style="font-size:12px;color:#595959;text-align:center;margin-top:12px;">
                Đặt phòng ngay để có chỗ nghỉ tốt nhất
            </div>
        </div>
    </div>

    
    <?php if($totalReviews > 0): ?>
    <div class="reviews-section">
        <h2 class="info-title">Đánh giá từ khách hàng (<?php echo e($totalReviews); ?>)</h2>
        <?php $__currentLoopData = $reviews->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="review-item">
            <div class="review-header">
                <span class="reviewer-name"><?php echo e($review->customer_name); ?></span>
                <span class="review-date"><?php echo e(\Carbon\Carbon::parse($review->created_at)->format('d/m/Y')); ?></span>
            </div>
            <div class="review-rating">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <span class="star"><?php echo e($i <= ($review->rating ?? 0) ? '★' : '☆'); ?></span>
                <?php endfor; ?>
            </div>
            <p><?php echo e($review->comment ?? 'Khách hàng đã để lại đánh giá tích cực.'); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
</div>

<script>
function changeMainImage(src){ document.getElementById('mainImage').src = src; }
function vn(n){ return (n||0).toLocaleString('vi-VN')+'₫'; }

function getNightsFromInputs(){
    const ci = document.getElementById('checkin-date')?.value || '';
    const co = document.getElementById('checkout-date')?.value || '';
    if(!ci || !co) return 0;
    const d1 = new Date(ci), d2 = new Date(co);
    let nights = Math.floor((d2 - d1)/(1000*60*60*24));
    if(nights < 1) nights = 1;
    return nights;
}

// Copy các dịch vụ được tick vào form (hidden)
function syncSelectedServicesIntoForm(){
    const bag = document.getElementById('service-hidden-bag');
    if(!bag) return;
    bag.innerHTML = '';

    document.querySelectorAll('.service-item').forEach(item=>{
        const cb = item.querySelector('.svc-check');
        if(!cb?.checked) return;

        const sid   = item.getAttribute('data-sid');
        const price = item.getAttribute('data-price') || 0;
        const qtyEl = item.querySelector('.svc-qty');
        const qty   = Math.max(1, parseInt(qtyEl?.value || '1', 10));
        const prefix = `services[${sid}]`;

        const s1 = document.createElement('input');
        s1.type='hidden'; s1.name=`${prefix}[s_id]`; s1.value=sid; bag.appendChild(s1);

        const s2 = document.createElement('input');
        s2.type='hidden'; s2.name=`${prefix}[quantity]`; s2.value=qty; bag.appendChild(s2);

        const s3 = document.createElement('input');
        s3.type='hidden'; s3.name=`${prefix}[unit_price]`; s3.value=price; bag.appendChild(s3);

        const s4 = document.createElement('input');
        s4.type='hidden'; s4.name=`${prefix}[pricing_model]`; s4.value='0'; bag.appendChild(s4); // 0=once
    });
}

function recalcTotalsWithServices(){
    const nights = getNightsFromInputs();
    const base   = <?php echo e((int)($roomType->base_price ?? 0)); ?>;
    const roomPrice = base * (nights || 0);

    let svcTotal = 0;
    document.querySelectorAll('.service-item').forEach(item=>{
        const cb = item.querySelector('.svc-check');
        const qtyEl = item.querySelector('.svc-qty');
        const price = Number(item.dataset.price || 0);
        const qty   = Math.max(0, Number(qtyEl?.value || 0));
        if(cb?.checked && qty > 0) svcTotal += price * qty; // once
    });

    const row = document.getElementById('services-total-row');
    const spn = document.getElementById('order-services-price');
    if(row && spn){
        if(svcTotal > 0){ row.style.display='flex'; spn.textContent = vn(svcTotal); }
        else{ row.style.display='none'; spn.textContent = '0₫'; }
    }

    const discountAmount = 0; // nếu có, bạn set từ server tương tự room price
    const finalTotal = Math.max(0, roomPrice - discountAmount + svcTotal);

    const mini = document.getElementById('mini-order-total');
    if(mini) mini.textContent = vn(finalTotal);

    // nhét tổng dịch vụ vào form
    const form = document.querySelector('.booking-form');
    if(form){
        let h = form.querySelector('input[name="services_total"]');
        if(!h){ h = document.createElement('input'); h.type='hidden'; h.name='services_total'; form.appendChild(h); }
        h.value = svcTotal;
    }
}

function toggleServiceRow(item, checked){
    const qty = item.querySelector('.svc-qty');
    qty.disabled = !checked;
}

function validateBookingForm(){
    // Có thể bổ sung kiểm tra ngày/khách…
    syncSelectedServicesIntoForm();
    recalcTotalsWithServices();
    return true;
}

document.addEventListener('DOMContentLoaded', function(){
    // thumbs
    const mainImage = document.getElementById('mainImage');
    const thumbs = document.querySelectorAll('.thumbnail');
    thumbs.forEach(thumb=>{
        thumb.addEventListener('click', ()=>{
            const src = thumb.dataset.src || thumb.src;
            if(mainImage) mainImage.src = src;
            thumbs.forEach(t=>t.classList.remove('is-active'));
            thumb.classList.add('is-active');
        });
    });

    // dịch vụ
    document.querySelectorAll('.service-item').forEach(item=>{
        const cb  = item.querySelector('.svc-check');
        const qty = item.querySelector('.svc-qty');

        cb?.addEventListener('change', ()=>{
            toggleServiceRow(item, cb.checked);
            syncSelectedServicesIntoForm();
            recalcTotalsWithServices();
        });
        qty?.addEventListener('input', ()=>{
            if(Number(qty.value) < 1) qty.value = 1;
            syncSelectedServicesIntoForm();
            recalcTotalsWithServices();
        });
    });

    // ngày
    document.getElementById('checkin-date')?.addEventListener('change', recalcTotalsWithServices);
    document.getElementById('checkout-date')?.addEventListener('change', recalcTotalsWithServices);

    // init
    recalcTotalsWithServices();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/user/room_type_detail.blade.php ENDPATH**/ ?>