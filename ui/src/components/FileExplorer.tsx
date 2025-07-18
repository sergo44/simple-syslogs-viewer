import React from "react";
import { Folder, ChevronDown, ChevronRight, FileText } from "lucide-react";

type FileNode = {
    type: "file" | "dir";
    name: string;
    path: string;
    children?: FileNode[];
};

type Props = {
    selectedFile: string | null;
    onSelectFile: (file: string) => void;
};

function FileTree({
                      nodes,
                      selectedFile,
                      onSelectFile,
                      level = 0,
                  }: {
    nodes: FileNode[];
    selectedFile: string | null;
    onSelectFile: (file: string) => void;
    level?: number;
}) {
    const [openDirs, setOpenDirs] = React.useState<Record<string, boolean>>({});

    const toggleDir = (path: string) => {
        setOpenDirs((prev) => ({ ...prev, [path]: !prev[path] }));
    };

    return (
        <ul className="pl-0">
            {nodes.map((node) => {
                if (node.type === "dir") {
                    const isOpen = openDirs[node.path] ?? true;
                    return (
                        <li key={node.path}>
                            <div
                                className={`flex items-center gap-1 cursor-pointer px-2 py-1 rounded select-none font-medium ${
                                    isOpen ? "bg-neutral-800" : ""
                                }`}
                                style={{ paddingLeft: 8 + level * 16 }}
                                onClick={() => toggleDir(node.path)}
                            >
                                <span>{isOpen ? <ChevronDown size={16} /> : <ChevronRight size={16} />}</span>
                                <Folder className="w-4 h-4 mr-1 text-violet-400" />
                                <span className="text-neutral-100">{node.name}</span>
                            </div>
                            {isOpen && node.children && (
                                <FileTree
                                    nodes={node.children}
                                    selectedFile={selectedFile}
                                    onSelectFile={onSelectFile}
                                    level={level + 1}
                                />
                            )}
                        </li>
                    );
                }
                return (
                    <li key={node.path}>
                        <div
                            className={`flex items-center gap-1 cursor-pointer px-2 py-1 rounded transition-colors
                ${selectedFile === node.path ? "bg-violet-800 text-violet-200 font-semibold" : "hover:bg-neutral-800"}`}
                            style={{ paddingLeft: 36 + level * 16 }}
                            onClick={() => onSelectFile(node.path)}
                        >
                            <FileText className="w-4 h-4 mr-1 text-violet-300" />
                            <span>{node.name}</span>
                        </div>
                    </li>
                );
            })}
        </ul>
    );
}

export const FileExplorer: React.FC<Props> = ({ selectedFile, onSelectFile }) => {
    const [files, setFiles] = React.useState<FileNode[]>([]);

    React.useEffect(() => {
        fetch("/Api/FileTree")
            .then((res) => res.json())
            .then((res) => setFiles(res?.payload?.tree ?? []))
            .catch(() => setFiles([]));
    }, []);

    if (!files.length)
        return (
            <div className="text-gray-400 text-sm italic px-2 py-4">No files</div>
        );

    return (
        <nav className="overflow-y-auto pr-2">
            <FileTree nodes={files} selectedFile={selectedFile} onSelectFile={onSelectFile} />
        </nav>
    );
};
