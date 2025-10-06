<?php

declare(strict_types=1);

use function N1ebieski\KSEFClient\Tests\getClientStub;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Auth\Token\Refresh\RefreshResponseFixture;
use N1ebieski\KSEFClient\ValueObjects\AccessToken;

use N1ebieski\KSEFClient\ValueObjects\RefreshToken;

/**
 * @return array<int, array<int, string>>
 */
dataset('resourceProvider', function () {
    return [
        ['sessions'],
        ['invoices'],
        ['certificates'],
        ['tokens']
    ];
});

test('auto access token refresh', function (string $resource) {
    $responseFixture = (new RefreshResponseFixture())->withValidUntil(new DateTimeImmutable('+15 minutes'));

    $accessToken = new AccessToken('access-token', new DateTimeImmutable('-15 minutes'));
    $refreshToken = new RefreshToken('refresh-token', new DateTimeImmutable('+7 days'));

    $clientStub = getClientStub($responseFixture)
        ->withAccessToken($accessToken)
        ->withRefreshToken($refreshToken);

    $clientStub->{$resource}();

    $newAccessToken = $clientStub->getAccessToken();

    expect($newAccessToken->isEquals($accessToken))->toBeFalse();

    expect($newAccessToken->isEquals($responseFixture->getAccessToken()))->toBeTrue();
})->with('resourceProvider');

test('throw exception if access token is expired', function (string $resource) {
    $accessToken = new AccessToken('access-token', new DateTimeImmutable('-15 minutes'));

    $clientStub = getClientStub(new RefreshResponseFixture())
        ->withAccessToken($accessToken);

    $clientStub->{$resource}();
})->with('resourceProvider')->throws(RuntimeException::class, 'Access token and refresh token are expired.');
