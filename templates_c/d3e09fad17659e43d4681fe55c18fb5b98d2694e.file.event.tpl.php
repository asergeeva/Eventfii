<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 09:05:52
         compiled from "templates/event.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16174658104dbfc5709ceec4-92929820%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd3e09fad17659e43d4681fe55c18fb5b98d2694e' => 
    array (
      0 => 'templates/event.tpl',
      1 => 1303523574,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16174658104dbfc5709ceec4-92929820',
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
	<?php $_template = new Smarty_Internal_Template("event_container.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</div>
<?php $_template = new Smarty_Internal_Template("global_js.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('JS_PATH')->value;?>
/event.js"></script>
</body>
<?php $_template = new Smarty_Internal_Template("footer.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>