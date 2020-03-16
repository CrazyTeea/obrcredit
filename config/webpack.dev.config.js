
const path = require('path');
const ExtractTextPlugin = require('mini-css-extract-plugin');

module.exports = {
    entry: ['@babel/polyfill', '../credit/frontAssets/index.js'],
    output: {
        path: path.resolve(__dirname, '../web/build/'),
        filename: 'index.js',
        publicPath: '/build/',
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


//console.log(path.resolve(__dirname, '../web/build/'), '11111111');
