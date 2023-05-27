<?php

namespace App\Http\Controllers;

use App\Http\Requests\BinCheckRequest;
use App\Services\BinCheckService;
use App\Traits\ResponseJson;
use Symfony\Component\HttpFoundation\Response;

class BinCheckController extends Controller
{
    use ResponseJson;

    private BinCheckService $binCheckService;

    public function __construct(BinCheckService $binCheckService)
    {
        $this->binCheckService = $binCheckService;
    }

    public function checkBinNumber(BinCheckRequest $request)
    {
        $binCheckService = $this->binCheckService->checkBin($request->bin);

        if ($binCheckService){
            return $this->data(
                Response::HTTP_OK,
                true,
                true,
                "The BIN number is valid.",
                $binCheckService);
        }


        return $this->data(
            Response::HTTP_NOT_FOUND,
            false,
            false,
            "The BIN number that you entered does not exist in our database. Please try again.",
            null);

    }
}
