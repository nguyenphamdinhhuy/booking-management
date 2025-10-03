@extends('admin.layouts.master')
@section("content")

<h1 class="page-title">
    <i class="fas fa-calendar-plus"></i>
    Đặt phòng cho khách
</h1>

<div class="content-section">

    {{-- FORM 1: Kiểm tra thông tin khách bằng số CCCD --}}
    <form action="{{ route('admin.bookings.checkUser') }}" method="POST" class="mb-6">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label>Số CCCD</label>
                <input type="text" name="citizen_id" class="form-input" placeholder="Nhập số CCCD của khách" value="{{ old('citizen_id') }}" required>
                @error('citizen_id')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Kiểm tra khách hàng
            </button>
        </div>
    </form>

    {{-- THÔNG BÁO KẾT QUẢ --}}
    @if(session('check_result'))
    @php($res = session('check_result'))
    @if($res['status'] === 'not_found')
    <div class="alert alert-warning mb-6">
        <i class="fas fa-exclamation-triangle"></i>
        Không tìm thấy khách nào có số CCCD <strong>{{ $res['citizen_id'] }}</strong>.
        Nhập thông tin khách mới và đặt phòng bên dưới.
    </div>

    {{-- FORM: Tạo KHÁCH MỚI + ĐẶT PHÒNG (submit 1 lần) --}}
    <form action="{{ route('admin.bookings.store') }}" method="POST">
        @csrf

        <div class="card p-4 mb-6">
            <h3 class="mb-3">Thông tin khách hàng mới</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label>Họ tên</label>
                    <input type="text" name="user[name]" class="form-input" value="{{ old('user.name') }}" required>
                    @error('user.name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="user[email]" class="form-input" value="{{ old('user.email') }}">
                    @error('user.email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="tel" name="user[phone]" class="form-input" value="{{ old('user.phone') }}">
                    @error('user.phone')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" name="user[address]" class="form-input" value="{{ old('user.address') }}">
                    @error('user.address')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Giới tính</label>
                    <select name="user[gender]" class="form-select">
                        <option value="">-- Chọn --</option>
                        <option value="Nam" {{ old('user.gender')==='Nam'?'selected':'' }}>Nam</option>
                        <option value="Nữ" {{ old('user.gender')==='Nữ'?'selected':'' }}>Nữ</option>
                        <option value="Khác" {{ old('user.gender')==='Khác'?'selected':'' }}>Khác</option>
                    </select>
                    @error('user.gender')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Ngày sinh</label>
                    <input type="date" name="user[dob]" class="form-input" value="{{ old('user.dob') }}">
                    @error('user.dob')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Số CCCD</label>
                    <input type="text" name="user[citizen_id]" class="form-input" value="{{ $res['citizen_id'] }}" required>
                    @error('user.citizen_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- FORM 2: Đặt phòng --}}
        <div class="card p-4">
            <h3 class="mb-3">Thông tin đặt phòng</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label>Loại phòng</label>
                    <select name="rt_id" class="form-select" required>
                        <option value="">-- Chọn loại phòng --</option>
                        @foreach($roomTypes as $rt)
                        <option value="{{ $rt->rt_id }}">{{ $rt->type_name }} - {{ number_format($rt->base_price) }} VNĐ/đêm</option>
                        @endforeach
                    </select>
                    @error('rt_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Ngày nhận phòng</label>
                    <input type="date" name="checkin" id="checkin-date" class="form-input" required value="{{ old('checkin') }}">
                    @error('checkin')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Ngày trả phòng</label>
                    <input type="date" name="checkout" id="checkout-date" class="form-input" required value="{{ old('checkout') }}">
                    @error('checkout')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Số khách</label>
                    <input type="number" name="guests" min="1" value="{{ old('guests',1) }}" class="form-input" required>
                    @error('guests')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Mã giảm giá</label>
                    <input type="text" name="discount_code" class="form-input" value="{{ old('discount_code') }}">
                </div>

                <div class="form-group">
                    <label>Số tiền giảm</label>
                    <input type="number" name="discount_amount" value="{{ old('discount_amount',0) }}" class="form-input">
                </div>
            </div>

            <h3>Dịch vụ kèm theo</h3>
            <div class="services-grid">
                @foreach($services as $svc)
                <div class="service-card">
                    <div class="service-head">
                        <div class="service-name">{{ $svc->name }}</div>
                        <div class="service-price">
                            {{ number_format($svc->price) }} VNĐ
                            @if(!empty($svc->unit)) / {{ $svc->unit }} @endif
                        </div>
                    </div>

                    <div class="service-controls">
                        <input type="hidden" name="services[{{ $svc->s_id }}][s_id]" value="{{ $svc->s_id }}">
                        <label>Số lượng</label>
                        <input type="number"
                            name="services[{{ $svc->s_id }}][quantity]"
                            class="form-input qty-input"
                            min="0"
                            @if($svc->max_quantity) max="{{ $svc->max_quantity }}" @endif
                        value="{{ old('services.'.$svc->s_id.'.quantity', 0) }}">
                    </div>

                </div>
                @endforeach
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.bookings.management') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Tạo khách & Đặt phòng
                </button>
            </div>
        </div>
    </form>
    @elseif($res['status'] === 'found')
    <div class="alert alert-success mb-6">
        <i class="fas fa-user-check"></i>
        Đã tìm thấy khách hàng trong hệ thống.
    </div>

    {{-- Thông tin khách (readonly) --}}
    <div class="card p-4 mb-6">
        <div class="form-grid">
            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" class="form-input" value="{{ $res['user']['name'] ?? '' }}" readonly>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-input" value="{{ $res['user']['email'] ?? '' }}" readonly>
            </div>
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="tel" class="form-input" value="{{ $res['user']['phone'] ?? '' }}" readonly>
            </div>
            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" class="form-input" value="{{ $res['user']['address'] ?? '' }}" readonly>
            </div>
            <div class="form-group">
                <label>Giới tính</label>
                <select class="form-select" disabled>
                    <option value="Nam" {{ ($res['user']['gender'] ?? '') === 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ ($res['user']['gender'] ?? '') === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                    <option value="Khác" {{ ($res['user']['gender'] ?? '') === 'Khác' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>
            <div class="form-group">
                <label>Ngày sinh</label>
                <input type="date" class="form-input" value="{{ $res['user']['dob'] ?? '' }}" readonly>
            </div>
            <div class="form-group">
                <label>Số CCCD</label>
                <input type="text" class="form-input" value="{{ $res['user']['citizen_id'] ?? '' }}" readonly>
            </div>
        </div>
        <input type="hidden" id="found_u_id" value="{{ $res['user']['id'] ?? '' }}">
    </div>

    {{-- FORM 2: Đặt phòng (cho user đã tồn tại) --}}
    <form action="{{ route('admin.bookings.store') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label>Khách hàng (User ID)</label>
                <input type="number" name="u_id" class="form-input" value="{{ $res['user']['id'] ?? '' }}" readonly required>
            </div>
            <div class="form-group">
                <label>Loại phòng</label>
                <select name="rt_id" class="form-select" required>
                    <option value="">-- Chọn loại phòng --</option>
                    @foreach($roomTypes as $rt)
                    <option value="{{ $rt->rt_id }}">{{ $rt->type_name }} - {{ number_format($rt->base_price) }} VNĐ/đêm</option>
                    @endforeach
                </select>
                @error('rt_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">

                <!-- ------------------------- -->
                <label>Ngày nhận phòng</label>
                <input type="date" name="checkin" id="checkin-date" class="form-input" required value="{{ old('checkin') }}">
                @error('checkin')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Ngày trả phòng</label>
                <input type="date" name="checkout" id="checkout-date" class="form-input" required value="{{ old('checkout') }}">
                @error('checkout')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Số khách</label>
                <input type="number" name="guests" min="1" value="{{ old('guests',1) }}" class="form-input" required>
                @error('guests')<div class="form-error">{{ $message }}</div>@enderror
            </div>


            <div class="form-group">
                <label>Mã giảm giá</label>
                <input type="text" name="discount_code" class="form-input" value="{{ old('discount_code') }}">
            </div>
            <div class="form-group">
                <label>Số tiền giảm</label>
                <input type="number" name="discount_amount" value="{{ old('discount_amount',0) }}" class="form-input">
            </div>
        </div>
        <h3>Dịch vụ kèm theo</h3>
        <div class="services-grid">
            @foreach($services as $svc)
            <div class="service-card">
                <div class="service-head">
                    <div class="service-name">{{ $svc->name }}</div>
                    <div class="service-price">
                        {{ number_format($svc->price) }} VNĐ
                        @if(!empty($svc->unit)) / {{ $svc->unit }} @endif
                    </div>
                </div>

                <div class="service-controls">
                    <input type="hidden" name="services[{{ $svc->s_id }}][s_id]" value="{{ $svc->s_id }}">
                    <label>Số lượng</label>
                    <input type="number"
                        name="services[{{ $svc->s_id }}][quantity]"
                        class="form-input qty-input"
                        min="0"
                        @if($svc->max_quantity) max="{{ $svc->max_quantity }}" @endif
                    value="{{ old('services.'.$svc->s_id.'.quantity', 0) }}">
                </div>

            </div>
            @endforeach
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.bookings.management') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Xác nhận đặt phòng
            </button>
        </div>
    </form>
    @endif
    @endif

</div>

<style>
    /* ====== Tokens / Variables ====== */
    :root {
        --bg: #f7f8fa;
        --card: #ffffff;
        --text: #111827;
        --muted: #6b7280;
        --border: #e5e7eb;

        --primary: #2563eb;
        /* Indigo-600 */
        --primary-700: #1d4ed8;
        --primary-50: #eef2ff;

        --success: #33c27f;
        --success-50: #e6f9ed;

        --warning: #ffb84d;
        --warning-50: #fff8e6;

        --info: #4da6ff;
        --info-50: #e6f3ff;

        --danger: #b91c1c;
        --radius: 12px;
        --radius-sm: 8px;

        --space-1: 6px;
        --space-2: 10px;
        --space-3: 14px;
        --space-4: 16px;
        --space-5: 20px;
    }

    /* ====== Layout cơ bản ====== */
    body {
        background: var(--bg);
        color: var(--text);
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 16px;
    }

    .page-title i {
        color: var(--primary);
    }

    /* Card/section */
    .content-section {
        background: transparent;
    }

    .card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
    }

    .p-4 {
        padding: var(--space-4);
    }

    .mb-6 {
        margin-bottom: 24px;
    }

    .mt-4 {
        margin-top: 16px;
    }

    /* ====== Grid cho forms ====== */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: var(--space-3);
    }

    .form-group {
        grid-column: span 6;
    }

    /* mặc định 2 cột */
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
    }

    @media (max-width: 1024px) {
        .form-group {
            grid-column: span 12;
        }

        /* tablet trở xuống: 1 cột */
    }

    /* ====== Inputs / Selects ====== */
    .form-input,
    .form-select,
    .qty-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: #fff;
        color: var(--text);
        outline: none;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .form-input:focus,
    .form-select:focus,
    .qty-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .form-input[readonly],
    .form-select[disabled] {
        background: #f9fafb;
        color: #4b5563;
    }

    .form-error {
        color: var(--danger);
        font-size: 13px;
        margin-top: 6px;
    }

    /* ====== Buttons ====== */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: var(--radius-sm);
        border: 0;
        cursor: pointer;
        font-weight: 600;
        transition: transform .04s ease, box-shadow .15s ease, background .15s ease;
    }

    .btn:active {
        transform: translateY(1px);
    }

    .btn-primary {
        background: var(--primary);
        color: #fff;
    }

    .btn-primary:hover {
        background: var(--primary-700);
    }

    .btn-primary:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    .btn-secondary {
        background: #eef2f7;
        color: #111827;
    }

    .btn-secondary:hover {
        background: #e5eaf1;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-top: var(--space-4);
        flex-wrap: wrap;
    }

    /* ====== Alerts ====== */
    .alert {
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        display: flex;
        gap: 8px;
        align-items: center;
        margin-bottom: var(--space-4);
        font-size: 15px;
        border: 1px solid transparent;
    }

    .alert i {
        font-size: 16px;
    }

    .alert-success {
        background: var(--success-50);
        border-color: var(--success);
        color: #20744d;
    }

    .alert-warning {
        background: var(--warning-50);
        border-color: var(--warning);
        color: #995400;
    }

    .alert-info {
        background: var(--info-50);
        border-color: var(--info);
        color: #0059b3;
    }

    /* ====== Services block ====== */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: var(--space-3);
    }

    .service-item {
        grid-column: span 6;
        /* 2 cột */
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: var(--space-3);
        background: #fff;
    }

    @media (max-width: 1024px) {
        .service-item {
            grid-column: span 12;
        }

        /* mobile: 1 cột */
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
    }

    .service-card {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        background: #fff;
        padding: var(--space-3);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .service-head {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 8px;
    }

    .service-name {
        font-weight: 600;
        font-size: 15px;
    }

    .service-price {
        color: #374151;
        font-size: 14px;
    }

    .service-controls {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 6px;
    }

    .service-controls label {
        font-size: 13px;
        color: var(--muted);
    }

    .qty-input {
        width: 80px;
    }

    .service-desc {
        margin-top: 8px;
        font-size: 13px;
        color: #6b7280;
    }

    .qty-label,
    .pm-label {
        font-size: 13px;
        color: var(--muted);
    }

    .qty-input {
        width: 100%;
    }

    .service-desc {
        margin-top: 8px;
        font-size: 13px;
        color: #6b7280;
    }

    /* ====== Helpers ====== */
    input[type="date"] {
        color-scheme: light;
    }

    /* tránh UI datepicker bị tối nếu dark mode */
    strong {
        font-weight: 700;
    }
</style>

<script>
    (function() {
        // chuẩn hoá YYYY-MM-DD theo múi giờ máy
        function fmtDate(d) {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        }

        function addDays(d, n) {
            const x = new Date(d.getFullYear(), d.getMonth(), d.getDate());
            x.setDate(x.getDate() + n);
            return x;
        }

        function parseISO(s) {
            if (!s) return null;
            const [y, m, d] = s.split('-').map(Number);
            if (!y || !m || !d) return null;
            return new Date(y, m - 1, d);
        }

        const today = new Date();
        const todayStr = fmtDate(today);

        // Tạo element báo lỗi sát ô checkout (nếu chưa có)
        function ensureErrorEl(inputEl) {
            const id = 'date-error-' + Math.random().toString(36).slice(2);
            let err = inputEl.parentElement.querySelector('.js-date-error');
            if (!err) {
                err = document.createElement('div');
                err.className = 'form-error js-date-error';
                err.style.display = 'none';
                inputEl.parentElement.appendChild(err);
            }
            return err;
        }

        function showError(errEl, msg) {
            if (!errEl) return;
            errEl.textContent = msg || '';
            errEl.style.display = msg ? 'block' : 'none';
        }

        // Gắn guard cho từng form có checkin/checkout
        document.querySelectorAll('form').forEach(function(form) {
            const checkin = form.querySelector('input[name="checkin"]');
            const checkout = form.querySelector('input[name="checkout"]');
            if (!checkin || !checkout) return;

            // min mặc định cho checkin là hôm nay
            checkin.setAttribute('min', todayStr);

            const errEl = ensureErrorEl(checkout);

            function syncMinCheckout() {
                const ci = parseISO(checkin.value);
                if (!ci) {
                    checkout.min = ''; // chưa chọn checkin
                    showError(errEl, '');
                    return;
                }
                const minCo = addDays(ci, 1);
                const minCoStr = fmtDate(minCo);
                checkout.setAttribute('min', minCoStr);

                // nếu checkout hiện tại không hợp lệ, tự sửa
                if (checkout.value && checkout.value < minCoStr) {
                    checkout.value = minCoStr;
                }
                showError(errEl, '');
            }

            function validatePairOnChange() {
                const ci = parseISO(checkin.value);
                const co = parseISO(checkout.value);
                if (!ci || !co) {
                    showError(errEl, '');
                    return;
                }
                if (co <= ci) {
                    // tự sửa thành ci + 1 ngày
                    const fix = fmtDate(addDays(ci, 1));
                    checkout.value = fix;
                    showError(errEl, 'Ngày trả phòng phải sau ngày nhận phòng ít nhất 1 ngày.');
                } else {
                    showError(errEl, '');
                }
            }

            // Khởi tạo theo giá trị sẵn có (old input)
            // Nếu checkin < today, nâng lên today
            if (checkin.value && checkin.value < todayStr) {
                checkin.value = todayStr;
            }
            syncMinCheckout();
            validatePairOnChange();

            // Sự kiện thay đổi
            checkin.addEventListener('change', function() {
                // không cho chọn quá khứ
                if (checkin.value < todayStr) checkin.value = todayStr;
                syncMinCheckout();
                validatePairOnChange();
            });
            checkout.addEventListener('change', function() {
                validatePairOnChange();
            });

            // Chặn submit nếu vẫn sai (trường hợp gõ tay)
            form.addEventListener('submit', function(e) {
                const ci = parseISO(checkin.value);
                const co = parseISO(checkout.value);
                if (!ci || !co) return; // HTML5 required sẽ xử lý
                // enforce min again
                if (checkin.value < todayStr) {
                    checkin.value = todayStr;
                }
                const minCoStr = fmtDate(addDays(ci, 1));
                if (checkout.value < minCoStr) {
                    e.preventDefault();
                    checkout.value = minCoStr;
                    showError(errEl, 'Ngày trả phòng phải sau ngày nhận phòng ít nhất 1 ngày.');
                    checkout.focus();
                } else {
                    showError(errEl, '');
                }
            });
        });
    })();
</script>

@endsection