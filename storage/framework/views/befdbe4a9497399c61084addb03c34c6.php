

<script src="https://cdn.jsdelivr.net/npm/chart.js">

</script>

<?php $__env->startSection("content"); ?>
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

        .chitiet {
            text-decoration: none;
        }


        .chart-actions {
            display: flex;

            gap: 12px;
            margin: 15px 0;
        }

        .chart-actions button {
            background-color: #6372b5ff;
            /* Màu xanh chính */
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .chart-actions button:hover {
            background-color: #465289ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        /* Nút "xem chi tiết" */
        .chart-actions button a.chitiet {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .chart-actions button a.chitiet:hover {
            text-decoration: underline;
        }

        .trend .up {
            color: green;
        }

        .trend .down {
            color: red;
        }

        .trend .neutral {
            color: gray;
        }
    </style>


    <div class="stats-grid">
        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="stat-card">
                <h3><?php echo e($stat['title']); ?></h3>
                <div class="number"><?php echo e($stat['number']); ?></div>
                <div class="trend">
                    <?php if($stat['trend'] > 0): ?>
                        <span class="up"><i class="fas fa-arrow-up"></i> <?php echo e($stat['trend']); ?></span>
                    <?php elseif($stat['trend'] < 0): ?>
                        <span class="down"><i class="fas fa-arrow-down"></i> <?php echo e($stat['trend']); ?></span>
                    <?php else: ?>
                        <span class="neutral"><i class="fas fa-minus"></i> <?php echo e($stat['trend']); ?></span>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="tongthongke">
        <?php $__currentLoopData = $data['doanhthu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $chart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="chart-box">
                <h2 id="chartTitle<?php echo e($i); ?>"><?php echo e($chart['years'][count($chart['years']) - 1]['title']); ?></h2>
                <div>
                    <div class="chart-actions">
                        <button type="button" onclick="prevYear(<?php echo e($i); ?>)">⬅ Năm trước</button>
                        <button type="button" onclick="nextYear(<?php echo e($i); ?>)">Năm sau ➡</button>
                        <button><a class="chitiet" href="<?php echo e(route('admin.statistical.detailed', ['type' => $i])); ?>">xem chi
                                tiết</a></button>
                    </div>

                </div>
                <canvas id="chart<?php echo e($i); ?>" width="500" height="300"></canvas>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <script>
        let chartData = <?php echo json_encode($data['doanhthu'], 15, 512) ?>;
        let currentYears = chartData.map(chart => chart.years.length - 1); // mặc định năm mới nhất
        let charts = [];

        chartData.forEach((chart, i) => {
            let ctx = document.getElementById('chart' + i).getContext('2d');
            charts[i] = new Chart(ctx, {
                type: chart.type,
                data: {
                    labels: chart.labels,
                    datasets: [{
                        label: chart.years[currentYears[i]].title,
                        data: chart.years[currentYears[i]].data,
                        backgroundColor: 'rgba(54,162,235,0.5)',
                        borderColor: 'rgba(54,162,235,1)',
                        borderWidth: 1,
                        fill: chart.type === 'line',
                        tension: chart.type === 'line' ? 0.4 : 0
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });
        });

        function updateChart(index) {
            let yearIndex = currentYears[index];
            let cData = chartData[index];
            charts[index].data.datasets[0].data = cData.years[yearIndex].data;
            charts[index].data.datasets[0].label = cData.years[yearIndex].title;
            document.getElementById("chartTitle" + index).innerText = cData.years[yearIndex].title;
            charts[index].update();
        }

        function prevYear(index) {
            if (currentYears[index] > 0) {
                currentYears[index]--;
                updateChart(index);
            }
        }

        function nextYear(index) {
            if (currentYears[index] < chartData[index].years.length - 1) {
                currentYears[index]++;
                updateChart(index);
            }
        }
    </script>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/statistical/statistical.blade.php ENDPATH**/ ?>