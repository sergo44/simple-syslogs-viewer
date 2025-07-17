<?php

declare(strict_types=1);

namespace App\Api\LogFile;

use App\FrontController\BaseController;
use App\FrontController\ControllerException;
use App\FrontController\ControllerInterface;
use App\FrontController\LayoutInterface;
use App\FrontController\RequestDataInterface;

class LogFileController extends BaseController implements ControllerInterface
{
    /**
     * Конструктор класса
     * @param RequestDataInterface $request
     * @param LogFileJsonView $view
     * @param LayoutInterface $layout
     */
    public function __construct(RequestDataInterface $request, LogFileJsonView $view, LayoutInterface $layout)
    {
        $this->request = $request;
        $this->view = $view;
        $this->layout = $layout;
    }

    /**
     * Возвращает дерево для отображения
     * @return $this
     */
    public function getFile(): self
    {
        try {

            $file_mame = $this->request->getString("file");
            if (!$file_mame) {
                throw new ControllerException("Не передан файл для загрузки");
            }

            if (!file_exists($file_mame)) {
                throw new ControllerException("Указанный вами файл не найден");
            }

            if (!is_file($file_mame)) {
                throw new ControllerException("Указанный файл является директорией или не типичным файлов");
            }

            if (!is_readable($file_mame)) {
                throw new ControllerException("Нет доступа для чтения файла");
            }

            $ext = strtolower(pathinfo($file_mame, PATHINFO_EXTENSION));
            if (!in_array($ext, ["txt", "log"])) {
                throw new ControllerException("Запрещенный тип файлов - поддерживается только txt, log файлы");
            }

            $realpath = realpath($file_mame);
            if (!$realpath || !str_starts_with($realpath, "/target/")) {
                throw new ControllerException("Попытка выйти за пределы доступной директории");
            }

            $this->view->content = $this->_readFile($realpath, $this->request->getString('search'));

        } catch (ControllerException $e) {
            $this->view->result->addError($e->getMessage());
        }

        return $this;
    }

    private function _readFile(string $path, ?string $search_string = null): string
    {
        $handle = fopen($path, "r");
        if (!$handle) {
            return "Ошибка открытия файла";
        }

        $bufferBefore = [];
        $collectAfter = 0;
        $result = '';
        $maxBefore = 10;
        $maxAfter = 10;
        $matchesFound = 0;

        while (($line = fgets($handle)) !== false) {

            if (!$search_string) {
                $result .= htmlspecialchars($line, ENT_QUOTES);
                continue;
            }

            if ($collectAfter > 0) {
                $result .= $line;
                $collectAfter--;
                continue;
            }

            // Окрасим в стиле
            $highlight_start = '<span style="background: #fde047; color: #000; font-weight: bold; padding: 0 2px; border-radius: 4px">';
            $highlight_end   = '</span>';

            // Если найдено совпадение
            if (str_contains(mb_strtolower($line), mb_strtolower($search_string))) {


                $matchesFound++;

                foreach ($bufferBefore as $bufLine) {
                    $result .= $bufLine;
                }
                $bufferBefore = [];

                // Саму строку с совпадением
                $result .= str_replace($search_string, sprintf("%s%s%s", $highlight_start, htmlspecialchars($search_string, ENT_QUOTES), $highlight_end), htmlspecialchars($line, ENT_QUOTES));

                // Теперь начинаем собирать строки "после"
                $collectAfter = $maxAfter;
                continue;
            }

            $bufferBefore[] = $line;
            if (sizeof($bufferBefore) > $maxBefore) {
                array_shift($bufferBefore);
            }
        }
        fclose($handle);

        return $matchesFound > 0 || !$search_string ? $result : "Совпадения не найдены.";
    }

}