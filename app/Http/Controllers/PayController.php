<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pay;

class PayController extends Controller
{
    public $mPay;

    public function __construct(Pay $tbPay)
    {
        $this->mPay = $tbPay;
    }

    public function index(Request $request)
    {
        $searchYear = isset($request->searchYear) ? $request->searchYear : date('Y');
        $searchMonth = isset($request->searchMonth) ? $request->searchMonth : date('m');

        $rows = $this->mPay->getPays($request);
        $payTypesCount = [];
        $paySum = 0;
        foreach ($rows as $idx => $val) {
            if ($val['time_cnt'] === 0) continue;
            $paySum += $val['time_cnt'];
            if (empty(array_has($payTypesCount, $val['time_cnt']))) {
                $payTypesCount = array_add($payTypesCount, $val['time_cnt'], 0);
            }

            $payTypesCount[$val['time_cnt']]++;
        }
        $dt =  \Carbon\Carbon::create($searchYear, $searchMonth, 01, 0, 0, 0);
        $dt->subMonths(1);
        $title="진채은 스캐줄표";
        $returnData = [
                'searchYear' => $searchYear
                ,'searchMonth' => $searchMonth
                ,'searchYear2' => $dt->format('Y')
                ,'searchMonth2' => $dt->format('m')
                ,'paySum' => $paySum
                ,'payTypesCount' => $payTypesCount
                ,'pathInfo' => $request->segment(1) === 'statement' ? '1' : '2'
                ,'title' => $request->segment(1) === 'statement' ? $title . "(정산)" : $title
                ,'thisUrl' => request()->url()
        ];
        return view('welcome', $returnData);
    }

    public function calenderData(Request $request)
    {
        $searchYear = isset($request->searchYear) ? $request->searchYear : date('Y');
        $searchMonth = isset($request->searchMonth) ? $request->searchMonth : date('m');

        $rows = $this->mPay->getPays($request);
        $list = [];
        foreach ($rows as $idx => $val) {
            $dt =  \Carbon\Carbon::createFromFormat('Y-m-d', $val['date']);
            $val['id'] = $idx;
            $val['title'] = (string)$val['time_cnt'];
            $val['week'] = $this->weekTextConvt($dt->dayOfWeek);
            $list[] = $val;
            $list2[$val['date']] = $val;
        }

        $returnData = [
            'list' => $list
            ,'list2' => $list2
            ,'searchYear' => $searchYear
            ,'searchMonth' => $searchMonth
        ];

        return response()->json($returnData);
    }

    public function timeCountSetting(Request $request)
    {
        $this->mPay->timeCntUpdate($request);
    }

    public function weekTextConvt($var)
    {
        $returnData = [];
        switch ($var) {
            case 0 :
                $returnData = "일";
                break;
            case 1 :
                $returnData = "월";
                break;
            case 2 :
                $returnData = "화";
                break;
            case 3 :
                $returnData = "수";
                break;
            case 4 :
                $returnData = "목";
                break;
            case 5 :
                $returnData = "금";
                break;
            case 6 :
                $returnData = "토";
                break;
        }

        return $returnData;
    }
}
