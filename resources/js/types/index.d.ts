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
    flash: {
        message: string;
    };
    exchange: {
        rate:number;
        created_at:string;
    };
    currency:string;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;

    roles: Role[];
}

export interface Condominium{
    id:number;
    id_client:number;
    tower:string,
    number:string,
    price:number;
    currency:string;
    clients?:Client;
}

export interface Client {
    id:number;
    no_contract:string;
    phone:string;
    nacionality:string;
    legal_personality:string;
    marital_partnership:string;
    has_nationalTaxID:boolean;
    has_CURP:boolean;
    created_at: string;
    updated_at: string;
    users?:User;
}

export interface Payment {
    id:number;
    id_condominium:number;
    amount:number;
    discount_condominium:number;
    currency:string;
    condominiums?:Condominium;
    created_at:string;
}

export interface Document {
    id:number;
    id_client:number;
    original_name:string;
    stored_name: string;
    type_document:string;
    status:string;
    path:string;
    clients?:Client;
}

export interface OrderPayment {
    id:number;
    id_client:number;
    amount:number;
    discount_condominium:number;
    currency:string;
    url_spei:string;
    clabe:string;
    bank_name:string;
    order_id:string;
    status:string;
    created_at:string;
    clients?:Client;
}

export interface enums{
    value:string;
    label:string;
}

export interface messages{
    success?:string;
    error?:string;
}

export interface DataTowers{
    towerName: string;
    towerValue: number;
    condominiums: number;
    soldCondominiums: number;
    condominiumsToSell: number;
    charged: number;
    pending: number;
    sold: number;
    inventory: number;
    penality: number;
    currency: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
