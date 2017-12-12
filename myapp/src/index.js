import dva from 'dva'
import createHistory from 'history/createHashHistory'
import request from 'utils/request.js'
import toast from 'utils/toast.js'
import './index.css'

// 1. Initialize
const app = dva({
  history: createHistory(),
  onError(error) {
    toast(error)
  },
})

// 2. Plugins
// app.use({});

// 3. Model
// app.model(require('./models/example'));

// 4. Router
app.router(require('./router'))

// 5. Start
app.start('#root')
