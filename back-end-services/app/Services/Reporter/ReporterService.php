<?php
namespace App\Services\Reporter;

use App\Repositories\Reporter\ReporterRepository;

class ReporterService
{

    protected $reporterRepository;

    public function __construct(ReporterRepository $reporterRepository)
    {
        $this->reporterRepository = $reporterRepository;
    }

    public function configSQL($key, $title, $description, $sql, $cache, $visible)
    {
        return $this->reporterRepository->configSQL($key, $title, $description, $sql, $cache, $visible);
    }

    public function configs($queries, $page, $limit)
    {
        return $this->reporterRepository->configs($queries, $page, $limit);
    }

    public function config($id)
    {
        $config = $this->reporterRepository->config($id);
        $mappings = $this->reporterRepository->mappings($id);
        return array_set($config, 'mappings', $mappings);
    }

    public function deleteSQL($id)
    {
        return $this->reporterRepository->deleteSQL($id);
    }

    public function updateSQL($id, $title, $description, $sql, $cache, $visible)
    {
        return $this->reporterRepository->updateSQL($id, $title, $description, $sql, $cache, $visible);
    }

    public function configMapping($id, $axis, $source, $alias, $min, $max, $group, $color, $graphics)
    {
        return $this->reporterRepository->configMapping($id, $axis, $source, $alias, $min, $max, $group, $color, $graphics);
    }

    public function deleteMapping($id)
    {
        return $this->reporterRepository->deleteMapping($id);
    }

    public function updateMapping($id, $axis, $source, $alias, $min, $max, $group, $color, $graphics)
    {
        return $this->reporterRepository->updateMapping($id, $axis, $source, $alias, $min, $max, $group, $color, $graphics);
    }

    public function dataRender()
    {
        return $this->reporterRepository->dataRender();
    }

    public function singleRender($id)
    {
        return $this->reporterRepository->singleRender($id);
    }

    public function all($type, $page, $limit)
    {
        return $this->reporterRepository->all($type, $page, $limit);
    }

    public function create($params)
    {
        return $this->reporterRepository->create($params);
    }

    public function update($id, $params)
    {
        return $this->reporterRepository->update($id, $params);
    }

    public function delete($id)
    {
        return $this->reporterRepository->delete($id);
    }

    public function allPage($reporterId)
    {
        return $this->reporterRepository->allPage($reporterId);
    }

    public function queryPage($query, $type, $page, $limit)
    {
        return $this->reporterRepository->queryPage($query, $type, $page, $limit);
    }

    public function createPage($params)
    {
        return $this->reporterRepository->createPage($params);
    }

    public function updatePage($id, $params)
    {
        return $this->reporterRepository->updatePage($id, $params);
    }

    public function deletePage($id)
    {
        return $this->reporterRepository->deletePage($id);
    }

    public function allParameter($query, $page, $limit)
    {
        return $this->reporterRepository->allParameter($query, $page, $limit);
    }

    public function parameters()
    {
        return $this->reporterRepository->parameters();
    }

    public function createParameter($params)
    {
        return $this->reporterRepository->createParameter($params);
    }

    public function updateParameter($id, $params)
    {
        return $this->reporterRepository->updateParameter($id, $params);
    }

    public function deleteParameter($id)
    {
        return $this->reporterRepository->deleteParameter($id);
    }
}