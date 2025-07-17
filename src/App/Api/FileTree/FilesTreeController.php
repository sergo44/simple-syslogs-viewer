<?php

declare(strict_types=1);

namespace App\Api\FileTree;

use App\FrontController\BaseController;
use App\FrontController\ControllerInterface;
use App\FrontController\LayoutInterface;
use App\FrontController\RequestDataInterface;

class FilesTreeController extends BaseController implements ControllerInterface
{
    /**
     * Конструктор класса
     * @param RequestDataInterface $request
     * @param FilesTreeJsonView $view
     * @param LayoutInterface $layout
     */
    public function __construct(RequestDataInterface $request, FilesTreeJsonView $view, LayoutInterface $layout)
    {
        $this->request = $request;
        $this->view = $view;
        $this->layout = $layout;
    }

    /**
     * Возвращает дерево для отображения
     * @return $this
     */
    public function getTree(): self
    {
        $this->view
            ->tree = $this->scanDir("/target");
        return $this;
    }

    /**
     * Директория для отображения
     * @param string $path
     * @param string|null $base
     * @return array
     */
    protected function scanDir(string $path): array
    {
        $result = [];
        $items = scandir($path);

        foreach ($items as $item) {

            if ($item === '.' || $item === '..') continue;

            $full_path = $path . DIRECTORY_SEPARATOR . $item;

            if (is_dir($full_path)) {
                $result[] = [
                    "type" => "dir",
                    "name" => $item,
                    "path" => $full_path,
                    "children" => $this->scanDir($full_path)
                ];
            } else {
                $result[] = [
                    "type" => "file",
                    "name" => $item,
                    "path" => $full_path
                ];
            }
        }

        return $result;
    }

}