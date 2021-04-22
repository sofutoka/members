import axios from 'axios';
import qs from 'qs';
import PropTypes from 'prop-types';

const { Component } = wp.element;

class CheckboxWithNetwork extends Component {
  constructor(props) {
    super(props);

    this.state = {
      isChecked: props.initialIsChecked,
    };

    this.handleChange = this.handleChange.bind(this);
  }

  handleChange(e) {
    e.persist();

    const newState = e.target.checked;

    // 前向きな感じでチェックしたかどうかを表示する
    // あとから事実が異なったらまた変える
    this.setState({ isChecked: newState });

    axios.post(wp.ajax.settings.url, qs.stringify({
      action: this.props.wpAction,
      has_key: newState,
      user_id: this.props.userId,
      // 例key_id: dataId
      [this.props.dataKeyName]: this.props.dataId,
      nonce: this.props.nonce,
    }))
      .then(res => {
        if (res.data.has_key !== newState) {
          this.setState({ isChecked: res.data.has_key });
        }
      });
  }

  render() {
    return (
      <input
        type="checkbox"
        checked={this.state.isChecked}
        onChange={this.handleChange}
      />
    );
  }
}

CheckboxWithNetwork.propTypes = {
  initialIsChecked: PropTypes.bool.isRequired,
  wpAction: PropTypes.string.isRequired,
  userId: PropTypes.string.isRequired,
  dataKeyName: PropTypes.string.isRequired,
  dataId: PropTypes.string.isRequired,
  nonce: PropTypes.string.isRequired,
};

export default CheckboxWithNetwork;
