import axios from 'axios';
import qs from 'qs';

import EditorTable from './components/EditorTable';
import LockIcon from './components/icons/Lock';

const { useState, useEffect, Fragment } = wp.element;

const KeysEditor = () => {
  const [isLoading, setIsLoading] = useState(true);
  const [locks, setLocks] = useState([]);

  useEffect(() => {
    (async () => {
      const result = await axios.post(
        wp.ajax.settings.url,
        qs.stringify({ action: 'sftk_mmbrs_get_locks' })
      );
      setLocks(result.data.locks);
      setIsLoading(false);
    })();
  }, []);

  const columnsOrder = ['name', 'label', 'behavior'];
  const columnLabels = ['ID', '名前', 'ブロック行動'];
  const numberColumns = [];

  return (
    <EditorTable
      title={<Fragment>ロックの設定 <LockIcon /></Fragment>}
      description="このテーブルではロックの設定や追加ができます。（追加するのはプロ版のみです）"
      columnsOrder={columnsOrder}
      columnLabels={columnLabels}
      numberColumns={numberColumns}
      isLoading={isLoading}
      rows={locks}
    />
  );
}

ReactDOM.render(
  <KeysEditor />,
  document.getElementById('sftk_mmbrs_locks_editor')
);
