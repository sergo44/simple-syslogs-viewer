<?php

declare(strict_types=1);

namespace App\FrontController;

use App\ApplicationError;

/**
 * Диспетчер фронт контроллера
 *
 * @package La\Core
 */
class Dispatcher
{
    /**
     * Путь вызова (URI)
     * @var string
     */
    protected(set) string $path;
    /**
     * Контроллер, который был вызван
     * @var ControllerInterface|null
     */
    protected(set) ?ControllerInterface $controller_entity = null;

    /**
     * Конструктор класса
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = preg_replace("#/{2,}#", "/", $path);

        if (!strlen($this->path)) {
            $this->path = "/";
        }
    }

    /**
     * Пробуем маршрутизировать запрос через файлы
     * @throws ApplicationError
     */
    public function routeViaFiles(): bool
    {
        foreach (glob(APP_SRC_DIR . "/App/*/Routes.php") as $extended_routes) {
            // Сканируем директорию на файлы маршрутов и пробуем запустить маршрут
            $extended_routes_realpath = realpath($extended_routes);
            if ($extended_routes_realpath) {
                $ns_start = strpos($extended_routes_realpath, "/App/");
                // $ns_end = strpos($extended_routes_realpath, "Routes.php");
                $class_name = str_replace("/", "\\", substr($extended_routes_realpath, $ns_start, -4));
                $route = new $class_name ($this);
                if ($route instanceof RouterInterface) {
                    $this->controller_entity = $route->tryRoute();
                    if ($this->controller_entity) {
                        return true;
                    }
                } else {
                    throw new ApplicationError("Non RouteInterface object was returned when call controller");
                }
            }
        }

        return false;
    }

}