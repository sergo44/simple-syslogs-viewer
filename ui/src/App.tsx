import React from "react";
import { AuthProvider, useAuth } from "@/components/AuthProvider";
import { FileExplorer } from "@/components/FileExplorer";
import { LogViewer } from "@/components/LogViewer";
import { SignIn } from "@/components/SignIn";
import {Footer} from "@/components/Footer.tsx";
import "./App.css";


const MainApp = () => {
    const [selectedFile, setSelectedFile] = React.useState<string | null>(null);
    return (
        <div className="flex flex-col h-screen w-screen bg-neutral-950 text-neutral-100">
            <div className="flex flex-1 min-h-0">
                {/* Sidebar */}
                <aside className="w-80 h-full bg-neutral-900 border-r border-neutral-800 shadow-lg p-4 flex flex-col">
                    <h2 className="text-lg font-bold mb-4 text-violet-400">Log Files</h2>
                    {/* Скроллируемый блок файлов */}
                    <div className="flex-1 overflow-y-auto">
                        <FileExplorer selectedFile={selectedFile} onSelectFile={setSelectedFile} />
                    </div>
                </aside>
                {/* Контент логов */}
                <main className="flex-1 h-full p-8 overflow-auto bg-neutral-950">
                    <LogViewer filePath={selectedFile} />
                </main>
            </div>
            {/* Футер */}
            <Footer/>
        </div>
    );
};


const App = () => (
    <AuthProvider>
        <AuthGate>
            <MainApp />
        </AuthGate>
    </AuthProvider>
);

const AuthGate: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const { isAuthEnabled, isLoading, isAuthenticated } = useAuth();
    if (isLoading) return <SignIn loading />;
    if (isAuthEnabled && !isAuthenticated) return <SignIn />;
    return <>{children}</>;
};

export default App;
