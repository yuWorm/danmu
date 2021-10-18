<?php

namespace app\index\controller;

class Danmu extends Base {
    public $danmu_config;

    public function __construct() {
        parent::__construct();
        $this->danmu_config = include_once(ROOT_PATH . 'addons/danmu/config.php');
    }

    public function v3() {
        $id = $this->request->get("id");
        if (!empty($id)) {
            if ($this->danmu_config[0]['value'] == "on") {
                if (!$this->isLogin()) {
                    return json_encode(['code' => 1, 'msg' => '登陆才能获取弹幕哦！']);
                }
            }
            return json_encode(model('Danmuku')->getDanmuList($id));
        } else {
            if($this->danmu_config[2]['value'] == 'on')
                if (!$this->isLogin()) return json_encode(['code' => 1, 'msg' => '登陆才能发送弹幕']);
            $lock = cookie('send_danmu_loc');
//            if (!empty($lock)) return json_encode(['code'=>1, 'msg'=>'您发弹幕的速度太快了呢']);
            return json_encode($this->store());
        }
    }


    public function player() {
        $url = $this->request->get('url');
        $this->assign("video_url", $url);
        return $this->fetch(ROOT_PATH.'static/addons/danmu/player.html',[],['__JS__'=>'/static/addons/danmu/js/']);
    }

    public function store() {
//        获取屏蔽词
        $pb_key = explode(';',$this->danmu_config[1]['value']);

        $data = [
            'vid' => $this->request->post('id'),
            'color' => $this->request->post('color'),
            'time' => $this->request->post('time'),
            'text' => $this->request->post('text'),
            'author' => cookie('user_name'),
            'uid' => cookie('user_id'),
            'type' => $this->request->post('type'),
            'ip' => $this->request->ip()
        ];
        if (empty($data['vid'])) return ['code'=>1, 'msg'=>'视频id为空'];

        if (empty($data['text'])) return ['code'=>1, 'msg'=>'弹幕内容id为空'];

        foreach ($pb_key as $v){
            if (strpos($data['text'], $v) !== false) {
                return ['code'=>1, 'msg'=>'您的弹幕含有违禁内容！', 'pre_key'=>$v];
            }
        }

        $res = model('Danmuku')->addDanmu($data);
        if ($res['code'] != 0) return ['code'=>1, 'msg'=>'添加弹幕失败'];
        cookie('send_danmu_loc', 'on', $this->danmu_config[2]['value']);
        return ['code' => 0, 'data' => $data];
    }

    public function isLogin() {
        $res = model('User')->checkLogin();
        if ($res['code'] > 1) {
            model('User')->logout();
            return false;
        }
        return true;
    }

}