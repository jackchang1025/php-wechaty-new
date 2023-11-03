<?php

namespace PhpWechat;

class PuppetOptions
{

    public function __construct(protected string $token,protected string $endPoint = '')
    {
    }

}