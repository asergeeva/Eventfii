<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 09:00:33
         compiled from "templates/cp.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14576745624dbfc4318c8577-66377250%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '940577180a40382abf9ed35484210383e49116c4' => 
    array (
      0 => 'templates/cp.tpl',
      1 => 1303549594,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14576745624dbfc4318c8577-66377250',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title',"Jumpstart your social life"); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("cp_css.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</head>

<body id="cp_body">
<div id="container">
	<?php $_template = new Smarty_Internal_Template("cp_container.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</div>
<?php $_template = new Smarty_Internal_Template("global_js.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('JS_PATH')->value;?>
/create_event_form.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('JS_PATH')->value;?>
/cp.js"></script>
</body>
<?php $_template = new Smarty_Internal_Template("footer.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>