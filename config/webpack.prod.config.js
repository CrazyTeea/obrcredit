
const path = require('path');
const ExtractTextPlugin = require('mini-css-extract-plugin');
const webpack = require("webpack");


module.exports = {
    entry: ['@babel/polyfill', '../educational_lending/frontAssets/index.js'],
    output: {
        path: path.resolve(__dirname, '../web/build/'),
        filename: 'index.js',
        publicPath: '/build/',
    },
    mode: 'production',
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
                    },
                        'css-loader',
                        'postcss-loader'
                ],
            },
        ],
    },
    plugins: [
        new ExtractTextPlugin('styles.css'),
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


//console.log(module.exports);
//console.log(path.resolve(__dirname, '../web/build/'), '11111111');
