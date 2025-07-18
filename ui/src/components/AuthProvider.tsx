// ui/src/components/AuthProvider.tsx

import React, { createContext, useContext, useEffect, useState } from "react";

type AuthContextType = {
    isAuthEnabled: boolean;
    isAuthenticated: boolean;
    isLoading: boolean;
    signIn: (login: string, password: string) => Promise<{ success: boolean; errors?: string[] }>;
    error?: string | null;
};

const AuthContext = createContext<AuthContextType>({
    isAuthEnabled: false,
    isAuthenticated: false,
    isLoading: true,
    signIn: async () => ({ success: false }),
});

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [isAuthEnabled, setAuthEnabled] = useState(false);
    const [isAuthenticated, setAuthenticated] = useState(false);
    const [isLoading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);


    useEffect(() => {
        fetch("/Api/Users/Auth/Enabled")
            .then((r) => r.json())
            .then((json) => {
                const enabled = !!json?.payload?.enabled;
                const authorized = !!json?.payload?.authorized;
                setAuthEnabled(enabled);
                setAuthenticated(!enabled || authorized); // <= ключевая строка!
                setLoading(false);
            })
            .catch(() => {
                setAuthEnabled(true);
                setAuthenticated(false);
                setLoading(false);
                setError("Failed to check authorization. Please check server availability.");
            });

    }, []);

    const signIn = async (login: string, password: string) => {
        const res = await fetch("/Api/Users/Auth/SignIn", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ login, password }),
        });
        const json = await res.json();
        if (json?.result?.success) {
            setAuthenticated(true);
            return { success: true };
        } else {
            setError((json?.result?.errors || []).map((e: any) => e.message).join(", "));
            return {
                success: false,
                errors: [],
            };
        }
    };

    return (
        <AuthContext.Provider value={{
            isAuthEnabled,
            isAuthenticated,
            isLoading,
            signIn,
            error, // добавили!
        }}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => useContext(AuthContext);
