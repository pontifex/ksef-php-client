<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Requests\Invoices\Exports\Init\InitRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Invoices\Exports\Init\InitRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Invoices\Exports\Init\InitResponseFixture;

use function N1ebieski\KSEFClient\Tests\getClientStub;

/**
 * @return array<string, array{InitRequestFixture, InitResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new InitRequestFixture(),
    ];

    $responses = [
        new InitResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{InitRequestFixture, InitResponseFixture}> */
    return $combinations;
});

test('valid response', function (InitRequestFixture $requestFixture, InitResponseFixture $responseFixture): void {
    $clientStub = getClientStub($responseFixture);

    $request = InitRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->invoices()->exports()->init($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        $requestFixture = new InitRequestFixture();

        $clientStub = getClientStub($responseFixture);

        $clientStub->invoices()->exports()->init($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
