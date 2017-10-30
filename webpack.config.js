// Launch
// ./node_modules/.bin/webpack --watch
const path = require('path');
const glob = require('glob');

module.exports = {
  entry: {
    'react-modules': glob.sync('./web/assets/react/*.jsx'),
  },
  output: {
    path: path.resolve('./web/assets/public'),
    filename: 'bundle.js'
  },
  module: {
    loaders: [
      { test: /\.js$/, loader: 'babel-loader', exclude: /node_modules/ },
      { test: /\.jsx$/, loader: 'babel-loader', exclude: /node_modules/ }
    ]
  }
}
