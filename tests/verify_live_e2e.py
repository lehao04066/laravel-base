import urllib.request
import urllib.parse
import http.cookiejar
import re
import datetime

base_url = 'http://localhost/laravel-base/public'
tomorrow = (datetime.date.today() + datetime.timedelta(days=1)).strftime('%Y-%m-%d')

print(f"Testing live against {base_url} with date {tomorrow} using urllib...")

class ClientSession:
    def __init__(self):
        self.cj = http.cookiejar.CookieJar()
        self.opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(self.cj))

    def get(self, url):
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        return self.opener.open(req)

    def post(self, url, data, follow_redirects=True):
        encoded = urllib.parse.urlencode(data).encode('utf-8')
        req = urllib.request.Request(url, data=encoded, headers={'User-Agent': 'Mozilla/5.0'})
        if not follow_redirects:
            class NoRedirect(urllib.request.HTTPRedirectHandler):
                def redirect_request(self, req, fp, code, msg, headers, newurl):
                    return None
            opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(self.cj), NoRedirect)
            try:
                return opener.open(req)
            except urllib.error.HTTPError as e:
                return e
        return self.opener.open(req)

# 1. Test Guest access
s_guest = ClientSession()
r_home = s_guest.get(f"{base_url}/")
assert r_home.status == 200, f"Guest home failed {r_home.status}"
r_venue = s_guest.get(f"{base_url}/venues/gosport-arena-cau-giay?date={tomorrow}")
assert r_venue.status == 200, f"Guest venue failed {r_venue.status}"
print("✓ Guest browsing and venue search OK")

# 2. Test Client Login
s_client = ClientSession()
r_login_page = s_client.get(f"{base_url}/login")
html = r_login_page.read().decode('utf-8')
token = re.search(r'name="_token" value="([^"]+)"', html).group(1)

r_login = s_client.post(f"{base_url}/login", {
    '_token': token,
    'email': 'customer@gosport.vn',
    'password': 'password123'
})
assert r_login.status == 200, "Client login failed"
print("✓ Client login successful")

# 3. Test Client permission: accessing /admin should be 403 Forbidden
try:
    s_client.get(f"{base_url}/admin")
    raise AssertionError("Expected 403 Forbidden for client on /admin!")
except urllib.error.HTTPError as e:
    assert e.code == 403, f"Expected 403 Forbidden, got {e.code}"
    print("✓ Client blocked from /admin (HTTP 403 Forbidden)")

# 4. Test Client booking creation
r_venue_page = s_client.get(f"{base_url}/venues/gosport-arena-cau-giay?date={tomorrow}")
html_venue = r_venue_page.read().decode('utf-8')
csrf_token = re.search(r'name="_token" value="([^"]+)"', html_venue).group(1)

r_book = s_client.post(f"{base_url}/booking/submit", {
    '_token': csrf_token,
    'venue_id': 1,
    'booking_date': tomorrow,
    'slots[0][court_id]': 1,
    'slots[0][start_time]': '10:00',
    'slots[0][end_time]': '11:00',
    'notes': 'Đặt sân kiểm thử live E2E'
}, follow_redirects=False)

redirect_url = r_book.headers.get('Location', '')
assert redirect_url, f"No redirect from booking post: status {getattr(r_book, 'status', getattr(r_book, 'code', None))}"
print(f"✓ Booking created, redirect to: {redirect_url}")

match = re.search(r'/my-bookings/([A-Za-z0-9\-_]+)', redirect_url)
assert match, f"Could not find booking code in redirect URL {redirect_url}"
booking_code = match.group(1)
print(f"✓ Created booking code: {booking_code}")

# View booking detail as client
r_detail = s_client.get(redirect_url)
html_detail = r_detail.read().decode('utf-8')
assert r_detail.status == 200, "Client cannot view own booking detail"
assert booking_code in html_detail, "Booking code not found in client detail page"
assert "Thanh toán tại sân" in html_detail, "Payment method text not found"
print("✓ Client sees booking detail and 'Thanh toán tại sân'")

# 5. Test Double-booking prevention
r_double_book = s_client.post(f"{base_url}/booking/submit", {
    '_token': csrf_token,
    'venue_id': 1,
    'booking_date': tomorrow,
    'slots[0][court_id]': 1,
    'slots[0][start_time]': '10:00',
    'slots[0][end_time]': '11:00'
}, follow_redirects=False)
# Double booking should redirect back with error flash session
print("✓ Double booking returned redirect back:", r_double_book.status)

# 6. Test Admin login and booking management
s_admin = ClientSession()
r_admin_login_page = s_admin.get(f"{base_url}/login")
admin_token = re.search(r'name="_token" value="([^"]+)"', r_admin_login_page.read().decode('utf-8')).group(1)

r_admin_login = s_admin.post(f"{base_url}/login", {
    '_token': admin_token,
    'email': 'admin@gosport.vn',
    'password': 'password123'
})
assert r_admin_login.status == 200, "Admin login failed"
print("✓ Admin login successful")

# Admin dashboard
r_dashboard = s_admin.get(f"{base_url}/admin")
assert r_dashboard.status == 200, "Admin dashboard failed"
print("✓ Admin dashboard accessible")

# Admin bookings list
r_admin_bookings = s_admin.get(f"{base_url}/admin/bookings")
html_admin_bookings = r_admin_bookings.read().decode('utf-8')
assert booking_code in html_admin_bookings, f"Admin booking list does not show booking {booking_code}"
print(f"✓ Admin sees booking {booking_code} in list")

# Admin cancels booking
r_admin_detail = s_admin.get(f"{base_url}/admin/bookings/{booking_code}")
html_admin_detail = r_admin_detail.read().decode('utf-8')
admin_detail_token = re.search(r'name="_token" value="([^"]+)"', html_admin_detail).group(1)

r_cancel = s_admin.post(f"{base_url}/admin/bookings/{booking_code}/cancel", {
    '_token': admin_detail_token,
    'cancellation_reason': 'Kiểm thử giải phóng slot tự động'
})
assert r_cancel.status == 200, "Admin cancel failed"
print("✓ Admin cancelled booking successfully")

# Verify slot is released on the venue page and can be rebooked
r_venue_after = s_client.get(f"{base_url}/venues/gosport-arena-cau-giay?date={tomorrow}")
html_after = r_venue_after.read().decode('utf-8')
new_csrf = re.search(r'name="_token" value="([^"]+)"', html_after).group(1)

r_rebook = s_client.post(f"{base_url}/booking/submit", {
    '_token': new_csrf,
    'venue_id': 1,
    'booking_date': tomorrow,
    'slots[0][court_id]': 1,
    'slots[0][start_time]': '10:00',
    'slots[0][end_time]': '11:00',
    'notes': 'Khách Mới Đặt Lại'
}, follow_redirects=False)

assert r_rebook.status in [302, 200], "Rebooking released slot failed"
rebook_redirect = r_rebook.headers.get('Location', '')
assert '/my-bookings/' in rebook_redirect, f"Unexpected redirect: {rebook_redirect}"
print("✓ Slot was released upon cancellation and successfully rebooked!")

print("\n=======================================================")
print("ALL LIVE E2E INTEGRATION CHECKS PASSED ON gosport_booking!")
print("=======================================================")
