import axios from 'axios';
import qs from 'qs';
import queryString from 'query-string';

import EditorTable from './components/EditorTable';
import KeyIcon from './components/icons/Key';

const { useState, useEffect, Fragment } = wp.element;

const KeysEditor = () => {
  const [isLoading, setIsLoading] = useState(true);
  const [keys, setKeys] = useState([]);
  const [nonce, setNonce] = useState(null);
  const userId = queryString.parse(window.location.search).user_id;

  useEffect(() => {
    (async () => {
      const result = await axios.post(
        wp.ajax.settings.url,
        qs.stringify({
          action: 'sftk_mmbrs_edit_profile_get_user_keys',
          user_id: userId,
        })
      );
      setKeys(result.data.data);
      setNonce(result.data.set_key_access_nonce);
      setIsLoading(false);
    })()
  }, []);

  const columnsOrder = ['name', 'label', 'description'];
  const columnLabels = ['ID', '名前', '一言'];

  return (
    <EditorTable
      title={<Fragment>メンバーズ　カギの付与 <KeyIcon /></Fragment>}
      description="このテーブルではカギを付与するか否かが可能です。"
      columnsOrder={columnsOrder}
      columnLabels={columnLabels}
      isLoading={isLoading}
      rows={keys}
      nonce={nonce}
      userId={userId}
    />
  );
}

ReactDOM.render(
  <KeysEditor />,
  document.getElementById('sftk_mmbrs_edit_profile_keys_editor')
);
