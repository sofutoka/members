import PropTypes from 'prop-types';

const { useState, useEffect } = wp.element;

import LoadingBackAndForth from '../LoadingBackAndForth';
import styles from './styles.scss';

const Loading = ({ message = '', isLoading = true }) => {
  const [showBar, setShowBar] = useState(false);

  // 読み込みが早ければflashを防ぐよう
  useEffect(() => {
    let isMounted = true;
    setTimeout(() => {
      if (isMounted) {
        setShowBar(true);
      }
    }, 200);
    return () => { isMounted = false; };
  }, []);

  if (isLoading) {
    return (
      <div className="sftk_mmbrs is_loading">
        <p>{message}</p>
        {showBar && <LoadingBackAndForth />}
      </div>
    );
  } else {
    return null;
  }
}

Loading.propTypes = {
  message: PropTypes.node.isRequired,
  isLoading: PropTypes.bool,
};

export default Loading;
