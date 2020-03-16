const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

//console.log(path.resolve(__dirname, '../web/build/'), '11111111');
module.exports = {
    entry: ['@babel/polyfill', '../credit/frontAssets/index.js'],
    output: {
        path: path.resolve(__dirname, '../web/build/'),
        filename: 'index.js',
        publicPath: `/build/`,
    },
    mode: "development",
    devtool: "source-map",
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
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: [
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
                }),
            },
        ],
    },

    plugins: [
        new ExtractTextPlugin('styles.css'),
        new BrowserSyncPlugin({
            // browse to http://localhost:3000/ during development,
            // ./public directory is being served
            host: 'localhost',
            port: 3000,
            proxy: 'http://localhost:8080/',
        })
    ],

    target: "web",
    resolve: {
        extensions: [
            '.css',
            '.js',
            '.jsx',
            '.scss',
        ],
    },
};
