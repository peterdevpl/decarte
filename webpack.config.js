var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public_html/build/')
    .setPublicPath('/build')
    .addEntry('app', './src/AppBundle/Resources/js/app.js')
    .createSharedEntry('vendor', [
        'jquery',
        './src/AppBundle/Resources/scss/bootstrap.scss'
    ])
    .enableSassLoader()
    .autoProvidejQuery()
    .enableSourceMaps(!Encore.isProduction())
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableVersioning()
;

// export the final configuration
module.exports = Encore.getWebpackConfig();
