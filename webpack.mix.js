const mix = require('laravel-mix');
const path = require('path');
require('laravel-mix-bundle-analyzer');
// require('laravel-mix-alias');

mix.webpackConfig({
    resolve: {
        alias: {
            styles: path.resolve(__dirname, 'resources', 'sass'),
            "@typescript": path.resolve(__dirname, 'resources', 'ts')
        }
    }
});

// mix.alias('sass', '/resources/sass');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.js('resources/js/app.js', 'public/js');

mix.js('resources/js/main.js', 'public/js');

// mix.postCss('resources/css/app.css', 'public/css', [
//     require('postcss-import'),
//     require('tailwindcss'),
//     require('autoprefixer'),
// ]);

//DESCOMENTAR
mix.sass('resources/sass/app.scss', 'public/css').options({
    postCss: [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer')
    ]
});

mix.sass('resources/sass/common/main-fonts.scss', 'public/css');


/* mix.webpackConfig({
     module: {
         rules: [{
             test: /\.tsx?$/,
             loader: 'ts-loader',
             options: { appendTsSuffixTo: [/\.vue$/] },
             exclude: /node_modules/,
         }, ],
     },
     resolve: {
         extensions: ['*', '.js', '.jsx', '.vue', '.ts', '.tsx'],
     },
}); */

mix.js('resources/vue/pages/chat.js', 'public/js')
.js('resources/vue/pages/admin.js', 'public/js')
.vue({ version: 3 });

mix.disableNotifications();

if (mix.isWatching()) {
    // mix.bundleAnalyzer();
    mix.sourceMaps();
}

if (mix.inProduction()) {
    mix.version();
    //mix.sourceMaps();
}
