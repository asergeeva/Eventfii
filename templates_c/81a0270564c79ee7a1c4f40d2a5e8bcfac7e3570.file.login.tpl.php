<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 08:59:16
         compiled from "templates/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17835875114dbfc3e4f2ac73-87419646%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '81a0270564c79ee7a1c4f40d2a5e8bcfac7e3570' => 
    array (
      0 => 'templates/login.tpl',
      1 => 1304204736,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17835875114dbfc3e4f2ac73-87419646',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title',"Jumpstart your social life"); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("home_css.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("cp_css.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</head>

<body>
<div id="container">
	<?php $_template = new Smarty_Internal_Template("login_form.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</div>
<?php $_template = new Smarty_Internal_Template("global_js.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('JS_PATH')->value;?>
/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('JS_PATH')->value;?>
/cp.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('JS_PATH')->value;?>
/create_event_form.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('JS_PATH')->value;?>
/login.js"></script>
</body>
<?php $_template = new Smarty_Internal_Template("footer.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>