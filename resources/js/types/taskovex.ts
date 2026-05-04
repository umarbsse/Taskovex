export interface AppUser {
    id: number;
    name: string;
    email?: string;
}

export interface SubTask {
    id: number;
    title: string;
    is_completed: boolean;
}

export interface TaskComment {
    id: number;
    body: string;
    created_at?: string | null;
    user: AppUser;
}

export interface TaskAttachment {
    id: number;
    original_name: string;
    mime_type?: string | null;
    size: number;
}

export interface Project {
    id: number;
    name: string;
    description?: string | null;
    color: string;
    created_at?: string | null;
    updated_at?: string | null;
    tasks_count: number;
    completed_tasks_count: number;
    tasks?: TaskovexTask[];
}

export interface TaskovexTask {
    id: number;
    project_id: number;
    assigned_user_id?: number | null;
    title: string;
    description?: string | null;
    status: string;
    priority: string;
    due_date?: string | null;
    completed_at?: string | null;
    created_at?: string | null;
    updated_at?: string | null;
    project?: Project;
    assigned_user?: AppUser | null;
    subtasks?: SubTask[];
    comments?: TaskComment[];
    attachments?: TaskAttachment[];
}

export interface ActivityLogItem {
    id: number;
    action: string;
    description: string;
    created_at?: string | null;
    user?: string | null;
    project?: string | null;
    task?: string | null;
}
