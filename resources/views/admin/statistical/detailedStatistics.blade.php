@extends('admin.layouts.master')
<style>
    /* ----------- FORM CHUNG ----------- */
    .date-filter-form {
        display: flex;
        align-items: center;
        gap: 12px;
        /* Khoảng cách giữa các ô */
        padding: 14px;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 12px;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        width: fit-content;
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
    }

    /* ----------- LABEL ----------- */
    .date-filter-form label {
        font-weight: 600;
        font-size: 14px;
        color: #333;
    }

    /* ----------- INPUT DATE ----------- */
    .date-filter-form input[type="date"] {
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        background-color: #fff;
        font-size: 14px;
        color: #333;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .date-filter-form input[type="date"]:hover {
        border-color: #007bff;
        box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.3);
    }

    /* ----------- SELECT (THÁNG + NĂM) ----------- */
    .date-filter-form select {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        background-color: #fff;
        font-size: 14px;
        color: #333;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .date-filter-form select:hover {
        border-color: #007bff;
        box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.3);
    }

    /* ----------- BUTTON ----------- */
    .date-filter-form button {
        padding: 8px 16px;
        background-color: #007bff;
        color: #fff;
        font-size: 14px;
        font-weight: bold;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .date-filter-form button:hover {
        background-color: #0056b3;
    }
</style>
@section('content')
    <h1 class="page-title">
        <i class="fas fa-tachometer-alt"></i> {{ $title }}
    </h1>
    <div class="table-container">
        <div class="table-header">

            @if($type == 0)
                <h2 class="table-title">Chi tiết doanh thu</h2>
            @elseif($type == 1)
                <h2 class="table-title">Chi tiết lượt đặt phòng</h2>
            @elseif($type == 2)
                <h2 class="table-title">Chi tiết khách hàng mới</h2>
            @elseif($type == 3)
                <h2 class="table-title">Chi tiết đánh giá</h2>
            @endif
            <div class="table-actions">
                <form method="GET" action="{{ route('admin.statistical.detailed', $type) }}" class="date-filter-form">
                    <label for="start_date">Từ ngày:</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}">

                    <label for="end_date">Đến ngày:</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}">

                    <select id="month" name="month">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>Tháng {{ $m }}</option>
                        @endfor
                    </select>

                    <select id="year" name="year">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>

                    <button type="submit">Xem</button>
                </form>

            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Năm</th>
                        <th>Tháng</th>
                        <th>Ngày</th>
                        @if($type == 0)
                            <th>Doanh thu</th>
                            <th>Đơn vị</th>

                        @elseif($type == 1)
                            <th>Số lượng đặt phòng</th>
                            <th>Đơn vị</th>

                        @elseif($type == 2)
                            <th>Số khách hàng mới</th>
                            <th>Đơn vị</th>


                        @elseif($type == 3)
                            <th>Đánh giá</th>
                            <th>Đơn vị</th>

                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr>
                            <td>{{ $row->year }}</td>
                            <td>Tháng {{ $row->month }}</td>
                            <td>Ngày {{ $row->day }}</td>
                            <td>{{ number_format($row->total, 0, ',', '.') }}</td>
                            @if($type == 0)
                                <td>VND</td>
                            @elseif($type == 1)
                                <td>Lượt</td>
                            @elseif($type == 2)
                                <td>Người</td>
                                <td>Lượt</td>
                            @elseif($type == 3)
                                <td>Sao</td>
                            @endif


                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Không có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const monthSelect = document.getElementById("month");
            const yearSelect = document.getElementById("year");
            const startDateInput = document.getElementById("start_date");
            const endDateInput = document.getElementById("end_date");

            function updateDateLimits() {
                const month = parseInt(monthSelect.value);
                const year = parseInt(yearSelect.value);

                if (!month || !year) return;

                // Tính số ngày trong tháng
                const daysInMonth = new Date(year, month, 0).getDate();

                // Tạo giá trị min và max cho input date
                const monthStr = String(month).padStart(2, "0");
                const minDate = `${year}-${monthStr}-01`;
                const maxDate = `${year}-${monthStr}-${String(daysInMonth).padStart(2, "0")}`;

                // Gán giá trị min/max cho 2 input date
                startDateInput.setAttribute("min", minDate);
                startDateInput.setAttribute("max", maxDate);
                endDateInput.setAttribute("min", minDate);
                endDateInput.setAttribute("max", maxDate);

                // Nếu ngày đã chọn nằm ngoài khoảng => reset
                if (startDateInput.value && (startDateInput.value < minDate || startDateInput.value > maxDate)) {
                    startDateInput.value = "";
                }
                if (endDateInput.value && (endDateInput.value < minDate || endDateInput.value > maxDate)) {
                    endDateInput.value = "";
                }

                // Nếu chọn từ ngày > đến ngày => chỉnh lại đến ngày = từ ngày
                if (startDateInput.value && endDateInput.value && startDateInput.value > endDateInput.value) {
                    endDateInput.value = startDateInput.value;
                }

                // Gán min của "Đến ngày" = "Từ ngày"
                endDateInput.min = startDateInput.value || minDate;
                // Gán max của "Từ ngày" = "Đến ngày"
                startDateInput.max = endDateInput.value || maxDate;
            }

            // Khi thay đổi tháng/năm thì cập nhật
            monthSelect.addEventListener("change", updateDateLimits);
            yearSelect.addEventListener("change", updateDateLimits);
            startDateInput.addEventListener("change", updateDateLimits);
            endDateInput.addEventListener("change", updateDateLimits);

            // Gọi ngay khi load trang
            updateDateLimits();
        });
    </script>



@endsection