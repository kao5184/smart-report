import React from 'react';
import { connect } from 'dva';
import styles from '../Report.css';

function Report() {
  return (
    <div className={styles.normal}>
      Route Component: Report
    </div>
  );
}

function mapStateToProps() {
  return {};
}

export default connect(mapStateToProps)(Report);
