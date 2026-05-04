export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export interface SharedNotification {
    id: string;
    type: string;
    message: string;
    read_at?: string | null;
    created_at?: string | null;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    notifications: {
        items: SharedNotification[];
        unread: number;
    };
};
