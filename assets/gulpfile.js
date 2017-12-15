/**
 * This file is part of TeamELF
 *
 * (c) GuessEver <guessever@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

const gulp = require('teamelf-gulp');

gulp({
  files: [
    './bower_components/es6-micro-loader/dist/system-polyfill.min.js',
    './bower_components/react/react.production.min.js',
    './bower_components/react/react-dom.production.min.js',
    './bower_components/jQuery/dist/jquery.min.js',
    './bower_components/moment/min/moment-with-locales.min.js',
    './node_modules/antd/dist/antd.min.js'
  ],
  modules: {
    'teamelf': 'js/**/*.js'
  },
  output: './dist/app.js'
}, {
  files: [
    './node_modules/antd/dist/antd.min.css'
  ],
  modules: ['less/main.less'],
  output: './dist/app.css'
});
