<?php

namespace App\Http\Controllers;

use App\Services\TransactionMapperService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;

class UploadController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(
        private TransactionMapperService $mapper,
    ) {}

    public function  form()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        if(!$request->file()) {
            throw new \Exception("No uploaded file!");
        }
        /** @var UploadedFile $file */
        $file = $request->file()['file'];
        $csv = array_map(function($line) {
            return str_getcsv($line, ";");
        }, explode(PHP_EOL, $file->getContent()));
        $transactions = $this->mapper->csvToTransactions($csv);
        $serialized = htmlspecialchars(serialize($transactions));
        return view('mapped', compact('transactions', "serialized"));
    }
}
