var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public_html/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/js/app.js')
    .createSharedEntry('vendor', [
        './assets/scss/bootstrap.scss'
    ])
    .enableSassLoader()
    .enableSourceMaps(!Encore.isProduction())
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableVersioning()
;

// export the final configuration
module.exports = Encore.getWebpackConfig();
