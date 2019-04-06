<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http\Server;

interface OnStopListener extends Listener
{
    public function onStop() : void;
}
