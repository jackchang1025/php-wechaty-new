<?php

namespace PhpWechat\Grpc;

use Grpc\ChannelCredentials;
use IO\Github\Wechaty\PuppetService\Auth\WechatyCA;
use IO\Github\Wechaty\Util\Console;
use Wechaty\Puppet\EventRequest;
use Wechaty\Puppet\StartRequest;
use Wechaty\PuppetClient;

class Grpc
{

    protected PuppetClient $grpcClient;

    public function __construct(protected string $hostname, protected string $token_type,protected string $access_token,protected bool $tls_insecure_client = false)
    {
        $this->grpcClient = $this->createChannelClient();
    }

    public function updateMetadata(): \Closure
    {
        return function ($metadata, $authUri = null, $client = null) {
            $metadataCopy = $metadata;
            $metadataCopy["authorization"] =
                [sprintf('%s %s',
                    $this->token_type,
                    $this->access_token)];

            return $metadataCopy;
        };
    }

    protected function createChannelCredentialsTTl(): PuppetClient
    {
        return new PuppetClient($this->hostname, [
            'credentials' => ChannelCredentials::createSsl(WechatyCA::TLS_CA_CERT),
            'update_metadata' => $this->updateMetadata(),
            'grpc.ssl_target_name_override' => WechatyCA::TLS_INSECURE_SERVER_CERT_COMMON_NAME,
            'grpc.default_authority' => $this->access_token,
        ]);
    }

    protected function createChannelCredentials(): PuppetClient
    {
        return new PuppetClient($this->hostname, [
            'credentials' => ChannelCredentials::createSsl(WechatyCA::TLS_CA_CERT),
            'update_metadata' => $this->updateMetadata(),
        ]);
    }

    protected function createChannelClient(): PuppetClient
    {
        return $this->tls_insecure_client ? $this->createChannelCredentialsTTl() : $this->createChannelCredentials();
    }

    public function start(StartRequest $request): \Grpc\UnaryCall
    {
        return $this->grpcClient->start($request);
    }

    public function startGrpcStream(EventRequest $eventRequest) {

        $call = $this->grpcClient->Event($eventRequest);
        $ret = $call->responses();//Generator Object
        while($ret->valid()) {
            // Console::logStr($ret->key() . " ");//0 1 2
            $response = $ret->current();
            $this->_onGrpcStreamEvent($response);
            $ret->next();
        }
    }
}