<?php

declare(strict_types=1);

namespace App\FrontController;

use App\Result;

/**
 * Интерфейс вида
 *
 * @package La\Core
 */
interface ViewInterface
{
    public Result $result {
        get;
    }

    /**
     * Render вида (fetch)
     * @return string
     */
    public function render(): string;
}