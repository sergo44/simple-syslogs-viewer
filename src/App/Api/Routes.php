<?php

declare(strict_types=1);

namespace App\Api;

use App\FrontController\ControllerInterface;
use App\FrontController\FileRoute;
use App\FrontController\RequestData\RequestData;
use App\FrontController\RouterInterface;
use App\Api;
use App\Layouts\JsonLayout;

class Routes extends FileRoute implements RouterInterface
{
    public function tryRoute(): ?ControllerInterface
    {
        if (preg_match("#^/?Api/FileTree/?$#", $this->dispatcher->path)) {
            return new Api\FileTree\FilesTreeController(
                new RequestData(),
                new Api\FileTree\FilesTreeJsonView(),
                new JsonLayout()
            )->getTree();
        }

        if (preg_match("#^/?Api/LogFile/?$#", $this->dispatcher->path)) {
            return new Api\LogFile\LogFileController(
                new RequestData(),
                new Api\LogFile\LogFileJsonView(),
                new JsonLayout()
            )->getFile();
        }

        return null;
    }
}