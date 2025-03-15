let mix = require('laravel-mix')

mix.js('resources/js/app.js', 'public/js').vue({
    version: 3,
    extractStyles: true,
    globalStyles: false
});

mix.postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('postcss-nested'),
    require('autoprefixer'),
]);

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync('localhost');
