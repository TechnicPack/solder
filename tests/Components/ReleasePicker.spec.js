import { mount } from '@vue/test-utils';
import expect from 'expect';
import moxios from 'moxios';
import ReleasePicker from '../../resources/assets/js/components/ReleasePicker.vue';

describe('ReleasePicker', () => {
    it('shows packages in alphabetic order', (done) => {
        let wrapper = mount(ReleasePicker);

        moxios.stubRequest('/api/packages', {
            status: 200,
            response: {
                data: [
                    { id: 1, name: 'Iron Tanks' },
                    { id: 2, name: 'Buildcraft' }
                ]
            }
        });

        moxios.wait(() => {
            expect(wrapper.find('#package').element.firstChild.textContent).toContain('Buildcraft');
            expect(wrapper.find('#package').element.lastChild.textContent).toContain('Iron Tanks');

            done()
        });


    });

    beforeEach(() => {
        moxios.install();
    });

    afterEach(() => {
        moxios.uninstall();
    });
});