export default {
  namespace: 'report',
  state: {
    modalVisible: {
      report: false,
      template: false,
      source: false,
    },
    formData: {},
  },
  subscriptions: {
  },
  effects: {
    * onModal({ payload: { modalVisible, formData } }, { call, put }) {
      yield put({
        type: 'saveModal',
        payload: {
          modalVisible,
          formData,
        },
      })
    },
  },
  // reducer 是一个函数，接受 state 和 action，返回老的或新的 state
  reducers: {
    saveModal(state, { payload: { modalVisible, formData } }) {
      return { ...state, modalVisible, formData }
    },
  },
}
