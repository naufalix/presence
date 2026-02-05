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
                ->orderBy('presence_date', 'desc')
                ->get()
        ]);
    }

    // recap berdasarkan bulan pilihan
    public function recap(Request $request){

        \Carbon\Carbon::setLocale('id');

        $user_id = auth()->user()->id;

        $month = $request->month ?? now()->format('Y-m');
        $date = Carbon::createFromFormat('Y-m', $month);

        // ambil presensi 1 bulan
        $presences = Presence::where('user_id', $user_id)
            ->whereMonth('presence_date', $date->month)
            ->whereYear('presence_date', $date->year)
            ->get()
            ->keyBy('presence_date'); // penting

        // generate semua tanggal dalam bulan
        $daysInMonth = [];

        for ($i = 1; $i <= $date->daysInMonth; $i++) {
            $daysInMonth[] = Carbon::create(
                $date->year,
                $date->month,
                $i
            )->format('Y-m-d');
        }

        // hitung hari kerja (kecuali Minggu)
        $totalWorkDays = 0;
        $totalHadir = 0;

        foreach ($daysInMonth as $day) {
            $carbon = Carbon::parse($day);

            // skip hari Minggu
            if ($carbon->isSunday()) {
                continue;
            }

            $totalWorkDays++;

            if (isset($presences[$day])) {
                $totalHadir++;
            }
        }

        $totalTidakHadir = $totalWorkDays - $totalHadir;

        $persentaseHadir = $totalWorkDays > 0
            ? round(($totalHadir / $totalWorkDays) * 100, 2)
            : 0;

        return view('dashboard.recap', [
            'meta' => $this->meta(),
            'presences' => $presences,
            'daysInMonth' => $daysInMonth,
            'activeMonth' => $date,
            'totalHadir' => $totalHadir,
            'totalTidakHadir' => $totalTidakHadir,
            'persentaseHadir' => $persentaseHadir,
        ]);
    }

}
