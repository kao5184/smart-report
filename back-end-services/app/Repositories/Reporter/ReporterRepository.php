<?php
namespace App\Repositories\Reporter;

use App\Repositories\BaseRefreshRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ReporterRepository extends BaseRefreshRepository
{
    public function configSQL($key, $title, $description, $sql, $cache, $visible)
    {
        return $this->connect()->table('dynamic_reporter')->insertGetId([
            'config_key' => $this->formatKey($key),
            'title' => $title,
            'description' => $description,
            'config_sql' => $sql,
            'need_cache' => $cache,
            'visible' => $visible,
            'created_at' => $this->now(),
            'updated_at' => $this->now(),
            'deleted_at' => null
        ]);
    }

    public function deleteSQL($id)
    {
        return $this->connect()->table('dynamic_reporter')
            ->where('id', $id)
            ->delete();
    }

    public function updateSQL($id, $title, $description, $sql, $cache, $visible)
    {
        return $this->connect()->table('dynamic_reporter')
            ->where('id', $id)
            ->update([
                'config_sql' => $sql,
                'title' => $title,
                'description' => $description,
                'need_cache' => $cache,
                'visible' => $visible,
                'updated_at' => $this->now(),
                'deleted_at' => null
            ]);
    }

    public function configs($queries, $page, $limit)
    {
        $total = $this->connect()->table('dynamic_reporter')->count();
        $items = $this->connect()->table('dynamic_reporter')
            ->forPage($page, $limit)
            ->get()
            ->toArray();

        return $this->pager($total, $items);
    }

    public function config($id)
    {
        $config = $this->connect()->table('dynamic_reporter')
            ->where('id', $id)
            ->first();

        return $config;
    }

    public function mappings($dynamicReporterId)
    {
        return $this->connect()->table('dynamic_reporter_coord')
            ->where('dynamic_reporter_id', $dynamicReporterId)
            ->get()
            ->toArray();
    }

    public function configMapping($dynamicReporterId, $axis, $source, $alias, $min, $max, $group, $color, $graphics)
    {
        return $this->connect()->table('dynamic_reporter_coord')
            ->insertGetId([
                'dynamic_reporter_id' => $dynamicReporterId,
                'axis' => $axis,
                'source' => $source,
                'alias' => $alias,
                'max' => $max,
                'min' => $min,
                'color' => $color,
                'graphics' => $graphics,
                'source_group' => $group,
            ]);
    }

    public function deleteMapping($id)
    {
        return $this->connect()->table('dynamic_reporter_coord')
            ->where('id', $id)
            ->delete();
    }

    public function updateMapping($id, $axis, $source, $alias, $min, $max, $group, $color, $graphics)
    {
        return $this->connect()->table('dynamic_reporter_coord')
            ->where('id', $id)
            ->update([
                'axis' => $axis,
                'source' => $source,
                'alias' => $alias,
                'max' => $max,
                'min' => $min,
                'color' => $color,
                'graphics' => $graphics,
                'source_group' => $group,
            ]);
    }

    public function dataRender()
    {
        $configs = $this->connect()->table('dynamic_reporter')
            ->where('visible', 1)
            ->get()
            ->toArray();

        return $this->loadCachedData($configs);
    }


    public function singleRender($id)
    {
        $cover = $this->connect()->table('dynamic_reporter_cover')
            ->where('id', $id)
            ->first();
        $items = $this->connect()->table('dynamic_reporter_pages')
            ->select([
                'dynamic_reporter_pages.*'
            ])
            ->join('dynamic_reporter_cover_pages', 'dynamic_reporter_cover_pages.page_id', '=', 'dynamic_reporter_pages.id')
            ->where('dynamic_reporter_cover_pages.reporter_id', $id)
            ->get()
            ->toArray();

        array_unshift($items, $cover);

        $params = [];
        foreach ($items as $item) {
            foreach ($item as $value) {
                if (is_string($value)) {
                    preg_match_all('/{{.*?}}/', $value, $matches);
                    foreach ($matches as $match) {
                        foreach ($match as $param) {
                            $params [] = $param;
                        }
                    }
                }
            }
        }

        $params = array_unique($params);
        $params = $this->loadCachedSource($params);

        array_shift($items);
        array_set($cover, 'settings', @json_decode(array_get($cover, 'settings'), true));

        $items = array_map(function ($item) {
            return array_set($item, 'settings', @json_decode(array_get($item, 'settings'), true));
        }, $items);


        array_set($cover, 'pages', $items);

        $cover = $this->renderParameter($cover, $params);
        return $cover;
    }

    protected function renderParameter($data, $params)
    {
        foreach ($data as $col => $datum) {
            if (is_array($datum)) {
                $datum = $this->renderParameter($datum, $params);
            } elseif (is_string($datum) && preg_match('/\{\{.*?\}\}/', $datum)) {
                foreach (array_keys($params) as $key) {
                    if ($datum == sprintf('{{%s}}', $key)) {
                        $datum = array_get($params, $key);
                        break;
                    } elseif (preg_match(sprintf('/{{%s}}/', $key), $datum)) {
                        $value = is_array(array_get($params, $key)) ? implode(',', array_get($params, $key)) : array_get($params, $key, '');
                        if (empty($value)) {
                            $value = '';
                        }
                        $datum = preg_replace(sprintf('/\s{1}{{%s}}\s{0,1}/', $key), $value, $datum);
                    }
                }
            }

            array_set($data, "$col", $datum);
        }
        return $data;
    }

    protected function loadCachedSource($params)
    {
        $params = array_map(function ($param) {
            $param = preg_replace('/{/', '', $param);
            $param = preg_replace('/}/', '', $param);
            return $param;
        }, $params);

        $source = $this->connect()->table('dynamic_reporter_source')->whereIn('key', $params)->get()->toArray();

        $configExecResults = [];

        foreach ($source as $config) {
            switch (array_get($config, 'type')) {
                case 'text':
                    array_set($configExecResults, array_get($config, 'key'), array_get($config, 'value'));
                    break;
                case 'script':
                    $data = $this->loadSource($config);
                    $arr = [];
                    foreach ($data as $idx => $datum) {
                        array_set($arr, "$idx", array_values($datum));
                    }
                    $arr = array_map(function ($d) {
                        return is_array($d) ? implode(',', $d) : $d;
                    }, $arr);

                    array_set($configExecResults, array_get($config, 'key'), implode(',', $arr));
                    break;
                case 'chart':
                    $data = $this->loadSource($config);
                    $settings = @json_decode(array_get($config, 'settings'), true);
                    array_set($configExecResults, array_get($config, 'key'), ['data' => $data, 'title' => array_get($config, 'title'), 'settings' => $settings]);
                    break;
                default :
                    array_set($configExecResults, array_get($config, 'key'), array_get($config, 'value'));
                    break;
            }
        }

        return $configExecResults;
    }

    protected function loadSource($config)
    {
        $cachedKey = md5(array_get($config, 'value'));

        $data = null;

        if (array_get($config, 'cache') && ($cachedData = @unserialize(Cache::get($cachedKey)))) {
            return $cachedData;
        } else {
            try {
                $data = $this->connect()->select(array_get($config, 'value'));

                if (array_get($config, 'cache')) {
                    Cache::put($cachedKey, serialize($data), 12 * 60);
                    $this->connect()->table('dynamic_reporter_source')
                        ->where('id', array_get($config, 'id'))
                        ->update([
                            'cached_at' => $this->now()
                        ]);
                } else {
                    Cache::forget($cachedKey);
                    $this->connect()->table('dynamic_reporter_source')
                        ->where('id', array_get($config, 'id'))
                        ->update([
                            'cached_at' => null
                        ]);
                }
            } catch (\Exception $ex) {
            }
        }

        return $data;
    }

    protected function loadCachedData($configs)
    {
        $configExecResults = [];

        foreach ($configs as $config) {
            $cachedKey = md5(array_get($config, 'config_sql'));

            if (array_get($config, 'need_cache') && ($cachedData = @unserialize(Cache::get($cachedKey)))) {
                array_set($configExecResults, "$cachedKey.data", $cachedData);
            } else {
                try {
                    $data = $this->connect()->select(array_get($config, 'config_sql'));
                    array_set($configExecResults, "$cachedKey.data", $data);

                    if (array_get($config, 'need_cache')) {
                        Cache::put($cachedKey, serialize($data), 12 * 60);
                        $this->connect()->table('dynamic_reporter')
                            ->where('id', array_get($config, 'id'))
                            ->update([
                                'cached_at' => $this->now()
                            ]);
                    } else {
                        Cache::forget($cachedKey);
                        $this->connect()->table('dynamic_reporter')
                            ->where('id', array_get($config, 'id'))
                            ->update([
                                'cached_at' => null
                            ]);
                    }
                } catch (\Exception $ex) {
                    continue;
                }
            }

            $mappings = $this->mappings(array_get($config, 'id'));
            array_set($configExecResults, "$cachedKey.mappings", $mappings);

            array_set($configExecResults, "$cachedKey.config.id", array_get($config, 'id', 0));
            array_set($configExecResults, "$cachedKey.config.key", array_get($config, 'config_key', 0));
            array_set($configExecResults, "$cachedKey.config.title", array_get($config, 'title', 0));
            array_set($configExecResults, "$cachedKey.config.description", array_get($config, 'description', 0));
        }

        return array_values($configExecResults);
    }

    protected function formatKey($key)
    {
        return strtolower(str_replace('_', '-', implode('-', preg_split('/[A-Z]/', preg_replace('/[^A-Za-z0-9_-]/', '', $key)))));
    }

    public function all(
        $type, $page, $limit
    ) {
        $builder = $this->connect()->table('dynamic_reporter_cover');

        if (!empty($type)) {
            $builder->where('menu', $type);
        }

        $total = $builder->count();
        // dd($total);
        $items = $builder
            ->orderBy('id', 'desc')
            ->forPage($page, $limit)
            ->get()
            ->toArray();

            $items = array_map(function ($item) {
                return array_set($item, 'settings', @json_decode(array_get($item, 'settings'), true));
            },$items);
        return $this->pager($total, $items);
    }

    public function create($params)
    {
        $title = array_get($params, 'title');
        $subtitle = array_get($params, 'subtitle');
        $author = array_get($params, 'author');
        $createdAt = array_get($params, 'createdAt');
        $description = array_get($params, 'description');
        $background = array_get($params, 'background');
        $settings = array_get($params, 'settings');
        $menu = array_get($params, 'menu');

        return $this->connect()->table('dynamic_reporter_cover')
            ->insertGetId([
                'title' => $title,
                'subtitle' => $subtitle,
                'author' => $author,
                'created_at' => $createdAt,
                'description' => $description,
                'background' => $background,
                'settings' => @json_encode($settings),
                'menu' => $menu,
            ]);
    }

    public function update($id, $params)
    {
        return $this->connect()->transaction(function () use ($id, $params) {
            $title = array_get($params, 'title');
            $subtitle = array_get($params, 'subtitle');
            $author = array_get($params, 'author');
            $createdAt = array_get($params, 'createdAt');
            $description = array_get($params, 'description');
            $background = array_get($params, 'background');
            $settings = array_get($params, 'settings');
            $pages = array_get($params, 'pages');
            $menu = array_get($params, 'menu');


            $this->connect()->table('dynamic_reporter_cover')
                ->where('id', $id)
                ->update([
                    'title' => $title,
                    'subtitle' => $subtitle,
                    'author' => $author,
                    'created_at' => $createdAt,
                    'description' => $description,
                    'background' => $background,
                    'settings' => @json_encode($settings),
                    'menu' => $menu,
                ]);

            if (!is_null($pages)) {
                $this->connect()->table('dynamic_reporter_cover_pages')->where('reporter_id', $id)->delete();
                foreach ($pages as $page) {
                    $this->connect()->table('dynamic_reporter_cover_pages')
                        ->insert([
                            'page_id' => array_get($page, 'id'),
                            'reporter_id' => $id,
                            'sort' => array_get($page, 'sort'),
                        ]);
                }
            }

            return true;
        });
    }

    public function delete(
        $id
    ) {
        return $this->connect()->table('dynamic_reporter_cover')
            ->where('id', $id)
            ->delete();
    }

    public function allPage(
        $reporterId
    ) {
        $reporter = $this->connect()->table('dynamic_reporter_cover')
            ->where('id', $reporterId)
            ->first();

        array_set($reporter, 'settings', @json_decode(array_get($reporter, 'settings'), true));

        $items = $this->connect()->table('dynamic_reporter_pages')
            ->select([
                'dynamic_reporter_pages.*'
            ])
            ->join('dynamic_reporter_cover_pages', 'dynamic_reporter_cover_pages.page_id', '=', 'dynamic_reporter_pages.id')
            ->where('dynamic_reporter_cover_pages.reporter_id', $reporterId)
            ->get()
            ->map(function ($item) {
                return array_set($item, 'settings', @json_decode(array_get($item, 'settings'), true));
            })
            ->toArray();
        array_set($reporter, 'pages', $items);

        return $reporter;
    }

    public function queryPage(
        $query, $type, $page, $limit
    ) {
        $builder = $this->connect()->table('dynamic_reporter_pages');
        $total = $this->connect()->table('dynamic_reporter_pages')->count();

        if (!empty($query)) {
            $builder->where('dynamic_reporter_pages.title', 'like', "%$query%");
        }
        if (!empty($type)) {
            $builder->where('dynamic_reporter_pages.type', $type);
        }
        $items = $builder->select([
            'dynamic_reporter_pages.*'
        ])
            ->orderBy('id', 'desc')
            ->forPage($page, $limit)
            ->get()
            ->map(function ($item) {
                return array_set($item, 'settings', @json_decode(array_get($item, 'settings'), true));
            })
            ->toArray();

        return $this->pager($total, $items);
    }

    public function createPage(
        $params
    ) {
        $settings = array_get($params, 'settings');
        $title = array_get($params, 'title');
        $description = array_get($params, 'description');

        return $this->connect()->table('dynamic_reporter_pages')
            ->insertGetId([
                'settings' => @json_encode($settings),
                'title' => $title,
                'description' => $description
            ]);
    }

    public function updatePage(
        $id, $params
    ) {
        $settings = array_get($params, 'settings');
        $title = array_get($params, 'title');
        $description = array_get($params, 'description');

        return $this->connect()->table('dynamic_reporter_pages')
            ->where('id', $id)
            ->update([
                'settings' => @json_encode($settings),
                'title' => $title,
                'description' => $description
            ]);
    }

    public function deletePage(
        $id
    ) {
        return $this->connect()->table('dynamic_reporter_pages')
            ->where('id', $id)
            ->delete();
    }

    public function allParameter(
        $query, $page, $limit
    ) {
        $total = $this->connect()->table('dynamic_reporter_source')->count();
        $builder = $this->connect()->table('dynamic_reporter_source');

        if (!empty($query)) {
            $builder->where('key', 'like', "%$query%");
        }

        $items = $builder
            ->orderBy('id', 'desc')
            ->forPage($page, $limit)
            ->get()
            ->map(function ($item) {
                return array_set($item, 'settings', @json_decode(array_get($item, 'settings'), true));
            })
            ->toArray();
        return $this->pager($total, $items);
    }

    public function parameters()
    {
        return $this->connect()->table('dynamic_reporter_source')
            ->select(['key'])
            ->whereIn('type', ['script', 'text'])
            ->get()
            ->toArray();
    }

    public function createParameter(
        $params
    ) {
        $type = array_get($params, 'type');
        $key = array_get($params, 'key');
        $title = array_get($params, 'title');
        $value = array_get($params, 'value');
        $cache = array_get($params, 'cache');
        $settings = array_get($params, 'settings');

        return $this->connect()->table('dynamic_reporter_source')
            ->insertGetId([
                'type' => $type,
                'key' => $key,
                'title' => $title,
                'value' => $value,
                'cache' => $cache,
                'settings' => @json_encode($settings)
            ]);
    }

    public function updateParameter(
        $id, $params
    ) {
        $type = array_get($params, 'type');
        $key = array_get($params, 'key');
        $title = array_get($params, 'title');
        $value = array_get($params, 'value');
        $cache = array_get($params, 'cache');
        $settings = array_get($params, 'settings');

        return $this->connect()->table('dynamic_reporter_source')
            ->where('id', $id)
            ->update([
                'type' => $type,
                'key' => $key,
                'title' => $title,
                'value' => $value,
                'cache' => $cache,
                'settings' => @json_encode($settings)
            ]);
    }

    public function deleteParameter(
        $id
    ) {
        return $this->connect()->table('dynamic_reporter_source')
            ->where('id', $id)
            ->delete();
    }
}
