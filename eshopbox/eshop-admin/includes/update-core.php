<?php
/**
 * WordPress core upgrade functionality.
 *
 * @package WordPress
 * @subpackage Administration
 * @since 2.7.0
 */

/**
 * Stores files to be deleted.
 *
 * @since 2.7.0
 * @global array $_old_files
 * @var array
 * @name $_old_files
 */
global $_old_files;

$_old_files = array(
// 2.0
'eshop-admin/import-b2.php',
'eshop-admin/import-blogger.php',
'eshop-admin/import-greymatter.php',
'eshop-admin/import-livejournal.php',
'eshop-admin/import-mt.php',
'eshop-admin/import-rss.php',
'eshop-admin/import-textpattern.php',
'eshop-admin/quicktags.js',
'wp-images/fade-butt.png',
'wp-images/get-firefox.png',
'wp-images/header-shadow.png',
'wp-images/smilies',
'wp-images/wp-small.png',
'wp-images/wpminilogo.png',
'wp.php',
// 2.0.8
'eshop-includes/js/tinymce/plugins/inlinepopups/readme.txt',
// 2.1
'eshop-admin/edit-form-ajax-cat.php',
'eshop-admin/execute-pings.php',
'eshop-admin/inline-uploading.php',
'eshop-admin/link-categories.php',
'eshop-admin/list-manipulation.js',
'eshop-admin/list-manipulation.php',
'eshop-includes/comment-functions.php',
'eshop-includes/feed-functions.php',
'eshop-includes/functions-compat.php',
'eshop-includes/functions-formatting.php',
'eshop-includes/functions-post.php',
'eshop-includes/js/dbx-key.js',
'eshop-includes/js/tinymce/plugins/autosave/langs/cs.js',
'eshop-includes/js/tinymce/plugins/autosave/langs/sv.js',
'eshop-includes/links.php',
'eshop-includes/pluggable-functions.php',
'eshop-includes/template-functions-author.php',
'eshop-includes/template-functions-category.php',
'eshop-includes/template-functions-general.php',
'eshop-includes/template-functions-links.php',
'eshop-includes/template-functions-post.php',
'eshop-includes/wp-l10n.php',
// 2.2
'eshop-admin/cat-js.php',
'eshop-admin/import/b2.php',
'eshop-includes/js/autosave-js.php',
'eshop-includes/js/list-manipulation-js.php',
'eshop-includes/js/wp-ajax-js.php',
// 2.3
'eshop-admin/admin-db.php',
'eshop-admin/cat.js',
'eshop-admin/categories.js',
'eshop-admin/custom-fields.js',
'eshop-admin/dbx-admin-key.js',
'eshop-admin/edit-comments.js',
'eshop-admin/install-rtl.css',
'eshop-admin/install.css',
'eshop-admin/upgrade-schema.php',
'eshop-admin/upload-functions.php',
'eshop-admin/upload-rtl.css',
'eshop-admin/upload.css',
'eshop-admin/upload.js',
'eshop-admin/users.js',
'eshop-admin/widgets-rtl.css',
'eshop-admin/widgets.css',
'eshop-admin/xfn.js',
'eshop-includes/js/tinymce/license.html',
// 2.5
'eshop-admin/css/upload.css',
'eshop-admin/images/box-bg-left.gif',
'eshop-admin/images/box-bg-right.gif',
'eshop-admin/images/box-bg.gif',
'eshop-admin/images/box-butt-left.gif',
'eshop-admin/images/box-butt-right.gif',
'eshop-admin/images/box-butt.gif',
'eshop-admin/images/box-head-left.gif',
'eshop-admin/images/box-head-right.gif',
'eshop-admin/images/box-head.gif',
'eshop-admin/images/heading-bg.gif',
'eshop-admin/images/login-bkg-bottom.gif',
'eshop-admin/images/login-bkg-tile.gif',
'eshop-admin/images/notice.gif',
'eshop-admin/images/toggle.gif',
'eshop-admin/includes/upload.php',
'eshop-admin/js/dbx-admin-key.js',
'eshop-admin/js/link-cat.js',
'eshop-admin/profile-update.php',
'eshop-admin/templates.php',
'eshop-includes/images/wlw/WpComments.png',
'eshop-includes/images/wlw/WpIcon.png',
'eshop-includes/images/wlw/WpWatermark.png',
'eshop-includes/js/dbx.js',
'eshop-includes/js/fat.js',
'eshop-includes/js/list-manipulation.js',
'eshop-includes/js/tinymce/langs/en.js',
'eshop-includes/js/tinymce/plugins/autosave/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/autosave/langs',
'eshop-includes/js/tinymce/plugins/directionality/images',
'eshop-includes/js/tinymce/plugins/directionality/langs',
'eshop-includes/js/tinymce/plugins/inlinepopups/css',
'eshop-includes/js/tinymce/plugins/inlinepopups/images',
'eshop-includes/js/tinymce/plugins/inlinepopups/jscripts',
'eshop-includes/js/tinymce/plugins/paste/images',
'eshop-includes/js/tinymce/plugins/paste/jscripts',
'eshop-includes/js/tinymce/plugins/paste/langs',
'eshop-includes/js/tinymce/plugins/spellchecker/classes/HttpClient.class.php',
'eshop-includes/js/tinymce/plugins/spellchecker/classes/TinyGoogleSpell.class.php',
'eshop-includes/js/tinymce/plugins/spellchecker/classes/TinyPspell.class.php',
'eshop-includes/js/tinymce/plugins/spellchecker/classes/TinyPspellShell.class.php',
'eshop-includes/js/tinymce/plugins/spellchecker/css/spellchecker.css',
'eshop-includes/js/tinymce/plugins/spellchecker/images',
'eshop-includes/js/tinymce/plugins/spellchecker/langs',
'eshop-includes/js/tinymce/plugins/spellchecker/tinyspell.php',
'eshop-includes/js/tinymce/plugins/wordpress/images',
'eshop-includes/js/tinymce/plugins/wordpress/langs',
'eshop-includes/js/tinymce/plugins/wordpress/wordpress.css',
'eshop-includes/js/tinymce/plugins/wphelp',
'eshop-includes/js/tinymce/themes/advanced/css',
'eshop-includes/js/tinymce/themes/advanced/images',
'eshop-includes/js/tinymce/themes/advanced/jscripts',
'eshop-includes/js/tinymce/themes/advanced/langs',
// 2.5.1
'eshop-includes/js/tinymce/tiny_mce_gzip.php',
// 2.6
'eshop-admin/bookmarklet.php',
'eshop-includes/js/jquery/jquery.dimensions.min.js',
'eshop-includes/js/tinymce/plugins/wordpress/popups.css',
'eshop-includes/js/wp-ajax.js',
// 2.7
'eshop-admin/css/press-this-ie-rtl.css',
'eshop-admin/css/press-this-ie.css',
'eshop-admin/css/upload-rtl.css',
'eshop-admin/edit-form.php',
'eshop-admin/images/comment-pill.gif',
'eshop-admin/images/comment-stalk-classic.gif',
'eshop-admin/images/comment-stalk-fresh.gif',
'eshop-admin/images/comment-stalk-rtl.gif',
'eshop-admin/images/del.png',
'eshop-admin/images/gear.png',
'eshop-admin/images/media-button-gallery.gif',
'eshop-admin/images/media-buttons.gif',
'eshop-admin/images/postbox-bg.gif',
'eshop-admin/images/tab.png',
'eshop-admin/images/tail.gif',
'eshop-admin/js/forms.js',
'eshop-admin/js/upload.js',
'eshop-admin/link-import.php',
'eshop-includes/images/audio.png',
'eshop-includes/images/css.png',
'eshop-includes/images/default.png',
'eshop-includes/images/doc.png',
'eshop-includes/images/exe.png',
'eshop-includes/images/html.png',
'eshop-includes/images/js.png',
'eshop-includes/images/pdf.png',
'eshop-includes/images/swf.png',
'eshop-includes/images/tar.png',
'eshop-includes/images/text.png',
'eshop-includes/images/video.png',
'eshop-includes/images/zip.png',
'eshop-includes/js/tinymce/tiny_mce_config.php',
'eshop-includes/js/tinymce/tiny_mce_ext.js',
// 2.8
'eshop-admin/js/users.js',
'eshop-includes/js/swfupload/plugins/swfupload.documentready.js',
'eshop-includes/js/swfupload/plugins/swfupload.graceful_degradation.js',
'eshop-includes/js/swfupload/swfupload_f9.swf',
'eshop-includes/js/tinymce/plugins/autosave',
'eshop-includes/js/tinymce/plugins/paste/css',
'eshop-includes/js/tinymce/utils/mclayer.js',
'eshop-includes/js/tinymce/wordpress.css',
// 2.8.5
'eshop-admin/import/btt.php',
'eshop-admin/import/jkw.php',
// 2.9
'eshop-admin/js/page.dev.js',
'eshop-admin/js/page.js',
'eshop-admin/js/set-post-thumbnail-handler.dev.js',
'eshop-admin/js/set-post-thumbnail-handler.js',
'eshop-admin/js/slug.dev.js',
'eshop-admin/js/slug.js',
'eshop-includes/gettext.php',
'eshop-includes/js/tinymce/plugins/wordpress/js',
'eshop-includes/streams.php',
// MU
'README.txt',
'htaccess.dist',
'index-install.php',
'eshop-admin/css/mu-rtl.css',
'eshop-admin/css/mu.css',
'eshop-admin/images/site-admin.png',
'eshop-admin/includes/mu.php',
'eshop-admin/wpmu-admin.php',
'eshop-admin/wpmu-blogs.php',
'eshop-admin/wpmu-edit.php',
'eshop-admin/wpmu-options.php',
'eshop-admin/wpmu-themes.php',
'eshop-admin/wpmu-upgrade-site.php',
'eshop-admin/wpmu-users.php',
'eshop-includes/images/wordpress-mu.png',
'eshop-includes/wpmu-default-filters.php',
'eshop-includes/wpmu-functions.php',
'wpmu-settings.php',
// 3.0
'eshop-admin/categories.php',
'eshop-admin/edit-category-form.php',
'eshop-admin/edit-page-form.php',
'eshop-admin/edit-pages.php',
'eshop-admin/images/admin-header-footer.png',
'eshop-admin/images/browse-happy.gif',
'eshop-admin/images/ico-add.png',
'eshop-admin/images/ico-close.png',
'eshop-admin/images/ico-edit.png',
'eshop-admin/images/ico-viewpage.png',
'eshop-admin/images/fav-top.png',
'eshop-admin/images/screen-options-left.gif',
'eshop-admin/images/wp-logo-vs.gif',
'eshop-admin/images/wp-logo.gif',
'eshop-admin/import',
'eshop-admin/js/wp-gears.dev.js',
'eshop-admin/js/wp-gears.js',
'eshop-admin/options-misc.php',
'eshop-admin/page-new.php',
'eshop-admin/page.php',
'eshop-admin/rtl.css',
'eshop-admin/rtl.dev.css',
'eshop-admin/update-links.php',
'eshop-admin/eshop-admin.css',
'eshop-admin/eshop-admin.dev.css',
'eshop-includes/js/codepress',
'eshop-includes/js/codepress/engines/khtml.js',
'eshop-includes/js/codepress/engines/older.js',
'eshop-includes/js/jquery/autocomplete.dev.js',
'eshop-includes/js/jquery/autocomplete.js',
'eshop-includes/js/jquery/interface.js',
'eshop-includes/js/scriptaculous/prototype.js',
'eshop-includes/js/tinymce/wp-tinymce.js',
// 3.1
'eshop-admin/edit-attachment-rows.php',
'eshop-admin/edit-link-categories.php',
'eshop-admin/edit-link-category-form.php',
'eshop-admin/edit-post-rows.php',
'eshop-admin/images/button-grad-active-vs.png',
'eshop-admin/images/button-grad-vs.png',
'eshop-admin/images/fav-arrow-vs-rtl.gif',
'eshop-admin/images/fav-arrow-vs.gif',
'eshop-admin/images/fav-top-vs.gif',
'eshop-admin/images/list-vs.png',
'eshop-admin/images/screen-options-right-up.gif',
'eshop-admin/images/screen-options-right.gif',
'eshop-admin/images/visit-site-button-grad-vs.gif',
'eshop-admin/images/visit-site-button-grad.gif',
'eshop-admin/link-category.php',
'eshop-admin/sidebar.php',
'eshop-includes/classes.php',
'eshop-includes/js/tinymce/blank.htm',
'eshop-includes/js/tinymce/plugins/media/css/content.css',
'eshop-includes/js/tinymce/plugins/media/img',
'eshop-includes/js/tinymce/plugins/safari',
// 3.2
'eshop-admin/images/logo-login.gif',
'eshop-admin/images/star.gif',
'eshop-admin/js/list-table.dev.js',
'eshop-admin/js/list-table.js',
'eshop-includes/default-embeds.php',
'eshop-includes/js/tinymce/plugins/wordpress/img/help.gif',
'eshop-includes/js/tinymce/plugins/wordpress/img/more.gif',
'eshop-includes/js/tinymce/plugins/wordpress/img/toolbars.gif',
'eshop-includes/js/tinymce/themes/advanced/img/fm.gif',
'eshop-includes/js/tinymce/themes/advanced/img/sflogo.png',
// 3.3
'eshop-admin/css/colors-classic-rtl.css',
'eshop-admin/css/colors-classic-rtl.dev.css',
'eshop-admin/css/colors-fresh-rtl.css',
'eshop-admin/css/colors-fresh-rtl.dev.css',
'eshop-admin/css/dashboard-rtl.dev.css',
'eshop-admin/css/dashboard.dev.css',
'eshop-admin/css/global-rtl.css',
'eshop-admin/css/global-rtl.dev.css',
'eshop-admin/css/global.css',
'eshop-admin/css/global.dev.css',
'eshop-admin/css/install-rtl.dev.css',
'eshop-admin/css/login-rtl.dev.css',
'eshop-admin/css/login.dev.css',
'eshop-admin/css/ms.css',
'eshop-admin/css/ms.dev.css',
'eshop-admin/css/nav-menu-rtl.css',
'eshop-admin/css/nav-menu-rtl.dev.css',
'eshop-admin/css/nav-menu.css',
'eshop-admin/css/nav-menu.dev.css',
'eshop-admin/css/plugin-install-rtl.css',
'eshop-admin/css/plugin-install-rtl.dev.css',
'eshop-admin/css/plugin-install.css',
'eshop-admin/css/plugin-install.dev.css',
'eshop-admin/css/press-this-rtl.dev.css',
'eshop-admin/css/press-this.dev.css',
'eshop-admin/css/theme-editor-rtl.css',
'eshop-admin/css/theme-editor-rtl.dev.css',
'eshop-admin/css/theme-editor.css',
'eshop-admin/css/theme-editor.dev.css',
'eshop-admin/css/theme-install-rtl.css',
'eshop-admin/css/theme-install-rtl.dev.css',
'eshop-admin/css/theme-install.css',
'eshop-admin/css/theme-install.dev.css',
'eshop-admin/css/widgets-rtl.dev.css',
'eshop-admin/css/widgets.dev.css',
'eshop-admin/includes/internal-linking.php',
'eshop-includes/images/admin-bar-sprite-rtl.png',
'eshop-includes/js/jquery/ui.button.js',
'eshop-includes/js/jquery/ui.core.js',
'eshop-includes/js/jquery/ui.dialog.js',
'eshop-includes/js/jquery/ui.draggable.js',
'eshop-includes/js/jquery/ui.droppable.js',
'eshop-includes/js/jquery/ui.mouse.js',
'eshop-includes/js/jquery/ui.position.js',
'eshop-includes/js/jquery/ui.resizable.js',
'eshop-includes/js/jquery/ui.selectable.js',
'eshop-includes/js/jquery/ui.sortable.js',
'eshop-includes/js/jquery/ui.tabs.js',
'eshop-includes/js/jquery/ui.widget.js',
'eshop-includes/js/l10n.dev.js',
'eshop-includes/js/l10n.js',
'eshop-includes/js/tinymce/plugins/wplink/css',
'eshop-includes/js/tinymce/plugins/wplink/img',
'eshop-includes/js/tinymce/plugins/wplink/js',
'eshop-includes/js/tinymce/themes/advanced/img/wpicons.png',
'eshop-includes/js/tinymce/themes/advanced/skins/wp_theme/img/butt2.png',
'eshop-includes/js/tinymce/themes/advanced/skins/wp_theme/img/button_bg.png',
'eshop-includes/js/tinymce/themes/advanced/skins/wp_theme/img/down_arrow.gif',
'eshop-includes/js/tinymce/themes/advanced/skins/wp_theme/img/fade-butt.png',
'eshop-includes/js/tinymce/themes/advanced/skins/wp_theme/img/separator.gif',
// Don't delete, yet: 'wp-rss.php',
// Don't delete, yet: 'wp-rdf.php',
// Don't delete, yet: 'wp-rss2.php',
// Don't delete, yet: 'wp-commentsrss2.php',
// Don't delete, yet: 'wp-atom.php',
// Don't delete, yet: 'wp-feed.php',
// 3.4
'eshop-admin/images/gray-star.png',
'eshop-admin/images/logo-login.png',
'eshop-admin/images/star.png',
'eshop-admin/index-extra.php',
'eshop-admin/network/index-extra.php',
'eshop-admin/user/index-extra.php',
'eshop-admin/images/screenshots/admin-flyouts.png',
'eshop-admin/images/screenshots/coediting.png',
'eshop-admin/images/screenshots/drag-and-drop.png',
'eshop-admin/images/screenshots/help-screen.png',
'eshop-admin/images/screenshots/media-icon.png',
'eshop-admin/images/screenshots/new-feature-pointer.png',
'eshop-admin/images/screenshots/welcome-screen.png',
'eshop-includes/css/editor-buttons.css',
'eshop-includes/css/editor-buttons.dev.css',
'eshop-includes/js/tinymce/plugins/paste/blank.htm',
'eshop-includes/js/tinymce/plugins/wordpress/css',
'eshop-includes/js/tinymce/plugins/wordpress/editor_plugin.dev.js',
'eshop-includes/js/tinymce/plugins/wordpress/img/embedded.png',
'eshop-includes/js/tinymce/plugins/wordpress/img/more_bug.gif',
'eshop-includes/js/tinymce/plugins/wordpress/img/page_bug.gif',
'eshop-includes/js/tinymce/plugins/wpdialogs/editor_plugin.dev.js',
'eshop-includes/js/tinymce/plugins/wpeditimage/css/editimage-rtl.css',
'eshop-includes/js/tinymce/plugins/wpeditimage/editor_plugin.dev.js',
'eshop-includes/js/tinymce/plugins/wpfullscreen/editor_plugin.dev.js',
'eshop-includes/js/tinymce/plugins/wpgallery/editor_plugin.dev.js',
'eshop-includes/js/tinymce/plugins/wpgallery/img/gallery.png',
'eshop-includes/js/tinymce/plugins/wplink/editor_plugin.dev.js',
// Don't delete, yet: 'wp-pass.php',
// Don't delete, yet: 'wp-register.php',
// 3.5
'eshop-admin/gears-manifest.php',
'eshop-admin/includes/manifest.php',
'eshop-admin/images/archive-link.png',
'eshop-admin/images/blue-grad.png',
'eshop-admin/images/button-grad-active.png',
'eshop-admin/images/button-grad.png',
'eshop-admin/images/ed-bg-vs.gif',
'eshop-admin/images/ed-bg.gif',
'eshop-admin/images/fade-butt.png',
'eshop-admin/images/fav-arrow-rtl.gif',
'eshop-admin/images/fav-arrow.gif',
'eshop-admin/images/fav-vs.png',
'eshop-admin/images/fav.png',
'eshop-admin/images/gray-grad.png',
'eshop-admin/images/loading-publish.gif',
'eshop-admin/images/logo-ghost.png',
'eshop-admin/images/logo.gif',
'eshop-admin/images/menu-arrow-frame-rtl.png',
'eshop-admin/images/menu-arrow-frame.png',
'eshop-admin/images/menu-arrows.gif',
'eshop-admin/images/menu-bits-rtl-vs.gif',
'eshop-admin/images/menu-bits-rtl.gif',
'eshop-admin/images/menu-bits-vs.gif',
'eshop-admin/images/menu-bits.gif',
'eshop-admin/images/menu-dark-rtl-vs.gif',
'eshop-admin/images/menu-dark-rtl.gif',
'eshop-admin/images/menu-dark-vs.gif',
'eshop-admin/images/menu-dark.gif',
'eshop-admin/images/required.gif',
'eshop-admin/images/screen-options-toggle-vs.gif',
'eshop-admin/images/screen-options-toggle.gif',
'eshop-admin/images/toggle-arrow-rtl.gif',
'eshop-admin/images/toggle-arrow.gif',
'eshop-admin/images/upload-classic.png',
'eshop-admin/images/upload-fresh.png',
'eshop-admin/images/white-grad-active.png',
'eshop-admin/images/white-grad.png',
'eshop-admin/images/widgets-arrow-vs.gif',
'eshop-admin/images/widgets-arrow.gif',
'eshop-admin/images/wpspin_dark.gif',
'eshop-includes/images/upload.png',
'eshop-includes/js/prototype.js',
'eshop-includes/js/scriptaculous',
'eshop-admin/css/eshop-admin-rtl.dev.css',
'eshop-admin/css/eshop-admin.dev.css',
'eshop-admin/css/media-rtl.dev.css',
'eshop-admin/css/media.dev.css',
'eshop-admin/css/colors-classic.dev.css',
'eshop-admin/css/customize-controls-rtl.dev.css',
'eshop-admin/css/customize-controls.dev.css',
'eshop-admin/css/ie-rtl.dev.css',
'eshop-admin/css/ie.dev.css',
'eshop-admin/css/install.dev.css',
'eshop-admin/css/colors-fresh.dev.css',
'eshop-includes/js/customize-base.dev.js',
'eshop-includes/js/json2.dev.js',
'eshop-includes/js/comment-reply.dev.js',
'eshop-includes/js/customize-preview.dev.js',
'eshop-includes/js/wplink.dev.js',
'eshop-includes/js/tw-sack.dev.js',
'eshop-includes/js/wp-list-revisions.dev.js',
'eshop-includes/js/autosave.dev.js',
'eshop-includes/js/admin-bar.dev.js',
'eshop-includes/js/quicktags.dev.js',
'eshop-includes/js/wp-ajax-response.dev.js',
'eshop-includes/js/wp-pointer.dev.js',
'eshop-includes/js/hoverIntent.dev.js',
'eshop-includes/js/colorpicker.dev.js',
'eshop-includes/js/wp-lists.dev.js',
'eshop-includes/js/customize-loader.dev.js',
'eshop-includes/js/jquery/jquery.table-hotkeys.dev.js',
'eshop-includes/js/jquery/jquery.color.dev.js',
'eshop-includes/js/jquery/jquery.color.js',
'eshop-includes/js/jquery/jquery.hotkeys.dev.js',
'eshop-includes/js/jquery/jquery.form.dev.js',
'eshop-includes/js/jquery/suggest.dev.js',
'eshop-admin/js/xfn.dev.js',
'eshop-admin/js/set-post-thumbnail.dev.js',
'eshop-admin/js/comment.dev.js',
'eshop-admin/js/theme.dev.js',
'eshop-admin/js/cat.dev.js',
'eshop-admin/js/password-strength-meter.dev.js',
'eshop-admin/js/user-profile.dev.js',
'eshop-admin/js/theme-preview.dev.js',
'eshop-admin/js/post.dev.js',
'eshop-admin/js/media-upload.dev.js',
'eshop-admin/js/word-count.dev.js',
'eshop-admin/js/plugin-install.dev.js',
'eshop-admin/js/edit-comments.dev.js',
'eshop-admin/js/media-gallery.dev.js',
'eshop-admin/js/custom-fields.dev.js',
'eshop-admin/js/custom-background.dev.js',
'eshop-admin/js/common.dev.js',
'eshop-admin/js/inline-edit-tax.dev.js',
'eshop-admin/js/gallery.dev.js',
'eshop-admin/js/utils.dev.js',
'eshop-admin/js/widgets.dev.js',
'eshop-admin/js/wp-fullscreen.dev.js',
'eshop-admin/js/nav-menu.dev.js',
'eshop-admin/js/dashboard.dev.js',
'eshop-admin/js/link.dev.js',
'eshop-admin/js/user-suggest.dev.js',
'eshop-admin/js/postbox.dev.js',
'eshop-admin/js/tags.dev.js',
'eshop-admin/js/image-edit.dev.js',
'eshop-admin/js/media.dev.js',
'eshop-admin/js/customize-controls.dev.js',
'eshop-admin/js/inline-edit-post.dev.js',
'eshop-admin/js/categories.dev.js',
'eshop-admin/js/editor.dev.js',
'eshop-includes/js/tinymce/plugins/wpeditimage/js/editimage.dev.js',
'eshop-includes/js/tinymce/plugins/wpdialogs/js/popup.dev.js',
'eshop-includes/js/tinymce/plugins/wpdialogs/js/wpdialog.dev.js',
'eshop-includes/js/plupload/handlers.dev.js',
'eshop-includes/js/plupload/wp-plupload.dev.js',
'eshop-includes/js/swfupload/handlers.dev.js',
'eshop-includes/js/jcrop/jquery.Jcrop.dev.js',
'eshop-includes/js/jcrop/jquery.Jcrop.js',
'eshop-includes/js/jcrop/jquery.Jcrop.css',
'eshop-includes/js/imgareaselect/jquery.imgareaselect.dev.js',
'eshop-includes/css/wp-pointer.dev.css',
'eshop-includes/css/editor.dev.css',
'eshop-includes/css/jquery-ui-dialog.dev.css',
'eshop-includes/css/admin-bar-rtl.dev.css',
'eshop-includes/css/admin-bar.dev.css',
'eshop-includes/js/jquery/ui/jquery.effects.clip.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.scale.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.blind.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.core.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.shake.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.fade.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.explode.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.slide.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.drop.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.highlight.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.bounce.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.pulsate.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.transfer.min.js',
'eshop-includes/js/jquery/ui/jquery.effects.fold.min.js',
'eshop-admin/images/screenshots/captions-1.png',
'eshop-admin/images/screenshots/captions-2.png',
'eshop-admin/images/screenshots/flex-header-1.png',
'eshop-admin/images/screenshots/flex-header-2.png',
'eshop-admin/images/screenshots/flex-header-3.png',
'eshop-admin/images/screenshots/flex-header-media-library.png',
'eshop-admin/images/screenshots/theme-customizer.png',
'eshop-admin/images/screenshots/twitter-embed-1.png',
'eshop-admin/images/screenshots/twitter-embed-2.png',
'eshop-admin/js/utils.js',
'eshop-admin/options-privacy.php',
'wp-app.php',
'eshop-includes/class-wp-atom-server.php',
'eshop-includes/js/tinymce/themes/advanced/skins/wp_theme/ui.css',
// 3.5.2
'eshop-includes/js/swfupload/swfupload-all.js',
// 3.6
'eshop-admin/js/revisions-js.php',
'eshop-admin/images/screenshots',
'eshop-admin/js/categories.js',
'eshop-admin/js/categories.min.js',
'eshop-admin/js/custom-fields.js',
'eshop-admin/js/custom-fields.min.js',
// 3.7
'eshop-admin/js/cat.js',
'eshop-admin/js/cat.min.js',
'eshop-includes/js/tinymce/plugins/wpeditimage/js/editimage.min.js',
// 3.8
'eshop-includes/js/tinymce/themes/advanced/skins/wp_theme/img/page_bug.gif',
'eshop-includes/js/tinymce/themes/advanced/skins/wp_theme/img/more_bug.gif',
'eshop-includes/js/thickbox/tb-close-2x.png',
'eshop-includes/js/thickbox/tb-close.png',
'eshop-includes/images/wpmini-blue-2x.png',
'eshop-includes/images/wpmini-blue.png',
'eshop-admin/css/colors-fresh.css',
'eshop-admin/css/colors-classic.css',
'eshop-admin/css/colors-fresh.min.css',
'eshop-admin/css/colors-classic.min.css',
'eshop-admin/js/about.min.js',
'eshop-admin/js/about.js',
'eshop-admin/images/arrows-dark-vs-2x.png',
'eshop-admin/images/wp-logo-vs.png',
'eshop-admin/images/arrows-dark-vs.png',
'eshop-admin/images/wp-logo.png',
'eshop-admin/images/arrows-pr.png',
'eshop-admin/images/arrows-dark.png',
'eshop-admin/images/press-this.png',
'eshop-admin/images/press-this-2x.png',
'eshop-admin/images/arrows-vs-2x.png',
'eshop-admin/images/welcome-icons.png',
'eshop-admin/images/wp-logo-2x.png',
'eshop-admin/images/stars-rtl-2x.png',
'eshop-admin/images/arrows-dark-2x.png',
'eshop-admin/images/arrows-pr-2x.png',
'eshop-admin/images/menu-shadow-rtl.png',
'eshop-admin/images/arrows-vs.png',
'eshop-admin/images/about-search-2x.png',
'eshop-admin/images/bubble_bg-rtl-2x.gif',
'eshop-admin/images/wp-badge-2x.png',
'eshop-admin/images/wordpress-logo-2x.png',
'eshop-admin/images/bubble_bg-rtl.gif',
'eshop-admin/images/wp-badge.png',
'eshop-admin/images/menu-shadow.png',
'eshop-admin/images/about-globe-2x.png',
'eshop-admin/images/welcome-icons-2x.png',
'eshop-admin/images/stars-rtl.png',
'eshop-admin/images/wp-logo-vs-2x.png',
'eshop-admin/images/about-updates-2x.png',
// 3.9
'eshop-admin/css/colors.css',
'eshop-admin/css/colors.min.css',
'eshop-admin/css/colors-rtl.css',
'eshop-admin/css/colors-rtl.min.css',
'eshop-admin/css/media-rtl.min.css',
'eshop-admin/css/media.min.css',
'eshop-admin/css/farbtastic-rtl.min.css',
'eshop-admin/images/lock-2x.png',
'eshop-admin/images/lock.png',
'eshop-admin/js/theme-preview.js',
'eshop-admin/js/theme-install.min.js',
'eshop-admin/js/theme-install.js',
'eshop-admin/js/theme-preview.min.js',
'eshop-includes/js/plupload/plupload.html4.js',
'eshop-includes/js/plupload/plupload.html5.js',
'eshop-includes/js/plupload/changelog.txt',
'eshop-includes/js/plupload/plupload.silverlight.js',
'eshop-includes/js/plupload/plupload.flash.js',
'eshop-includes/js/plupload/plupload.js',
'eshop-includes/js/tinymce/plugins/spellchecker',
'eshop-includes/js/tinymce/plugins/inlinepopups',
'eshop-includes/js/tinymce/plugins/media/js',
'eshop-includes/js/tinymce/plugins/media/css',
'eshop-includes/js/tinymce/plugins/wordpress/img',
'eshop-includes/js/tinymce/plugins/wpdialogs/js',
'eshop-includes/js/tinymce/plugins/wpeditimage/img',
'eshop-includes/js/tinymce/plugins/wpeditimage/js',
'eshop-includes/js/tinymce/plugins/wpeditimage/css',
'eshop-includes/js/tinymce/plugins/wpgallery/img',
'eshop-includes/js/tinymce/plugins/wpfullscreen/css',
'eshop-includes/js/tinymce/plugins/paste/js',
'eshop-includes/js/tinymce/themes/advanced',
'eshop-includes/js/tinymce/tiny_mce.js',
'eshop-includes/js/tinymce/mark_loaded_src.js',
'eshop-includes/js/tinymce/wp-tinymce-schema.js',
'eshop-includes/js/tinymce/plugins/media/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/media/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/media/media.htm',
'eshop-includes/js/tinymce/plugins/wpview/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/wpview/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/directionality/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/directionality/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/wordpress/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/wordpress/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/wpdialogs/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/wpdialogs/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/wpeditimage/editimage.html',
'eshop-includes/js/tinymce/plugins/wpeditimage/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/wpeditimage/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/fullscreen/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/fullscreen/fullscreen.htm',
'eshop-includes/js/tinymce/plugins/fullscreen/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/wplink/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/wplink/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/wpgallery/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/wpgallery/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/tabfocus/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/tabfocus/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/wpfullscreen/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/wpfullscreen/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/paste/editor_plugin.js',
'eshop-includes/js/tinymce/plugins/paste/pasteword.htm',
'eshop-includes/js/tinymce/plugins/paste/editor_plugin_src.js',
'eshop-includes/js/tinymce/plugins/paste/pastetext.htm',
'eshop-includes/js/tinymce/langs/wp-langs.php',
);

/**
 * Stores new files in wp-content to copy
 *
 * The contents of this array indicate any new bundled plugins/themes which
 * should be installed with the WordPress Upgrade. These items will not be
 * re-installed in future upgrades, this behaviour is controlled by the
 * introduced version present here being older than the current installed version.
 *
 * The content of this array should follow the following format:
 * Filename (relative to wp-content) => Introduced version
 * Directories should be noted by suffixing it with a trailing slash (/)
 *
 * @since 3.2.0
 * @global array $_new_bundled_files
 * @var array
 * @name $_new_bundled_files
 */
global $_new_bundled_files;

$_new_bundled_files = array(
	'plugins/akismet/'       => '2.0',
	'themes/twentyten/'      => '3.0',
	'themes/twentyeleven/'   => '3.2',
	'themes/twentytwelve/'   => '3.5',
	'themes/twentythirteen/' => '3.6',
	'themes/twentyfourteen/' => '3.8',
);

/**
 * Upgrade the core of WordPress.
 *
 * This will create a .maintenance file at the base of the WordPress directory
 * to ensure that people can not access the web site, when the files are being
 * copied to their locations.
 *
 * The files in the {@link $_old_files} list will be removed and the new files
 * copied from the zip file after the database is upgraded.
 *
 * The files in the {@link $_new_bundled_files} list will be added to the installation
 * if the version is greater than or equal to the old version being upgraded.
 *
 * The steps for the upgrader for after the new release is downloaded and
 * unzipped is:
 *   1. Test unzipped location for select files to ensure that unzipped worked.
 *   2. Create the .maintenance file in current WordPress base.
 *   3. Copy new WordPress directory over old WordPress files.
 *   4. Upgrade WordPress to new version.
 *     4.1. Copy all files/folders other than wp-content
 *     4.2. Copy any language files to WP_LANG_DIR (which may differ from WP_CONTENT_DIR
 *     4.3. Copy any new bundled themes/plugins to their respective locations
 *   5. Delete new WordPress directory path.
 *   6. Delete .maintenance file.
 *   7. Remove old files.
 *   8. Delete 'update_core' option.
 *
 * There are several areas of failure. For instance if PHP times out before step
 * 6, then you will not be able to access any portion of your site. Also, since
 * the upgrade will not continue where it left off, you will not be able to
 * automatically remove old files and remove the 'update_core' option. This
 * isn't that bad.
 *
 * If the copy of the new WordPress over the old fails, then the worse is that
 * the new WordPress directory will remain.
 *
 * If it is assumed that every file will be copied over, including plugins and
 * themes, then if you edit the default theme, you should rename it, so that
 * your changes remain.
 *
 * @since 2.7.0
 *
 * @param string $from New release unzipped path.
 * @param string $to Path to old WordPress installation.
 * @return WP_Error|null WP_Error on failure, null on success.
 */
function update_core($from, $to) {
	global $wp_filesystem, $_old_files, $_new_bundled_files, $wpdb;

	@set_time_limit( 300 );

	/**
	 * Filter feedback messages displayed during the core update process.
	 *
	 * The filter is first evaluated after the zip file for the latest version
	 * has been downloaded and unzipped. It is evaluated five more times during
	 * the process:
	 *
	 * 1. Before WordPress begins the core upgrade process.
	 * 2. Before Maintenance Mode is enabled.
	 * 3. Before WordPress begins copying over the necessary files.
	 * 4. Before Maintenance Mode is disabled.
	 * 5. Before the database is upgraded.
	 *
	 * @since 2.5.0
	 *
	 * @param string $feedback The core update feedback messages.
	 */
	apply_filters( 'update_feedback', __( 'Verifying the unpacked files&#8230;' ) );

	// Sanity check the unzipped distribution.
	$distro = '';
	$roots = array( '/wordpress/', '/wordpress-mu/' );
	foreach ( $roots as $root ) {
		if ( $wp_filesystem->exists( $from . $root . 'readme.html' ) && $wp_filesystem->exists( $from . $root . 'eshop-includes/version.php' ) ) {
			$distro = $root;
			break;
		}
	}
	if ( ! $distro ) {
		$wp_filesystem->delete( $from, true );
		return new WP_Error( 'insane_distro', __('The update could not be unpacked') );
	}

	// Import $wp_version, $required_php_version, and $required_mysql_version from the new version
	// $wp_filesystem->wp_content_dir() returned unslashed pre-2.8
	$versions_file = trailingslashit( $wp_filesystem->wp_content_dir() ) . 'upgrade/version-current.php';
	if ( ! $wp_filesystem->copy( $from . $distro . 'eshop-includes/version.php', $versions_file ) ) {
		$wp_filesystem->delete( $from, true );
		return new WP_Error( 'copy_failed_for_version_file', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), 'eshop-includes/version.php' );
	}

	$wp_filesystem->chmod( $versions_file, FS_CHMOD_FILE );
	require( WP_CONTENT_DIR . '/upgrade/version-current.php' );
	$wp_filesystem->delete( $versions_file );

	$php_version    = phpversion();
	$mysql_version  = $wpdb->db_version();
	$old_wp_version = $GLOBALS['wp_version']; // The version of WordPress we're updating from
	$development_build = ( false !== strpos( $old_wp_version . $wp_version, '-' )  ); // a dash in the version indicates a Development release
	$php_compat     = version_compare( $php_version, $required_php_version, '>=' );
	if ( file_exists( WP_CONTENT_DIR . '/db.php' ) && empty( $wpdb->is_mysql ) )
		$mysql_compat = true;
	else
		$mysql_compat = version_compare( $mysql_version, $required_mysql_version, '>=' );

	if ( !$mysql_compat || !$php_compat )
		$wp_filesystem->delete($from, true);

	if ( !$mysql_compat && !$php_compat )
		return new WP_Error( 'php_mysql_not_compatible', sprintf( __('The update cannot be installed because WordPress %1$s requires PHP version %2$s or higher and MySQL version %3$s or higher. You are running PHP version %4$s and MySQL version %5$s.'), $wp_version, $required_php_version, $required_mysql_version, $php_version, $mysql_version ) );
	elseif ( !$php_compat )
		return new WP_Error( 'php_not_compatible', sprintf( __('The update cannot be installed because WordPress %1$s requires PHP version %2$s or higher. You are running version %3$s.'), $wp_version, $required_php_version, $php_version ) );
	elseif ( !$mysql_compat )
		return new WP_Error( 'mysql_not_compatible', sprintf( __('The update cannot be installed because WordPress %1$s requires MySQL version %2$s or higher. You are running version %3$s.'), $wp_version, $required_mysql_version, $mysql_version ) );

	/** This filter is documented in eshop-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Preparing to install the latest version&#8230;' ) );

	// Don't copy wp-content, we'll deal with that below
	// We also copy version.php last so failed updates report their old version
	$skip = array( 'wp-content', 'eshop-includes/version.php' );
	$check_is_writable = array();

	// Check to see which files don't really need updating - only available for 3.7 and higher
	if ( function_exists( 'get_core_checksums' ) ) {
		// Find the local version of the working directory
		$working_dir_local = WP_CONTENT_DIR . '/upgrade/' . basename( $from ) . $distro;

		$checksums = get_core_checksums( $wp_version, isset( $wp_local_package ) ? $wp_local_package : 'en_US' );
		if ( is_array( $checksums ) && isset( $checksums[ $wp_version ] ) )
			$checksums = $checksums[ $wp_version ]; // Compat code for 3.7-beta2
		if ( is_array( $checksums ) ) {
			foreach( $checksums as $file => $checksum ) {
				if ( 'wp-content' == substr( $file, 0, 10 ) )
					continue;
				if ( ! file_exists( ABSPATH . $file ) )
					continue;
				if ( ! file_exists( $working_dir_local . $file ) )
					continue;
				if ( md5_file( ABSPATH . $file ) === $checksum )
					$skip[] = $file;
				else
					$check_is_writable[ $file ] = ABSPATH . $file;
			}
		}
	}

	// If we're using the direct method, we can predict write failures that are due to permissions.
	if ( $check_is_writable && 'direct' === $wp_filesystem->method ) {
		$files_writable = array_filter( $check_is_writable, array( $wp_filesystem, 'is_writable' ) );
		if ( $files_writable !== $check_is_writable ) {
			$files_not_writable = array_diff_key( $check_is_writable, $files_writable );
			foreach ( $files_not_writable as $relative_file_not_writable => $file_not_writable ) {
				// If the writable check failed, chmod file to 0644 and try again, same as copy_dir().
				$wp_filesystem->chmod( $file_not_writable, FS_CHMOD_FILE );
				if ( $wp_filesystem->is_writable( $file_not_writable ) )
					unset( $files_not_writable[ $relative_file_not_writable ] );
			}

			// Store package-relative paths (the key) of non-writable files in the WP_Error object.
			$error_data = version_compare( $old_wp_version, '3.7-beta2', '>' ) ? array_keys( $files_not_writable ) : '';

			if ( $files_not_writable )
				return new WP_Error( 'files_not_writable', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), implode( ', ', $error_data ) );
		}
	}

	/** This filter is documented in eshop-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Enabling Maintenance mode&#8230;' ) );
	// Create maintenance file to signal that we are upgrading
	$maintenance_string = '<?php $upgrading = ' . time() . '; ?>';
	$maintenance_file = $to . '.maintenance';
	$wp_filesystem->delete($maintenance_file);
	$wp_filesystem->put_contents($maintenance_file, $maintenance_string, FS_CHMOD_FILE);

	/** This filter is documented in eshop-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Copying the required files&#8230;' ) );
	// Copy new versions of WP files into place.
	$result = _copy_dir( $from . $distro, $to, $skip );
	if ( is_wp_error( $result ) )
		$result = new WP_Error( $result->get_error_code(), $result->get_error_message(), substr( $result->get_error_data(), strlen( $to ) ) );

	// Since we know the core files have copied over, we can now copy the version file
	if ( ! is_wp_error( $result ) ) {
		if ( ! $wp_filesystem->copy( $from . $distro . 'eshop-includes/version.php', $to . 'eshop-includes/version.php', true /* overwrite */ ) ) {
			$wp_filesystem->delete( $from, true );
			$result = new WP_Error( 'copy_failed_for_version_file', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), 'eshop-includes/version.php' );
		}
		$wp_filesystem->chmod( $to . 'eshop-includes/version.php', FS_CHMOD_FILE );
	}

	// Check to make sure everything copied correctly, ignoring the contents of wp-content
	$skip = array( 'wp-content' );
	$failed = array();
	if ( isset( $checksums ) && is_array( $checksums ) ) {
		foreach ( $checksums as $file => $checksum ) {
			if ( 'wp-content' == substr( $file, 0, 10 ) )
				continue;
			if ( ! file_exists( $working_dir_local . $file ) )
				continue;
			if ( file_exists( ABSPATH . $file ) && md5_file( ABSPATH . $file ) == $checksum )
				$skip[] = $file;
			else
				$failed[] = $file;
		}
	}

	// Some files didn't copy properly
	if ( ! empty( $failed ) ) {
		$total_size = 0;
		foreach ( $failed as $file ) {
			if ( file_exists( $working_dir_local . $file ) )
				$total_size += filesize( $working_dir_local . $file );
		}

		// If we don't have enough free space, it isn't worth trying again.
		// Unlikely to be hit due to the check in unzip_file().
		$available_space = @disk_free_space( ABSPATH );
		if ( $available_space && $total_size >= $available_space ) {
			$result = new WP_Error( 'disk_full', __( 'There is not enough free disk space to complete the update.' ) );
		} else {
			$result = _copy_dir( $from . $distro, $to, $skip );
			if ( is_wp_error( $result ) )
				$result = new WP_Error( $result->get_error_code() . '_retry', $result->get_error_message(), substr( $result->get_error_data(), strlen( $to ) ) );
		}
	}

	// Custom Content Directory needs updating now.
	// Copy Languages
	if ( !is_wp_error($result) && $wp_filesystem->is_dir($from . $distro . 'wp-content/languages') ) {
		if ( WP_LANG_DIR != ABSPATH . WPINC . '/languages' || @is_dir(WP_LANG_DIR) )
			$lang_dir = WP_LANG_DIR;
		else
			$lang_dir = WP_CONTENT_DIR . '/languages';

		if ( !@is_dir($lang_dir) && 0 === strpos($lang_dir, ABSPATH) ) { // Check the language directory exists first
			$wp_filesystem->mkdir($to . str_replace(ABSPATH, '', $lang_dir), FS_CHMOD_DIR); // If it's within the ABSPATH we can handle it here, otherwise they're out of luck.
			clearstatcache(); // for FTP, Need to clear the stat cache
		}

		if ( @is_dir($lang_dir) ) {
			$wp_lang_dir = $wp_filesystem->find_folder($lang_dir);
			if ( $wp_lang_dir ) {
				$result = copy_dir($from . $distro . 'wp-content/languages/', $wp_lang_dir);
				if ( is_wp_error( $result ) )
					$result = new WP_Error( $result->get_error_code() . '_languages', $result->get_error_message(), substr( $result->get_error_data(), strlen( $wp_lang_dir ) ) );
			}
		}
	}

	/** This filter is documented in eshop-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Disabling Maintenance mode&#8230;' ) );
	// Remove maintenance file, we're done with potential site-breaking changes
	$wp_filesystem->delete( $maintenance_file );

	// 3.5 -> 3.5+ - an empty twentytwelve directory was created upon upgrade to 3.5 for some users, preventing installation of Twenty Twelve.
	if ( '3.5' == $old_wp_version ) {
		if ( is_dir( WP_CONTENT_DIR . '/themes/twentytwelve' ) && ! file_exists( WP_CONTENT_DIR . '/themes/twentytwelve/style.css' )  ) {
			$wp_filesystem->delete( $wp_filesystem->wp_themes_dir() . 'twentytwelve/' );
		}
	}

	// Copy New bundled plugins & themes
	// This gives us the ability to install new plugins & themes bundled with future versions of WordPress whilst avoiding the re-install upon upgrade issue.
	// $development_build controls us overwriting bundled themes and plugins when a non-stable release is being updated
	if ( !is_wp_error($result) && ( ! defined('CORE_UPGRADE_SKIP_NEW_BUNDLED') || ! CORE_UPGRADE_SKIP_NEW_BUNDLED ) ) {
		foreach ( (array) $_new_bundled_files as $file => $introduced_version ) {
			// If a $development_build or if $introduced version is greater than what the site was previously running
			if ( $development_build || version_compare( $introduced_version, $old_wp_version, '>' ) ) {
				$directory = ('/' == $file[ strlen($file)-1 ]);
				list($type, $filename) = explode('/', $file, 2);

				// Check to see if the bundled items exist before attempting to copy them
				if ( ! $wp_filesystem->exists( $from . $distro . 'wp-content/' . $file ) )
					continue;

				if ( 'plugins' == $type )
					$dest = $wp_filesystem->wp_plugins_dir();
				elseif ( 'themes' == $type )
					$dest = trailingslashit($wp_filesystem->wp_themes_dir()); // Back-compat, ::wp_themes_dir() did not return trailingslash'd pre-3.2
				else
					continue;

				if ( ! $directory ) {
					if ( ! $development_build && $wp_filesystem->exists( $dest . $filename ) )
						continue;

					if ( ! $wp_filesystem->copy($from . $distro . 'wp-content/' . $file, $dest . $filename, FS_CHMOD_FILE) )
						$result = new WP_Error( "copy_failed_for_new_bundled_$type", __( 'Could not copy file.' ), $dest . $filename );
				} else {
					if ( ! $development_build && $wp_filesystem->is_dir( $dest . $filename ) )
						continue;

					$wp_filesystem->mkdir($dest . $filename, FS_CHMOD_DIR);
					$_result = copy_dir( $from . $distro . 'wp-content/' . $file, $dest . $filename);

					// If a error occurs partway through this final step, keep the error flowing through, but keep process going.
					if ( is_wp_error( $_result ) ) {
						if ( ! is_wp_error( $result ) )
							$result = new WP_Error;
						$result->add( $_result->get_error_code() . "_$type", $_result->get_error_message(), substr( $_result->get_error_data(), strlen( $dest ) ) );
					}
				}
			}
		} //end foreach
	}

	// Handle $result error from the above blocks
	if ( is_wp_error($result) ) {
		$wp_filesystem->delete($from, true);
		return $result;
	}

	// Remove old files
	foreach ( $_old_files as $old_file ) {
		$old_file = $to . $old_file;
		if ( !$wp_filesystem->exists($old_file) )
			continue;
		$wp_filesystem->delete($old_file, true);
	}

	// Upgrade DB with separate request
	/** This filter is documented in eshop-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Upgrading database&#8230;' ) );
	$db_upgrade_url = admin_url('upgrade.php?step=upgrade_db');
	wp_remote_post($db_upgrade_url, array('timeout' => 60));

	// Clear the cache to prevent an update_option() from saving a stale db_version to the cache
	wp_cache_flush();
	// (Not all cache backends listen to 'flush')
	wp_cache_delete( 'alloptions', 'options' );

	// Remove working directory
	$wp_filesystem->delete($from, true);

	// Force refresh of update information
	if ( function_exists('delete_site_transient') )
		delete_site_transient('update_core');
	else
		delete_option('update_core');

	/**
	 * Fires after WordPress core has been successfully updated.
	 *
	 * @since 3.3.0
	 *
	 * @param string $wp_version The current WordPress version.
	 */
	do_action( '_core_updated_successfully', $wp_version );

	// Clear the option that blocks auto updates after failures, now that we've been successful.
	if ( function_exists( 'delete_site_option' ) )
		delete_site_option( 'auto_core_update_failed' );

	return $wp_version;
}

/**
 * Copies a directory from one location to another via the WordPress Filesystem Abstraction.
 * Assumes that WP_Filesystem() has already been called and setup.
 *
 * This is a temporary function for the 3.1 -> 3.2 upgrade, as well as for those upgrading to
 * 3.7+
 *
 * @ignore
 * @since 3.2.0
 * @since 3.7.0 Updated not to use a regular expression for the skip list
 * @see copy_dir()
 *
 * @param string $from source directory
 * @param string $to destination directory
 * @param array $skip_list a list of files/folders to skip copying
 * @return mixed WP_Error on failure, True on success.
 */
function _copy_dir($from, $to, $skip_list = array() ) {
	global $wp_filesystem;

	$dirlist = $wp_filesystem->dirlist($from);

	$from = trailingslashit($from);
	$to = trailingslashit($to);

	foreach ( (array) $dirlist as $filename => $fileinfo ) {
		if ( in_array( $filename, $skip_list ) )
			continue;

		if ( 'f' == $fileinfo['type'] ) {
			if ( ! $wp_filesystem->copy($from . $filename, $to . $filename, true, FS_CHMOD_FILE) ) {
				// If copy failed, chmod file to 0644 and try again.
				$wp_filesystem->chmod( $to . $filename, FS_CHMOD_FILE );
				if ( ! $wp_filesystem->copy($from . $filename, $to . $filename, true, FS_CHMOD_FILE) )
					return new WP_Error( 'copy_failed__copy_dir', __( 'Could not copy file.' ), $to . $filename );
			}
		} elseif ( 'd' == $fileinfo['type'] ) {
			if ( !$wp_filesystem->is_dir($to . $filename) ) {
				if ( !$wp_filesystem->mkdir($to . $filename, FS_CHMOD_DIR) )
					return new WP_Error( 'mkdir_failed__copy_dir', __( 'Could not create directory.' ), $to . $filename );
			}

			// generate the $sub_skip_list for the subdirectory as a sub-set of the existing $skip_list
			$sub_skip_list = array();
			foreach ( $skip_list as $skip_item ) {
				if ( 0 === strpos( $skip_item, $filename . '/' ) )
					$sub_skip_list[] = preg_replace( '!^' . preg_quote( $filename, '!' ) . '/!i', '', $skip_item );
			}

			$result = _copy_dir($from . $filename, $to . $filename, $sub_skip_list);
			if ( is_wp_error($result) )
				return $result;
		}
	}
	return true;
}

/**
 * Redirect to the About WordPress page after a successful upgrade.
 *
 * This function is only needed when the existing install is older than 3.4.0.
 *
 * @since 3.3.0
 *
 */
function _redirect_to_about_wordpress( $new_version ) {
	global $wp_version, $pagenow, $action;

	if ( version_compare( $wp_version, '3.4-RC1', '>=' ) )
		return;

	// Ensure we only run this on the update-core.php page. The Core_Upgrader may be used in other contexts.
	if ( 'update-core.php' != $pagenow )
		return;

 	if ( 'do-core-upgrade' != $action && 'do-core-reinstall' != $action )
 		return;

	// Load the updated default text localization domain for new strings
	load_default_textdomain();

	// See do_core_upgrade()
	show_message( __('WordPress updated successfully') );

	// self_admin_url() won't exist when upgrading from <= 3.0, so relative URLs are intentional.
	show_message( '<span class="hide-if-no-js">' . sprintf( __( 'Welcome to WordPress %1$s. You will be redirected to the About WordPress screen. If not, click <a href="%2$s">here</a>.' ), $new_version, 'about.php?updated' ) . '</span>' );
	show_message( '<span class="hide-if-js">' . sprintf( __( 'Welcome to WordPress %1$s. <a href="%2$s">Learn more</a>.' ), $new_version, 'about.php?updated' ) . '</span>' );
	echo '</div>';
	?>
<script type="text/javascript">
window.location = 'about.php?updated';
</script>
	<?php

	// Include admin-footer.php and exit
	include(ABSPATH . 'eshop-admin/admin-footer.php');
	exit();
}
add_action( '_core_updated_successfully', '_redirect_to_about_wordpress' );
