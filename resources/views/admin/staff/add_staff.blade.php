@extends('admin.layouts.master')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>

    <style>
        .cv-form-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 40px;
            background-color: #fdfdfd;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            font-family: 'Segoe UI', sans-serif;
        }

        .cv-form-title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .cv-section {
            margin-bottom: 30px;
        }

        .cv-section-title {
            font-size: 20px;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 15px;
            border-left: 4px solid #3498db;
            padding-left: 10px;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #2f2f2f;
        }

        .form-control {
            padding: 10px 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-error {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 4px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            font-weight: bold;
            background-color: #3498db;
            border: none;
            color: #fff;
            border-radius: 6px;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background-color: #2980b9;
        }

        .form-group img.preview {
            margin-top: 10px;
            width: 160px;
            height: auto;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .qr-scanner {
            display: none;
            margin-top: 15px;
            text-align: center;
        }

        .qr-scanner.show {
            display: block;
        }

        #qr-video {
            width: 100%;
            max-width: 300px;
            height: auto;
            border: 2px solid #3498db;
            border-radius: 8px;
        }

        .qr-controls {
            margin-top: 10px;
        }

        .btn-qr {
            padding: 8px 16px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-qr.start {
            background-color: #27ae60;
            color: white;
        }

        .btn-qr.stop {
            background-color: #e74c3c;
            color: white;
        }

        .qr-status {
            margin-top: 10px;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .qr-status.scanning {
            background-color: #e3f2fd;
            color: #1976d2;
            border: 1px solid #2196f3;
        }

        .qr-status.success {
            background-color: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #4caf50;
        }

        .qr-status.error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #f44336;
        }

        .auto-filled {
            background-color: #e8f5e8 !important;
            border-color: #4caf50 !important;
            animation: highlight 2s ease-in-out;
        }

        @keyframes highlight {
            0% {
                background-color: #e8f5e8;
            }

            50% {
                background-color: #c8e6c9;
            }

            100% {
                background-color: #e8f5e8;
            }
        }

        .qr-upload-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 2px dashed #3498db;
            text-align: center;
            margin-bottom: 20px;
        }

        .qr-upload-section h4 {
            color: #3498db;
            margin-bottom: 15px;
        }

        .upload-options {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
    </style>

    <div class="cv-form-container">
        <h2 class="cv-form-title">Đăng ký hồ sơ nhân viên</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.staff') }}" enctype="multipart/form-data">
            @csrf
            {{-- Thông tin CCCD --}}
            <div class="cv-section">
                <h3 class="cv-section-title">Thông tin căn cước công dân</h3>

                {{-- QR Scanner Section --}}
                <div class="qr-upload-section">
                    <h4><i class="fas fa-qrcode"></i> Quét mã QR từ CCCD</h4>

                    <div class="upload-options">
                        <label for="qr-file-input" class="btn-qr start" style="display: inline-block; cursor: pointer;">
                            <i class="fas fa-upload"></i> Tải ảnh QR lên
                            <input type="file" id="qr-file-input" accept="image/*" style="display: none;">
                        </label>
                    </div>

                    <div class="qr-scanner" id="qr-scanner">
                        <video id="qr-video" autoplay muted playsinline></video>
                        <div class="qr-controls">
                            <button type="button" id="stop-camera" class="btn-qr stop">
                                <i class="fas fa-stop"></i> Dừng Camera
                            </button>
                        </div>
                    </div>

                    <div id="qr-status" class="qr-status" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="citizen_id" class="form-label">Số CCCD</label>
                    <input id="citizen_id" type="text" class="form-control" name="citizen_id"
                        value="{{ old('citizen_id') }}">
                    @error('citizen_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="issue_date" class="form-label">Ngày cấp</label>
                    <input id="issue_date" type="date" class="form-control" name="issue_date"
                        value="{{ old('issue_date') }}">
                    @error('issue_date') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="issue_place" class="form-label">Nơi cấp</label>
                    <input id="issue_place" type="text" class="form-control" name="issue_place"
                        value="{{ old('issue_place') }}">
                    @error('issue_place') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="id_card_front" class="form-label">Ảnh mặt trước CCCD</label>
                    <input type="file" name="id_card_front" class="form-control" id="id_card_front" accept="image/*">
                    @error('id_card_front') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="id_card_back" class="form-label">Ảnh mặt sau CCCD</label>
                    <input type="file" name="id_card_back" class="form-control" id="id_card_back" accept="image/*">
                    @error('id_card_back') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Thông tin cá nhân --}}
            <div class="cv-section">
                <h3 class="cv-section-title">Thông tin cá nhân</h3>

                <div class="form-group">
                    <label for="name" class="form-label">Họ tên</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="password">Mật khẩu</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                        required>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input id="phone" type="tel" class="form-control" name="phone" value="{{ old('phone') }}">
                    @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="gender" class="form-label">Giới tính</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value="">-- Chọn giới tính --</option>
                        <option value="Nam" {{ old('gender') == 'Nam' ? 'selected' : '' }}>Nam</option>
                        <option value="Nữ" {{ old('gender') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        <option value="Khác" {{ old('gender') == 'Khác' ? 'selected' : '' }}>Khác</option>
                    </select>
                    @error('gender') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="dob" class="form-label">Ngày sinh</label>
                    <input id="dob" type="date" class="form-control" name="dob" value="{{ old('dob') }}">
                    @error('dob') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}">
                    @error('address') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>


            {{-- Thông tin công việc --}}
            <div class="cv-section">
                <h3 class="cv-section-title">Thông tin công việc</h3>

                <div class="form-group">
                    <label for="start_date" class="form-label">Ngày bắt đầu làm việc</label>
                    <input id="start_date" type="date" class="form-control" name="start_date"
                        value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}"
                        max="{{ date('Y-m-d', strtotime('+15 days')) }}">
                    @error('start_date') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="contract_type" class="form-label">Loại hợp đồng</label>
                    <select name="contract_type" id="contract_type" class="form-control">
                        <option value="">-- Chọn loại hợp đồng --</option>
                        <option value="Chính thức" {{ old('contract_type') == 'Chính thức' ? 'selected' : '' }}>Chính thức
                        </option>
                        <option value="Thử việc" {{ old('contract_type') == 'Thử việc' ? 'selected' : '' }}>Thử việc</option>
                        <option value="Part-time" {{ old('contract_type') == 'Part-time' ? 'selected' : '' }}>Part-time
                        </option>
                    </select>
                    @error('contract_type') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="salary" class="form-label">Lương khởi điểm (VNĐ)</label>
                    <input id="salary" type="text" class="form-control" name="salary" value="{{ old('salary') }}">
                    @error('salary') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn-submit">Lưu hồ sơ nhân viên</button>
        </form>
    </div>
    <script>
        const qrFileInput = document.getElementById('qr-file-input');
        const qrStatus = document.getElementById('qr-status');

        qrFileInput.addEventListener("change", function () {
            const file = this.files[0];
            if (!file) {
                alert("Vui lòng chọn ảnh!");
                return;
            }

            const reader = new FileReader();
            reader.onload = function () {
                const image = new Image();
                image.onload = function () {
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");
                    canvas.width = image.width;
                    canvas.height = image.height;
                    ctx.drawImage(image, 0, 0);

                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, canvas.width, canvas.height);

                    if (code) {
                        const str = code.data;
                        console.log("QR Data:", str); // Debug log

                        const result = str.split("|");
                        console.log("Parsed result:", result); // Debug log

                        // Lấy dữ liệu từ các vị trí trong mảng
                        const cccd = result[0]?.trim() || "";
                        const cmnd = result[1]?.trim() || ""; // Có thể là CMND
                        const name = result[2]?.trim() || "";
                        const dob = result[3]?.trim() || "";
                        const gender = result[4]?.trim() || "";
                        const address = result[5]?.trim() || "";
                        const issueDate = result[6]?.trim() || "";

                        console.log("Extracted data:", {
                            cccd, cmnd, name, dob, gender, address, issueDate
                        });

                        // Điền vào các input với kiểm tra
                        if (cccd) {
                            document.getElementById('citizen_id').value = cccd;
                            document.getElementById('citizen_id').classList.add("auto-filled");
                        }

                        if (name) {
                            document.getElementById('name').value = name;
                            document.getElementById('name').classList.add("auto-filled");
                        }

                        if (dob) {
                            const formattedDob = formatDate(dob);
                            if (formattedDob) {
                                document.getElementById('dob').value = formattedDob;
                                document.getElementById('dob').classList.add("auto-filled");
                            }
                        }

                        if (gender) {
                            // Chuẩn hóa giới tính
                            const normalizedGender = normalizeGender(gender);
                            if (normalizedGender) {
                                document.getElementById('gender').value = normalizedGender;
                                document.getElementById('gender').classList.add("auto-filled");
                            }
                        }

                        if (address) {
                            document.getElementById('address').value = address;
                            document.getElementById('address').classList.add("auto-filled");

                            // Tự động điền nơi cấp từ địa chỉ
                            const issuePlace = getIssuePlaceFromAddress(address);
                            if (issuePlace) {
                                document.getElementById('issue_place').value = issuePlace;
                                document.getElementById('issue_place').classList.add("auto-filled");
                            }
                        }

                        if (issueDate) {
                            const formattedIssueDate = formatDate(issueDate);
                            if (formattedIssueDate) {
                                document.getElementById('issue_date').value = formattedIssueDate;
                                document.getElementById('issue_date').classList.add("auto-filled");
                            }
                        }

                        // Xóa hiệu ứng sau 3 giây
                        setTimeout(() => {
                            document.querySelectorAll('.auto-filled').forEach(el => {
                                el.classList.remove("auto-filled");
                            });
                        }, 3000);

                        qrStatus.textContent = "Đã quét thành công mã QR và điền thông tin.";
                        qrStatus.className = "qr-status success";
                        qrStatus.style.display = "block";

                    } else {
                        qrStatus.textContent = "Không tìm thấy mã QR trong ảnh.";
                        qrStatus.className = "qr-status error";
                        qrStatus.style.display = "block";
                    }
                };
                image.src = reader.result;
            };
            reader.readAsDataURL(file);
        });

        function formatDate(dmy) {
            if (!dmy) return "";

            try {
                // Xử lý format dd/mm/yyyy
                const parts = dmy.split("/");
                if (parts.length === 3) {
                    const [d, m, y] = parts;
                    return `${y}-${m.padStart(2, '0')}-${d.padStart(2, '0')}`;
                }

                // Xử lý format ddmmyyyy
                if (dmy.length === 8 && !dmy.includes("/")) {
                    const d = dmy.substring(0, 2);
                    const m = dmy.substring(2, 4);
                    const y = dmy.substring(4, 8);
                    return `${y}-${m}-${d}`;
                }

                return "";
            } catch (error) {
                console.error("Error formatting date:", error);
                return "";
            }
        }

        function normalizeGender(gender) {
            if (!gender) return "";

            const g = gender.toLowerCase().trim();

            if (g.includes('nam') || g === 'male' || g === 'm') {
                return 'Nam';
            } else if (g.includes('nữ') || g.includes('nu') || g === 'female' || g === 'f') {
                return 'Nữ';
            } else {
                return 'Khác';
            }
        }

        function getIssuePlaceFromAddress(address) {
            if (!address) return "";

            // Danh sách tỉnh/thành phố Việt Nam
            const provinces = [
                'An Giang', 'Bà Rịa - Vũng Tàu', 'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu', 'Bắc Ninh',
                'Bến Tre', 'Bình Định', 'Bình Dương', 'Bình Phước', 'Bình Thuận', 'Cà Mau',
                'Cao Bằng', 'Đắk Lắk', 'Đắk Nông', 'Điện Biên', 'Đồng Nai', 'Đồng Tháp',
                'Gia Lai', 'Hà Giang', 'Hà Nam', 'Hà Tĩnh', 'Hải Dương', 'Hậu Giang',
                'Hòa Bình', 'Hưng Yên', 'Khánh Hòa', 'Kiên Giang', 'Kon Tum', 'Lai Châu',
                'Lâm Đồng', 'Lạng Sơn', 'Lào Cai', 'Long An', 'Nam Định', 'Nghệ An',
                'Ninh Bình', 'Ninh Thuận', 'Phú Thọ', 'Quảng Bình', 'Quảng Nam', 'Quảng Ngãi',
                'Quảng Ninh', 'Quảng Trị', 'Sóc Trăng', 'Sơn La', 'Tây Ninh', 'Thái Bình',
                'Thái Nguyên', 'Thanh Hóa', 'Thừa Thiên Huế', 'Tiền Giang', 'Trà Vinh',
                'Tuyên Quang', 'Vĩnh Long', 'Vĩnh Phúc', 'Yên Bái', 'Phú Yên',
                'Cần Thơ', 'Đà Nẵng', 'Hải Phòng', 'Hà Nội', 'TP Hồ Chí Minh'
            ];

            // Tách địa chỉ thành các phần
            const parts = address.split(/[,\-]/).map(part => part.trim());

            // Tìm tỉnh/thành phố trong địa chỉ
            for (let i = parts.length - 1; i >= 0; i--) {
                const part = parts[i];

                // Xử lý các trường hợp đặc biệt trước
                const lowerPart = part.toLowerCase();
                if (lowerPart.includes('hồ chí minh') || lowerPart.includes('tp hcm') || lowerPart.includes('sài gòn')) {
                    return 'TP Hồ Chí Minh';
                }
                if (lowerPart.includes('hà nội') || lowerPart.includes('hanoi')) {
                    return 'Hà Nội';
                }
                if (lowerPart.includes('đà nẵng') || lowerPart.includes('da nang')) {
                    return 'Đà Nẵng';
                }
                if (lowerPart.includes('hải phòng') || lowerPart.includes('hai phong')) {
                    return 'Hải Phòng';
                }
                if (lowerPart.includes('cần thơ') || lowerPart.includes('can tho')) {
                    return 'Cần Thơ';
                }

                // Tìm kiếm trong danh sách tỉnh
                const foundProvince = provinces.find(province => {
                    const provinceLower = province.toLowerCase();
                    return lowerPart === provinceLower ||
                        lowerPart.includes(provinceLower) ||
                        provinceLower.includes(lowerPart);
                });

                if (foundProvince) {
                    return foundProvince;
                }
            }

            // Nếu không tìm thấy, trả về phần cuối cùng
            return parts[parts.length - 1] || "";
        }
    </script>
@endsection