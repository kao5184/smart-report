import React from 'react'
import axios from 'axios'
import PropTypes from 'prop-types'
import { Modal, Form, Button, Input, message, Spin, Row, Col, Radio } from 'antd'
import style from '../Index.less'
import ChartSetting from './ChartSetting.js'

const { TextArea } = Input
const RadioGroup = Radio.Group
const FormItem = Form.Item

class SourceModal extends React.Component {
  state = {
    loading: false,
    settingVisible: true,
    settings: {},
  }
  componentWillReceiveProps = (nextProps) => {
    if (typeof nextProps.data === 'object' && nextProps.data.settings) {
      this.state.settings = nextProps.data.settings
    }
  }
  handleSubmit = (e) => {
    e.preventDefault()
    this.props.form.validateFields((err, values) => {
      if (!err) {
        const data = this.props.data || {}
        values.settings = this.state.settings
        // 编辑
        if (data.id > 0) {
          axios.put(`/reporter/source/${data.id}`, values).then((res) => {
            message.success('修改成功')
          }).then(() => {
            this.handleClose()
          })
        } else {
          axios.post('/reporter/source', values).then((res) => {
            message.success('创建成功')
          }).then(() => {
            this.handleClose()
          })
        }
      }
    })
  }
  handleClose = () => {
    this.props.close && this.props.close()
    this.props.onChange && this.props.onChange()
    this.props.form.resetFields()
  }
  handleTypeChange = (e) => {
    if (e.target.value === 'chart') {
      this.setState({ settingVisible: true })
    } else {
      this.setState({ settingVisible: false })
    }
  }
  handleStChange = (val) => {
    this.state.settings = val
  }
  render() {
    let { data } = this.props
    data = data || {}
    const title = data.id ? '编辑数据元' : '新增数据元'
    const { getFieldDecorator, getFieldValue } = this.props.form
    const formItemLayout = {
      labelCol: { span: 4 },
      wrapperCol: { span: 18 },
    }
    const halfFormItemLayout = {
      labelCol: { span: 8 },
      wrapperCol: { span: 14 },
    }
    let chartSetting = null
    if (getFieldValue('type') === 'chart') {
      chartSetting = <ChartSetting data={data.settings} onChange={this.handleStChange} />
    }
    return (
      <Modal
        className="add-modal"
        width={1200}
        title={title}
        visible={this.props.visible}
        footer={null}
        onCancel={this.handleClose}
      >
        <Spin spinning={this.state.loading} className="sping">
          <Form onSubmit={this.handleSubmit}>
            <FormItem {...formItemLayout} label="Key">
              {getFieldDecorator('key', {
                rules: [{ required: true }],
              })(
                <Input />,
              )}
            </FormItem>
            <FormItem {...formItemLayout} label="Title">
              {getFieldDecorator('title', {
                rules: [{ required: true }],
              })(
                <Input />,
              )}
            </FormItem>
            <FormItem {...formItemLayout} label="Value">
              {getFieldDecorator('value', {
                rules: [{ required: true }],
              })(
                <TextArea autosize={{ minRows: 2, maxRows: 10 }} />,
              )}
            </FormItem>
            <Row>
              <Col span={12}>
                <FormItem {...halfFormItemLayout} label="Type">
                  {getFieldDecorator('type', {
                    rules: [{ required: true }],
                  })(
                    <RadioGroup onChange={this.handleTypeChange}>
                      <Radio value="chart">图表</Radio>
                      <Radio value="script">脚本</Radio>
                      <Radio value="text">文本</Radio>
                    </RadioGroup>,
                  )}
                </FormItem>
              </Col>
              <Col span={12}>
                <FormItem {...halfFormItemLayout} label="是否缓存">
                  {getFieldDecorator('cache', {
                    rules: [],
                    initialValue: 0,
                  })(
                    <RadioGroup>
                      <Radio value={1}>是</Radio>
                      <Radio value={0}>否</Radio>
                    </RadioGroup>,
                  )}
                </FormItem>
              </Col>
            </Row>
            { chartSetting }
            <FormItem wrapperCol={{ span: 8, offset: 4 }}>
              <Button type="primary" htmlType="submit"> 提交 </Button>
            </FormItem>
          </Form>
        </Spin>
      </Modal>
    )
  }
}
SourceModal.propTypes = {
  visible: PropTypes.bool,
  data: PropTypes.object,
  form: PropTypes.object,
  close: PropTypes.func,
  onChange: PropTypes.func,
}

const _SourceModal = Form.create({
  mapPropsToFields(props) {
    return {
      key: Form.createFormField({
        value: props.data.key,
      }),
      title: Form.createFormField({
        value: props.data.title,
      }),
      type: Form.createFormField({
        value: props.data.type,
      }),
      value: Form.createFormField({
        value: props.data.value,
      }),
      cache: Form.createFormField({
        value: props.data.cache === 1 ? 1 : 0,
      }),
    }
  },
})(SourceModal)

export default _SourceModal
