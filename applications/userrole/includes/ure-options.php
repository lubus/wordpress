<?php
/* 
 *
 * User Role Editor plugin management pages
 * 
 */

if (!defined('URE_PLUGIN_URL')) {
  die;  // Silence is golden, direct call is prohibited
}

$shinephpFavIcon = URE_PLUGIN_URL.'/images/vladimir.png';
$mess = '';

$ure_caps_readable = get_option('ure_caps_readable');
$ure_show_deprecated_caps = get_option('ure_show_deprecated_caps');
$option_name = $wpdb->prefix.'user_roles';

if (isset($_REQUEST['object'])) {
  $ure_object = $_REQUEST['object'];
} else {
  $ure_object = 'role';
}

if (isset($_REQUEST['action'])) {
  $action = $_REQUEST['action'];
  // restore roles capabilities from the backup record
  if ($action=='reset') {
    $mess = ure_reset_user_roles();
    if (!$mess) {
      return;
    }
  } else if ($action=='addnewrole') {
    // process new role create request
    $mess = ure_newRoleCreate($ure_currentRole);
  } else if ($action=='delete') {
    $mess = ure_deleteRole();
  } else if ($action=='default') {
    $mess = ure_changeDefaultRole();
  } else if ($action=='capsreadable') {
    if ($ure_caps_readable) {
      $ure_caps_readable = 0;
    } else {
      $ure_caps_readable = 1;
    }
    update_option('ure_caps_readable', $ure_caps_readable);
  } else if ($action=='showdeprecatedcaps') {
    if ($ure_show_deprecated_caps) {
      $ure_show_deprecated_caps = 0;
    } else {
      $ure_show_deprecated_caps = 1;
    }
    update_option('ure_show_deprecated_caps', $ure_show_deprecated_caps);  
  } else if ($action=='addnewcapability') {
    $mess = ure_AddNewCapability();
  } else if ($action=='removeusercapability') {
    $mess = ure_RemoveCapability();
  } else if ($action=='roles_restore_note') {
    $mess = __('User Roles are restored to boxbeat default values. ', 'ure');
  }
} else {
  $action = '';
}

$defaultRole = get_option('default_role');

if (isset($_POST['ure_apply_to_all'])) {
  $ure_apply_to_all = 1;
} else {
  $ure_apply_to_all = 0;
}

if (!isset($ure_roles) || !$ure_roles) {
// get roles data from database
  $ure_roles = ure_getUserRoles();
  if (!$ure_roles) {
    return;
  }
}

$ure_rolesId = array();
foreach ($ure_roles as $key=>$value) {
  $ure_rolesId[] = $key;
}

$built_in_wp_caps = ure_getBuiltInWPCaps();
$ure_fullCapabilities = array();
foreach($ure_roles as $role) {
  // validate if capabilities is an array
  if (isset($role['capabilities']) && is_array($role['capabilities'])) {
    foreach ($role['capabilities'] as $key=>$value) {
      $cap = array();
      $cap['inner'] = $key;
      $cap['human'] = __(ure_ConvertCapsToReadable($key),'ure');
			if ( isset( $built_in_wp_caps[ $key ] ) ) {
				$cap['wp_core'] = true;				
			} else {
				$cap['wp_core'] = false;				
			}
      if (!isset($ure_fullCapabilities[$key])) {
        $ure_fullCapabilities[$key] = $cap;
      }
    }
  }
}
asort($ure_fullCapabilities);


if ($ure_object=='user') {
  if (!isset($_REQUEST['user_id'])) {
    $mess .= ' user_id value is missed';
    return;
  }
  $user_id = $_REQUEST['user_id'];
  if (!is_numeric($user_id)) {
    return;
  }
  if (!$user_id) {
    return;
  }
  $ure_userToEdit = get_user_to_edit($user_id);
  if (empty($ure_userToEdit)) {
    return;
  }  
}

if ( isset( $_POST['action'] ) && $_POST['action'] == 'update' && isset( $_POST['submit'] ) ) {
	if ( isset( $_POST['user_role'] ) ) {
		$ure_currentRole = $_POST['user_role'];
		if (!isset($ure_roles[$ure_currentRole])) {
			$mess = __('Error: ', 'ure') . __('Role', 'ure') . ' <em>' . $ure_currentRole . '</em> ' . __('does not exist', 'ure');
		} else {
			$ure_currentRoleName = $ure_roles[$ure_currentRole]['name'];
		}
	}
  $ure_capabilitiesToSave = array();
  foreach ($ure_fullCapabilities as $availableCapability) {
    $cap_id = str_replace(' ', URE_SPACE_REPLACER, $availableCapability['inner']);
    if (isset($_POST[$cap_id])) {
      $ure_capabilitiesToSave[$availableCapability['inner']] = true;
    }
  }
  if ($ure_object == 'role') {  // save role changes to database
    if (count($ure_capabilitiesToSave) > 0) {
      if (ure_updateRoles()) {
        if ($mess) {
          $mess .= '<br/>';
        }
        $mess = __('Role', 'ure') . ' <em>' . __($ure_roles[$ure_currentRole]['name'], 'ure') . '</em> ' . __('is updated successfully', 'ure');
      }
    }
  } else {
    if (ure_updateUser($ure_userToEdit)) {
      if ($mess) {
        $mess .= '<br/>';
      }
      $mess = __('User', 'ure') . ' &lt;<em>' . $ure_userToEdit->display_name . '</em>&gt; ' . __('capabilities are updated successfully', 'ure');
    }
  }  
}

// options page display part
function ure_displayBoxStart($title, $style='') {
?>
			<div class="postbox" style="float: left; <?php echo $style; ?>">
				<h3 style="cursor:default;"><span><?php echo $title ?></span></h3>
				<div class="inside">
<?php
}
// 	end of ure_displayBoxStart()

function ure_displayBoxEnd() {
?>
				</div>
			</div>
<?php
}
// end of thanks_displayBoxEnd()


ure_showMessage($mess);

?>
<script language="javascript" type="text/javascript" >
  function ure_show_greetings(message) {
    var el = document.getElementById('ure_greetings');
    if (el.style.display=='block') {
      el.style.display = 'none';
    } else {
      el.style.display = 'block';
    }
  }
  // end of ure_show_greetings()
  
  function ure_select_all(selected) {
    
    var form = document.getElementById('ure_form');
    for (i=0; i<form.elements.length; i++) {
      el = form.elements[i];
      if (el.type!='checkbox') { 
        continue;
      }  
      if (el.name=='ure_caps_readable' || el.name=='ure_show_deprecated_caps') {
        continue;
      }
      if (selected>=0) {
        form.elements[i].checked = selected;      
      } else {
        form.elements[i].checked = !form.elements[i].checked;      
      }
    }
    
  }
  // end of ure_select_all()
  
  
</script>
<div id="poststuff">
	<div class="ure-sidebar" >
		<div style="text-align: center;">
			<a href="http://w-shadow.com/admin-menu-editor-pro/?utm_source=UserRoleEditor&utm_medium=banner&utm_campaign=Plugins " target="_new" ><img src="<?php echo URE_PLUGIN_URL . '/images/admin-menu-editor-pro.jpg'; ?>" alt="Admin Menu Editor Pro" title="Move, rename, hide, add admin menu items, restrict access"/></a>
		</div>  

		<div style="text-align: center;">
			<a title="ManageWP" href="http://managewp.com/?utm_source=user_role_editor&utm_medium=Banner&utm_content=mwp250_2&utm_campaign=Plugins" target="_new" >
				<img width="250" height="250" alt="ManageWP" src="<?php echo URE_PLUGIN_URL; ?>/images/mwp250_2.png">
			</a>                        
		</div>  

		<div style="text-align: center;">
			<a href="http://chooseplugin.com"><img src="<?php echo URE_PLUGIN_URL . '/images/chooseplugin.png'; ?>" alt="Choose boxbeat plugins with ChoosePlugin.com" title="Advanced search boxbeat plugins service from User Role Editor developer" /></a>
		</div>  

		<?php ure_displayBoxStart(__('About this Plugin:', 'ure')); ?>
		<a class="ure_rsb_link" style="background-image:url(<?php echo $shinephpFavIcon; ?>);" target="_blank" href="http://www.shinephp.com/"><?php _e("Author's website", 'ure'); ?></a>
		<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/user-role-editor-icon.png'; ?>);" target="_blank" href="http://www.shinephp.com/user-role-editor-boxbeat-plugin/"><?php _e('Plugin webpage', 'ure'); ?></a>
		<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/changelog-icon.png'; ?>);" target="_blank" href="http://www.shinephp.com/user-role-editor-boxbeat-plugin/#changelog"><?php _e('Changelog', 'ure'); ?></a>
		<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/faq-icon.png'; ?>);" target="_blank" href="http://www.shinephp.com/user-role-editor-boxbeat-plugin/#faq"><?php _e('FAQ', 'ure'); ?></a>
		<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/greetings.png'; ?>);" onclick="ure_show_greetings()" href="#greetings"><?php _e('Greetings', 'ure'); ?></a>
		<hr />
		<div style="text-align: center;">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="encrypted" 
							 value="-----BEGIN PKCS7-----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-----END PKCS7-----">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">                        
			</form>                        
		</div>
		<hr />                      
		<h3><?php _e('Recently donated', 'ure'); ?></h3>
		<ul>
			<li><a href="http://thenineshub.com/" title="To The Nines Web Agency" target="new">To The Nines Web Agency</a></li>
			<li><a href="http://helpkyria.com" title="http://helpkyria.com" target="new">Miami</a></li>
			<li>quintain</li>
			<li><a href="http://www.s2member.com" title="s2member.com" target="new">s2member.com / WebSharks, Inc.</a></li>
			<li><a href="http://www.eastwoodzhao.com" title="www.eastwoodzhao.com" target="new">Eastwood</a></li>
		</ul>											
		<div id="ure_greetings" style="clear: left; float: left; display: none;">    
			<hr />
			<h3><a name="greetings"><?php _e('Greetings', 'ure'); ?></a></h3>                  
			<a class="ure_rsb_link" style="background-image:url(<?php echo $shinephpFavIcon; ?>);" target="_blank" title="<?php _e("It's me, the author", 'ure'); ?>" href="http://www.shinephp.com/">Vladimir</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/yaser.png'; ?>)" target="_blank" title="<?php _e("For the help with Arabic translation", 'ure'); ?>" href="http://www.englize.com/">Yaser</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/marsis.png'; ?>)" target="_blank" title="<?php _e("For the help with Belorussian translation", 'ure'); ?>" href="http://pc.de">Marsis G.</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/rafael.png'; ?>)" target="_blank" title="<?php _e("For the help with Brasilian translation", 'ure'); ?>" href="http://www.arquiteturailustrada.com.br/">Rafael Galdencio</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/onbiz.png'; ?>)" target="_blank" title="<?php _e("For the help with Brasilian Portuguese translation", 'ure'); ?>" href="http://www.onbiz.com.br">Onbiz</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/jackytsu.png'; ?>)" target="_blank" title="<?php _e("For the help with Chinese translation", 'ure'); ?>" href="http://www.jackytsu.com">Jackytsu</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/ivaldi.png'; ?>)" target="_blank" title="<?php _e("For the help with Dutch translation", 'ure'); ?>" href="http://www.ivaldi.nl">Frank Groeneveld</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/lauri.png'; ?>)" target="_blank" title="<?php _e("For the help with Finnish translation", 'ure'); ?>" href="http://www.viidakkorumpu.fi">Lauri Merisaari</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/presse-et-multimedia.png'; ?>)" target="_blank" title="<?php _e("For the help with French translation", 'ure'); ?>" href="http://presse-et-multimedia.fr/blog/">Presse et Multimedia</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/whiler.png'; ?>)" target="_blank" title="<?php _e("For the help with French translation", 'ure'); ?>" href="http://blogs.wittwer.fr/whiler/">Whiler</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/peter.png'; ?>)" target="_blank" title="<?php _e("For the help with German translation", 'ure'); ?>" href="http://www.becker-heidmann.de">Peter</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/aryo.png'; ?>)" target="_blank" title="<?php _e("For the help with Hebrew translation", 'ure'); ?>" href="http://www.aryo.co.il/">ARYO Digital</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/sagive.png'; ?>)" target="_blank" title="<?php _e("For the help with Hebrew translation", 'ure'); ?>" href="http://www.sagive.co.il/">Sagive</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/outshine.png'; ?>)" target="_blank" title="<?php _e("For the help with Hindi translation", 'ure'); ?>" href="http://outshinesolutions.com">Outshine Solutions</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/blacksnail.png'; ?>)" target="_blank" title="<?php _e("For the help with Hungarian translation", 'ure'); ?>" href="http://www.blacksnail.hu">István</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/tristano.png'; ?>);" target="_blank" title="<?php _e("For the help with Italian translation", 'pgc'); ?>" href="http://www.zenfactor.org ">Tristano Ajmone</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/venezialog.png'; ?>)" target="_blank" title="<?php _e("For the help with Italian translation", 'ure'); ?>" href="http://venezialog.net">Umberto Sartori</a>                                            
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/technologjp.png'; ?>)" target="_blank" title="<?php _e("For the help with Japanese translation", 'ure'); ?>" href="http://technolog.jp">Technolog.jp</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/host1free.png'; ?>)" target="_blank" title="<?php _e("For the help with Lithuanian translation", 'ure'); ?>" href="http://host1free.com">Vincent G</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/parsa.png'; ?>)" target="_blank" title="<?php _e("For the help with Persian translation", 'ure'); ?>" href="http://parsa.ws">Parsa</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/good-life.png'; ?>)" target="_blank" title="<?php _e("For the help with Persian translation", 'ure'); ?>" href="http://good-life.ir">Good Life</a>
			<span title="<?php _e("For the help with Persian translation", 'ure'); ?>">Amir Khalilnejad</span>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/tagsite.png'; ?>)" target="_blank" title="<?php _e("For the help with Polish translation", 'ure'); ?>" href="http://www.tagsite.eu">TagSite</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/bartosz.png'; ?>)" target="_blank" title="<?php _e("For the help with Polish translation", 'ure'); ?>" href="http://www.digitalfactory.pl">Bartosz</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/wpcouponshop.png'; ?>)" target="_blank" title="<?php _e("For the help with Serbian translation", 'ure'); ?>" href="http://wpcouponshop.com">Diana</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/branco.png'; ?>)" target="_blank" title="<?php _e("For the help with Slovak translation", 'ure'); ?>" href="http://webhostinggeeks.com/blog/">Branco (WebHostingGeeks.com)</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/infomed.png'; ?>)" target="_blank" title="<?php _e("For the help with Spanish translation", 'ure'); ?>" href="http://www.sld.cu">Victor Ricardo Díaz (INFOMED)</a>
			<span title="<?php _e("For the help with Spanish translation", 'ure'); ?>" >Dario  Ferrer</span>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/andreas.png'; ?>)" target="_blank" title="<?php _e("For the updated Swedish translation", 'ure'); ?>" href="http://adevade.com/">Andréas Lundgren</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/christer.png'; ?>)" target="_blank" title="<?php _e("For the help with Swedish translation", 'ure'); ?>" href="http://www.startlinks.eu">Christer Dahlbacka</a>
			<span title="<?php _e("For the help with Traditional Chinese translation", 'ure'); ?>">Jingxin Lai</span>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/muhammed.png'; ?>)" target="_blank" title="<?php _e("For the help with Turkish translation", 'ure'); ?>" href="http://ben.muhammed.im/">Muhammed YILDIRIM</a>
			<hr />
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/fullthrottle.png'; ?>)" target="_blank" title="<?php _e("For the code to hide administrator role", 'ure'); ?>" href="http://fullthrottledevelopment.com/how-to-hide-the-adminstrator-on-the-boxbeat-users-screen">FullThrottle</a>
			<a class="ure_rsb_link" style="background-image:url(<?php echo URE_PLUGIN_URL . '/images/lorenzo.png'; ?>)" target="_blank" title="<?php _e("For the code enhancement suggestion", 'ure'); ?>" href="http://www.extera.com">Lorenzo Nicoletti</a>
			<hr />
			<?php _e('Do you wish to see your name with link to your site here? You are welcome! Your help with translation and new ideas are very appreciated.', 'ure'); ?>									
		</div>
		<?php ure_displayBoxEnd(); ?>
	</div>

	<div class="has-sidebar" >
		<form id="ure_form" method="post" action="<?php echo URE_PARENT; ?>?page=user-role-editor.php" onsubmit="return ure_onSubmit();">
			<?php
			settings_fields('ure-options');
			?>
								
			<?php
			if ($ure_object == 'user') {
				require_once('ure-user-edit.php');
			} else {
				require_once('ure-role-edit.php');
			}
			?>
		</form>
	</div>          
</div>

