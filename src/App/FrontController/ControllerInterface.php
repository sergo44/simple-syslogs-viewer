<?php

declare(strict_types=1);

namespace App\FrontController;

/**
 * Интерфейс описывает доступ к контроллерам
 *
 * @package La\Core
 */
interface ControllerInterface
{
    /**
     * Данные из запроса
     * @var RequestDataInterface
     */
    public RequestDataInterface $request {
        get;
    }
    /**
     * Возвращает представление контроллера
     * @var ViewInterface
     */
    public ViewInterface $view {
        get;
    }

    /**
     * Возвращает макет контроллера
     * @var LayoutInterface
     */
    public LayoutInterface $layout {
        get;
    }

}