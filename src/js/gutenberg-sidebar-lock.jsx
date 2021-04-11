const { registerPlugin } = wp.plugins;
const { PluginDocumentSettingPanel } = wp.editPost;
const { Fragment } = wp.element;

import LockIcon from './components/icons/Lock';
import SidebarLock from './components/SidebarLock';

registerPlugin('landing-page-gutenberg-template', {
  icon: LockIcon,
  render: () => (
    <Fragment>
      <PluginDocumentSettingPanel title='メンバーズロック'>
        <SidebarLock />
      </PluginDocumentSettingPanel>
    </Fragment>
  ),
});
