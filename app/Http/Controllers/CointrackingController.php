<?php

namespace App\Http\Controllers;

use App\Services\CointrackingApiService;
use App\Services\TransactionMapperService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;

class CointrackingController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(
        private TransactionMapperService $mapper,
        private CointrackingApiService $api
    ) {}

    public function send(Request $request)
    {
        $transactions = unserialize(htmlspecialchars_decode($request->get('transactions')));
        $json = $this->mapper->transactionsToJson($transactions);
        $this->api->send($json);
        return view('success');
    }
}
