import React from 'react'
import { connect } from 'dva'
import { withRouter } from 'dva/router'
import PropTypes from 'prop-types'
import { LocaleProvider, Layout, Menu } from 'antd'
import zhCN from 'antd/lib/locale-provider/zh_CN'
import styles from './App.less'

const { Header, Footer, Content } = Layout

const App = ({ children, dispatch, app, loading, location }) => {
  const activeKey = location.pathname.split('/').pop()
  return (
    <LocaleProvider locale={zhCN}>
      <Layout className={styles.root}>
        <Header>
          <Menu
            theme="dark"
            mode="horizontal"
            defaultSelectedKeys={[activeKey]}
            style={{ lineHeight: '64px', paddingLeft: '40vw' }}
          >
            <Menu.Item key="show">
              <a href="/#/report/show">dashboard</a>
            </Menu.Item>
            <Menu.Item key="config">
              <a href="/#/report/config">config</a>
            </Menu.Item>
          </Menu>
        </Header>
        <Content style={{ padding: '50px 100px' }}>
          { children }
        </Content>
        <Footer style={{ textAlign: 'center' }}>
           Smart Report ©2017
        </Footer>
      </Layout>
    </LocaleProvider>
  )
}

App.propTypes = {
  children: PropTypes.element.isRequired,
  location: PropTypes.object,
  dispatch: PropTypes.func,
  app: PropTypes.object,
  loading: PropTypes.object,
}

export default withRouter(connect(({ app, loading }) => ({ app, loading }))(App))
