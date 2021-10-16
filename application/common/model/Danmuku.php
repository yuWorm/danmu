<?php

namespace app\common\model;

use think\db;
use think\Exception;
use think\Paginator;

//弹幕库model，返回数据中code=0为成功，1为异常
class Danmuku extends Base {


    //设置数据表名
    protected $name = "danmuku";

    // 定义时间戳字段名
    protected $createTime = '';
    protected $updateTime = '';

    // 自动完成
    protected $auto = [];
    protected $insert = [];
    protected $update = [];

    public function getDanmuList($vid, $page = "",$all=true,$li="") {
        if ($all) {
            $list = Db::name("Danmuku")->where("vid", $vid)->select();
        } else {
            $p = empty($page) ? 1 : $page;
            $limit = empty($li) ? 10 : $li;
            $start = ($p-1)*$limit;
            $end = $p*$limit;
            $list = Db::name('Danmuku')->limit("$start,$end")->select();
        }
        if (empty($list)) {
            return [
                'code' => 1,
                'version' => 2,
                'msg' => '暂时没有一条弹幕'
            ];
        }
        $result = [];
        foreach ($list as $k => $v) {
            $result[] = [
                $v["time"],
                $v["type"],
                $v["color"],
                $v["author"],
                $v["text"]
            ];
        }

        if($all){
            return [
                'code' => 0,
                'version' => 2,
                'data' => $result,
            ];
        }

        return [
            'code'=>0,
            'count'=>$this->count(),
            'data'=>$list,
        ];
    }

    public function findDanmu($key, $page, $li) {
        $p = empty($page) ? 1 : $page;
        $limit = empty($li) ? 10 : $li;
        $start = ($p-1)*$limit;
        $end = $p*$limit;
        $table = config('database.prefix').'danmuku';
        $count = Db::name("Danmuku")->query("select count(*) from $table where text like '%$key%';");
        $list = Db::name("Danmuku")->query("select * from $table where text like '%$key%' limit $start, $end;");
        if (empty($list))
            return ["code" => 1, "msg" => "无相关弹幕"];

        return [
            'code' => 0,
            'count'=>$count,
            'msg' => '查询弹幕成功',
            'data' => $list,
        ];
    }

    public function addDanmu($data) {
        $data["create_time"] = time();
        $res = $this->allowField(true)->insert($data);
        if (false === $res) {
            return ['code' => 1, 'msg' => lang('save_err') . '：' . $this->getError()];
        }
        return ['code' => 0, 'msg' => '添加弹幕成功'];
    }

    public function deleteOne($id) {
        $res = $this->where('id', $id)->delete();
        if ($res === false) {
            return ['code' => 1, 'msg' => lang('del_err') . '：' . $this->getError()];
        }
        return ['code' => 0, 'msg' => "删除成功"];
    }

    public function deleteList($data) {
        $db = $this->db(false);
        $db->startTrans();
        try {
            foreach ($data as $id) {
                $db->where('id', $id)->delete();
            }
            $db->commit();
        } catch (Exception $e) {
            return ['code' => 1, 'msg' => $e->getMessage()];
        }
        return ['code' => 0, 'msg' => '删除成功'];
    }

    public function getCount(){
        return $this->count();
    }
}
