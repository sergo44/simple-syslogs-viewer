import React from "react";
import { Loader2, Search, Clipboard } from "lucide-react";

type Props = {
    filePath: string | null;
};

export const LogViewer: React.FC<Props> = ({ filePath }) => {
    const [log, setLog] = React.useState<string>("");
    const [loading, setLoading] = React.useState(false);
    const [search, setSearch] = React.useState("");
    const [copied, setCopied] = React.useState(false);

    // 1. Загружаем лог при смене filePath (без поиска)
    React.useEffect(() => {
        if (!filePath) return;
        setLoading(true);
        setLog("");
        setSearch(""); // сбрасываем поиск
        fetch(`/Api/LogFile?file=${encodeURIComponent(filePath)}`)
            .then(async (r) => {
                if (!r.ok) throw new Error("Ошибка загрузки файла");
                const json = await r.json();

                if (
                    json.result?.success === false &&
                    json.result?.error === true &&
                    Array.isArray(json.result?.errors) &&
                    json.result.errors.length > 0
                ) {
                    const msg = json.result.errors.map(
                        (e: { message: string; field?: string | null }) => e.message
                    ).join('\n');
                    throw new Error(msg);
                }
                return json;
            })
            .then((r) => setLog(r?.payload?.content))
            .catch((err) => {
                setLog(err.message);
            })
            .finally(() => setLoading(false));
    }, [filePath]);

    // 2. Поиск только по кнопке "Найти" или Enter
    const handleSearch = React.useCallback(() => {
        if (!filePath) return;
        setLoading(true);
        setLog("");
        fetch(`/Api/LogFile?file=${encodeURIComponent(filePath)}&search=${encodeURIComponent(search)}`)
            .then(async (r) => {
                if (!r.ok) throw new Error("Ошибка загрузки файла");
                const json = await r.json();

                if (
                    json.result?.success === false &&
                    json.result?.error === true &&
                    Array.isArray(json.result?.errors) &&
                    json.result.errors.length > 0
                ) {
                    const msg = json.result.errors.map(
                        (e: { message: string; field?: string | null }) => e.message
                    ).join('\n');
                    throw new Error(msg);
                }
                return json;
            })
            .then((r) => setLog(r?.payload?.content))
            .catch((err) => {
                setLog(err.message);
            })
            .finally(() => setLoading(false));
    }, [filePath, search]);

    const onSearchKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === "Enter") {
            handleSearch();
        }
    };

    const handleCopy = () => {
        navigator.clipboard.writeText(log);
        setCopied(true);
        setTimeout(() => setCopied(false), 1500);
    };

    if (!filePath)
        return (
            <div className="flex items-center justify-center h-full bg-neutral-900 rounded text-neutral-500 text-lg">
                Выберите лог для просмотра
            </div>
        );

    return (
        <div className="flex flex-col h-full w-full bg-neutral-900 rounded shadow">
            <div className="flex items-center justify-between border-b border-neutral-800 px-4 py-2">
                <span className="font-mono text-base text-neutral-400">{filePath}</span>
                <div className="flex gap-2 items-center">
                    <div className="relative w-64">
                        <Search className="absolute left-2 top-2 w-4 h-4 text-neutral-500" />
                        <input
                            type="text"
                            placeholder="Поиск по логам"
                            className="pl-8 pr-2 py-1 bg-neutral-800 text-neutral-200 rounded outline-none border border-neutral-700 focus:border-violet-500 text-xs w-full"
                            value={search}
                            onChange={e => setSearch(e.target.value)}
                            onKeyDown={onSearchKeyDown}
                            spellCheck={false}
                        />
                    </div>
                    <button
                        className="ml-1 px-4 py-2 rounded bg-violet-700 hover:bg-violet-800 transition-colors text-white text-xs font-medium"
                        onClick={handleSearch}
                        disabled={loading}
                    >
                        Найти
                    </button>
                    <button
                        className="ml-2 p-2 rounded bg-neutral-800 hover:bg-violet-700 transition-colors text-neutral-200 flex items-center"
                        onClick={handleCopy}
                        title="Скопировать лог"
                    >
                        <Clipboard className="w-4 h-4" />
                        {copied && <span className="ml-1 text-xs">Скопировано!</span>}
                    </button>
                </div>
            </div>
            <div className="flex-1 overflow-auto">
                {loading ? (
                    <div className="flex items-center justify-center h-40 text-neutral-400">
                        <Loader2 className="animate-spin mr-2" /> Загрузка...
                    </div>
                ) : (
                    <pre
                        className="bg-neutral-950 text-neutral-100 font-mono p-4 max-h-[100vh] min-h-[400px] w-full h-full overflow-auto text-xs leading-relaxed select-text rounded-none"
                        dangerouslySetInnerHTML={{ __html: log }}
                    >
                    </pre>
                )}
            </div>
        </div>
    );
};
