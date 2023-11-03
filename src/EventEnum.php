<?php

namespace PhpWechat;

enum  EventEnum
{
    //Scan
    const SCAN ='scan';

    //登录事件
    const LOGIN = 'login';

    //退出事件
    const LOGOUT = 'logout';

    //消息事件
    const MESSAGE ='message';


}