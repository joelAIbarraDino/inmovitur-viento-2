import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

interface TableAttribute {
  key: string
  prefix?: string
  suffix?: string
  format?: 'currency' | 'date' | 'uppercase' | 'capitalize' | ((value: any, record?: any) => string)
}

export interface TableAction<T> {
  type: 'link' | 'button';
  icon: LucideIcon;
  tooltip?:string;
  handle: string | ((record: T) => void | Promise<void>);
}

export interface TableConfig<T>{
    attributes:(string | TableAttribute)[];
    columns_name:string[];
    records:T[];
    caption?:string;
    actions: TableAction<T>[];
}

export interface Enum {
    label:string;
    value:string;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Client {
    id:number;
    id_user:number;
    phone:string;
    nacionality:string;
    legal_personality:string;
    marital_partnership:string;
    has_nationalTaxID:boolean;
}

export interface Condominiums{
    id:number;
    id_client: number;
    tower:stringnumber;
    number:stringnumber;
    price:number;
    currency:number;
    created_at:string;
}

export type BreadcrumbItemType = BreadcrumbItem;
