module.exports = (webpackConfig, env) => {
  // alias
  webpackConfig.resolve.alias = {
    components: `${__dirname}/src/components`,
    assets: `${__dirname}/assets`,
    utils: `${__dirname}/src/utils`,
    services: `${__dirname}/src/services`,
    models: `${__dirname}/src/models`,
    routes: `${__dirname}/src/routes`,
  };
  return webpackConfig;
};
