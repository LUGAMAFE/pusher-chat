export declare class Language {
    get(arg0: string): string | undefined;
    static instance: Language;
    constructor();
    setFallback(arg0: string): void;
    getLocale(): string;
    static getInstance(): Language;
}
export function getInstance(): Language

