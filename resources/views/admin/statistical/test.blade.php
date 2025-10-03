@extends('admin.layouts.master')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section("content")
    <h1 class="page-title">
        <i class="fas fa-tachometer-alt"></i> Thống kê
    </h1>

    <style>
    .tongthongke {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .chart-box {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .chart-box h2 {
        margin-bottom: 10px;
    }
    .chart-actions {
        margin: 10px 0;
    }
    .chart-actions button {
        margin-right: 5px;
    }
    </style>
 

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Tổng đặt phòng hôm nay</h3>
            <div class="number">24</div>
            <div class="trend">
                <i class="fas fa-arrow-up"></i> +12% so với hôm qua
            </div>
        </div>
        <div class="stat-card">
            <h3>Tỷ lệ lấp đầy</h3>
            <div class="number">85%</div>
            <div class="trend">
                <i class="fas fa-arrow-up"></i> +5% so với tuần trước
            </div>
        </div>
        <div class="stat-card">
            <h3>Doanh thu tháng này</h3>
            <div class="number">450M</div>
            <div class="trend">
                <i class="fas fa-arrow-up"></i> +18% so với tháng trước
            </div>
        </div>
        <div class="stat-card">
            <h3>Khách hàng mới</h3>
            <div class="number">156</div>
            <div class="trend">
                <i class="fas fa-arrow-up"></i> +25% so với tuần trước
            </div>
        </div>
    </div>
    <div class="tongthongke">
    @foreach ([
            ['id' => 'myAreaChart', 'title' => 'Doanh thu theo tháng'],
            ['id' => 'bieudo2', 'title' => 'Số lượng phòng được đặt'],
            ['id' => 'bieudo3', 'title' => 'Tỉ lệ đánh giá'],
            ['id' => 'bieudo4', 'title' => 'Khách hàng mới']
        ] as $i => $chart)
            <div class="chart-box">
                <h2>{{ $chart['title'] }}</h2>
                <div class="chart-actions">
                    <button onclick="prevWeek({{ $i }})">⬅ Tuần trước</button>
                    <button onclick="nextWeek({{ $i }})">Tuần sau ➡</button>
                </div>
                <canvas id="{{ $chart['id'] }}" width="500" height="300"></canvas>
            </div>
    @endforeach
    </div>

    <script>
    const chartData = [
        { // Chart 1 - Line
            labels: ["T2","T3","T4","T5","T6","T7","CN"],
            weeks: [
                { data: [10, 12, 8, 14, 6, 9, 11], title: "Tuần 1" },
                { data: [8, 15, 12, 10, 9, 13, 7], title: "Tuần 2" },
                { data: [14, 9, 11, 15, 12, 10, 8], title: "Tuần 3 (Mới nhất)" }
            ],
            type: 'line',
            options: { fill: true, tension: 0.4, backgroundColor: 'rgba(54,162,235,0.3)', borderColor: 'rgba(54,162,235,1)' }
        },
        { // Chart 2 - Line
            labels: ["T2","T3","T4","T5","T6","T7","CN"],
            weeks: [
                { data: [14, 2, 52, 12, 53, 12, 22], title: "Tuần 1" },
                { data: [14, 9, 11, 15, 12, 10, 8], title: "Tuần 2" },
                { data: [23, 9, 22, 10, 19, 23, 31], title: "Tuần 3 (Mới nhất)" }
            ],
            type: 'line',
            options: { fill: true, tension: 0.4, backgroundColor: 'rgba(255,159,64,0.3)', borderColor: 'rgba(255,159,64,1)' }
        },
        { // Chart 3 - Horizontal bar
            labels: ['1*', "2*", "3*", "4*", "5*"],
            weeks: [
                { data: [12, 23, 34, 13, 7], title: "Tuần 1" },
                { data: [4, 2, 6, 4, 13], title: "Tuần 2" },
                { data: [22, 12, 3, 5, 31], title: "Tuần 3 (Mới nhất)" }
            ],
            type: 'bar',
            options: { indexAxis: 'y', backgroundColor: 'rgba(75,192,192,0.6)' }
        },
        { // Chart 4 - Pie
            labels: ['Khách mới', 'Khách quay lại'],
            weeks: [
                { data: [50, 20], title: "Tuần 1" },
                { data: [60, 25], title: "Tuần 2" },
                { data: [80, 40], title: "Tuần 3 (Mới nhất)" }
            ],
            type: 'pie',
            options: { backgroundColor: ['rgba(153,102,255,0.6)','rgba(255,206,86,0.6)'] }
        }
    ];

    let currentWeeks = chartData.map(() => 2);
    let charts = [];

    chartData.forEach((c, i) => {
        let ctx = document.getElementById([
            'myAreaChart','bieudo2','bieudo3','bieudo4'
        ][i]).getContext('2d');

        charts[i] = new Chart(ctx, {
            type: c.type,
            data: {
                labels: c.labels,
                datasets: [{
                    label: c.weeks[2].title,
                    data: c.weeks[2].data,
                    ...c.options
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } } }
        });
    });

    function updateChart(index) {
        let weekIndex = currentWeeks[index];
        let cData = chartData[index];
        charts[index].data.datasets[0].data = cData.weeks[weekIndex].data;
        charts[index].data.datasets[0].label = cData.weeks[weekIndex].title;
        charts[index].update();
    }

    function prevWeek(index) {
        if (currentWeeks[index] > 0) {
            currentWeeks[index]--;
            updateChart(index);
        }
    }
    function nextWeek(index) {
        if (currentWeeks[index] < chartData[index].weeks.length - 1) {
            currentWeeks[index]++;
            updateChart(index);
        }
    }
    </script>
@endsection
