<?php

namespace App\Http\Controllers;

use App\Services\Concrete\GoldRateService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class GoldRateController extends Controller
{
    use JsonResponse;
    protected $gold_rate_service;


    public function __construct(
        GoldRateService $gold_rate_service
    ) {
        $this->gold_rate_service = $gold_rate_service;
    }

    public function index()
    {
        abort_if(Gate::denies('gold_chart_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $gold_rate = $this->gold_rate_service->recentGoldRate();
        $gold = 100;
        $impurity = 0;
        $ratti = 96;
        $ratti_impurity = 0;
        $rate = $gold_rate->rate_tola;
        $rate_gram = $gold_rate->rate_tola / 11.664;

        $gold_array = [];
        $impurity_array = [];
        $ratti_array = [];
        $ratti_impurity_array = [];
        $rate_array = [];
        $rate_gram_array = [];
        for ($i = 24; $i > 0; $i--) {
            if ($i == 24) {
                $gold_array[$i] = $gold;
                $impurity_array[$i] = $impurity;
                $ratti_array[$i] = $ratti;
                $ratti_impurity_array[$i] = $ratti_impurity;
                $rate_array[$i] = (float)$rate;
                $rate_gram_array[$i] = (float)round($rate_gram,3);
            } else {
                $gold = ($gold / ($i+1)) * $i;
                $impurity = 100 - $gold;
                $ratti = ($ratti_array[($i+1)] / ($i+1))*$i;
                $ratti_impurity = 100-$ratti_array[($i+1)];
                $rate = $rate_array[24] / 24*$i;
                $rate_gram = $rate/11.664;


                $gold_array[$i] = (float)round($gold,3);
                $impurity_array[$i] = (float)round($impurity,3);
                $ratti_array[$i] = $ratti;
                $ratti_impurity_array[$i] = $ratti_impurity;
                $rate_array[$i] = (float)round($rate,3);
                $rate_gram_array[$i] = (float)round($rate_gram,3);
            }

        }

        return view('gold_rate.chart',compact('gold_array','impurity_array','ratti_array','ratti_impurity_array','rate_array','rate_gram_array'));
    }


    public function logs()
    {
        abort_if(Gate::denies('gold_rate_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('gold_rate.logs');
    }
    public function getData(Request $request)
    {
        abort_if(Gate::denies('gold_rate_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $this->gold_rate_service->getGoldRateSource();
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('gold_rate_log_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make(
            $request->all(),
            [
                'gold_rate' => 'required'
            ],
            $this->validationMessage()
        );

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {

            $obj = [
                'rate_tola' => $request->gold_rate,
                'rate_gram' => $request->gold_rate / 11.664,
                'createdby_id' => Auth::user()->id
            ];
            $response = $this->gold_rate_service->save($obj);
            if (!$response)
                return redirect()->back()->with('error', config('enum.error'));


            return redirect()->back()->with('success', config('enum.updated'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', config('enum.error'));
        }
    }

    // Dollar Rate
    public function dollarLog() {
        abort_if(Gate::denies('dollar_rate_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('dollar_rate.logs');
    }

    public function getDollarData(Request $request)
    {
        abort_if(Gate::denies('dollar_rate_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $this->gold_rate_service->getDollarRateSource();
    }

    public function storeDollar(Request $request)
    {
        abort_if(Gate::denies('dollar_rate_log_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make(
            $request->all(),
            [
                'dollar_rate' => 'required'
            ],
            $this->validationMessage()
        );

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {

            $obj = [
                'rate' => $request->dollar_rate,
                'createdby_id' => Auth::user()->id
            ];
            $response = $this->gold_rate_service->saveDollarRate($obj);
            if (!$response)
                return redirect()->back()->with('error', config('enum.error'));


            return redirect()->back()->with('success', config('enum.updated'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', config('enum.error'));
        }
    }
}
