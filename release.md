Release Notes
---------
[v7.5.1]()
- Fixed : Consul client not executing under linux.

[v7.5](https://github.com/vagharsh/consul-tree/pull/23/commits/6d6489fadafb6b2a0d2faed67f4b4807f85766a1)
- Updated : instead of using the standard CURL for export now it uses the consul binary which is very fast. 

[v7.4](https://github.com/vagharsh/consul-tree/commit/64e7363f5ca914984a7fc826122e2fd5e35bc188)
- Updated : Send the exported data to the backend using chunks, instead of a huge object, which was preventing the export when the keys count was more than 64000. 
- Added : Export function progress bar which indicates the percentage of the received data.

[v7.3](https://github.com/vagharsh/consul-tree/commit/c276d1b0e65bd3a6833dc4b8e3af7b9ed2951468)
- Fixed : now the get value function in the frontend receives the content encoded with base64.
- Fixed : other UI minor tweaks.  

[v7.2](https://github.com/vagharsh/consul-tree/pull/22/commits/9bff209c1f1c91fe46bfab05a75af64c8a9da5b8)
- Fixed : Not updating values using the update button.
- Fixed : Multiple selects when returning from export.  

[v7.1](https://github.com/vagharsh/consul-tree/commit/01a57b7325c2f094e76cf5eb5f9f0c96084d793b)
- Added : Data import summary which indicated if the key was imported successfully or not (OK, NOK).
- Added : Content-Length, which indicator below the value box.
- Added : Data is being sent to the backend as Base64 encoded format.
- Added : External API for importing JSON file into Consul, more is mentioned [here](#https://github.com/vagharsh/consul-tree#external-api-for-importing-json-file-into-consul).

[v7.0](https://github.com/vagharsh/consul-tree/commit/477d85711b8051d8ba7d70772f50765c64ee3b79)
- Added the ability to configure (containerized) consul-tree to be accessible from under virtual-directory e.g. `http://test.domain.com/consuldirectory`.
- Fixed the issue of not deleting old data after copy/cut/paste from localstorage.
- Cleanup and reformatting some code parts.
- Moved Copy/Cut/Paste from under edit submenu, to the main context-menu.
- Replaced the alert box with modal window for the node delete function.
- Modal will appear to save the exported json file name as.

[v6.9](#)
- Removed the F2 hotkey which simulates the rename process.
- Updated the JsTree to 3.3.5.
- Changed the delete process from showing alert box to modal.
- Modified the way it keeps the data in localstorage which enables opening multiple consul-trees at the same time without each one interfering with another.

[v6.8](#)
- Added scroll-bar to the tree for better UI/UX. [commit](https://github.com/vagharsh/consul-tree/commit/3c6ba5486109aad647def581a0aa37993d7fe4fe)
- Removed the fixed position of the key/value view box.
- Added the login.php in the backend to redirect to the home when a session gets broken. [commit](https://github.com/vagharsh/consul-tree/commit/32b6ccd347cfc8a4a6169b4464de2f560d2e7b01).
- Desktop / Tablet / Mobile friendly.
- Added an indicator to the search field when results are found the field's border gets green when no results it gets red.
- Re-organized files structure.[commit](https://github.com/vagharsh/consul-tree/commit/18fbd05a2506163a5b0aa6f5727477e3a3b2c969)
- Added version file for code centralization. [commit](https://github.com/vagharsh/consul-tree/commit/ae484a07584d7d97b657b0215705173c871766b0)

[v6.7](#)
- Re-organizing the JS functions.
- Fixed issue with misbehave when performing (Rename, Duplicate, Copy, Cut) actions on a root folder/file.

[v6.6](https://github.com/vagharsh/consul-tree/commit/6f395a9563a6bcb23746946be189edc664354927)
- Added Duplicate in the right-click context menu for easy duplicate keys, folders.
- Fixed a bug in the rename functionality.
- CCP function now passes only the selected node name.

[v6.5](https://github.com/vagharsh/consul-tree/commit/d8188837f4b6ef02e0a385de7961abfdd60021d6)
- Access Control List.
- Auto user login.
- Enhanced delete functionality ( now it deletes faster "recursively").
- Update value now does not reload the page.
- Copy, Cut, Paste, Import functions now asks for data overwrite (yes, no).
- UI/UX enhancement.
- Fixed issue with the CUT function.
- Fixed the fix-tree (delete temp folders function).
- Overall faster functionality and UI.

[v6.2](#)
- Fixed issue with the CUT function.
- Update value now does not reload the page.
- UI/UX enhancement.

[v6.1](https://github.com/vagharsh/consul-tree/commit/e57666896eb35130a785c2a2f6a3c4a04d59fc2f)
- Rename Feature.
- Copy - CUT - PASTE between Consuls.
- Fix-tree modal hide issue-fix.
- Fixed issue with showing root files properly.
- Warning Modal header colors.
- Code structural change and cleanup.
- UI enhancement.

[v6.0](https://github.com/vagharsh/consul-tree/pull/11/commits/48eb372729a1fcb516b80f19ebacea58d85c7b90) :
- Consul-tree now supports multiple roots.
- Access to multiple Consuls from one UI.
- Create Root folder if does not exist.
- Landing page is changed from php to pure html.
- Config file has been changed from mixed php and js to pure json.
- Create tmp dirs inside default php tmp dir
- UI enhancement.

[v5.2](https://github.com/vagharsh/consul-tree/commit/03d31d75ab089f0eccaeadd1257a8c94bc9e932d) :
- Fixed the loading value field.
- UI enhancement.

[v5.1](https://github.com/vagharsh/consul-tree/commit/550abf3fd8e3ee65730ee58d83c0ab90e9539d34) :
- Fixed Export issue.

[v5.0](https://github.com/vagharsh/consul-tree/commit/e8f1c4e867606ad8ccf8edd6dfbf92fcccc678c9) :
- UI/UX enhancements.
- Fixed issue with large data export / import.
- Fixed issue with copy / cut / paste / delete.

[v4.9](https://github.com/vagharsh/consul-tree/commit/fd797bce0aaf37f8ab24d2ff58396009d40fd68d) :
- Many UI addition for better user interaction (modals).
- Export is now being handled by the back-end.
- Data validate function improvements. 
- NO Data, NO Connection notifications (modals) were added.
- Some steps are now logged in the console.

[v4.7](https://github.com/vagharsh/consul-tree/commit/429d6bf010e994c130483ede84cdcff715154276) :
- Bug fixes
- Code correction - Cleanup

[v4.6](https://github.com/vagharsh/consul-tree/commit/9c51de632f5e5b6c32ecc8c9090723f76a33809a) :
- Fixed issue [#9](https://github.com/vagharsh/consul-tree/issues/9)
- Some minor UI fixes
- Some code correction

[v4.5](https://github.com/vagharsh/consul-tree/commit/fd1278cad8aab2cdf6da5c416e9debb3d9785db9) :
- Minor bug fixes.

[v4.4](https://github.com/vagharsh/consul-tree/commit/0e47e8d3e72655bd0183e5b8a26c17788035483a) :
- UI enhancements.
- Some clean up in the code.
- Custom Title for the Consul-tree.

[v4.3](https://github.com/vagharsh/consul-tree/commit/9940426cc53c1f611be62f3191dd3ed67a47d878) :
- Disable export when no tree data is available.

[v4.2](https://github.com/vagharsh/consul-tree/commit/67549411048cb0a98825226e3a63817f51e0b593) :
- Some UX fixes.
- After copy paste added the fixTree function.
- Loading, Processing, Fixing modals can't be hidden by Esc or other mouse clicks. it only gets hidden by a command or page reload (after finishing the requests).

[v4.1](https://github.com/vagharsh/consul-tree/commit/b2db4f019b7c0d70a51026a98be440b68b1c0391) :
- Export is separated from the import / export modal. to do full export you have to select all the tree from the root

[v4.0](https://github.com/vagharsh/consul-tree/commit/43b78f5205bf2b7c145044bca47be51c560e1b1a) :
- all requests are now done form the back-end ( it caused the application to respond a bit slow ) but in my opinion its better than sending a request for every array item.

[v3.7](https://github.com/vagharsh/consul-tree/commit/f4fc18e6c5c2ea0515b6e5d991ef0414626db1ed) :
- Focus folder / Key name field on create new modal open

[v3.6](https://github.com/vagharsh/consul-tree/commit/2fc80314a83d89ac99143d198736dab6673dfab0) :
- Manual/Partial/Custom export feature
- Improvements in the UI

[v3.5](https://github.com/vagharsh/consul-tree/commit/6684d1ab6d3a5d5ecf88f03c5701cc8ca59a59a0) :
- Fixed key-Value box does not change its value once its edited

[v3.4](https://github.com/vagharsh/consul-tree/commit/79095d36bd12d1eb80b5d22db78803821fb4f910) :
- Horizontal scrolling of the tree enabled

v3.3
- Re-arranging files and folders.

[v3.2](https://github.com/vagharsh/consul-tree/commit/4222c38e52beb7c176f7a1bad94bf868bd3cac97) :
- Fixed not export issue.

[v3.1](https://github.com/vagharsh/consul-tree/commit/c670b093a54306fa5d2a952ce4e5447b09a59066) :
- Disabled force fix tree on page load.
- Waiting and loading modals were added to improve the UX.
- Fix Importing large Data issue.

[v3.0](https://github.com/vagharsh/consul-tree/commit/30df8eb9fcf8dcd9428e637d5a6837ef87ce3af3)  : 
- Import and Export functionality.
- Fixed the issue when creating a key or a folder while doing a right click on the key and not a folder.
- Notifies if there is not root for the tree.
- Some improvements in the coding.

v2.7 :
- fixed the delete node issue.
- update fields are disabled and hidden when the selected item is a folder and not a key.

v2.6 : 
- New icons.
- fixed value box position.
- last updated date in the footer.
