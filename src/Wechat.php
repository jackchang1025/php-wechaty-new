<?php

namespace PhpWechat;

use PhpWechat\Puppet\InterfacePuppet;

class Wechat
{

    public function __construct(protected InterfacePuppet $puppetService)
    {
    }


}