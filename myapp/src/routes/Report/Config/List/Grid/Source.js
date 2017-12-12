import React from 'react'
import { connect } from 'dva'
import PropTypes from 'prop-types'
import axios from 'axios'
import { Table, Button, message, Pagination, Popconfirm } from 'antd'
import SourceModal from '../Modal/SourceModal.js'
import '../Index.less'

class SourceGrid extends React.Component {
  constructor(props) {
    super(props)

    this.columns = [
      { title: 'ID', dataIndex: 'id', width: '5%' },
      { title: 'Key', dataIndex: 'key' },
      { title: 'Title', dataIndex: 'title' },
      { title: 'Type', dataIndex: 'type' },
      { title: 'Value', dataIndex: 'value', width: '30%' },
      { title: '是否缓存', dataIndex: 'cache' },
      { title: '缓存时间', dataIndex: 'cached_at' },
      {
        title: '操作',
        dataIndex: '',
        render: (text, record) => {
          return (
            <div className="option-group">
              <Button shape="circle" icon="edit" onClick={() => this.onModal(true, record)} />
              <Popconfirm title="确认删除这条记录吗？" onConfirm={() => this.remove(record)}>
                <Button className="btn-danger" shape="circle" icon="delete" />
              </Popconfirm>
            </div>
          )
        },
      },
    ]
  }
  state = {
    loading: false,
    addModalVisible: false,
    form: {},
    pagination: { total: 0, size: 20, page: 1 },
    query: {},
    data: [],
  }
  componentDidMount = () => {
    this.load()
  }
  onPageChange = (page, size) => {
    this.state.pagination.page = page
    this.setState(this.state)
    this.load(this.state.query)
  }
  onShowSizeChange = (current, size) => {
    this.state.pagination.size = size
    this.setState(this.state)
    this.load(this.state.query)
  }
  onChange = (v) => {
    this.state.query = Object.assign({}, this.state.query, v)
    this.setState(this.state)
  }
  onSearch = (v) => {
    this.state.pagination.page = 1
    this.setState(this.state)
    this.load(v)
  }
  onModal = (flag, form = {}) => {
    this.state.form = form
    this.props.dispatch({
      type: 'report/onModal',
      payload: {
        modalVisible: { source: flag },
      },
    })
  }
  remove = (record) => {
    if (!record.id) return
    axios.delete(`/reporter/source/${record.id}`).then((res) => {
      message.success('删除成功')
      this.load()
    })
  }
  load = (v) => {
    this.state.loading = true
    this.state.query = Object.assign({}, this.state.query, v)
    this.setState(this.state)

    const query = Object.assign({}, this.state.query, {
      limit: this.state.pagination.size,
      page: this.state.pagination.page,
    })
    axios.get('/reporter/source', {
      params: query,
    }).then((res) => {
      this.state.data = res.data.items
      this.state.pagination.total = res.data.total
    }).then((res) => {
      this.state.loading = false
      this.setState(this.state)
    })
  }
  render() {
    const columns = this.columns
    return (
      <div>
        <Table
          pagination={false}
          columns={columns}
          scroll={{ x: true }}
          dataSource={this.state.data}
          bordered
          rowKey="id"
        />
        <Pagination
          style={{ marginTop: '20px' }}
          showSizeChanger
          showQuickJumper
          total={this.state.pagination.total}
          showTotal={(total, range) => `${range[0]}-${range[1]} of ${total} items`}
          onChange={this.onPageChange}
          onShowSizeChange={this.onShowSizeChange}
          pageSize={this.state.pagination.size}
          current={this.state.pagination.page}
          defaultCurrent={1}
        />
        <SourceModal
          visible={this.props.modalVisible.source}
          data={this.state.form}
          close={() => this.onModal(false)}
          onChange={() => this.load()}
        />
      </div>
    )
  }
}

SourceGrid.propTypes = {
  dispatch: PropTypes.func,
  modalVisible: PropTypes.object,
}

const mapStateToProps = (state) => {
  const { modalVisible } = state.report
  return {
    modalVisible,
  }
}

export default connect(mapStateToProps)(SourceGrid)
