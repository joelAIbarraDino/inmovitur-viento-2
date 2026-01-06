<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { LayoutGrid, UserSearch, User, DoorOpen, CircleDollarSign, Hotel, Files, HandCoins } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const isAdmin = computed(() => {
    const user = page.props.auth?.user;
    return user && user.roles && user.roles.length > 0
        ? user.roles[0].name === 'admin' || user.roles[0].name === 'supervisor'
        : false;
});

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Información de torres',
        href: '/towers',
        icon: Hotel,
    },
    {
        title: 'Clientes',
        href: '/clients',
        icon: User,
    },
    {
        title: 'Condominios',
        href: '/condominiums',
        icon: DoorOpen,
    },
    {
        title: 'Pagos',
        href: '/payments',
        icon: CircleDollarSign,
    },

    {
        title: 'Ordenes de pagos (mxn)',
        href: '/order-payments',
        icon: HandCoins,
    },

    {
        title: 'Ordenes de pagos (usd)',
        href: '/prof-payments',
        icon: HandCoins,
    },
    {
        title: 'Supervisores',
        href: '/supervisors',
        icon: UserSearch,
    },
];

const mainNavItemsClient: NavItem[] = [
    {
        title: 'Mi panel',
        href: '/my-account',
        icon: LayoutGrid,
    },
    {
        title: 'Mis documentos',
        href: '/my-files',
        icon: Files,
    },
];

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <AppLogo />
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain v-if="isAdmin" :items="mainNavItems" />
            <NavMain v-else :items="mainNavItemsClient" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
