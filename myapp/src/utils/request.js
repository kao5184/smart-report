import axios from 'axios'
import toast from 'utils/toast'

axios.defaults.baseURL = 'http://localhost:8888'

axios.interceptors.response.use((res) => {
  return res.data
}, (res) => {
  toast(res.response)
  return Promise.reject(res.response)
})
