var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    .addEntry('js/app', './assets/js/app.js')
    .addStyleEntry('css/master', './assets/scss/master.scss')
    .addPlugin(new CopyWebpackPlugin([{ from: 'assets/images', to: 'images' }]))
    .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
