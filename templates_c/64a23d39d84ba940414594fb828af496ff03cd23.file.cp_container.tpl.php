<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 08:59:22
         compiled from "templates/cp_container.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7919936904dbfc3ea66ceb0-72397119%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '64a23d39d84ba940414594fb828af496ff03cd23' => 
    array (
      0 => 'templates/cp_container.tpl',
      1 => 1303414591,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7919936904dbfc3ea66ceb0-72397119',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="cp_container">
  <div id="header">
  <?php $_template = new Smarty_Internal_Template("cp_header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
  </div>
  <div id="middle">
  <?php $_template = new Smarty_Internal_Template("cp_middle.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
  </div>
  <div id="footer"></div>
</div>