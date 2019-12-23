
const path = require('path');
const ExtractTextPlugin = require('mini-css-extract-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

module.exports = {
    entry: ['@babel/polyfill', '../educational_lending/frontAssets/index.js'],
    output: {
        path: path.resolve(__dirname, '../web/build/'),
        filename: 'index.js',
        publicPath: '/educational_lending/web/build',
    },
    mode: 'development',
    module: {
        rules: [
            {
                test: /\.js/,
                exclude: /(node_modules|bower_components)/,
                use: [{
                    loader: 'babel-loader',
                }],
            },
            {
                test: /\.scss$/,

                use: [
                    {
                        loader: ExtractTextPlugin.loader,
                        options: {
                            // only enable hot in development
                            hmr: true,
                            // if hmr does not work, this is a forceful method.
                            reloadAll: true,
                        },
                    },
                    {
                        loader: 'css-loader',
                        options: {
                            importLoaders: 1,
                            sourceMap: true,
                        },
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            sourceMap: true,
                            config: {
                                path: 'postcss.config.js',
                            },
                        },
                    },
                ],
            },
        ],
    },
    devServer: {

        disableHostCheck: false,
        port: 5000,
        hot:true,
        liveReload: true,
        proxy: {
            '**': {
                target: 'http://localhost/',
            },
        },
    },
    plugins: [
        new ExtractTextPlugin('styles.css'),
        new BrowserSyncPlugin({
            // browse to http://localhost:3000/ during development,
            // ./public directory is being served
            host: 'localhost',
            port: 5000,
            server: {
                baseDir: ['web','frontAssets'],
            }
        })
       // new webpack.NamedModulesPlugin(),
      //  new webpack.HotModuleReplacementPlugin()
    ],
    resolve: {
        extensions: [
            '.css',
            '.js',
            '.jsx',
            '.scss',
        ],
    },
};


console.log(module.exports);
//console.log(path.resolve(__dirname, '../web/build/'), '11111111');
