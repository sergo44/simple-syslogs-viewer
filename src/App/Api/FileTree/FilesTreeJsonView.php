<?php

declare(strict_types=1);

namespace App\Api\FileTree;

use App\FrontController\ViewInterface;
use App\Views\JsonView;
use JsonSerializable;

class FilesTreeJsonView extends JsonView implements ViewInterface, JsonSerializable
{
    /**
     * Дерево для отображения
     * @var array
     */
    public array $tree = array();

    /**
     * @inheritDoc
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            "result" => $this->result,
            "payload" => array (
                "tree" => $this->tree
            )
        );
    }
}