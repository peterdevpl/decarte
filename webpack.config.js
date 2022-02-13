const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public_html/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/js/app.js')
    .createSharedEntry('vendor', './assets/scss/bootstrap.scss')
    .disableSingleRuntimeChunk()
    .enableSassLoader()
    .enableSourceMaps(!Encore.isProduction())
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableVersioning(Encore.isProduction())
;

// export the final configuration
module.exports = Encore.getWebpackConfig();
