<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 09:03:16
         compiled from "templates/home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5278593664dbfc4d4e1e661-75894201%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '44473f1c7779fd2764925cf1eff848b4714c81c4' => 
    array (
      0 => 'templates/home.tpl',
      1 => 1304413243,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5278593664dbfc4d4e1e661-75894201',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title',"Jumpstart your social life"); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("home_css.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</head>

<body>
<div id="container">
	<?php $_template = new Smarty_Internal_Template("home_container.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</div>
<?php $_template = new Smarty_Internal_Template("global_js.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('JS_PATH')->value;?>
/home_event.js"></script>
</body>
<?php $_template = new Smarty_Internal_Template("footer.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>