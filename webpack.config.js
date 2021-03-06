const path = require('path');

const isProduction = process.env.NODE_ENV === 'production';

const config = {
  entry: {
    'gutenberg-sidebar-lock': path.resolve(__dirname, 'src/js/gutenberg-sidebar-lock.jsx'),
    'admin-keys-editor': path.resolve(__dirname, 'src/js/admin-keys-editor.jsx'),
    'admin-locks-editor': path.resolve(__dirname, 'src/js/admin-locks-editor.jsx'),
    'edit-profile-keys-editor': path.resolve(__dirname, 'src/js/edit-profile-keys-editor.jsx'),
  },

  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'dist/js'),
    publicPath: path.resolve(__dirname, 'dist/js'),
  },

  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        loader: 'babel-loader',
      },
      {
        test: /\.s[ac]ss$/i,
        use: [
          // Creates `style` nodes from JS strings
          'style-loader',
          // Translates CSS into CommonJS
          'css-loader',
          // Compiles Sass to CSS
          'sass-loader',
        ],
      },
    ],
  },

  externals: {
    // Use WordPress-provided React
    react: 'React',
    'react-dom': 'ReactDOM',
  },

  resolve: {
    extensions: ['.js', '.jsx'],
  },

  mode: isProduction ? 'production' : 'development',
  target: 'browserslist',
  devtool: isProduction ? false : 'inline-source-map',
};

module.exports = config;
