<?php

// app/Http/Controllers/BookingsController.php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingsController extends Controller
{
    public function store(Request $request)
    {
        // Валидация входных данных
        $validator = Validator::make($request->all(), [
            'tour_name' => 'required|string',
            'hunter_name' => 'required|string',
            'guide_id' => 'required|exists:guides,id',
            'date' => 'required|date',
            'participants_count' => 'required|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $guide = Guide::find($request->guide_id);

        if (!$guide || !$guide->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Гид не найден или неактивен'
            ], 404);
        }

        // Проверка на наличие других бронирований у гида
        if ($guide->bookings()->whereDate('date', $request->date)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'У гида уже есть бронирование на эту дату'
            ], 409);
        }

        // Создание бронирования
        $booking = Booking::create([
            'tour_name' => $request->tour_name,
            'hunter_name' => $request->hunter_name,
            'guide_id' => $request->guide_id,
            'date' => $request->date,
            'participants_count' => $request->participants_count
        ]);

        return redirect()->route('bookings.index');
    }

    public function create()
    {
        $guides = Guide::where('is_active', true)->get();

        return view('bookings.create', compact('guides'));
    }

    public function index(Request $request)
    {
        $query = Booking::with('guide');

        // Фильтрация по дате
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        }

        // Поиск по названию тура или имени охотника
        if ($request->filled('search')) {
            $query->where('tour_name', 'like', "%{$request->search}%")
                ->orWhere('hunter_name', 'like', "%{$request->search}%");
        }

        // Сортировка
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $query->orderBy($sort, $order);

        $bookings = $query->latest()->paginate(10);

        return view('bookings.index', compact('bookings', 'request', 'order'));
    }

    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $guides = Guide::where('is_active', true)->get();

        return view('bookings.edit', compact('booking', 'guides'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validatedData = $request->validate([
            'tour_name' => 'required|string|max:255',
            'hunter_name' => 'required|string|max:255',
            'guide_id' => 'nullable|exists:guides,id',
            'date' => 'required|date|after:today',
            'participants_count' => 'required|integer|min:1',
        ]);

        try {
            $booking->update($validatedData);

            return redirect()
                ->route('bookings.show', $booking)
                ->with('success', 'Бронирование успешно обновлено');
        } catch (\Exception $e) {
            report($e);
            return redirect()
                ->back()
                ->withErrors(['error' => 'Ошибка при обновлении бронирования'])
                ->withInput();
        }
    }

    public function checkDate(Request $request)
    {
        // Валидация входящих данных
        $validatedData = $request->validate([
            'date' => 'required|date',
            'guide_id' => 'nullable|integer|exists:guides,id'
        ]);

        $date = $validatedData['date'];
        $guideId = $validatedData['guide_id'] ?? null;

        $isAvailable = true;

        if ($guideId) {
            $isAvailable = !Booking::query()->where(['guide_id' => $guideId])->where(['date' => $date])->first();
            // dump($isAvailable);
            $isAvailable = !empty($isAvailable);
            // dump($isAvailable);
        }

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Дата доступна' : 'Гид занят в этот день'
        ]);

    }


    /**
     * Удалить бронирование
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Booking $booking)
    {
        try {
            $booking->delete();

            return redirect()
                ->route('bookings.index')
                ->with('success', 'Бронирование успешно удалено');
        } catch (\Exception $e) {
            report($e);
            return redirect()
                ->back()
                ->withErrors(['error' => 'Ошибка при удалении бронирования']);
        }

    }


}
