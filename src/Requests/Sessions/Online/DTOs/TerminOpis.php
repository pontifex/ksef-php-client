<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Online\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Sessions\Online\ValueObjects\Ilosc;
use N1ebieski\KSEFClient\Requests\Sessions\Online\ValueObjects\Jednostka;
use N1ebieski\KSEFClient\Requests\Sessions\Online\ValueObjects\ZdarzeniePoczatkowe;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class TerminOpis extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public Ilosc $ilosc,
        public Jednostka $jednostka,
        public ZdarzeniePoczatkowe $zdarzeniePoczatkowe
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $terminOpis = $dom->createElement('TerminOpis');
        $dom->appendChild($terminOpis);

        $ilosc = $dom->createElement('Ilosc');
        $ilosc->appendChild($dom->createTextNode((string) $this->ilosc));

        $terminOpis->appendChild($ilosc);

        $jednostka = $dom->createElement('Jednostka');
        $jednostka->appendChild($dom->createTextNode((string) $this->jednostka));

        $terminOpis->appendChild($jednostka);

        $zdarzeniePoczatkowe = $dom->createElement('ZdarzeniePoczatkowe');
        $zdarzeniePoczatkowe->appendChild($dom->createTextNode((string) $this->zdarzeniePoczatkowe));

        $terminOpis->appendChild($zdarzeniePoczatkowe);

        return $dom;
    }
}
