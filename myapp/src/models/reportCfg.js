import pathToRegexp from 'path-to-regexp'
import { setTitle } from 'utils/helper.js'
import * as service from 'services/report.js'

export default {
  namespace: 'reportCfg',
  state: {
    row: 1,
    col: 1,
    updates: 0,
    data: {},
    pages: [],
    curIndex: null,
    rows: [],
  },
  subscriptions: {
    setup({ dispatch, history }) {
      return history.listen(({ pathname, query }) => {
        const match = pathToRegexp('/setting/report/:id').exec(pathname)
        if (match) {
          dispatch({ type: 'fetch', payload: match[1] })
        }
      })
    },
  },
  effects: {
    * fetch({ payload: id }, { call, put }) {
      const data = yield call(service.fetch, id)
      setTitle(`${data.title} 报表配置`)
      yield put({
        type: 'saveData',
        payload: { data },
      })
    },
    * updateReport({ payload: data }, { call, put, select }) {
      yield call(service.update, data)
      const _data = yield call(service.fetch, data.id)
      yield put({
        type: 'saveData',
        payload: { data: _data },
      })
    },
    * updateLayout({ payload: { row, col } }, { call, put }) {
      yield put({
        type: 'saveLayout',
        payload: {
          row,
          col,
        },
      })
    },
    * storeData({ payload: { data } }, { call, put }) {
      yield put({
        type: 'saveData',
        payload: { data },
      })
    },
    * updatePageIndex({ payload: { curIndex } }, { call, put }) {
      yield put({
        type: 'saveIndex',
        payload: { curIndex },
      })
    },
    * storeRows({ payload: { rows } }, { put }) {
      yield put({
        type: 'saveRows',
        payload: { rows },
      })
    },
  },
  // reducer 是一个函数，接受 state 和 action，返回老的或新的 state
  reducers: {
    saveData(state, { payload: { data } }) {
      state.pages = data.pages
      const page = state.pages[state.curIndex]
      if (page && page.settings) {
        state.row = page.settings.row
        state.col = page.settings.col
        state.rows = page.settings.rows
      }
      state.updates += 1
      return { ...state, data }
    },
    saveLayout(state, { payload: { row, col } }) {
      for (let i = 0; i < row; i += 1) {
        if (!state.rows[i]) {
          state.rows[i] = { cols: [] }
        }
        for (let m = 0; m < col; m += 1) {
          if (!state.rows[i].cols[m]) {
            state.rows[i].cols[m] = { type: null }
          }
        }
      }
      return { ...state, row, col }
    },
    saveIndex(state, { payload: { curIndex } }) {
      if (state.pages[curIndex] && state.pages[curIndex].settings) {
        state.row = state.pages[curIndex].settings.row || 1
        state.col = state.pages[curIndex].settings.col || 1
        state.rows = state.pages[curIndex].settings.rows || [{
          cols: [{ type: null }],
        }]
      } else {
        state.rows = [{ cols: [{ type: null }] }]
      }
      state.updates += 1
      return { ...state, curIndex }
    },
    saveRows(state, { payload: { rows } }) {
      return { ...state, rows }
    },
  },
}
