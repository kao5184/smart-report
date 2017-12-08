<?php

use Carbon\Carbon;

const DB_DATE_TIME_FORMAT = 'Y-m-d H:i:s';

/**
 * @param string|Carbon $source
 *
 * @return string
 */
function output_date_time($source)
{
    if ($source instanceof Carbon) {
        return $source->toIso8601String();
    }

    return Carbon::createFromTimestamp(strtotime($source))->toIso8601String();
}

/**
 * @param string|Carbon $source
 *
 * @return string
 */
function input_date_time($source)
{
    if ($source instanceof Carbon) {
        return $source->format(DB_DATE_TIME_FORMAT);
    }

    return Carbon::createFromFormat(DateTime::ISO8601, $source)->format(DB_DATE_TIME_FORMAT);
}

/**
 * @param string|int|null $time
 *
 * @return Carbon
 */
function carbon($time = null)
{
    if (null === $time) {
        return Carbon::now();
    }

    if (is_int($time)) {
        return Carbon::createFromTimestampUTC($time);
    }

    return Carbon::createFromTimestampUTC(strtotime($time));
}

function db_json_encode($data)
{
    return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
}

/**
 * 将数组的key 转为 snake_case.
 *
 * @param array $data
 *
 * @return array
 */
function snake_case_keys(array $data)
{
    $arr = [];
    foreach ($data as $key => $value) {
        $arr[snake_case($key)] = $value;
    }

    return $arr;
}

/**
 * 获取分页参数.
 *
 * @param \Illuminate\Http\Request $request
 *
 * @return array
 */
function parse_pagination_param(\Illuminate\Http\Request $request)
{
    return [
        'offset' => $request->input('pagination.offset', 0),
        'limit' => $request->input('pagination.limit', 20),
    ];
}

/**
 * 用数组中的某个值作为key.
 *
 * @method array_with_key
 *
 * @param array $arr
 * @param string $key
 * @param array $attr
 *
 * @return array
 */
function array_with_key(array $arr, $key, array $attr = null)
{
    $newArr = [];
    foreach ($arr as $value) {
        if (null !== $value) {
            if (null !== $attr) {
                $newArr[$value[$key]] = array_only($value, $attr);
            } else {
                $newArr[$value[$key]] = $value;
            }
        }
    }

    return $newArr;
}

/**
 * 返回数组中指定key的和.
 *
 * @param array $arr
 * @param null $column
 *
 * @return float|int
 */
function array_sum_column(array $arr, $column = null)
{
    if (null === $column) {
        return array_sum($arr);
    }

    return array_sum(array_pluck($arr, $column));
}

/**
 * 返回用户姓名
 * 优先：判断并拼接 familyName 和 givenName， 如果有则返回
 * 其次：直接返回 name
 *
 * @param array|null $user
 * @param bool $reversed
 * @param string $glue
 *
 * @return null|string
 */
function concat_user_name(array $user = null, $reversed = false, $glue = '')
{
    if (null === $user) {
        return null;
    }

    $family_name = array_get($user, 'familyName', '');
    $given_name = array_get($user, 'givenName', '');
    if ($family_name === '' && $given_name === '') {
        return array_get($user, 'name');
    }

    return $reversed ? $given_name . $glue . $family_name : $family_name . $glue . $given_name;
}

/**
 * 一维数组过滤+去重
 *
 * @param array|null $arr
 * @param bool $keep_index
 *
 * @return array
 */
function array_clean(array $arr = null, $keep_index = false)
{
    if (null === $arr) {
        return [];
    }
    if ($keep_index) {
        return array_unique(array_filter($arr));
    }
    return array_values(array_unique(array_filter($arr)));
}

function to_prc_datetime($source)
{
    return Carbon::createFromTimestamp(strtotime($source))
        ->setTimezone('PRC')
        ->format('Y-m-d H:i:s');
}

function query_search($str, $field, $whereString)
{
    if ($field) {
        if ($whereString) {
            $whereString .= ' AND ';
        }
        $whereString .= " $str = $field ";
        return $whereString;
    }
}



