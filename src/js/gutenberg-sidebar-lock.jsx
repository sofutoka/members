const { registerPlugin } = wp.plugins;
const { PluginDocumentSettingPanel } = wp.editPost;

import LockIcon from './components/icons/Lock';
import SidebarLock from './components/SidebarLock';

registerPlugin('members-lock-gutenberg-template', {
  icon: LockIcon,
  render: () => (
    <PluginDocumentSettingPanel title='メンバーズロック'>
      <SidebarLock />
    </PluginDocumentSettingPanel>
  ),
});
