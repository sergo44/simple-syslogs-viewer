<?php

declare(strict_types=1);

namespace App\Views;

use App\Result;

abstract class JsonView
{
    /**
     * Результат выполнения запроса
     * @var Result
     */
    public Result $result;

    /**
     * Конструктор класса
     */
    public function __construct()
    {
        $this->result = new Result();
    }

    /**
     * Render вида
     * @return string
     */
    public function render(): string
    {
        return json_encode($this, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}