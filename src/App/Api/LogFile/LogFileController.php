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
                throw new ControllerException("No file provided");
            }

            if (!file_exists($file_mame)) {
                throw new ControllerException("File not found");
            }

            if (!is_file($file_mame)) {
                throw new ControllerException("Path is not regular file");
            }

            if (!is_readable($file_mame)) {
                throw new ControllerException("Open file permissions error");
            }

            $ext = strtolower(pathinfo($file_mame, PATHINFO_EXTENSION));

            if (in_array($ext, explode(",", mb_strtolower(getenv("SIMPLE_SYSLOG_VIEWER_ARCHIVES_EXT_LIST") ?: "gz,xz,zip")))) {
                throw new ControllerException("Can't open archive file yet");
            }

            if (!getenv("SIMPLE_SYSLOG_VIEWER_SKIP_EXT_SECURE_CHECK") || mb_strtolower(getenv("SIMPLE_SYSLOG_VIEWER_SKIP_EXT_SECURE_CHECK")) === "false") {
                if (!in_array($ext, explode(',', mb_strtolower(getenv("SIMPLE_SYSLOG_VIEWER_ALLOWED_EXT_LIST") ?: "txt,log")))) {
                    throw new ControllerException("Insecure file extension");
                }
            }

            $realpath = realpath($file_mame);
            if (!$realpath || !str_starts_with($realpath, "/target/")) {
                throw new ControllerException("Insecure file path");
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
            return "Error opening file";
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

        return $matchesFound > 0 || !$search_string ? $result : "Match not found";
    }

}