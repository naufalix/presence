<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Meta;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashRecap extends Controller
{
    private function meta()
    {
        $meta = Meta::$data_meta;
        $meta['title'] = 'Dashboard | Recap';
        return $meta;
    }

    // default recap: bulan berjalan
    public function index()
    {
        $user_id = auth()->user()->id;
        $now = Carbon::now();

        return view('dashboard.recap', [
            "meta" => $this->meta(),
            "presences" => Presence::where('user_id', $user_id)
                ->whereMonth('presence_date', $now->month)
                ->whereYear('presence_date', $now->year)
                ->orderBy('presence_date', 'ASC')
                ->get()
        ]);
    }

    // recap berdasarkan bulan pilihan
    public function recap(Request $request)
    {
        $validatedData = $request->validate([
            'month' => 'required|string', // format: YYYY-MM
        ]);

        $user_id = auth()->user()->id;

        $date = Carbon::createFromFormat('Y-m', $validatedData['month']);

        return view('dashboard.recap', [
            "meta" => $this->meta(),
            "presences" => Presence::where('user_id', $user_id)
                ->whereMonth('presence_date', $date->month)
                ->whereYear('presence_date', $date->year)
                ->orderBy('presence_date', 'ASC')
                ->get()
        ]);
    }
}
