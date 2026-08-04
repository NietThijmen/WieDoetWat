export interface AdminUser {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
    created_at: string;
}

export interface AdminTask {
    id: number;
    title: string;
    description: string | null;
    weight: number;
    users_count: number;
    created_at: string;
}
