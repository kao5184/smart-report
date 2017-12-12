import React from 'react'
import { connect } from 'dva'
import PropTypes from 'prop-types'
import { Layout, Button, Spin, Tabs } from 'antd'
import SourceGrid from './Grid/Source.js'
import TplCfg from './Grid/Template.js'
import ReportGrid from './Grid/Report.js'
import './Index.less'

const TabPane = Tabs.TabPane
const { Content } = Layout

class ReportSetting extends React.Component {
  state = {
    loading: false,
    tabs: [
      { tab: 'report', title: '报告配置' },
      { tab: 'template', title: '模板配置' },
      { tab: 'source', title: '数据元' },
    ],
    tab: 'report',
  }
  onTabChange = (tab) => {
    this.setState({ tab })
  }
  onModal = (tab, modalVisible) => {
    this.onTabChange(tab)
    this.props.dispatch({
      type: 'report/onModal',
      payload: {
        modalVisible,
      },
    })
  }
  render() {
    const tabs = this.state.tabs.map(v => <TabPane tab={v.title} key={v.tab} />)
    const extra = (
      <div>
        <Button type="primary" style={{ margin: '10px 24px 0 0' }} onClick={() => this.onModal('report', { report: true }, {})}>新增报告</Button>
        <Button type="primary" style={{ margin: '10px 24px 0 0' }} onClick={() => this.onModal('template', { template: true }, {})}>新增模板</Button>
        <Button type="primary" style={{ margin: '10px 24px 0 0' }} onClick={() => this.onModal('source', { source: true }, true, {})}>新增数据元</Button>
      </div>
    )
    let grid
    switch (this.state.tab) {
      case 'report':
      default:
        grid = (
          <ReportGrid
            ref={(ref) => { this.tplRef = ref }}
            location={this.props.location}
          />
        )
        break
      case 'template':
        grid = (
          <TplCfg
            ref={(ref) => { this.tplRef = ref }}
            location={this.props.location}
          />
        )
        break
      case 'source':
        grid = (
          <SourceGrid
            ref={(ref) => { this.sourceRef = ref }}
            location={this.props.location}
          />
        )
        break
    }
    return (
      <Content className="icrs-washer-index">
        <Spin spinning={this.state.loading} size="large">
          <Tabs
            className="tabs-no-border"
            activeKey={this.state.tab}
            onChange={this.onTabChange}
            style={{ backgroundColor: '#fff' }}
            tabBarExtraContent={extra}
          >
            { tabs }
          </Tabs>
          <div style={{ padding: 24, background: '#fff' }}>
            { grid }
          </div>
        </Spin>
      </Content>
    )
  }
}

ReportSetting.propTypes = {
  dispatch: PropTypes.func,
  location: PropTypes.object,
}

export default connect()(ReportSetting)
