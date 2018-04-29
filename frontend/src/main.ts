/// <reference types="aurelia-loader-webpack/src/webpack-hot-interface"/>
import 'font-awesome/css/font-awesome.css';
import 'bootstrap/dist/css/bootstrap.css';
import '../static/styles.css';

import 'select2';

import { Aurelia } from 'aurelia-framework';
import { PLATFORM } from 'aurelia-pal';
import * as Bluebird from 'bluebird';

Bluebird.config({ warnings: { wForgottenReturn: false } });

export async function configure(aurelia: Aurelia) {
    aurelia.use
        .standardConfiguration()
        .developmentLogging()
    ;

    await aurelia.start();
    await aurelia.setRoot(PLATFORM.moduleName('app'));
}
