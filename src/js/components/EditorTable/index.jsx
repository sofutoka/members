import humanizeDuration from 'humanize-duration';
import _isPlainObject from 'lodash.isplainobject';
import PropTypes from 'prop-types';

import Loading from '../Loading';
import CheckboxWithNetwork from './CheckboxWithNetwork';
import styles from './styles.scss';

const EditorTable = ({
  title,
  description,
  columnsOrder,
  columnLabels,
  numberColumns = [],
  isLoading = true,
  rows,
  nonce,
  userId,
}) => {
  const displayCheckboxes = rows.find(a => a.checked !== undefined) !== undefined;

  return (
    <article className="sftk_mmbrs">
      <header className="sftk_mmbrs">
        <h2 className="sftk_mmbrs_table__header__title">
          {title}
        </h2>
        <p className="sftk_mmbrs_table__header__description">
          {description}
        </p>
      </header>

      <Loading message="テーブルの" isLoading={isLoading} />

      {!isLoading && (
        <table className="sftk_mmbrs">
          <thead>
          <tr>
            {displayCheckboxes && <th scope="col">付与</th>}
            {columnLabels.map((text, i) => <th key={i} scope="col">{text}</th>)}
          </tr>
          </thead>
          <tbody>
          {rows.map(row => (
            <tr key={row.id} className={row.protected ? 'sftk_mmbrs_protected' : ''}>
              {displayCheckboxes && (
                <td>
                  <CheckboxWithNetwork
                    initialIsChecked={row.checked}
                    wpAction="sftk_mmbrs_edit_profile_set_key_access"
                    userId={userId}
                    dataKeyName="key_id"
                    dataId={row.id}
                    nonce={nonce}
                  />
                </td>
              )}
              {columnsOrder.map(column => {
                const value = row[column];
                const key = column;

                if (numberColumns.indexOf(column) !== -1) {
                  if (value === '-1') {
                    return <td key={key}>無限</td>;
                  } else {
                    return <td key={key}>{humanizeDuration(value, { language: 'ja' })}</td>;
                  }
                } else if (_isPlainObject(value)) {
                  // ロックのbehavior columnの前提で
                  return (
                    <td key={key}>
                      <span className="sftk_mmbrs_tag">
                        リダイレクト：{value.redirect_to}
                      </span>
                    </td>
                  );
                } else {
                  return <td key={key}>{value}</td>;
                }
              })}
            </tr>
          ))}
          </tbody>
        </table>
      )}
    </article>
  );
};

EditorTable.propTypes = {
  title: PropTypes.node.isRequired,
  description: PropTypes.node.isRequired,
  columnsOrder: PropTypes.arrayOf(PropTypes.string).isRequired,
  columnLabels: PropTypes.arrayOf(PropTypes.string).isRequired,
  numberColumns: PropTypes.arrayOf(PropTypes.string),
  isLoading: PropTypes.bool,
  rows: PropTypes.array,
  nonce: PropTypes.string,
  userId: PropTypes.string,
};

export default EditorTable;
