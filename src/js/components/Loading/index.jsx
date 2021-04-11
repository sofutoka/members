const { useState, useEffect } = wp.element;

import LoadingBackAndForth from '../LoadingBackAndForth';
import styles from './styles.scss';

export default ({ message = '', isLoading }) => {
  const [showBar, setShowBar] = useState(false);

  // この仕組みは、読み込みが早ければflashを防ぐためです
  useEffect(() => {
    setTimeout(() => setShowBar(true), 200);
  }, []);

  if (isLoading) {
    return (
      <div className="sftk_mmbrs is_loading">
        <p>{message}読み込み中</p>
        {showBar && <LoadingBackAndForth />}
      </div>
    );
  } else {
    return null;
  }
}
