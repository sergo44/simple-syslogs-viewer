## Simple Log Viewer

Контейнер для простой системы просмотра логов на PHP и React.  
Позволяет просматривать `.log`-файлы с сервера через удобный веб-интерфейс.  
Поддерживает чтение логов от root (например, `/var/log`) для анализа системных событий и приложений.

### Использование

1. Соберите и запустите контейнер:

```
docker run -it --rm -p 8080:80 -v /var/log:/target:ro sergo44/simple-syslogs-viewer:latest
```

2. Откройте [http://localhost:8080](http://localhost:8080) в браузере.

3. Настройте volume `/target` для нужных логов, если требуется.

### Environment переменные
#### Авторизация

- `SIMPLE_SYSLOG_VIEWER_LOGIN` — Логин для доступа к приложению.
- `SIMPLE_SYSLOG_VIEWER_PASSWORD` — Пароль для доступа к приложению.

#### Ограничение расширений файлов

- `SIMPLE_SYSLOG_VIEWER_SKIP_EXT_SECURE_CHECK` — Если переменная задана, проверка расширений файлов отключается, и можно просматривать любые файлы.
- `SIMPLE_SYSLOG_VIEWER_ALLOWED_EXT_LIST` — Список разрешённых для просмотра расширений файлов (по умолчанию: `txt,log`). Работает, если не задана переменная `SIMPLE_SYSLOG_VIEWER_SKIP_EXT_SECURE_CHECK`.
- `SIMPLE_SYSLOG_VIEWER_ARCHIVES_EXT_LIST` — Список расширений архивных файлов (по умолчанию: `gz,xz,zip`).

#### Пример

```bash
export SIMPLE_SYSLOG_VIEWER_LOGIN=admin
export SIMPLE_SYSLOG_VIEWER_PASSWORD=secret
export SIMPLE_SYSLOG_VIEWER_ALLOWED_EXT_LIST=txt,log,json
export SIMPLE_SYSLOG_VIEWER_ARCHIVES_EXT_LIST=gz,xz,zip,tar
```

### Особенности

- **Frontend:** React + Vite + shadcn/ui  
- **Backend:** чистый PHP (без фреймворков)  
- Поддержка чтения логов с правами root или группы adm  
- Современный UI и минимальный размер контейнера  

### GitHub
[https://github.com/sergo44/simple-syslogs-viewer](https://github.com/sergo44/simple-syslogs-viewer)

---

## Simple Log Viewer (English)

Container for a simple log viewing system built with PHP and React.  
Allows you to browse `.log` files from your server via a convenient web interface.  
Supports reading logs as root (e.g. `/var/log`) for analyzing system and application events.

### Usage

1. Build and run the container:


```
docker run -it --rm -p 8080:80 -v /var/log:/target:ro sergo44/simple-syslogs-viewer:latest
```

2. Open [http://localhost:8080](http://localhost:8080) in your browser.

3. Mount the `/target` volume with your desired log files if needed.

### Environment Variables

#### Authorization

- `SIMPLE_SYSLOG_VIEWER_LOGIN` — Login for accessing the application.
- `SIMPLE_SYSLOG_VIEWER_PASSWORD` — Password for accessing the application.

#### File Extension Restriction

- `SIMPLE_SYSLOG_VIEWER_SKIP_EXT_SECURE_CHECK` — If set, file extension checking is disabled and all files will be available for viewing.
- `SIMPLE_SYSLOG_VIEWER_ALLOWED_EXT_LIST` — List of allowed file extensions for viewing (default: `txt,log`). Works if `SIMPLE_SYSLOG_VIEWER_SKIP_EXT_SECURE_CHECK` is **not** set.
- `SIMPLE_SYSLOG_VIEWER_ARCHIVES_EXT_LIST` — List of archive file extensions (default: `gz,xz,zip`).

#### Example

```bash
export SIMPLE_SYSLOG_VIEWER_LOGIN=admin
export SIMPLE_SYSLOG_VIEWER_PASSWORD=secret
export SIMPLE_SYSLOG_VIEWER_ALLOWED_EXT_LIST=txt,log,json
export SIMPLE_SYSLOG_VIEWER_ARCHIVES_EXT_LIST=gz,xz,zip,tar
```
 
### Features

- **Frontend:** React + Vite + shadcn/ui  
- **Backend:** pure PHP (no frameworks)  
- Supports reading logs with root or adm group permissions  
- Modern UI and minimal container size  


### GitHub
[https://github.com/sergo44/simple-syslogs-viewer](https://github.com/sergo44/simple-syslogs-viewer)
