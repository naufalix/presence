<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Meta;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DashHome extends Controller
{
    private function meta(){
        $meta = Meta::$data_meta;
        $meta['title'] = 'Dashboard | Home';
        return $meta;
    }

    public function index(){
        $user_id = auth()->user()->id;

        return view('dashboard.home',[
            "meta" => $this->meta(),
            "presence" => Presence::where('user_id', $user_id)
                ->whereDate('presence_date', Carbon::today())
                ->first()
        ]);
    }

    public function postHandler(Request $request){
        if (now()->isSunday()) {
            return back()->with('error', 'Presensi tidak tersedia di hari Minggu');
        }
        
        if ($request->submit == 'check_in') {
            $res = $this->check_in($request);
            return back()->with($res['status'], $res['message']);
        }

        if ($request->submit == 'check_out') {
            $res = $this->check_out($request);
            return back()->with($res['status'], $res['message']);
        }

        return back()->with('info', 'Submit not found');
    }

    /* ================= CHECK IN ================= */
    public function check_in(Request $request){
        $user_id = auth()->user()->id;

        $validatedData = $request->validate([
            'location' => 'required|string',
            'image' => 'required|string',
        ]);

        // Cegah double check-in
        $exist = Presence::where('user_id', $user_id)
            ->whereDate('presence_date', today())
            ->first();

        if ($exist && $exist->status != 0) {
            return ['status'=>'error','message'=>'Anda sudah check-in hari ini'];
        }

        // Read image
        $image = $request->image;

        // hilangkan spasi hasil url-encoding
        $image = str_replace(' ', '+', $image);

        // validasi format base64 image
        if (!preg_match('/^data:image\/(\w+);base64,/', $image)) {
            abort(400, 'Format gambar tidak valid');
        }

        $manager = new ImageManager(new Driver());
        $image = $manager->read($image);

        // ======= JANGAN DIUBAH (logic resize kamu) =======
        $maxwidth = 300;
        $maxheight = 300;
        if ($image->width() > $image->height()) {
            if ($image->width() > $maxwidth) {
                $newheight = $image->height() / ($image->width() / $maxwidth);
                $image->resize($maxwidth, $newheight);
            }
        } else {
            if ($image->height() > $maxheight) {
                $newwidth = $image->width() / ($image->height() / $maxheight);
                $image->resize($newwidth, $maxheight);
            }
        }
        // ===============================================

        // Convert to webp
        $imageWebp = $image->toWebp(100);

        // Check directory
        $dir = 'storage/img/absent';
        if (!is_dir($dir)) {mkdir($dir, 0755, true);}

        $filename = date('Y-m-d') . '-' . $user_id . '-in-' . time() . '.webp';
        $imageWebp->save('storage/img/absent/' . $filename);

        Presence::create([
            'user_id'       => $user_id,
            'presence_date' => today(),
            'time_in'       => now()->format('H:i:s'),
            'location_in'   => $validatedData['location'],
            'image_in'      => $filename,
            'status'        => 1,
        ]);

        return ['status'=>'success','message'=>'Presensi masuk berhasil'];
    }

    /* ================= CHECK OUT ================= */
    public function check_out(Request $request){
        $user_id = auth()->user()->id;

        $validatedData = $request->validate([
            'location' => 'required|string',
            'image' => 'required|string',
        ]);

        $presence = Presence::where('user_id', $user_id)
            ->whereDate('presence_date', today())
            ->first();

        if (!$presence || $presence->status != 1) {
            return ['status'=>'error','message'=>'Anda belum check-in'];
        }

        // Read image
        $image = $request->image;

        // hilangkan spasi hasil url-encoding
        $image = str_replace(' ', '+', $image);

        // validasi format base64 image
        if (!preg_match('/^data:image\/(\w+);base64,/', $image)) {
            abort(400, 'Format gambar tidak valid');
        }

        $manager = new ImageManager(new Driver());
        $image = $manager->read($image);

        // ======= JANGAN DIUBAH (logic resize kamu) =======
        $maxwidth = 300;
        $maxheight = 300;
        if ($image->width() > $image->height()) {
            if ($image->width() > $maxwidth) {
                $newheight = $image->height() / ($image->width() / $maxwidth);
                $image->resize($maxwidth, $newheight);
            }
        } else {
            if ($image->height() > $maxheight) {
                $newwidth = $image->width() / ($image->height() / $maxheight);
                $image->resize($newwidth, $maxheight);
            }
        }
        // ===============================================

        // Convert to webp
        $imageWebp = $image->toWebp(100);

        $filename = date('Y-m-d') . '-' . $user_id . '-out-' . time() . '.webp';
        $imageWebp->save('storage/img/absent/' . $filename);

        $presence->update([
            'time_out'     => now()->format('H:i:s'),
            'location_out' => $validatedData['location'],
            'image_out'    => $filename,
            'status'       => 2,
        ]);

        return ['status'=>'success','message'=>'Anda berhasil check-out'];
    }
}
