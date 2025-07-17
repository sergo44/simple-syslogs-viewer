import React from "react";
import { FileExplorer } from "@/components/FileExplorer";
import { LogViewer } from "@/components/LogViewer";
import "./App.css";

const App: React.FC = () => {
    const [selectedFile, setSelectedFile] = React.useState<string | null>(null);

    return (
        <div className="flex h-screen w-screen bg-neutral-950 text-neutral-100">
            <aside className="w-80 h-full bg-neutral-900 border-r border-neutral-800 shadow-lg p-4 flex flex-col">
                <h2 className="text-lg font-bold mb-4 text-violet-400">Файлы логов</h2>
                <FileExplorer selectedFile={selectedFile} onSelectFile={setSelectedFile} />
            </aside>
            <main className="flex-1 h-full p-8 overflow-auto bg-neutral-950">
                <LogViewer filePath={selectedFile} />
            </main>
        </div>
    );
};

export default App;
