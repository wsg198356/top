<?php
use think\Db;
//将字符解析成数组
function parseParams($str)
{
    $arrParams = [];
    parse_str(html_entity_decode(urldecode($str)), $arrParams);
    return $arrParams;
}
//子孙数，菜单整理
function subTree($param, $pid = 0)
{
    static $res = [];
    foreach ($param as $key => $vo) {
        if ($pid == $vo['pid']) {
            $res[] = $vo;
            subTree($param, $vo['pid']);
        }
    }
    return $res;
}
/**
 * 记录日志
 */
function writelog($uid, $username, $description, $status)
{
    $data['admin_id'] = $uid;
    $data['admin_name'] = $username;
    $data['description'] = $description;
    $data['status'] = $status;
    $data['ip'] = request()->ip();
    $data['add_time'] = time();
    $log = Db::name('Log')->insert($data);
}
/**
 * 整理菜单树方法
 */
function prepareMenu($param)
{
    $parent = [];
    $child = [];
    foreach ($param as $key => $vo) {
        if ($vo['pid'] == 0) {
            $vo['href'] = '';
            $parent[] = $vo;
        } else {
            $vo['href'] = url($vo['name']);
            $child[] = $vo;
        }
    }
    foreach ($parent as $key => $vo) {
        foreach ($child as $k => $v) {
            if ($v['pid'] == $vo['id']) {
                $parent[$key]['child'][] = $v;
            }
        }
    }
    unset($child);
    return $parent;
}
/**
 * 格式化字节大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }
    return $size . $delimiter . $units[$i];
}