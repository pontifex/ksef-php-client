<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Invoices;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Invoices\InvoicesResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Invoices\Download\DownloadHandler;
use N1ebieski\KSEFClient\Requests\Invoices\Download\DownloadRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Psr\Log\LoggerInterface;

final class InvoicesResource extends AbstractResource implements InvoicesResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ?LoggerInterface $logger = null
    ) {
    }

    public function download(DownloadRequest | array $request): ResponseInterface
    {
        if ($request instanceof DownloadRequest === false) {
            $request = DownloadRequest::from($request);
        }

        return new DownloadHandler($this->client)->handle($request);
    }
}
