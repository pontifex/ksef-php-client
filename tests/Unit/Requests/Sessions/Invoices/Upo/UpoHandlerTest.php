<?php

declare(strict_types=1);

use function N1ebieski\KSEFClient\Tests\getClientStub;
use N1ebieski\KSEFClient\Requests\Sessions\Invoices\Upo\UpoRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Invoices\Upo\UpoRequestFixture;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Invoices\Upo\UpoResponseFixture;

/**
 * @return array<string, array{UpoRequestFixture, UpoResponseFixture}>
 */
dataset('validResponseProvider', function () {
    $requests = [
        new UpoRequestFixture(),
    ];

    $responses = [
        new UpoResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{UpoRequestFixture, UpoResponseFixture}> */
    return $combinations;
});

test('valid response', function (UpoRequestFixture $requestFixture, UpoResponseFixture $responseFixture) {
    $clientStub = getClientStub($responseFixture);

    $request = UpoRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->sessions()->invoices()->upo($requestFixture->data)->body();

    expect($response)->toBe($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function () {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture) {
        $requestFixture = new UpoRequestFixture();

        $clientStub = getClientStub($responseFixture);

        $clientStub->sessions()->invoices()->upo($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
