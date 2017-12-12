import React from 'react'
import PropTypes from 'prop-types'
import { Route, Switch, routerRedux } from 'dva/router'
import dynamic from 'dva/dynamic'
import App from './routes/App'

const { ConnectedRouter } = routerRedux

const Routers = ({ history, app }) => {
  const routes = [
    {
      path: '/report/show',
      component: () => import('./routes/Report/Show/Index'),
    },
    // {
    //   path: '/dashboard/report/:id',
    //   component: () => import('./routes/Report/reports/detail'),
    // },
    // {
    //   path: '/demo/report/:id',
    //   component: () => import('./routes/Report/reports/demo'),
    // },
    {
      path: '/report/config',
      models: () => [import('./models/report')],
      component: () => import('./routes/Report/Config/List/Index'),
    },
    // {
    //   path: '/report/config/:id',
    //   models: () => [import('./models/reportCfg')],
    //   component: () => import('./routes/Report/Config/reportCfg/'),
    // },
  ]
  return (
    <ConnectedRouter history={history}>
      <App>
        <Switch>
          {
            routes.map(({ path, ...dynamics }, key) => (
              <Route
                key={key}
                exact
                path={path}
                component={dynamic({
                  app,
                  ...dynamics,
                })}
              />
            ))
          }
        </Switch>
      </App>
    </ConnectedRouter>
  )
}

Routers.propTypes = {
  history: PropTypes.object,
  app: PropTypes.object,
}

export default Routers
