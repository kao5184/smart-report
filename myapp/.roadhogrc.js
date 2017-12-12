export default {
  entry : 'src/index.js',
  "theme": {
    "font-size-base": "14px",
  },
  env : {
    development: {
      extraBabelPlugins: [
        "dva-hmr",
        "transform-runtime",
        ["import", { "libraryName": "antd", "libraryDirectory": "es", "style": true }]
      ]
    },
    production: {
      extraBabelPlugins: [
        "transform-runtime",
        ["import", { "libraryName": "antd", "libraryDirectory": "es", "style": true }]
      ]
    }
  }
}
