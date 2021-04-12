import humanizeDuration from 'humanize-duration';
import _isPlainObject from 'lodash.isplainobject';

import Loading from '../Loading';
import styles from './styles.scss';

export default ({
  title,
  description,
  columnsOrder,
  columnLabels,
  numberColumns,
  isLoading,
  data,
}) => {
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
          <div>
            <thead>
            <tr>
              {columnLabels.map(text => <th scope="col">{text}</th>)}
            </tr>
            </thead>
            <tbody>
            {data.map(row => (
              <tr className={row.protected ? 'sftk_mmbrs_protected' : ''}>
                {columnsOrder.map(column => {
                  const value = row[column];

                  if (numberColumns.indexOf(column) !== -1) {
                    if (value === '-1') {
                      return <td>無限</td>;
                    } else {
                      return <td>{humanizeDuration(value, {language: 'ja'})}</td>;
                    }
                  } else if (_isPlainObject(value)) {
                    // ロックのbehavior columnという前提で
                    return (
                      <td>
                        <span className="sftk_mmbrs_tag">
                          リダイレクト：{value.redirect_to}
                        </span>
                      </td>
                    );
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
