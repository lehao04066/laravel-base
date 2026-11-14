<?php

namespace Tests\Feature;

use App\Models\Amenity;
use App\Models\OperatingHour;
use App\Models\Venue;
use Carbon\Carbon;
use Tests\TestCase;

class CV01ExploreFilterTest extends TestCase
{
    /**
     * 1. Test chưa lọc: Hiển thị đầy đủ danh sách cụm sân và bộ lọc
     */
    public function test_explore_page_renders_unfiltered_venues(): void
    {
        $response = $this->get('/explore');

        $response->assertStatus(200);
        $venues = $response->viewData('venues');
        $this->assertEquals(3, $venues->total());
        $this->assertTrue($venues->contains('name', 'GoSport Arena Cầu Giấy'));
        $this->assertTrue($venues->contains('name', 'GoSport Premium Mỹ Đình'));
        $this->assertTrue($venues->contains('name', 'GoSport Star Thanh Xuân'));

        $response->assertSee('Áp dụng bộ lọc');
        $response->assertSee('Xóa bộ lọc');
    }

    /**
     * 2. Test lọc theo từ khóa (tên cụm sân hoặc địa chỉ)
     */
    public function test_filter_by_keyword(): void
    {
        // Tìm theo tên
        $responseName = $this->get('/explore?q=Cầu+Giấy');
        $responseName->assertStatus(200);
        $venuesName = $responseName->viewData('venues');
        $this->assertEquals(1, $venuesName->total());
        $this->assertTrue($venuesName->contains('name', 'GoSport Arena Cầu Giấy'));
        $this->assertFalse($venuesName->contains('name', 'GoSport Premium Mỹ Đình'));

        // Tìm theo địa chỉ
        $responseAddress = $this->get('/explore?q=Phạm+Văn+Đồng');
        $responseAddress->assertStatus(200);
        $venuesAddress = $responseAddress->viewData('venues');
        $this->assertEquals(1, $venuesAddress->total());
        $this->assertTrue($venuesAddress->contains('name', 'GoSport Arena Cầu Giấy'));
    }

    /**
     * 3. Test lọc theo quận/huyện
     */
    public function test_filter_by_district(): void
    {
        $response = $this->get('/explore?district=Nam+Từ+Liêm');
        $response->assertStatus(200);
        $venues = $response->viewData('venues');
        $this->assertEquals(1, $venues->total());
        $this->assertTrue($venues->contains('name', 'GoSport Premium Mỹ Đình'));
        $this->assertFalse($venues->contains('name', 'GoSport Arena Cầu Giấy'));
        $this->assertFalse($venues->contains('name', 'GoSport Star Thanh Xuân'));
    }

    /**
     * 4. Test lọc theo khoảng giá độc lập (min_price, max_price)
     */
    public function test_filter_by_price_range_independent(): void
    {
        // Chỉ nhập min_price = 200.000:
        // Cầu Giấy có giá 200.000, Mỹ Đình có giá 210.000, Thanh Xuân max là 180.000
        $resMin = $this->get('/explore?min_price=200000');
        $resMin->assertStatus(200);
        $venuesMin = $resMin->viewData('venues');
        $this->assertEquals(2, $venuesMin->total());
        $this->assertTrue($venuesMin->contains('name', 'GoSport Arena Cầu Giấy'));
        $this->assertTrue($venuesMin->contains('name', 'GoSport Premium Mỹ Đình'));
        $this->assertFalse($venuesMin->contains('name', 'GoSport Star Thanh Xuân'));

        // Chỉ nhập max_price = 115.000:
        // Thanh Xuân có mức 110.000, Cầu Giấy min 120.000, Mỹ Đình min 130.000
        $resMax = $this->get('/explore?max_price=115000');
        $resMax->assertStatus(200);
        $venuesMax = $resMax->viewData('venues');
        $this->assertEquals(1, $venuesMax->total());
        $this->assertTrue($venuesMax->contains('name', 'GoSport Star Thanh Xuân'));

        // Nhập cả hai min_price = 110.000 và max_price = 125.000:
        // Cầu Giấy có 120.000, Thanh Xuân có 110.000
        $resBoth = $this->get('/explore?min_price=110000&max_price=125000');
        $resBoth->assertStatus(200);
        $venuesBoth = $resBoth->viewData('venues');
        $this->assertEquals(2, $venuesBoth->total());
        $this->assertTrue($venuesBoth->contains('name', 'GoSport Arena Cầu Giấy'));
        $this->assertTrue($venuesBoth->contains('name', 'GoSport Star Thanh Xuân'));
        $this->assertFalse($venuesBoth->contains('name', 'GoSport Premium Mỹ Đình'));
    }

    /**
     * 5. Test lọc nhiều tiện ích (AND logic: phải có đủ tất cả tiện ích đã chọn)
     */
    public function test_filter_by_multiple_amenities_requires_all(): void
    {
        // Amenity 1 (Sân điều hòa) & Amenity 7 (Dịch vụ căng vợt điện tử)
        // Venue 1 có cả 1 và 7. Venue 3 có 1 nhưng KHÔNG có 7.
        $response = $this->get('/explore?amenities[]=1&amenities[]=7');
        $response->assertStatus(200);
        $venues = $response->viewData('venues');
        $this->assertTrue($venues->contains('name', 'GoSport Arena Cầu Giấy'));
        $this->assertFalse($venues->contains('name', 'GoSport Star Thanh Xuân'));
    }

    /**
     * 6. Test kết hợp nhiều tiêu chí cùng lúc (AND)
     */
    public function test_filter_combined_multi_criteria(): void
    {
        $response = $this->get('/explore?q=GoSport&district=Cầu+Giấy&min_price=100000&max_price=250000&amenities[]=1');
        $response->assertStatus(200);
        $venues = $response->viewData('venues');
        $this->assertEquals(1, $venues->total());
        $this->assertTrue($venues->contains('name', 'GoSport Arena Cầu Giấy'));
        $this->assertFalse($venues->contains('name', 'GoSport Premium Mỹ Đình'));
        $this->assertFalse($venues->contains('name', 'GoSport Star Thanh Xuân'));
    }

    /**
     * 7. Test validation tham số GET và thông báo lỗi tiếng Việt
     */
    public function test_validation_errors_in_vietnamese(): void
    {
        // Giá âm
        $resNegative = $this->get('/explore?min_price=-5000');
        $resNegative->assertStatus(200);
        $resNegative->assertSee('Giá tối thiểu không được âm.');

        // Giá tối đa nhỏ hơn giá tối thiểu
        $resRangeInvalid = $this->get('/explore?min_price=200000&max_price=100000');
        $resRangeInvalid->assertStatus(200);
        $resRangeInvalid->assertSee('Giá tối đa phải lớn hơn hoặc bằng giá tối thiểu.');

        // Trạng thái không hợp lệ
        $resInvalidStatus = $this->get('/explore?status=khong_hop_le');
        $resInvalidStatus->assertStatus(200);
        $resInvalidStatus->assertSee('Trạng thái mở cửa không hợp lệ.');

        // Tiện ích không tồn tại
        $resInvalidAmenity = $this->get('/explore?amenities[]=999999');
        $resInvalidAmenity->assertStatus(200);
        $resInvalidAmenity->assertSee('Tiện ích đã chọn không tồn tại trong hệ thống.');
    }

    /**
     * 8. Test kết quả rỗng hiển thị đúng thông điệp
     */
    public function test_empty_results_display(): void
    {
        $response = $this->get('/explore?q=CumSanHoanToanKhongTonTai999');
        $response->assertStatus(200);
        $response->assertSee('Không tìm thấy cụm sân phù hợp');
    }

    /**
     * 9. Test xác định trạng thái mở cửa ca qua nửa đêm và sát giờ biên
     */
    public function test_opening_hours_cross_midnight_and_edge_cases(): void
    {
        $venue = Venue::with('operatingHours')->find(1);

        // Ca chuẩn: 06:00:00 - 23:30:00
        $this->assertTrue($venue->isOpen('2026-11-13 06:00:00')); // Sát biên mở
        $this->assertTrue($venue->isOpen('2026-11-13 14:00:00')); // Giữa ca
        $this->assertTrue($venue->isOpen('2026-11-13 23:30:00')); // Sát biên đóng
        $this->assertFalse($venue->isOpen('2026-11-13 23:30:01')); // Vừa qua giờ đóng
        $this->assertFalse($venue->isOpen('2026-11-13 05:59:59')); // Trước giờ mở

        // Thử nghiệm ca qua nửa đêm: Đổi giờ Thứ 6 (Day 5) từ 18:00 đến 02:00 sáng Thứ 7
        $ohFriday = OperatingHour::where('venue_id', 1)->where('day_of_week', 5)->first();
        $origOpen = $ohFriday->open_time;
        $origClose = $ohFriday->close_time;

        $ohFriday->update(['open_time' => '18:00:00', 'close_time' => '02:00:00']);
        $venueFresh = Venue::with('operatingHours')->find(1);

        // Thứ 6 17:59 (chưa mở)
        $this->assertFalse($venueFresh->isOpen('2026-11-13 17:59:00'));
        // Thứ 6 18:00 (bắt đầu mở)
        $this->assertTrue($venueFresh->isOpen('2026-11-13 18:00:00'));
        // Thứ 6 23:59 (sát nửa đêm)
        $this->assertTrue($venueFresh->isOpen('2026-11-13 23:59:00'));
        // Thứ 7 00:01 (vừa qua nửa đêm, tiếp tục ca Thứ 6)
        $this->assertTrue($venueFresh->isOpen('2026-11-14 00:01:00'));
        // Thứ 7 01:30 (rạng sáng hôm sau)
        $this->assertTrue($venueFresh->isOpen('2026-11-14 01:30:00'));
        // Thứ 7 02:00 (sát biên đóng)
        $this->assertTrue($venueFresh->isOpen('2026-11-14 02:00:00'));
        // Thứ 7 02:01 (đã đóng ca đêm)
        $this->assertFalse($venueFresh->isOpen('2026-11-14 02:01:00'));

        // Khôi phục giờ cũ
        $ohFriday->update(['open_time' => $origOpen, 'close_time' => $origClose]);
    }
}
