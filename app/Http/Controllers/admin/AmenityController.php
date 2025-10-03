<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AmenityController extends Controller
{
    protected string $jsonPath = 'amenities.json'; // storage/app/amenities.json

    public function index(Request $request)
    {
        // Trả về danh sách đã được chuẩn hoá thành mảng string
        $list = $this->normalizeAmenities($this->readRaw());
        return response()->json([
            'ok' => true,
            'amenities' => $list,
        ]);
    }

    public function store(Request $request)
    {
        $name = trim((string) $request->input('name', ''));
        if ($name === '') {
            return response()->json(['ok' => false, 'message' => 'Tên tiện nghi không được để trống'], 422);
        }
        if (mb_strlen($name) > 100) {
            return response()->json(['ok' => false, 'message' => 'Tên tiện nghi tối đa 100 ký tự'], 422);
        }

        // Đọc và CHUẨN HOÁ
        $list = $this->normalizeAmenities($this->readRaw());

        // check trùng (case-insensitive)
        $lower = mb_strtolower($name);
        foreach ($list as $item) {
            if (mb_strtolower($item) === $lower) {
                // đã có → coi như thành công, trả lại list hiện tại
                return response()->json(['ok' => true, 'amenities' => $list]);
            }
        }

        $list[] = $name;
        $list = $this->uniqueCaseInsensitive($list);
        natcasesort($list);
        $list = array_values($list);

        $this->writeAmenities($list);

        return response()->json(['ok' => true, 'amenities' => $list]);
    }

    public function delete(Request $request)
    {
        $name = trim((string) $request->input('name', ''));
        if ($name === '') {
            return response()->json(['ok' => false, 'message' => 'Thiếu tên tiện nghi'], 422);
        }

        // Đọc và CHUẨN HOÁ
        $list = $this->normalizeAmenities($this->readRaw());

        $list = array_values(array_filter($list, function ($i) use ($name) {
            return mb_strtolower($i) !== mb_strtolower($name);
        }));

        $this->writeAmenities($list);

        return response()->json(['ok' => true, 'amenities' => $list]);
    }

    /* ==================== Helpers ==================== */

    /**
     * Đọc thô từ file JSON (có thể chứa mảng/obj lẫn chuỗi).
     */
    protected function readRaw(): array
    {
        if (!Storage::disk('local')->exists($this->jsonPath)) {
            $default = [
                'WiFi miễn phí',
                'Điều hòa không khí',
                'Tivi màn hình phẳng',
                'Minibar',
                'Két an toàn',
                'Dịch vụ phòng 24h',
                'Ban công riêng',
                'Bồn tắm',
                'Vòi sen tắm đứng',
                'Máy sấy tóc',
                'Áo choàng tắm',
                'Dép trong phòng',
                'Bàn làm việc',
                'Ghế sofa',
                'Máy pha cà phê/trà',
                'Tủ lạnh',
                'View biển',
                'View thành phố',
                'View hồ bơi',
                'View vườn',
                'Phòng không hút thuốc',
                'Phòng cho người khuyết tật'
            ];
            Storage::disk('local')->put(
                $this->jsonPath,
                json_encode($default, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            );
            return $default;
        }

        $raw = Storage::disk('local')->get($this->jsonPath);
        $data = json_decode($raw, true);

        // Nếu decode lỗi hoặc không phải mảng → trả mảng rỗng để normalize xử lý
        return is_array($data) ? $data : [];
    }

    /**
     * Chuẩn hoá danh sách tiện nghi thành mảng CHUỖI:
     * - Nếu phần tử là chuỗi: trim & giữ lại
     * - Nếu phần tử là mảng/object có key 'name': dùng giá trị đó
     * - Bỏ phần tử rỗng, trùng (case-insensitive)
     */
    protected function normalizeAmenities(array $list): array
    {
        $out = [];

        foreach ($list as $item) {
            $val = null;

            if (is_string($item)) {
                $val = trim($item);
            } elseif (is_array($item)) {
                // một số nơi có thể đã lưu dạng ['name' => 'WiFi']
                if (isset($item['name']) && is_string($item['name'])) {
                    $val = trim($item['name']);
                } elseif (isset($item[0]) && is_string($item[0])) {
                    // hoặc ['WiFi'] → lấy phần tử đầu
                    $val = trim($item[0]);
                }
            } elseif (is_object($item) && isset($item->name) && is_string($item->name)) {
                $val = trim($item->name);
            }

            if ($val !== null && $val !== '') {
                $out[] = $val;
            }
        }

        $out = $this->uniqueCaseInsensitive($out);
        natcasesort($out);
        return array_values($out);
    }

    /**
     * Ghi file (đảm bảo là mảng chuỗi).
     */
    protected function writeAmenities(array $list): void
    {
        // Chuẩn hoá 1 lần nữa để chắc chắn
        $list = $this->normalizeAmenities($list);

        Storage::disk('local')->put(
            $this->jsonPath,
            json_encode($list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    /**
     * Loại bỏ phần tử trùng (không phân biệt hoa thường).
     */
    protected function uniqueCaseInsensitive(array $arr): array
    {
        $seen = [];
        $out = [];
        foreach ($arr as $s) {
            if (!is_string($s)) continue;
            $k = mb_strtolower($s);
            if (!isset($seen[$k])) {
                $seen[$k] = true;
                $out[] = $s;
            }
        }
        return $out;
    }
}
