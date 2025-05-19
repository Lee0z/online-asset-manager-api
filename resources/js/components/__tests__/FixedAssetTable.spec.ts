import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import FixedAssetTable from '@/components/FixedAssetTable.vue';

const assets = [
    {
        id: 1,
        asset_name: 'Komputer',
        kst_symbol: '123',
        initial_value: 1000,
        current_value: 900,
        acquisition_document_type: 'Faktura',
        depreciation_rate: 20,
        status: 'nabyty',
        acquisition_date: '2024-01-01',
        commissioning_date: '2024-01-02',
        liquidation_date: null,
        liquidation_reason: null,
        user: { name: 'Jan Kowalski' },
    },
];

describe('FixedAssetTable', () => {
    it('renders asset table with correct columns', () => {
        const wrapper = mount(FixedAssetTable, {
            props: { assets },
            global: {
                mocks: {
                    $page: { props: { auth: { user: { name: 'Admin' } }, company: {} } },
                },
            },
        });
        expect(wrapper.text()).toContain('Komputer');
        expect(wrapper.text()).toContain('123');
        expect(wrapper.text()).toContain('nabyty');
    });

    it('shows report modal when button clicked', async () => {
        const wrapper = mount(FixedAssetTable, {
            props: { assets },
            global: {
                mocks: {
                    $page: { props: { auth: { user: { name: 'Admin' } }, company: {} } },
                },
            },
        });
        await wrapper.find('button:contains("Generuj raport")').trigger('click');
        expect(wrapper.html()).toContain('Generuj raport PDF');
    });
});
