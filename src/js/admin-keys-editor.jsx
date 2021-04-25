import axios from 'axios';
import qs from 'qs';

import EditorTable from './components/EditorTable';
import KeyIcon from './components/icons/Key';

const { useState, useEffect, Fragment } = wp.element;

const KeysEditor = () => {
  const [isLoading, setIsLoading] = useState(true);
  const [keys, setKeys] = useState([]);

  useEffect(() => {
    (async () => {
      const result = await axios.post(
        wp.ajax.settings.url,
        qs.stringify({ action: 'sftk_mmbrs_get_keys' })
      );
      setKeys(result.data.keys);
      setIsLoading(false);
    })();
  }, []);

  const columnsOrder = ['name', 'label', 'description', 'starts_offset', 'ends_offset'];
  const columnLabels = ['ID', '名前', '一言', '入手遅れ期間', '期間'];
  const numberColumns = ['starts_offset', 'ends_offset'];

  return (
    <EditorTable
      title={<Fragment>カギの設定 <KeyIcon /></Fragment>}
      description="このテーブルではカギの設定や追加ができます。（追加するのはプロ版のみです）"
      columnsOrder={columnsOrder}
      columnLabels={columnLabels}
      numberColumns={numberColumns}
      isLoading={isLoading}
      rows={keys}
    />
  );
}

ReactDOM.render(
  <KeysEditor />,
  document.getElementById('sftk_mmbrs_keys_editor')
);
