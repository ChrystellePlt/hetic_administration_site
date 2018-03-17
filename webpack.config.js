var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    .addEntry('js/app', './assets/js/app.js')
    .addStyleEntry('css/master', './assets/scss/master.scss')
    .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
