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
          $page: { props: { users } },
        },
      },
    });
    expect(wrapper.text()).toContain('Jan Kowalski');
    expect(wrapper.text()).toContain('admin@example.com');
    await wrapper.find('button').trigger('click');
    expect(wrapper.html()).toContain('Dodaj użytkownika');
  });
});
