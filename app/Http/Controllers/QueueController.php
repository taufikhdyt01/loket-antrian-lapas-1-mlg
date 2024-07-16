<?php

namespace App\Http\Controllers;

use App\Models\Loket;
use Illuminate\Http\Request;
use App\Models\Queue;

class QueueController extends Controller
{
    public function index()
    {
        if (request()->expectsJson()) {
            $lokets = Loket::all();
            return response()->json(['lokets' => $lokets]);
        }

        $lokets = Loket::all();
        return view('dashboard', compact('lokets'));
    }

    public function addLoket()
    {
        $loketCount = Loket::count();
        $loket = Loket::create(['name' => 'Loket ' . ($loketCount + 1)]);

        return redirect()->back()->with('status', 'Loket berhasil ditambahkan.');;
    }

    public function removeLoket(Request $request)
    {
        $loket = Loket::findOrFail($request->id);
        $loket->delete();

        return redirect()->back()->with('status', 'Loket berhasil dihapus.');
    }

    public function next(Request $request)
    {
        $lastQueue = Queue::orderBy('number', 'desc')->first();
        $nextNumber = $lastQueue ? $lastQueue->number + 1 : 1;
        $queue = new Queue();
        $queue->number = $nextNumber;
        $queue->loket = $request->loket;
        $queue->save();

        // Update nomor antrian terakhir yang dipanggil di loket
        $loket = Loket::find($request->loket);
        $loket->last_called_number = $queue->number;
        $loket->save();

        // Pemanggilan suara
        $audioFiles = $this->getAudioFiles($queue->number, $queue->loket);

        return response()->json(['audioFiles' => $audioFiles, 'number' => $queue->number, 'loket' => $queue->loket]);
    }

    public function repeat(Request $request)
    {
        $queue = Queue::where('loket', $request->loket)
            ->orderBy('number', 'desc')
            ->first();

        if ($queue) {
            // Pemanggilan suara
            $audioFiles = $this->getAudioFiles($queue->number, $queue->loket);
            return response()->json(['audioFiles' => $audioFiles, 'number' => $queue->number, 'loket' => $queue->loket]);
        }
 
        return response()->json(['audioFiles' => [], 'number' => null, 'loket' => $request->loket]);
    }

    public function recall(Request $request)
    {
        $number = $request->number;
        $loket = $request->loket;

        // Pemanggilan suara
        $audioFiles = $this->getAudioFiles($number, $loket);

        // Update nomor antrian terakhir yang dipanggil di loket
        $loket = Loket::find($loket);
        $loket->last_called_number = $number;
        $loket->save();

        return response()->json(['audioFiles' => $audioFiles, 'number' => $number, 'loket' => $loket]);
    }

    private function getAudioFiles($number, $loket)
    {
        $path = asset('sound/');
        $files = [$path . '/bell_in.mp3', $path . '/no_urut.mp3'];

        if ($number <= 11) {
            $files[] = $path . '/' . $number . '.mp3';
        } elseif ($number > 11 && $number < 20) {
            $files[] = $path . '/' . ($number - 10) . '.mp3';
            $files[] = $path . '/belas.mp3';
        } elseif ($number >= 20 && $number % 10 == 0) {
            $files[] = $path . '/' . floor($number / 10) . '.mp3';
            $files[] = $path . '/puluh.mp3';
        } elseif ($number > 20) {
            $files[] = $path . '/' . floor($number / 10) . '.mp3';
            $files[] = $path . '/puluh.mp3';
            if ($number % 10 != 0) {
                $files[] = $path . '/' . $number % 10 . '.mp3';
            }
        }

        $files[] = $path . '/di_loket.mp3';

        // Cari nama loket berdasarkan ID loket
        $loket = Loket::find($loket);
        if ($loket) {
            $loketName = $loket->name;

            // Misalkan nama loket adalah "Loket 1", "Loket 2", dst.
            $loketNumber = preg_replace('/[^0-9]/', '', $loketName);
            $files[] = $path . '/' . $loketNumber . '.mp3';
        }

        $files[] = $path . '/bell_out.mp3';
        return $files;
    }

    public function reset()
    {
        Queue::truncate();

        Loket::query()->update(['last_called_number' => 0]);

        return redirect()->back()->with('status', 'Antrian berhasil di-reset.');
    }
}
