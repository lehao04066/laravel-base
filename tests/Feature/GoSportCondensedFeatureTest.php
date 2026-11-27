<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Court;
use App\Models\CourtPricingRule;
use App\Models\CourtType;
use App\Models\OperatingHour;
use App\Models\User;
use App\Models\Venue;
use App\Services\BookingService;
use App\Services\CourtAvailabilityService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoSportCondensedFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $client1;
    protected User $client2;
    protected Venue $venue;
    protected Court $court1;
    protected Court $court2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Admin
        $this->admin = User::create([
            'name' => 'Admin GoSport',
            'email' => 'admin@gosport.vn',
            'phone' => '0901000001',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        // 2. Create Clients
        $this->client1 = User::create([
            'name' => 'Client One',
            'email' => 'client1@gosport.vn',
            'phone' => '0911000001',
            'password' => 'password123',
            'role' => 'client',
        ]);

        $this->client2 = User::create([
            'name' => 'Client Two',
            'email' => 'client2@gosport.vn',
            'phone' => '0911000002',
            'password' => 'password123',
            'role' => 'client',
        ]);

        // 3. Create Venue Complex
        $this->venue = Venue::create([
            'owner_id' => $this->admin->id,
            'name' => 'GoSport Arena Cầu Giấy',
            'slug' => 'gosport-arena-cau-giay',
            'address' => '26 Phạm Văn Đồng',
            'district' => 'Cầu Giấy',
            'city' => 'Hà Nội',
            'phone' => '02438886666',
            'opening_time' => '06:00:00',
            'closing_time' => '23:00:00',
            'is_active' => true,
        ]);

        // 4. Operating hours
        for ($d = 0; $d <= 6; $d++) {
            OperatingHour::create([
                'venue_id' => $this->venue->id,
                'day_of_week' => $d,
                'open_time' => '06:00:00',
                'close_time' => '23:00:00',
                'is_closed' => false,
            ]);
        }

        // 5. Pricing Rules
        CourtPricingRule::create([
            'venue_id' => $this->venue->id,
            'name' => 'Giờ thường ngày trong tuần',
            'day_type' => 'weekday',
            'start_time' => '06:00:00',
            'end_time' => '17:00:00',
            'price_per_hour' => 120000.00,
            'is_peak' => false,
        ]);

        CourtPricingRule::create([
            'venue_id' => $this->venue->id,
            'name' => 'Giờ vàng tối trong tuần',
            'day_type' => 'weekday',
            'start_time' => '17:00:00',
            'end_time' => '22:00:00',
            'price_per_hour' => 200000.00,
            'is_peak' => true,
        ]);

        CourtPricingRule::create([
            'venue_id' => $this->venue->id,
            'name' => 'Khung giờ cuối tuần',
            'day_type' => 'weekend',
            'start_time' => '06:00:00',
            'end_time' => '23:00:00',
            'price_per_hour' => 190000.00,
            'is_peak' => true,
        ]);

        // 6. Courts
        $this->court1 = Court::create([
            'venue_id' => $this->venue->id,
            'court_number' => 1,
            'name' => 'Sân 1 - Yonex Standard',
            'surface_type' => 'Yonex BWF Standard',
            'is_active' => true,
        ]);

        $this->court2 = Court::create([
            'venue_id' => $this->venue->id,
            'court_number' => 2,
            'name' => 'Sân 2 - Victor Pro',
            'surface_type' => 'Victor Mat',
            'is_active' => true,
        ]);
    }

    /**
     * Test 1: End-to-end full life cycle
     * Admin creates court & price -> Client finds court -> Client books & receives code
     * -> Admin sees booking -> Admin cancels booking -> Slot re-appears as available.
     */
    public function test_end_to_end_full_flow(): void
    {
        // Step 1: Admin adds a new child court and configures a new price rule
        $this->actingAs($this->admin);

        $addCourtResponse = $this->post(route('admin.courts.store', $this->venue->id), [
            'court_number' => 3,
            'name' => 'Sân 3 - Li-Ning Speed',
            'surface_type' => 'Li-Ning Standard',
        ]);
        $addCourtResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('courts', ['venue_id' => $this->venue->id, 'court_number' => 3]);

        $addPriceResponse = $this->post(route('admin.pricing.storeRule'), [
            'venue_id' => $this->venue->id,
            'name' => 'Khung giờ đêm',
            'day_type' => 'all',
            'start_time' => '22:00',
            'end_time' => '23:00',
            'price_per_hour' => 160000,
            'is_peak' => 0,
        ]);
        $addPriceResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('court_pricing_rules', ['venue_id' => $this->venue->id, 'name' => 'Khung giờ đêm']);

        // Step 2: Client searches and finds the venue
        $this->actingAs($this->client1);

        $searchResponse = $this->get(route('explore', ['q' => 'Cầu Giấy']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('GoSport Arena Cầu Giấy');

        // Step 3: Client books slot for tomorrow: 18:00 - 20:00 (2 hours)
        $bookingDate = Carbon::tomorrow()->toDateString();
        $isWeekend = Carbon::parse($bookingDate)->isWeekend();
        $expectedHourlyRate = $isWeekend ? 190000.00 : 200000.00;
        $expectedTotal = $expectedHourlyRate * 2.0;

        $bookResponse = $this->post(route('booking.submit'), [
            'venue_id' => $this->venue->id,
            'booking_date' => $bookingDate,
            'slots' => [
                ['court_id' => $this->court1->id, 'start_time' => '18:00:00', 'end_time' => '19:00:00'],
                ['court_id' => $this->court1->id, 'start_time' => '19:00:00', 'end_time' => '20:00:00'],
            ],
            'notes' => 'Kiểm thử đặt sân end-to-end',
        ]);

        $booking = Booking::where('user_id', $this->client1->id)->latest()->first();
        $this->assertNotNull($booking);
        $bookResponse->assertRedirect(route('my-bookings.show', $booking->booking_code));

        // Verify booking properties
        $this->assertEquals('confirmed', $booking->status);
        $this->assertEquals('unpaid', $booking->payment_status);
        $this->assertEquals('pay_at_venue', $booking->payment_method);
        $this->assertEquals($expectedTotal, (float)$booking->final_amount);
        $this->assertCount(2, $booking->items);

        // Verify slot is now NOT available
        $availabilityService = app(CourtAvailabilityService::class);
        $this->assertFalse($availabilityService->isSlotAvailable($this->court1->id, $bookingDate, '18:00:00', '19:00:00'));

        // Step 4: Admin views booking in admin list
        $this->actingAs($this->admin);

        $adminBookingsResponse = $this->get(route('admin.bookings.index', ['date' => $bookingDate]));
        $adminBookingsResponse->assertStatus(200);
        $adminBookingsResponse->assertSee($booking->booking_code);

        // Step 5: Admin cancels the booking
        $cancelResponse = $this->post(route('admin.bookings.cancel', $booking->id), [
            'reason' => 'Khách hủy theo yêu cầu qua điện thoại',
        ]);
        $cancelResponse->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
        $this->assertEquals('unpaid', $booking->payment_status); // payment status stays separated

        // Check history log was created
        $this->assertDatabaseHas('booking_status_histories', [
            'booking_id' => $booking->id,
            'new_status' => 'cancelled',
            'changed_by_user_id' => $this->admin->id,
        ]);

        // Step 6: Verify slot is immediately released and available again!
        $this->assertTrue($availabilityService->isSlotAvailable($this->court1->id, $bookingDate, '18:00:00', '19:00:00'));
        $this->assertTrue($availabilityService->isSlotAvailable($this->court1->id, $bookingDate, '19:00:00', '20:00:00'));
    }

    /**
     * Test 2: Adjacent time slots vs overlapping time slots
     * Two adjacent bookings (e.g., 08:00 - 09:00 and 09:00 - 10:00) are allowed.
     * Overlapping slots (e.g., 08:30 - 09:30) must be rejected.
     */
    public function test_adjacent_slots_allowed_and_overlapping_rejected(): void
    {
        $bookingDate = Carbon::tomorrow()->toDateString();
        $bookingService = app(BookingService::class);

        // 1. Client 1 books 08:00:00 - 09:00:00
        $booking1 = $bookingService->createBooking($this->client1, $this->venue, $bookingDate, [
            ['court_id' => $this->court1->id, 'start_time' => '08:00:00', 'end_time' => '09:00:00'],
        ]);
        $this->assertEquals('confirmed', $booking1->status);

        // 2. Client 2 books adjacent slot 09:00:00 - 10:00:00 on the SAME court -> MUST SUCCEED
        $booking2 = $bookingService->createBooking($this->client2, $this->venue, $bookingDate, [
            ['court_id' => $this->court1->id, 'start_time' => '09:00:00', 'end_time' => '10:00:00'],
        ]);
        $this->assertEquals('confirmed', $booking2->status);

        // 3. Client 2 attempts to book overlapping slot 08:30:00 - 09:30:00 -> MUST THROW EXCEPTION
        $this->expectException(Exception::class);
        $bookingService->createBooking($this->client2, $this->venue, $bookingDate, [
            ['court_id' => $this->court1->id, 'start_time' => '08:30:00', 'end_time' => '09:30:00'],
        ]);
    }

    /**
     * Test 3: Two concurrent requests on the same court and slot only one can succeed
     */
    public function test_double_booking_same_slot_rejected(): void
    {
        $bookingDate = Carbon::tomorrow()->toDateString();
        $bookingService = app(BookingService::class);

        // 1. First booking succeeds
        $booking1 = $bookingService->createBooking($this->client1, $this->venue, $bookingDate, [
            ['court_id' => $this->court1->id, 'start_time' => '10:00:00', 'end_time' => '11:00:00'],
        ]);
        $this->assertNotNull($booking1);

        // 2. Second booking on the exact same slot must fail
        $caught = false;
        try {
            $bookingService->createBooking($this->client2, $this->venue, $bookingDate, [
                ['court_id' => $this->court1->id, 'start_time' => '10:00:00', 'end_time' => '11:00:00'],
            ]);
        } catch (Exception $e) {
            $caught = true;
            $this->assertStringContainsString('không khả dụng', $e->getMessage());
        }

        $this->assertTrue($caught, 'Second concurrent booking was expected to be rejected.');
        $this->assertEquals(1, Booking::whereDate('booking_date', $bookingDate)->whereHas('items', function ($q) {
            $q->where('court_id', $this->court1->id)
              ->where('start_time', '10:00:00');
        })->count());
    }

    /**
     * Test 4: Security - Client cannot access admin routes
     */
    public function test_client_cannot_access_admin_portal(): void
    {
        // 1. Unauthenticated guest -> redirected to login
        $guestResponse = $this->get(route('admin.dashboard'));
        $guestResponse->assertRedirect(route('login'));

        // 2. Authenticated client -> 403 Forbidden
        $this->actingAs($this->client1);
        $clientResponse = $this->get(route('admin.dashboard'));
        $clientResponse->assertStatus(403);

        $clientVenuesResponse = $this->get(route('admin.venues.index'));
        $clientVenuesResponse->assertStatus(403);

        $clientBookingsResponse = $this->get(route('admin.bookings.index'));
        $clientBookingsResponse->assertStatus(403);
    }

    /**
     * Test 5: Security - Client cannot view other clients' bookings
     */
    public function test_client_cannot_view_other_clients_bookings(): void
    {
        $bookingDate = Carbon::tomorrow()->toDateString();
        $bookingService = app(BookingService::class);

        // Client 1 creates a booking
        $booking1 = $bookingService->createBooking($this->client1, $this->venue, $bookingDate, [
            ['court_id' => $this->court1->id, 'start_time' => '14:00:00', 'end_time' => '15:00:00'],
        ]);

        // Client 2 attempts to view Client 1's booking
        $this->actingAs($this->client2);

        $response = $this->get(route('my-bookings.show', $booking1->booking_code));
        $response->assertStatus(404);

        $cancelResponse = $this->post(route('my-bookings.cancel', $booking1->booking_code), [
            'reason' => 'Hack attempt',
        ]);
        $cancelResponse->assertStatus(404);

        // Client 1 CAN view their own booking
        $this->actingAs($this->client1);
        $ownerResponse = $this->get(route('my-bookings.show', $booking1->booking_code));
        $ownerResponse->assertStatus(200);
        $ownerResponse->assertSee($booking1->booking_code);
    }

    /**
     * Test 6: Registration creates client role and guest can search venues
     */
    public function test_registration_creates_client_and_guest_can_search(): void
    {
        // Guest can view homepage and explore
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee($this->venue->name);

        // Public registration creates user with client role
        $registerResponse = $this->post(route('register'), [
            'name' => 'Nguyễn Văn Mới',
            'email' => 'newclient@gosport.vn',
            'phone' => '0987654321',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $registerResponse->assertRedirect(route('home'));

        $newUser = User::where('email', 'newclient@gosport.vn')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals('client', $newUser->role);
        $this->assertTrue($newUser->isClient());
        $this->assertFalse($newUser->isAdmin());
    }

    /**
     * Test 7: Booking validation - Past dates and times are rejected
     */
    public function test_booking_past_datetime_is_rejected(): void
    {
        $this->actingAs($this->client1);

        // Past date
        $pastDate = Carbon::yesterday()->toDateString();
        $response = $this->post(route('booking.submit'), [
            'venue_id' => $this->venue->id,
            'booking_date' => $pastDate,
            'slots' => [
                ['court_id' => $this->court1->id, 'start_time' => '10:00:00', 'end_time' => '11:00:00'],
            ],
        ]);
        $response->assertSessionHasErrors('booking_date');
    }

    /**
     * Test 8: Booking validation - Outside operating hours is rejected
     */
    public function test_booking_outside_operating_hours_is_rejected(): void
    {
        $bookingDate = Carbon::tomorrow()->toDateString();
        $bookingService = app(BookingService::class);

        // Venue operates from 06:00 to 23:00. Attempt 04:00 - 05:00.
        $caught = false;
        try {
            $bookingService->createBooking($this->client1, $this->venue, $bookingDate, [
                ['court_id' => $this->court1->id, 'start_time' => '04:00:00', 'end_time' => '05:00:00'],
            ]);
        } catch (Exception $e) {
            $caught = true;
        }

        $this->assertTrue($caught, 'Booking outside operating hours should be rejected.');
    }

    /**
     * Test 9: Deactivated court or venue rejects bookings
     */
    public function test_deactivated_court_rejects_booking(): void
    {
        // Deactivate court 2
        $this->court2->update(['is_active' => false]);

        $bookingDate = Carbon::tomorrow()->toDateString();
        $bookingService = app(BookingService::class);

        $caught = false;
        try {
            $bookingService->createBooking($this->client1, $this->venue, $bookingDate, [
                ['court_id' => $this->court2->id, 'start_time' => '14:00:00', 'end_time' => '15:00:00'],
            ]);
        } catch (Exception $e) {
            $caught = true;
        }

        $this->assertTrue($caught, 'Booking on deactivated court should be rejected.');
    }
}
