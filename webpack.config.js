const path = require('path');

module.exports = {
    entry: {
        editor: './assets/js/editor.js',
        frontend: './assets/js/frontend.js'
    },
    output: {
        path: path.resolve(__dirname, 'assets/build'),
        filename: '[name].js'
    },
    module: {
        rules: [
            {
                test: /\.css$/i,
                type: 'asset/source'
            }
        ]
    }
};