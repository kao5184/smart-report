import React from 'react'
import PropTypes from 'prop-types'
import { debounce } from 'lodash'
import { Form, Icon, Input, Row, Radio } from 'antd'
import style from '../Index.less'

const RadioGroup = Radio.Group
const FormItem = Form.Item

class ChartSetting extends React.Component {
  constructor(props) {
    super(props)
    this.emit = debounce(this.emit, 300)
  }
  state = {
    loading: false,
    settingVisible: false,
    form: {
      graphics: 'bar',
      cols: [],
      x1: {},
      y1: {},
      y2: {},
      group: {},
      donut: {},
      map: {},
      margin: {},
    },
  }
  componentDidMount = () => {
    if (typeof this.props.data === 'object') {
      this.setState({
        form: Object.assign(this.state.form, this.props.data),
      })
    }
  }
  handleChange = (key, value, index) => {
    if (key === 'graphics') {
      this.state.form = {
        graphics: value,
        x1: {},
        cols: [],
        y1: {},
        y2: {},
        group: {},
        donut: {},
        map: {},
        margin: {},
      }
      if (value === 'table') {
        this.state.form.cols = [
          { source: '', alias: '' },
        ]
      }
    } else if (key === 'cols') {
      this.state.form.cols[index] = Object.assign(this.state.form.cols[index], value)
    } else {
      if (Array.isArray(this.state.form[key])) {
        this.state.form[key] = {}
      }
      this.state.form[key] = Object.assign(this.state.form[key], value)
    }
    this.setState(this.state)
    this.emit()
  }
  addCol = () => {
    this.state.form.cols.push({ source: '', alias: '' })
    this.setState(this.state)
  }
  removeCol = (index) => {
    this.state.form.cols.splice(index, 1)
    this.setState(this.state)
  }
  emit = () => {
    this.props.onChange && this.props.onChange(this.state.form)
  }
  render() {
    const { form } = this.state
    const formItemLayout = {
      labelCol: { span: 4 },
      wrapperCol: { span: 18 },
    }
    let setting = []
    let y2 = null
    let donut = null
    let map = null
    let tableAlias = null
    let table = null
    const x1 = (
      <FormItem key="x1" {...formItemLayout} label="x1">
        <div className="input-group">
          <div><label> source: </label><Input value={form.x1.source} onChange={e => this.handleChange('x1', { source: e.target.value })} /></div>
          <div><label> alias: </label><Input value={form.x1.alias} onChange={e => this.handleChange('x1', { alias: e.target.value })} /></div>
          <div><label> min: </label><Input value={form.x1.min} onChange={e => this.handleChange('x1', { min: e.target.value })} /></div>
          <div><label> max: </label><Input value={form.x1.max} onChange={e => this.handleChange('x1', { max: e.target.value })} /></div>
        </div>
      </FormItem>
    )
    const y1 = (
      <FormItem key="y1" {...formItemLayout} label="y1">
        <div className="input-group">
          <div><label> source: </label><Input value={form.y1.source} onChange={e => this.handleChange('y1', { source: e.target.value })} /></div>
          <div><label> alias: </label><Input value={form.y1.alias} onChange={e => this.handleChange('y1', { alias: e.target.value })} /></div>
          <div><label> min: </label><Input value={form.y1.min} onChange={e => this.handleChange('y1', { min: e.target.value })} /></div>
          <div><label> max: </label><Input value={form.y1.max} onChange={e => this.handleChange('y1', { max: e.target.value })} /></div>
          <div><label> width(px): </label><Input value={form.y1.width} onChange={e => this.handleChange('y1', { width: e.target.value })} /></div>
        </div>
      </FormItem>
    )
    const group = (
      <FormItem key="group" {...formItemLayout} label="group">
        <div className="input-group">
          <div><label> dodge: </label><Input value={form.group.dodge} onChange={e => this.handleChange('group', { dodge: e.target.value })} /></div>
          <div><label> stack: </label><Input value={form.group.stack} onChange={e => this.handleChange('group', { stack: e.target.value })} /></div>
        </div>
      </FormItem>
    )
    const margin = (
      <FormItem key="margin" {...formItemLayout} label="margin">
        <div className="input-group">
          <div><label> top: </label><Input value={form.margin.top} onChange={e => this.handleChange('margin', { top: e.target.value })} /></div>
          <div><label> bottom: </label><Input value={form.margin.bottom} onChange={e => this.handleChange('margin', { bottom: e.target.value })} /></div>
          <div><label> left: </label><Input value={form.margin.left} onChange={e => this.handleChange('margin', { left: e.target.value })} /></div>
          <div><label> right: </label><Input value={form.margin.right} onChange={e => this.handleChange('margin', { right: e.target.value })} /></div>
        </div>
      </FormItem>
    )
    switch (form.graphics) {
      case 'table':
        tableAlias = form.cols.map((v, i) => {
          return (
            <div className="input-group" key={`cols${i}`}>
              <label>source:</label>
              <Input value={v.source} onChange={e => this.handleChange('cols', { source: e.target.value }, i)} />
              <label>alias:</label>
              <Input value={v.alias} onChange={e => this.handleChange('cols', { alias: e.target.value }, i)} />
              <a onClick={e => this.removeCol(i)}><Icon type="close" /></a>
            </div>
          )
        })
        tableAlias.push(
          <div className="input-group" key="col-option">
            <a onClick={this.addCol}><Icon type="plus" /></a>
          </div>,
        )
        table = (
          <FormItem key="colums" {...formItemLayout} label="colums">
            { tableAlias }
          </FormItem>
        )
        setting = [table]
        break
      case 'bar':
      case 'line':
      default:
        setting = [x1, y1, group, margin]
        break
      case 'line-bar':
        y2 = (
          <FormItem key="y2" {...formItemLayout} label="y2">
            <div className="input-group">
              <div><label> source: </label><Input value={form.y2.source} onChange={e => this.handleChange('y2', { source: e.target.value })} /></div>
              <div><label> alias: </label><Input value={form.y2.alias} onChange={e => this.handleChange('y2', { alias: e.target.value })} /></div>
              <div><label> min: </label><Input value={form.y2.min} onChange={e => this.handleChange('y2', { min: e.target.value })} /></div>
              <div><label> max: </label><Input value={form.y2.max} onChange={e => this.handleChange('y2', { max: e.target.value })} /></div>
              <div><label> width(px): </label><Input value={form.y2.width} onChange={e => this.handleChange('y2', { width: e.target.value })} /></div>
            </div>
          </FormItem>
        )
        setting = [x1, y1, y2, group, margin]
        break
      case 'donut':
        donut = (
          <FormItem key="source" {...formItemLayout} label="source">
            <div className="input-group">
              <Input value={form.donut.source} onChange={e => this.handleChange('donut', { source: e.target.value })} />
              <label>dodge:</label>
              <Input value={form.donut.dodge} onChange={e => this.handleChange('donut', { dodge: e.target.value })} />
            </div>
          </FormItem>
        )
        setting = [donut, margin]
        break
      case 'map-province':
        map = (
          <FormItem key="source" {...formItemLayout} label="source">
            <div className="input-group">
              <Input value={form.map.source} onChange={e => this.handleChange('map', { source: e.target.value })} />
              <label>dodge:</label>
              <Input value={form.map.dodge} onChange={e => this.handleChange('map', { dodge: e.target.value })} />
            </div>
          </FormItem>
        )
        setting = [map, margin]
        break
    }
    return (
      <Row>
        <FormItem {...formItemLayout} label="图表类型">
          <RadioGroup value={form.graphics} onChange={e => this.handleChange('graphics', e.target.value)}>
            <Radio value="table">表格</Radio>
            <Radio value="bar">柱状图</Radio>
            <Radio value="line">折线图</Radio>
            <Radio value="line-bar">折线柱状图</Radio>
            <Radio value="donut">圆环图</Radio>
            <Radio value="map-province">中国省份地图</Radio>
          </RadioGroup>
        </FormItem>
        { setting }
      </Row>
    )
  }
}
ChartSetting.propTypes = {
  data: PropTypes.any,
  onChange: PropTypes.func,
}

export default ChartSetting
