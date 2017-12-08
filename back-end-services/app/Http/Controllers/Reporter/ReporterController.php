<?php
namespace App\Http\Controllers\Reporter;

use App\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\Reporter\ReporterService;
use App\Supports\HtmlToPdfHelper;
use Illuminate\Http\Request;

class ReporterController extends Controller
{

    protected $reporterService;

    public function __construct(ReporterService $reporterService)
    {
        $this->reporterService = $reporterService;
    }

    public function all(Request $request)
    {
        $type = $request->input('type', '');
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        $data = $this->reporterService->all($type, $page, $limit);
        return Response::success($data);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $params = [
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
            'author' => $request->input('author'),
            'createdAt' => $request->input('created_at'),
            'description' => $request->input('description'),
            'background' => $request->input('background'),
            'settings' => $request->input('settings'),
            'menu' => $request->input('menu'),
        ];

        $data = $this->reporterService->create($params);
        return Response::success($data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'pages' => 'array',
        ]);

        $params = [
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
            'author' => $request->input('author'),
            'createdAt' => $request->input('created_at'),
            'description' => $request->input('description'),
            'background' => $request->input('background'),
            'settings' => $request->input('settings'),
            'pages' => $request->input('pages'),
            'menu' => $request->input('menu'),
        ];

        $data = $this->reporterService->update($id, $params);
        return Response::success($data);
    }

    public function delete($id)
    {
        $data = $this->reporterService->delete($id);
        return Response::success($data);
    }

    public function allPage($id)
    {
        $data = $this->reporterService->allPage($id);
        return Response::success($data);
    }

    public function queryPage(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);
        $query = $request->input('query', '');
        $type = $request->input('type', '');

        $data = $this->reporterService->queryPage($query, $type, $page, $limit);
        return Response::success($data);
    }

    public function createPage(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $params = [
            'settings' => $request->input('settings'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ];

        $data = $this->reporterService->createPage($params);
        return Response::success($data);
    }

    public function updatePage(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $params = [
            'settings' => $request->input('settings'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ];

        $data = $this->reporterService->updatePage($id, $params);
        return Response::success($data);
    }

    public function deletePage($id)
    {
        $data = $this->reporterService->deletePage($id);
        return Response::success($data);
    }

    public function allParameter(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);
        $query = $request->input('query', '');
        $data = $this->reporterService->allParameter($query, $page, $limit);
        return Response::success($data);
    }

    public function parameters()
    {
        $data = $this->reporterService->parameters();
        return Response::success($data);
    }

    public function createParameter(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'key' => 'required',
            'title' => 'required',
            'value' => 'required',
            'cache' => 'required',
        ]);

        $params = [
            'type' => $request->input('type'),
            'key' => $request->input('key'),
            'title' => $request->input('title'),
            'value' => $request->input('value'),
            'cache' => $request->input('cache'),
            'settings' => $request->input('settings'),
        ];

        $data = $this->reporterService->createParameter($params);
        return Response::success($data);
    }

    public function updateParameter(Request $request, $id)
    {
        $this->validate($request, [
            'type' => 'required',
            'key' => 'required',
            'title' => 'required',
            'value' => 'required',
            'cache' => 'required',
        ]);

        $params = [
            'type' => $request->input('type'),
            'key' => $request->input('key'),
            'title' => $request->input('title'),
            'value' => $request->input('value'),
            'cache' => $request->input('cache'),
            'settings' => $request->input('settings'),
        ];

        $data = $this->reporterService->updateParameter($id, $params);
        return Response::success($data);
    }

    public function deleteParameter($id)
    {
        $data = $this->reporterService->deleteParameter($id);
        return Response::success($data);
    }

    public function export(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'url' => 'required',
        ]);

        $token = $request->header('token', '1234567890');
        $title = $request->input('title');
        $url = $request->input('url');

        return HtmlToPdfHelper::export($title, $url, $token);
    }
}