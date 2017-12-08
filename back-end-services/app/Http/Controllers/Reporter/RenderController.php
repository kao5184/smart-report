<?php
namespace App\Http\Controllers\Reporter;

use App\Http\Response;
use App\Services\Reporter\ReporterService;

class RenderController
{

    protected $reporterService;

    public function __construct(ReporterService $reporterService)
    {
        $this->reporterService = $reporterService;
    }

    public function dataRender()
    {
        $data = $this->reporterService->dataRender();

        return Response::success($data);
    }

    public function singleRender($id)
    {
        $data = $this->reporterService->singleRender($id);

        return Response::success($data);
    }
}