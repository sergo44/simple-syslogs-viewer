import React, { useState } from "react";
import { useAuth } from "./AuthProvider";
import { Loader2 } from "lucide-react";

type Props = { loading?: boolean };

export const SignIn: React.FC<Props> = ({ loading = false }) => {
    const { isAuthEnabled, isLoading, signIn, error } = useAuth();
    const [login, setLogin] = useState("");
    const [password, setPassword] = useState("");
    const [submitting, setSubmitting] = useState(false);
    const [errors, setErrors] = useState<string[]>([]);
    const disabled = !isAuthEnabled || isLoading || loading || submitting;

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setSubmitting(true);
        setErrors([]);
        const res = await signIn(login, password);
        if (!res.success) setErrors(res.errors || ["Login failed"]);
        setSubmitting(false);
    };

    return (
        <div className="fixed inset-0 flex items-center justify-center bg-black/90 z-50">
            <form
                className="bg-neutral-900 rounded-2xl shadow-2xl p-8 min-w-[320px] w-full max-w-xs flex flex-col items-center gap-5 border border-neutral-800"
                onSubmit={handleSubmit}
            >
                <h2 className="font-bold text-xl text-violet-400">Sign In</h2>
                {error && (
                    <div className="mb-4 text-center text-red-400 text-sm bg-red-900/60 p-3 rounded-xl border border-red-700">
                        {error}
                    </div>
                )}
                <div className="w-full flex flex-col gap-3">
                    <label className="w-full flex flex-col gap-1">
                        <span className="text-sm text-neutral-400">Login</span>
                        <input
                            className="bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2 text-base focus:outline-none disabled:opacity-70 text-neutral-100"
                            type="text"
                            value={login}
                            disabled={disabled}
                            autoComplete="username"
                            onChange={e => setLogin(e.target.value)}
                        />
                    </label>
                    <label className="w-full flex flex-col gap-1">
                        <span className="text-sm text-neutral-400">Password</span>
                        <input
                            className="bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2 text-base focus:outline-none disabled:opacity-70 text-neutral-100"
                            type="password"
                            value={password}
                            disabled={disabled}
                            autoComplete="current-password"
                            onChange={e => setPassword(e.target.value)}
                        />
                    </label>
                </div>
                <button
                    className="bg-violet-600 text-white font-semibold py-2 rounded-xl w-full mt-2 transition disabled:opacity-60"
                    disabled={disabled}
                    type="submit"
                >
                    {submitting ? (
                        <span className="flex items-center justify-center gap-2">
                            <Loader2 className="w-4 h-4 animate-spin" /> Checking...
                        </span>
                    ) : (
                        "Sign In"
                    )}
                </button>
                {(isLoading || loading) && (
                    <div className="flex items-center gap-2 text-neutral-400 mt-2">
                        <Loader2 className="w-4 h-4 animate-spin" /> Checking if authentication is enabled...
                    </div>
                )}
                {errors.length > 0 && (
                    <div className="text-red-400 text-sm mt-3 text-center">
                        {errors.map((err, idx) => <div key={idx}>{err}</div>)}
                    </div>
                )}
            </form>
        </div>
    );
};
