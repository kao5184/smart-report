import { message } from 'antd'

export default function toast(error) {
  if (error.status) {
    error = error.data.errors
  }

  let text = ''
  if (Array.isArray(error)) {
    text = error.map(v => v.message || v.code || v).join('、')
  }
  if (text) {
    message.error(text)
  } else {
    message.error('出错了')
  }
}
