import React from 'react';
import { Router, Route, Switch } from 'dva/router';
import IndexPage from './routes/IndexPage';

import Report from './routes/Report/Show/Index.js';

function RouterConfig({ history }) {
  return (
    <Router history={history}>
      <Switch>
        <Route path="/" exact component={IndexPage} />
      </Switch>
      <Route path="/report" component={Report} />
    </Router>
  );
}

export default RouterConfig;
