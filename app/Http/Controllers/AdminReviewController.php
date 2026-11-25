<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'venue', 'booking', 'reply.user'])
            ->when($request->filled('status'), fn ($q) => $q->where('is_approved', $request->status === 'approved'))
            ->latest()->paginate(12)->withQueryString();

        return view('admin.reviews', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Đã duyệt đánh giá và hiển thị cho khách hàng.');
    }

    public function reply(Request $request, Review $review)
    {
        $data = $request->validate(['reply_text' => ['required', 'string', 'max:2000']]);
        ReviewReply::updateOrCreate(
            ['review_id' => $review->id],
            ['user_id' => $request->user()->id, 'reply_text' => $data['reply_text']]
        );
        return back()->with('success', 'Đã gửi phản hồi CSKH.');
    }
}
