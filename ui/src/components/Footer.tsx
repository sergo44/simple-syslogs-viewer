import React from "react";
import packageJson from '../../package.json';

const version = packageJson.version;

export const Footer: React.FC = () => (
    <footer className="w-full py-4 text-center text-sm text-neutral-500 border-t border-neutral-800 bg-neutral-950">
        © 2025 Simple Log Viewer &nbsp;|&nbsp; v{version} | &nbsp;
        <a
            href="https://github.com/sergo44/simple-sysloo-viewer"
            className="text-violet-400 hover:underline"
            target="_blank"
            rel="noopener noreferrer"
        >
            GitHub
        </a>

    </footer>
);