<?php

declare(strict_types=1);

namespace App\Api\Users;

use App\FrontController\ViewInterface;
use App\Views\JsonView;

class AuthJsonView extends JsonView implements ViewInterface, \JsonSerializable
{
    public bool $enabled = false;

    public function jsonSerialize(): array
    {
        return array(
            "result" => $this->result,
            "payload" => array(
                "enabled" => $this->enabled,
                "authorized" => ($_SESSION['authorized'] ?? false) === true
            )
        );
    }

}