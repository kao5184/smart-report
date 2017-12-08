<?php
namespace App\Http\Controllers\Reporter;

use App\Http\Controllers\Controller;
use App\Http\Response;
use App\Services\Reporter\ReporterService;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{

    protected $reporterService;

    public function __construct(ReporterService $reporterService)
    {
        $this->reporterService = $reporterService;
    }

    public function configSQL(Request $request)
    {
        $this->validate($request, [
            'key' => 'required',
            'sql' => 'required',
            'cache' => 'required|integer',
            'visible' => 'required|integer',
            'title' => 'required',
        ]);

        $key = $request->input('key');
        $sql = $request->input('sql');
        $cache = $request->input('cache');
        $visible = $request->input('visible');
        $title = $request->input('title');
        $description = $request->input('description', '');

        $data = $this->reporterService->configSQL($key, $title, $description, $sql, $cache, $visible);
        return Response::success($data);
    }

    public function config($id)
    {
        $data = $this->reporterService->config($id);
        return Response::success($data);
    }

    public function configs(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);

        $data = $this->reporterService->configs([], $page, $limit);
        return Response::success($data);
    }

    public function deleteSQL($id)
    {
        $data = $this->reporterService->deleteSQL($id);
        return Response::success($data);
    }

    public function updateSQL(Request $request, $id)
    {
        $this->validate($request, [
            'sql' => 'required',
            'cache' => 'required|integer',
            'visible' => 'required|integer',
            'title' => 'required',
        ]);

        $sql = $request->input('sql');
        $cache = $request->input('cache');
        $visible = $request->input('visible');
        $title = $request->input('title');
        $description = $request->input('description', '');

        $data = $this->reporterService->updateSQL($id, $title, $description, $sql, $cache, $visible);

        return Response::success($data);
    }

    public function configMapping(Request $request, $drId)
    {
        $this->validate($request, [
            'axis' => 'required',
            'source' => 'required',
        ]);

        $color = $request->input('color');
        $group = $request->input('source_group');
        $alias = $request->input('alias');
        $max = $request->input('max');
        $min = $request->input('min');
        $axis = $request->input('axis');
        $source = $request->input('source');
        $graphics = $request->input('graphics');

        $data = $this->reporterService->configMapping($drId, $axis, $source, $alias, $min, $max, $group, $color, $graphics);

        return Response::success($data);
    }

    public function updateMapping(Request $request, $id)
    {
        $this->validate($request, [
            'dynamic_reporter_id' => 'required',
            'axis' => 'required',
            'source' => 'required',
        ]);

        $color = $request->input('color');
        $group = $request->input('source_group');
        $alias = $request->input('alias');
        $max = $request->input('max');
        $min = $request->input('min');
        $axis = $request->input('axis');
        $source = $request->input('source');
        $graphics = $request->input('graphics');
        $data = $this->reporterService->updateMapping($id, $axis, $source, $alias, $min, $max, $group, $color, $graphics);

        return Response::success($data);
    }

    public function deleteMapping($id)
    {
        $data = $this->reporterService->deleteMapping($id);

        return Response::success($data);
    }
}