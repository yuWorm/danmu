<?php
namespace app\common\model;
use think\db;
use think\Exception;

//弹幕库model，返回数据中code=0为成功，1为异常
class Danmuku extends Base{


    //设置数据表名
    protected $name = "danmuku";

    // 定义时间戳字段名
    protected $createTime = '';
    protected $updateTime = '';

    // 自动完成
    protected $auto       = [];
    protected $insert     = [];
    protected $update     = [];

    public function getDanmuList($vid){
        $list = Db::name("Danmuku")->where("vid", $vid)->select();
        if(empty($list)){
            return [
                'code' => 1,
                'version' => 2,
                'msg'=>'暂时没有一条弹幕'
            ];
        }
        $result = [];
        foreach ($list as $k=>$v ){
            $result[] = [
                $v["time"],
                $v["type"],
                $v["color"],
                $v["author"],
                $v["text"]
            ];
        }

        return [
            'code' => 0,
            'version' => 2,
            'data' => $result,
        ];
    }

    public function findDanmu($key){
        $list = Db::name("Danmuku")->query("select * from __TABLE__ where vod_actor like '%$key%'");
        if(empty($list))
            return ["code"=>1, "msg"=>"无相关弹幕"];

        $result = [];
        foreach ($list as $k=>$v ){
            $result[] = [
                "vid"=>$v["vid"],
                "time"=>$v["time"],
                "type"=>$v["type"],
                "color"=>$v["color"],
                "author"=>$v["author"],
                "text"=>$v["text"],
                "create_time"=>$v["create_time"]
            ];
        }

        return [
            'code' => 0,
            'msg' => '查询弹幕成功',
            'danmuku' => $result,
        ];
    }

    public function addDanmu($data){
        $data["create_time"] = time();
        $res = $this->allowField(true)->insert($data);
        if(false === $res){
            return ['code'=>1,'msg'=>lang('save_err').'：'.$this->getError() ];
        }
        return ['code'=>0, 'msg'=>'添加弹幕成功'];
    }

    public function deleteOne($id){
        $res = $this->where('id', $id)->delete();
        if($res===false){
            return ['code'=>1,'msg'=>lang('del_err').'：'.$this->getError() ];
        }
        return ['code'=>0,'msg'=>"删除成功"];
    }

    public function deleteList($data){
        $db = $this->db(false);
        $db->startTrans();
        try {
            foreach ($data as $id) {
                $db->where('id', $id)->delete();
            }
            $db->commit();
        }catch (Exception $e){
            return ['code'=>1, 'msg'=>$e->getMessage()];
        }
        return ['code'=>0, 'msg'=>'删除成功'];
    }
}
