import React from 'react'
import axios from 'axios'
import PropTypes from 'prop-types'
import { Modal, Form, Button, Input, message, Spin } from 'antd'
import style from '../Index.less'

const { TextArea } = Input
const FormItem = Form.Item

class TemplateModal extends React.Component {
  state = {
    loading: false,
    settingVisible: true,
    settings: {},
  }
  handleSubmit = (e) => {
    e.preventDefault()
    this.props.form.validateFields((err, values) => {
      if (!err) {
        const data = this.props.data || {}
        // 编辑
        if (data.id > 0) {
          axios.put(`/reporter/page/${data.id}`, values).then((res) => {
            message.success('修改成功')
          }).then(() => {
            this.handleClose()
          })
        } else {
          axios.post('/reporter/page', values).then((res) => {
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
    const title = data.id ? '编辑模板' : '新增模板'
    const { getFieldDecorator } = this.props.form
    const formItemLayout = {
      labelCol: { span: 4 },
      wrapperCol: { span: 18 },
    }
    return (
      <Modal
        className="add-modal"
        width={700}
        title={title}
        visible={this.props.visible}
        footer={null}
        onCancel={this.handleClose}
      >
        <Spin spinning={this.state.loading} className="sping">
          <Form onSubmit={this.handleSubmit}>
            <FormItem {...formItemLayout} label="Title">
              {getFieldDecorator('title', {
                rules: [{ required: true }],
              })(
                <Input />,
              )}
            </FormItem>
            <FormItem {...formItemLayout} label="Description">
              {getFieldDecorator('description', {
                rules: [],
              })(
                <TextArea autosize={{ minRows: 2, maxRows: 5 }} />,
              )}
            </FormItem>
            <FormItem wrapperCol={{ span: 8, offset: 4 }}>
              <Button type="primary" htmlType="submit"> 提交 </Button>
            </FormItem>
          </Form>
        </Spin>
      </Modal>
    )
  }
}
TemplateModal.propTypes = {
  visible: PropTypes.bool,
  data: PropTypes.object,
  form: PropTypes.object,
  close: PropTypes.func,
  onChange: PropTypes.func,
}

const _TemplateModal = Form.create({
  mapPropsToFields(props) {
    return {
      title: Form.createFormField({
        value: props.data.title,
      }),
      description: Form.createFormField({
        value: props.data.description,
      }),
    }
  },
})(TemplateModal)

export default _TemplateModal
