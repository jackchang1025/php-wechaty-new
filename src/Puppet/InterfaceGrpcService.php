<?php

namespace PhpWechat\Puppet;

use Grpc\ChannelCredentials;

interface InterfaceGrpcService
{
    public function start();

    public function createChannelCredentials();
}