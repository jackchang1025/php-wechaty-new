<?php

namespace PhpWechat\Puppet;

use PhpWechat\PuppetOptions;

class PuppetService implements InterfacePuppet
{

    public function __construct(protected PuppetOptions $puppetOptions)
    {
    }
}