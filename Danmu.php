<?php
namespace addons\danmu;	// 注意命名空间规范
use think\Addons;
use think\Db;
/**
 * 插件测试
 * @author byron sampson
 */
class Danmu extends Addons	// 需继承thinkaddonsAddons类
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {

        return false;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    public function enable(){
        $pre = config('database.prefix');
        $tableName=$pre.'danmuku';
        $isTable=db()->query("SHOW TABLES LIKE '$tableName';");
        if(!$isTable){
            $sql = "CREATE TABLE `".config('database.database')."`.`".$pre."danmuku` ( `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '弹幕id' , `vid` TEXT NOT NULL COMMENT '视频id(根据视频名称和集数来)' , `uid` INT(11) NOT NULL COMMENT '发布的用户id' , `author` TEXT NOT NULL COMMENT '作者id' , `text` TEXT NOT NULL COMMENT '弹幕内容' , `color` TEXT NOT NULL COMMENT '弹幕颜色' , `type` INT(1) NOT NULL COMMENT '弹幕类型(0:滚动,1:顶部,2:底部)' , `time` TEXT NOT NULL COMMENT '弹幕播出时间' , `ip` TEXT NOT NULL COMMENT '弹幕发送者ip' , `create_time` TEXT NOT NULL COMMENT '创建时间' , PRIMARY KEY (`id`)) ENGINE = MyISAM COMMENT = '弹幕存储表';";
//            die($sql);
            Db::execute($sql);
        }

    }


}
