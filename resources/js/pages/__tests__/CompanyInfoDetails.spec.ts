import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import CompanyInfoDetails from '@/pages/CompanyInfoDetails.vue';

describe('CompanyInfoDetails.vue', () => {
    it('renders company info fields', () => {
        const company = {
            company_name: 'Test Company',
            company_tax_number: '1234567890',
            company_street: 'Testowa',
            company_street_number: '1',
            company_zip_code: '12345',
            company_city: 'Miasto',
            company_state: 'Województwo',
            company_country: 'Polska',
        };
        const wrapper = mount(CompanyInfoDetails, {
            props: {},
            global: {
                mocks: {
                    $page: { props: { company } },
                },
            },
        });
        expect(wrapper.text()).toContain('Test Company');
        expect(wrapper.text()).toContain('1234567890');
        expect(wrapper.text()).toContain('Testowa');
        expect(wrapper.text()).toContain('Polska');
    });
});
