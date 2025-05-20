import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Users from '@/pages/Users.vue';

describe('Users.vue', () => {
    it('renders user table and add user modal', async () => {
        const users = [
            { id: 1, name: 'Jan Kowalski', email: 'jan@example.com', is_admin: false },
            { id: 2, name: 'Admin', email: 'admin@example.com', is_admin: true },
        ];
        const wrapper = mount(Users, {
            props: {},
            global: {
                mocks: {
                    $page: { props: { users, auth: {}, errors: {}, company: {} } }, // add company to prevent undefined
                },
            },
        });
        expect(wrapper.text()).toContain('Jan Kowalski');
        expect(wrapper.text()).toContain('admin@example.com');
        // Find the button by text instead of :contains selector
        const buttons = wrapper.findAll('button');
        const addButton = buttons.find(btn => btn.text().includes('Dodaj użytkownika'));
        await addButton?.trigger('click');
        expect(wrapper.html()).toContain('Dodaj użytkownika');
    });
});
