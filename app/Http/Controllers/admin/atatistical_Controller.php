<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class atatistical_Controller extends Controller
{
    // chuyển giá trị tiền tệ 
    private function formatMoney($number)
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return $number;
    }

    // tính % tăng trưởng
    private function calculateGrowth($today, $yesterday)
    {
        if ($yesterday > 0) {
            return round((($today - $yesterday) / $yesterday) * 100);
        } elseif ($yesterday == 0 && $today > 0) {
            return 100;
        }
        return 0;
    }
    // hàm tính tỉ lệ lấp đầy 
    private function occupancyRate($sold, $available)
    {
        if ($available == 0) {
            return 0;
        } else {
            $rate = ($sold / $available) * 100;
            return round($rate, 2);
        }
    }
    public function index(Request $request)
    {
        $year = Carbon::now()->year;
        $yearsDataRevenue = [];
        $yearsDataBooking = [];
        $yearsDataUsers = [];

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // tăng trưởng đặt phòng
        $todayBookingToday = DB::table('booking')
            ->whereDate('created_at', $today)
            ->where('status', 1)
            ->count();
        $todayBookingyesterday = DB::table('booking')
            ->whereDate('created_at', $yesterday)
            ->where('status', 1)
            ->count();

        $bookingTrend = $this->calculateGrowth($todayBookingToday, $todayBookingyesterday);


        // tăng trưởng doanh thu 
        $todayRevenue = now();
        $startOfMonth = $todayRevenue->copy()->startOfMonth();
        $endOfToday = $todayRevenue;
        // doanh thu dầu tháng tới nay 
        $thisMonthRevenue = DB::table('booking')
            ->whereBetween('created_at', [$startOfMonth, $endOfToday])
            ->where('status', 1)
            ->sum('total_price');
        // doanh thu cùng kì tháng trước
        $startOfLastMonth = $todayRevenue->copy()->subMonth()->startOfMonth();
        $endOfSameDayLastMonth = $startOfLastMonth->copy()->addDays($todayRevenue->day - 1);
        $lastMonthRevenue = DB::table('booking')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfSameDayLastMonth])
            ->where('status', 1)
            ->sum('total_price');
        // goi ham chuyen tin
        $thisMonthRevenueFormatted = $this->formatMoney($thisMonthRevenue);
        $lastMonthRevenueFormatted = $this->formatMoney($lastMonthRevenue);
        $monthRevenueFormatted = $this->calculateGrowth($thisMonthRevenue, $lastMonthRevenue);


        //  khách hàng tháng này 
        $thisMonthUser = DB::table('users')
            ->whereBetween('created_at', [$startOfMonth, $endOfToday])
            ->where('role', 'user')
            ->count();
        // khách hàng tháng trước
        $lastMonthUser = DB::table('users')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfSameDayLastMonth])
            ->where('role', 'user')
            ->count();
        $monthUserFormatted = $this->calculateGrowth($thisMonthUser, $lastMonthUser);

        // Tỉ lệ lấp đầy 
        // tổng số phòng khả dụng
        // tháng năm hiện tại
        $currentMonth = now()->month;
        $currentYear = now()->year;
        // tháng & năm trước
        $lastMonth = now()->subMonth()->month;
        $lastYear = now()->subYear()->year;
        // số ngày trong tháng hiện tại và tháng trước
        $daysInCurrentMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $daysInLastMonth = cal_days_in_month(CAL_GREGORIAN, $lastMonth, $lastYear);
        $thisTotalMonthRooms = DB::table('rooms')
            ->where('status', 1)
            ->count();
        // Ngày khả dụng tháng nay & thang truoc
        $AvailableDateThisMonth = $thisTotalMonthRooms * $daysInCurrentMonth;
        $AvailableDateLastMonth = $thisTotalMonthRooms * $daysInLastMonth;

        // Tính tổng số ngày phòng sử dụng trong tháng hiện tại
        $currentMonthDays = DB::table('booking')
            ->whereIn('status', [1, 2, 3])
            ->whereBetween('check_in_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('SUM(DATEDIFF(check_out_date, check_in_date)) as total_days')
            ->value('total_days');

        // Tính tổng số ngày phòng sử dụng trong tháng trước
        $lastMonthDays = DB::table('booking')
            ->whereIn('status', [1, 2, 3])
            ->whereBetween('check_in_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->selectRaw('SUM(DATEDIFF(check_out_date, check_in_date)) as total_days')
            ->value('total_days');

        // Nếu không có dữ liệu thì gán = 0 để tránh null
        $currentMonthDays = $currentMonthDays ?? 0;
        $lastMonthDays = $lastMonthDays ?? 0;

        //  tỉ lệ lấp đầy
        $occupancyRateThisMonth = $this->occupancyRate($currentMonthDays, $AvailableDateThisMonth);
        $lastmonthOccupancyRate = $this->occupancyRate($lastMonthDays, $AvailableDateLastMonth);
        $fillGrowth = $this->calculateGrowth($currentMonthDays, $lastMonthDays);



        // biểu đồ
        $yearDataEvaluate = [];
        for ($y = $year - 3; $y <= $year; $y++) {
            // Doanh thu
            $revenuePerMonth = DB::table('booking')
                ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
                ->whereYear('created_at', $y)
                ->where('status', 1)
                ->groupBy('month')
                ->pluck('total', 'month');
            $dataRevenue = [];
            for ($m = 1; $m <= 12; $m++) {
                $dataRevenue[] = isset($revenuePerMonth[$m]) ? (int) $revenuePerMonth[$m] : 0;
            }
            $yearsDataRevenue[] = [
                'data' => $dataRevenue,
                'title' => "Doanh thu năm $y"
            ];

            // Số lượng booking
            $bookingPerMonth = DB::table('booking')
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', $y)
                ->where('status', 1)
                ->groupBy('month')
                ->pluck('total', 'month');
            $dataBooking = [];
            for ($m = 1; $m <= 12; $m++) {
                $dataBooking[] = isset($bookingPerMonth[$m]) ? (int) $bookingPerMonth[$m] : 0;
            }
            $yearsDataBooking[] = [
                'data' => $dataBooking,
                'title' => "Lượt đặt phòng năm $y"
            ];

            // khách hàng mới 
            $usersPerMonth = DB::table('users')
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', $y)
                ->where('role', 'user')
                ->groupBy('month')
                ->pluck('total', 'month');
            $dataUsers = [];
            for ($m = 1; $m <= 12; $m++) {
                $dataUsers[] = isset($usersPerMonth[$m]) ? (int) $usersPerMonth[$m] : 0;
            }
            $yearsDataUsers[] = [
                'data' => $dataUsers,
                'title' => "Lượt khách hàng đăng ký năm $y"
            ];
            // lượt đánh giá
            $EvaluatePrerMonth = DB::table('reviews')
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', $y)
                ->where('status', 1)
                ->groupBy('month')
                ->pluck('total', 'month');
            $dataEvaluate = [];
            for ($m = 1; $m <= 12; $m++) {
                $dataEvaluate[] = isset($EvaluatePrerMonth[$m]) ? (int) $EvaluatePrerMonth[$m] : 0;
            }
            $yearDataEvaluate[] = [
                'data' => $dataEvaluate,
                'title' => "Lượt đánh giá năm $y"
            ];
        }




        $doanhthu = [
            [
                'labels' => ["T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8", "T9", "T10", "T11", "T12"],
                'years' => $yearsDataRevenue,
                'type' => 'line'
            ],
            [
                'labels' => ["T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8", "T9", "T10", "T11", "T12"],
                'years' => $yearsDataBooking,
                'type' => 'bar'
            ],
            [
                'labels' => ["T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8", "T9", "T10", "T11", "T12"],
                'years' => $yearsDataUsers,
                'type' => 'line'
            ],
            [
                'labels' => ["T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8", "T9", "T10", "T11", "T12"],
                'years' => $yearDataEvaluate,
                'type' => 'line'
            ]
        ];

        $data = [
            'doanhthu' => $doanhthu,
            'todayBookingToday' => $todayBookingToday,
            'todayBookingyesterday' => $todayBookingyesterday,
            'bookingTrend' => $bookingTrend,
            'monthRevenueFormatted' => $monthRevenueFormatted,
            'thisMonthRevenueFormatted' => $thisMonthRevenueFormatted,
            'thisMonthUser' => $thisMonthUser,
            'monthUserFormatted' => $monthUserFormatted,
            'occupancyRateThisMonth' => $occupancyRateThisMonth,
            'daysInCurrentMonth' => $daysInCurrentMonth,
            'daysInLastMonth' => $daysInLastMonth,
            'currentMonthDays' => $currentMonthDays,
            'lastMonthDays' => $lastMonthDays,
            'fillGrowth' => $fillGrowth,
        ];
        $stats = [
            [
                'title' => 'Tổng đặt phòng hôm nay',
                'number' => $data['todayBookingToday'],
                'trend' => $data['bookingTrend'] . '% so với hôm qua',
            ],
            [
                'title' => 'Tỉ lệ lấp đầy',
                'number' => $data['occupancyRateThisMonth'] . '%',
                'trend' => $data['fillGrowth'] . '% so với cùng kỳ tháng trước',
            ],
            [
                'title' => 'Doanh thu tháng này',
                'number' => $data['thisMonthRevenueFormatted'],
                'trend' => $data['monthRevenueFormatted'] . '% so với cùng kỳ tháng trước',
            ],
            [
                'title' => 'Khách hàng mới',
                'number' => $data['thisMonthUser'],
                'trend' => $data['monthUserFormatted'] . '% so với tháng trước',
            ],
        ];

        return view('admin.statistical.statistical', compact('data', 'stats'));
    }


    // chi tiết thống kê
    public function show($type, Request $request)
    {
        $title = match ($type) {
            0 => "Chi tiết doanh thu",
            1 => "Chi tiết lượt đặt phòng",
            2 => "Chi tiết khách hàng mới",
            3 => "Chi tiết lượt đánh giá",
            default => "Chi tiết thống kê",
        };

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $end_Date = \Carbon\Carbon::parse($endDate)->endOfDay();

        // Tạo query base
        switch ($type) {
            case 0: // Doanh thu
                $query = DB::table('booking')
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, SUM(total_price) as total')
                    ->where('status', 1);
                break;

            case 1: // Lượt đặt phòng
                $query = DB::table('booking')
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, COUNT(*) as total')
                    ->where('status', 1);
                break;

            case 2: // Khách hàng mới
                $query = DB::table('users')
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, COUNT(*) as total')
                    ->where('role', 'user');
                break;

            case 3: // Lượt đánh giá
                $query = DB::table('reviews')
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, COUNT(*) AS total')
                    ->where('status', 1);
                break;

            default:
                $query = collect();
                break;
        }
        if ($query instanceof \Illuminate\Database\Query\Builder) {
            if (!empty($startDate) && !empty($endDate)) {
                $query->whereBetween('created_at', [$startDate, $end_Date]);
            } else {
                $query->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            }

            $data = $query->groupBy('year', 'month', 'day')
                ->orderBy('day', 'desc')
                ->get();
        } else {
            $data = [];
        }


        return view('admin.statistical.detailedStatistics', compact('title', 'type', 'data', 'month', 'year', 'startDate', 'endDate'));
    }


}
