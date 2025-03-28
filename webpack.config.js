const path = require('path');

module.exports = {
  mode: 'development',
  entry: {
    main: './assets/js/src/main.ts',
    styles: './assets/css/styles.css',
  },
  output: {
    filename: '[name].bundle.js',
    path: path.resolve(__dirname, 'assets/dist'),
  },
  module: {
    rules: [
      {
        test: /\.ts$/,
        use: 'ts-loader',
        exclude: /node_modules/,
      },
      {
        test: /\.css$/,
        use: [
          'style-loader',
          'css-loader',
          'postcss-loader',
        ],
      },
    ],
  },
  resolve: {
    extensions: ['.ts', '.js'],
  },
}; 