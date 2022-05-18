<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class pay extends Model
{
    protected $dates = ['deleted_at', 'requested_at'];

    public function __construct()
    {
        $this->table = 'pays';
    }

    public function getPays(request $request)
    {
        $searchYear = isset($request->searchYear) ? $request->searchYear : date('Y');
        $searchMonth = isset($request->searchMonth) ? $request->searchMonth : date('m');

        $dt = \Carbon\Carbon::create($searchYear, $searchMonth, 01, 0, 0, 0);
        $selectDate = $dt->toDateString();
        $tbPays = Pay::where('date', $selectDate)->get();
        if ($tbPays->count() === 0) {
            $dayEndCount = date('t', strtotime($selectDate));
            for ($i=1; $i <= $dayEndCount; $i++) {
                $daySet = $i < 10 ? '0' . $i : $i;
                $dt = \Carbon\Carbon::create($searchYear, $searchMonth, $daySet, 0, 0, 0);

                $timeCntSet = 2;
                if ($dt->dayOfWeek === 0 || $dt->dayOfWeek === 6) {
                    $timeCntSet=0;
                }
                $insertData = [
                    'date' => $dt->toDateString(),
                    'time_cnt' => $timeCntSet
                ];

                Pay::insert($insertData);
            }
        }
        $tbPays = Pay::whereRaw("date like '%" . $searchYear . "-" . $searchMonth . "%'")->get();
        return $tbPays;
    }

    public function timeCntUpdate(Request $request)
    {
        $date = $request->date;
        $time_cnt = $request->time_cnt;
        Pay::where('date', $date)->update(['time_cnt'=>$time_cnt]);
    }
}
