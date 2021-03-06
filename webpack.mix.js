const path = require('path')
const fs = require('fs-extra')
const mix = require('laravel-mix')
require('laravel-mix-versionhash')
// const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer')

mix
  .js('resources/js/main.js', 'public/dist/js')
  .sass('resources/js/assets/scss/app.scss', 'public/dist/css')

  .disableNotifications()

if (mix.inProduction()) {
  mix
    // .extract() // Disabled until resolved: https://github.com/JeffreyWay/laravel-mix/issues/1889
    // .version() // Use `laravel-mix-versionhash` for the generating correct Laravel Mix manifest file.
    .versionHash()
} else {
  mix.sourceMaps()
}

mix.webpackConfig({
  plugins: [
    // new BundleAnalyzerPlugin()
  ],
  resolve: {
    extensions: ['.js', '.json', '.vue'],
    alias: {
      '~': path.join(__dirname, './resources/js')
    }
  },
  output: {
    // publicPath: '/public/',
    chunkFilename: 'dist/js/[chunkhash].js',
    path: mix.config.hmr
      ? '/'
      : path.resolve(__dirname, mix.inProduction() ? './public/build' : './public')
  }
})

mix.then(() => {
  if (mix.inProduction()) {
    process.nextTick(() => publishAseets())
  }
})

mix.options({
  hmrOptions: {
    host: 'localhost',
    port: '9000'
  }
})

function publishAseets () {
  const publicDir = path.resolve(__dirname, './public')

  fs.removeSync(path.join(publicDir, 'dist'))
  fs.removeSync(path.join(publicDir, 'images'))
  fs.removeSync(path.join(publicDir, 'fonts'))
  fs.copySync(path.join(publicDir, 'build', 'dist'), path.join(publicDir, 'dist'))
  fs.copySync(path.join(publicDir, 'build', 'images'), path.join(publicDir, 'images'))
  fs.copySync(path.join(publicDir, 'build', 'fonts'), path.join(publicDir, 'fonts'))
  fs.removeSync(path.join(publicDir, 'build'))
}
