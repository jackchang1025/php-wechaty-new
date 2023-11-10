<?php

namespace PhpWechat\Grpc;

use Wechaty\Puppet\EventRequest;
use Wechaty\Puppet\StartRequest;

interface InterfaceGrpcService
{
    public function start(StartRequest $request);

    public function event(EventRequest $request);
}