<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, UserCog, Folder, LayoutGrid } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

interface AuthUser {
  is_admin?: boolean;
  // add other user properties if needed
}
const page = usePage<{ auth?: { user?: AuthUser } }>();
const isAdmin = !!(page.props.auth && page.props.auth.user && page.props.auth.user.is_admin);

const mainNavItems: NavItem[] = [
  {
    title: 'Środki trwałe',
    href: '/dashboard',
    icon: LayoutGrid,
  }
];
if (isAdmin) {
  mainNavItems.push({
    title: 'Użytkownicy',
    href: '/users',
    icon: UserCog,
  });
}

const footerNavItems: NavItem[] = [

];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
