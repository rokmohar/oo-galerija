import { customAttribute, inject } from 'aurelia-framework';
import 'jquery';
import 'select2';

@inject(Element)
@customAttribute('select2')
export class Select2 {

    private value: any;

    constructor(private element) {}

    private attached(): void {
        const $element = $(this.element);
        $element.select2(this.value);
        $element.on('change', evt => {
            if (evt.originalEvent) {
                return;
            }

            this.element.dispatchEvent(new Event('change'));
        });
    }

    private detached(): void {
        const $element = $(this.element);
        $element.select2('destroy');
    }

}
