import PropTypes from 'prop-types';
import axios from 'axios';
import qs from 'qs';

const { useState, useEffect } = wp.element;
const {
  PanelRow,
  SelectControl,
} = wp.components;
const { withDispatch } = wp.data;

const Sidebar = ({ setMetaFieldValue }) => {
  const [availableLocks, setAvailableLocks] = useState([{ label: '読み込み中', value: null }]);
  const [lockId, setLockId] = useState('');

  useEffect(() => {
    const { _sftk_mmbrs_lock_id } = wp.data.select('core/editor').getEditedPostAttribute('meta');
    if (_sftk_mmbrs_lock_id != null && (_sftk_mmbrs_lock_id > 0 || _sftk_mmbrs_lock_id.length > 0)) {
      setLockId(_sftk_mmbrs_lock_id);
    }
  }, []);

  useEffect(() => {
    (async () => {
      const availableLocks = await axios.post(
        wp.ajax.settings.url,
        qs.stringify({
          action: 'sftk_mmbrs_editor_get_available_locks',
        })
      );
      let dropdownOptions = [{ label: 'ロックなし', value: '__DISABLED__' }];
      dropdownOptions = dropdownOptions.concat(
        availableLocks.data.map(({ id, label }) => ({
          label: 'ロック：' + label,
          value: id + '',
        }))
      );
      setAvailableLocks(dropdownOptions);
    })();
  }, []);

  const changeSetting = value => {
    setLockId(value);
    setMetaFieldValue('_sftk_mmbrs_lock_id', value);
  }

  return (
    <PanelRow>
      <label>
        <p>設定したいロックを選んでください</p>
        <SelectControl
          value={lockId}
          onChange={changeSetting}
          options={availableLocks}
        />
      </label>
    </PanelRow>
  );
};

Sidebar.propTypes = {
  setMetaFieldValue: PropTypes.func.isRequired,
};

export default withDispatch(dispatch => ({
  setMetaFieldValue(key, value) {
    dispatch('core/editor').editPost({ meta: { [key]: value } });
  },
}))(Sidebar);
