<?php

return array (
  0 => 
  array (
    'name' => 'login_look',
    'title' => '石否启用登陆才能获取弹幕',
    'type' => 'radio',
    'content' => 
    array (
      'on' => '启用',
      'off' => '关闭',
    ),
    'value' => 'on',
    'rule' => 'required',
    'msg' => '',
    'tip' => '启用后只有登陆才能看到弹幕',
    'ok' => '',
    'extend' => '',
  ),
  1 => 
  array (
    'name' => 'pb_key',
    'title' => '屏蔽词',
    'type' => 'text',
    'content' => 
    array (
    ),
    'value' => 'sb;我操尼玛;你妈逼',
    'rule' => 'required',
    'msg' => '',
    'tip' => '弹幕屏蔽词,用英文;隔开',
    'ok' => '',
    'extend' => 'style="height: 150px;"',
  ),
  2 => 
  array (
    'name' => 'login_send',
    'title' => '石否启用登陆才能发送弹幕',
    'type' => 'radio',
    'content' => 
    array (
      'on' => '启用',
      'off' => '关闭',
    ),
    'value' => 'on',
    'rule' => 'required',
    'msg' => '',
    'tip' => '启用后只有登陆才能发送弹幕',
    'ok' => '',
    'extend' => '',
  ),
);
