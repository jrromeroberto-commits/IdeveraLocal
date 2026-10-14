import { Link, usePage } from '@inertiajs/react';
import { Globe, LayoutGrid } from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
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
import type { Auth, NavItem } from '@/types';
import { index as accounts } from '@/routes/owner/accounts';

const mainNavItems: NavItem[] = [
    {
        title: 'Mi espacio',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Visitar el estudio',
        href: '/',
        icon: Globe,
    },
];

export function AppSidebar() {
    const { auth } = usePage<{ auth: Auth }>().props;
    const items: NavItem[] =
        auth.user.role === 'OWNER'
            ? [
                  ...mainNavItems,
                  { title: 'Cuentas', href: accounts(), icon: LayoutGrid },
                  {
                      title: 'Contenido del estudio',
                      href: '/owner/content',
                      icon: Globe,
                  },
                  {
                      title: 'Mensajes de contacto',
                      href: '/owner/contact',
                      icon: LayoutGrid,
                  },
              ]
            : mainNavItems;
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={items} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
