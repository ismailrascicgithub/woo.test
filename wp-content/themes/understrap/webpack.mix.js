const mix = require('laravel-mix')
const LiveReloadWebpackPlugin = require('@kooneko/livereload-webpack-plugin')

module.exports = {
  plugins: [
    new LiveReloadWebpackPlugin()
  ]
}

mix.js('js/main.js', 'js/dist/app.js').sass('scss/app.scss', 'css')

if (mix.inProduction()) {
  mix.version()
}
