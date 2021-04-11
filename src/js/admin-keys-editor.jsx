import axios from 'axios';
import qs from 'qs';
import humanizeDuration from 'humanize-duration';
import _isNumber from 'lodash.isnumber';

import KeyIcon from './components/icons/Key';
import Loading from './components/Loading';

const { useState, useEffect, Fragment } = wp.element;

const KeysEditor = () => {
  const [isLoading, setIsLoading] = useState(true);
  const [keys, setLocks] = useState([]);

  useEffect(async () => {
    const result = await axios.post(
      wp.ajax.settings.url,
      qs.stringify({ action: 'sftk_mmbrs_get_keys' })
    );
    setLocks(result.data);
    setIsLoading(false);
  }, []);

  const columnsOrder = ['name', 'label', 'description', 'starts_offset', 'ends_offset'];
  const columnsText = ['ID', '名前', '一言', '入手遅れ期間', '期間'];
  const numberColumns = ['starts_offset', 'ends_offset'];

  return (
    <article className="sftk_mmbrs">
      <header className="sftk_mmbrs">
        <h2 className="sftk_mmbrs_table__header__title">
          カギの設定 <KeyIcon />
        </h2>
        <p className="sftk_mmbrs_table__header__description">
          このテーブルではカギの設定や追加ができます。
        </p>
      </header>
      <Loading message="テーブルの" isLoading={isLoading} />

      {!isLoading && (
        <table className="sftk_mmbrs">
          <div>
            <thead>
            <tr>
              {columnsText.map(text => <th scope="col">{text}</th>)}
            </tr>
            </thead>
            <tbody>
            {keys.map(key => (
              <tr className={key.protected ? 'sftk_mmbrs_protected' : ''}>
                {columnsOrder.map(column => {
                  const value = key[column];

                  if (numberColumns.indexOf(column) !== -1) {
                    if (value === '-1') {
                      return <td>無限</td>;
                    } else {
                      return <td>{humanizeDuration(value, { language: 'ja' })}</td>;
                    }
                  } else {
                    return <td>{value}</td>;
                  }
                })}
              </tr>
            ))}
            </tbody>
          </div>
        </table>
      )}
    </article>
  );
};

ReactDOM.render(
  <KeysEditor />,
  document.getElementById('sftk_mmbrs_keys_editor')
);
