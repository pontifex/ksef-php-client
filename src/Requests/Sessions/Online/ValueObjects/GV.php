<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Online\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum GV: string implements EnumInterface
{
    case Yes = '1';

    case No = '2';
}
