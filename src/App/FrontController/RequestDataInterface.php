<?php

declare(strict_types=1);

namespace App\FrontController;

interface RequestDataInterface
{
    /**
     * Возвращает входные данные запроса
     * @return array
     */
    public function getData(): array;

    /**
     * Возвращает входные данные запроса по ключу
     * @param string $key
     * @param int $max_length
     * @return string|null
     */
    public function getString(string $key, int $max_length): ?string;
}