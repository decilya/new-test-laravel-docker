<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tour_name' => 'required|string',
            'hunter_name' => 'required|string',
            'guide_id' => 'required|exists:guides,id',
            'date' => 'required|date',
            'participants_count' => 'required|integer|min:1|max:10'
        ]);

        $guide = Guide::find($validated['guide_id']);

        if (!$guide->is_active) {
            return response()->json(['error' => 'Гид неактивен'], 400);
        }

        if ($guide->bookings()->whereDate('date', $validated['date'])->exists()) {
            return response()->json(['error' => 'У гида уже есть бронирование на эту дату'], 422);
        }

        HuntingBooking::create($validated);

        return response()->json(['message' => 'Бронирование успешно создано'], 201);
    }
}
