Release Notes
---------
[v6.6](https://github.com/vagharsh/consul-tree/commit/82195636e7835cc1813349361ee1b634180a24fb)
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
- Fixed key-Value box doesn't change its value once its edited

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
