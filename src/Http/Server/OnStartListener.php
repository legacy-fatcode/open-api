<?php declare(strict_types=1);

namespace FatCode\OpenApi\Http\Server;

interface OnStartListener extends Listener
{
    public function onStart() : void;
}
