const path = require('path');

const isProduction = process.env.ENV === 'production';

const config = {
  entry: {
    'gutenberg-sidebar-lock': path.resolve(__dirname, 'src/js/gutenberg-sidebar-lock.jsx'),
  },

  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'assets/js'),
    publicPath: path.resolve(__dirname, 'assets/js'),
  },

  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        loader: 'babel-loader',
      },
    ],
  },

  externals: {
    // Use WordPress-provided React
    react: 'React',
  },

  mode: isProduction ? 'production' : 'development',
  target: 'browserslist',
  devtool: isProduction ? null : 'inline-source-map',
};

module.exports = config;
