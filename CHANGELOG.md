# ChangeLog

## 1.0.1
* 
* Добавлена страница авторизации. Для включения данной возможности укажите environment переменные: ```SIMPLE_SYSLOG_VIEWER_LOGIN``` и ```SIMPLE_SYSLOG_VIEWER_PASSWORD```
* Добавлена environment переменная ```SIMPLE_SYSLOG_VIEWER_SKIP_EXT_SECURE_CHECK``` - если установлена - не будет проверяться расширение файла (txt, log) и будет доступен просмотр всех файлов
* Добавлена environment переменная ```SIMPLE_SYSLOG_VIEWER_ALLOWED_EXT_LIST``` - содержит допустимые расширения файлов для просмотра (по умолчанию "txt,log"). Работает, если **не** установлен ```SIMPLE_SYSLOG_VIEWER_SKIP_EXT_SECURE_CHECK=1```
* Добавлена environment переменная ```SIMPLE_SYSLOG_VIEWER_ARCHIVES_EXT_LIST``` - содержит список расширений архивных файлов (по умолчанию "gz,xz,zip")

## 1.0.1

* Added an authorization page. To enable this feature, set the environment variables: `SIMPLE_SYSLOG_VIEWER_LOGIN` and `SIMPLE_SYSLOG_VIEWER_PASSWORD`.
* Added the environment variable `SIMPLE_SYSLOG_VIEWER_SKIP_EXT_SECURE_CHECK`. If set, file extension checking (txt, log) will be disabled and all files will be available for viewing.
* Added the environment variable `SIMPLE_SYSLOG_VIEWER_ALLOWED_EXT_LIST`, which defines the list of allowed file extensions for viewing (default: "txt,log"). This option works if `SIMPLE_SYSLOG_VIEWER_SKIP_EXT_SECURE_CHECK=1` is **not** set.
* Added the environment variable `SIMPLE_SYSLOG_VIEWER_ARCHIVES_EXT_LIST`, which defines the list of archive file extensions (default: "gz,xz,zip").