<?php

declare(strict_types=1);

namespace App\Api\LogFile;

use App\FrontController\ViewInterface;
use App\Views\JsonView;
use JsonSerializable;

class LogFileJsonView extends JsonView implements ViewInterface, JsonSerializable
{
    /**
     * Контент для отображения
     * @var string|null
     */
    public ?string $content = null;

    /**
     * @inheritDoc
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            "result" => $this->result,
            "payload" => array (
                "content" => $this->content
            )
        );
    }
}