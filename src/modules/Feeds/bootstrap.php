<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

if (!System::isInstalling() && !PluginUtil::isAvailable('systemplugin.simplepie')) {
    throw new Exception(__('<strong>Fatal error: The required SimplePie system plugin is not available.</strong><br /><br />
Zikula ships with the SimplePie plugin located in the docs/examples/plugins/ExampleSystemPlugin/SimplePie directory. It must be copied (or symlinked) from there and pasted into /plugins<br />
The plugin must then be installed. This is done via the Extensions module. Click on the System Plugins menu item and install the SimplePie plugin.'));
}



